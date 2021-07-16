<?php

namespace App\Http\Controllers;

use App\Account;
use App\Credit;
use App\CreditSupplierDetail;
use App\Pago;
use App\Payment;
use App\Receiving;
use App\Supplier;
use App\Parameter;
use App\Traits\TransactionsTrait;
use Illuminate\Http\Request;
use App\Traits\DatesTrait;
use App\Traits\SaleTrait;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CreditSupplier;
use \Auth, \Redirect, \Validator, \Input, \Session;
use DB;
use mysql_xdevapi\Exception;

class CreditSupplierController extends Controller
{
    use TransactionsTrait;
    use DatesTrait;
    use SaleTrait;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('parameter');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stado = Input::get('estado');
        $stado = $stado == null ? 'Pendientes' : $stado;

        $Credits = CreditSupplier::leftJoin('suppliers', 'suppliers.id', '=', 'credit_suppliers.supplier_id')
            ->select(['suppliers.id', 'suppliers.company_name', 'credit_suppliers.status_id', DB::Raw('sum(credit_suppliers.credit_total) as credit_total,sum(credit_suppliers.paid_amount) as paid,min(credit_suppliers.created_at) as first_credit,min(credit_suppliers.date_payments) as min_due_date,datediff(min(credit_suppliers.date_payments),now()) as vencimiento, count(distinct credit_suppliers.id) as facturas')])
            ->orderBy('credit_suppliers.created_at', 'asc')
            ->groupBy('suppliers.id');

        if ($stado == 'Todos') {
            $dataCredits = $Credits->get();
        } elseif ($stado == 'Pendientes') {
            $dataCredits = $Credits->where('status_id', 7)->get();
        } elseif ($stado == 'Cancelados') {
            $dataCredits = $Credits->where('status_id', 6)->get();
        }
        // dd($dataCredits);
        return view('credit_supplier.index', ['datosCredito' => $dataCredits, 'stado' => $stado]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function statement_invoice($id)
    {
        $credit = CreditSupplier::where('receiving_id', $id)->first();
        $details = CreditSupplierDetail::where('credit_supplier_id', $credit->id)->get();
        $name_receiving = Receiving::join('series', 'series.id', '=', 'receivings.id_serie')
            ->join('documents', 'documents.id', '=', 'series.id_document')
            ->select(\Illuminate\Support\Facades\DB::raw('concat(documents.name, " ", series.name, "-", receivings.correlative) as name'))
            ->orderBy('receivings.correlative', 'desc')
            ->where('receivings.id', $id)
            ->first();
        return view('credit_supplier.statement_invoice')
            ->with('credit', $credit)
            ->with('details', $details)
            ->with('name_sale', $name_receiving->name);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            /*
             * REGISTRAR LA TRANSACCIÓN EN LA CUENTA
             * */
            $gastos = [];
            $gastos['account_id'] = $request->account_id;
            $gastos['paid_at'] = $request->paid_at;
            $gastos['amount'] = $request->total_pagos;
            $gastos['description'] = $request->description;
            $gastos['category_id'] = 4;
            $gastos['reference'] = $request->reference;
            $gastos['user_id'] = Auth::user()->id;
            $gastos['status'] = 5;
            $gastos['payment_method'] = $request->payment_method;
            $gastos['supplier_id'] = $request->supplier_id;
            $gastos['amount_applied'] = $request->total_pagos;
            $nuevo = new Request($gastos);
            $guardarPago = $this->saveExpense($nuevo);
            if ($guardarPago[0] < 0) {
                /**
                 * Si hubo errores al guardar FOrma de pago
                 * Hacemos Rollback de la transaccion
                 * Definimos la bandera de error, guardamos el error.
                 */
                $message = "Error registro de pago a proveedores:" . $guardarPago[1];
                throw new \Exception($message, 6);
                $url = '#';
                $flag = 2;
            }

            /*
             * ACTUALIZAR ESTADO DE LAS FACTURAS
             * */
            $indice = 0;
            // $cuantas_facturas =  $request->invoices_count;
            // dd($request->all());
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'id_credito') !== false) {
                    $items_array[$indice]['id_credito'] = $value;
                } elseif (strpos($key, 'id_factura') !== false) {
                    $items_array[$indice]['id_factura'] = $value;
                } elseif (strpos($key, 'abono') !== false) {

                    $items_array[$indice]['abono'] = $value;
                    $id_credito = $items_array[$indice]['id_credito'];
                    $id_factura = $items_array[$indice]['id_factura'];
                    $abono = floatval($items_array[$indice]['abono']);
                    // echo ' id credito '.$id_credito.' id_factura '.$id_factura.' abono '.$abono.'<br>';
                    // $id_credito = ${"$request->id_credito_" . $i};
                    // $abono = ${"$request->abono_" . $i};
                    // $id_factura = ${"$request->id_factura_" . $i};
                    if ($abono > 0) {
                        $comentario = $this->getSaleDocument($id_factura);
                        $pago_factura = [];
                        $pago_factura['paid_date'] = $request->paid_at;
                        $pago_factura['credit_id'] = $id_credito;
                        $pago_factura['payment_id'] = $guardarPago[0];
                        $pago_factura['amount'] = $abono;
                        $pago_factura['id_factura'] = $id_factura;
                        $pago_factura['comment'] = 'Pago ' . $comentario;
                        $pago_factura['created_by'] = Auth::user()->id;
                        $reques_pago = new Request($pago_factura);
                        // var_dump($reques_pago->all());
                        $pago_factura = $this->saveCreditPaymentSupplier($reques_pago);
                        // echo 'pago exitoso '.'<br>';
                        // var_dump($pago_factura);
                        if ($pago_factura[0] < 0) {
                            /**
                             * Si hubo errores al guardar pagos indiviudales
                             * Hacemos Rollback de la transaccion
                             */
                            DB::rollBack();
                            $message = "Error abono:" . $pago_factura[1];
                            return Redirect::back()->withInput()->withErrors($message);
                        }
                        //buscamos factura y  actualizamos saldo
                        // $findInvoice=Sale::find($id_factura);
                        // $findInvoice->total_paid = $findInvoice->total_paid+$abono;
                        // $findInvoice->save();
                    }
                    $indice++;
                }
            }

            //buscamos cliente y  actualizamos saldo
            $findSupplier = Supplier::find($request->supplier_id);
            $findSupplier->balance = $findSupplier->balance - $request->amount;
            $findSupplier->save();
            DB::commit();
            return Redirect::to('credit_suppliers/printPayment/' . $guardarPago[0]);
        } catch (\Exception $ex) {
            DB::rollback();
            $message = $ex->getMessage() . 'linea: ' . $ex->getLine();
            Session::flash('message', $message);
            Session::flash('alert-type', trans('danger'));
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function printPayment($id)
    {
        $parameters = Parameter::first();
        $detalleCredito = CreditSupplierDetail::with('credit', 'payment', 'credit.supplier')->where('expense_id', $id)->get();
        if (count($detalleCredito) == 0) {
            return Redirect::to('credit_suppliers');
        }
        return view('credit_supplier.completeCreditDetail_voucher')
            ->with('parameters', $parameters)
            ->with('detalleCredito', $detalleCredito);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataCredit = CreditSupplier::with('invoice', 'supplier')
            ->select('credit_suppliers.id', 'credit_suppliers.status_id', 'credit_suppliers.credit_total', 'credit_suppliers.paid_amount', 'credit_suppliers.created_at', 'credit_suppliers.date_payments', 'credit_suppliers.receiving_id', DB::raw('credit_suppliers.credit_total-credit_suppliers.paid_amount as balance,datediff(credit_suppliers.date_payments,now()) as vencimiento'))
            ->where('credit_suppliers.status_id', 7)
            ->where('credit_suppliers.supplier_id', $id)
            ->orderBy('credit_suppliers.created_at', 'ASC')->get();
        /**
         * Tipos de pago para ingreso
         */
        $payments = Pago::bankin()->get();

        /**
         * Cuentas
         */
        $accounts = Account::all();


        $totalSaldo = CreditSupplier::where('credit_suppliers.status_id', 7)
            ->where('credit_suppliers.supplier_id', $id)
            ->sum(DB::Raw('credit_suppliers.credit_total-credit_suppliers.paid_amount'));


        $supplier = Supplier::find($id);

        return view('credit_supplier.createpayment')
            ->with('dataCredit', $dataCredit)
            ->with('pagos', $payments)
            ->with('totalSaldo', $totalSaldo)
            ->with('cliente', $supplier)
            ->with('accounts', $accounts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function statement($id)
    {
        $supplier = Supplier::find($id);
        $all = Input::get('all') == null ? 0 : 1;
        $status = Input::get('status') ==null ? '7':Input::get('status');
        $fecha1= $this->fixFecha(Input::get('date1'));
        $fecha2=$this->fixFechaFin(Input::get('date2'));
        


        /**Facturas de compra */
        $receivings = Receiving::join('series','series.id','=','id_serie')
        ->Join('documents','series.id_document','=','documents.id')
        ->Join('pagos','pagos.id','=','id_pago')
        ->leftJoin('credit_suppliers','receivings.id','=','credit_suppliers.receiving_id')
        ->where('receivings.supplier_id',$id)
        ->where('receivings.cancel_bill',0);
        if($status==7){
            $receivings->where('credit_suppliers.status_id',$status);
        }
        if($all==1)
        {
            $receivings->whereBetween('receivings.date',[$fecha1,$fecha2]);
        }
        $receivings->select('receivings.id','receivings.created_at','receivings.date','correlative','receivings.reference',
        'credit_suppliers.date_payments as due_date','total_cost as amount_debit',DB::raw('0 as amount_credit'),
        DB::raw('total_cost-total_paid as pending_amount'),DB::raw('1 as document'),
        DB::raw('UPPER(concat(documents.name," ",series.name,"-",receivings.correlative)) as document_and_correlative,
        pagos.name as payment_type, coalesce(datediff(credit_suppliers.date_payments,now()),1) as vencimiento, 0 as expense_id'),
        DB::raw('"FACT" as type_doc'));

        /** Abonos a facturas al crédito */        
            $receipts = CreditSupplierDetail::join('bank_tx_payments','credit_supplier_details.expense_id','=','bank_tx_payments.id')         
            ->join('pagos','pagos.id','=','payment_method')
            ->join('credit_suppliers','credit_suppliers.id','=','credit_supplier_details.credit_supplier_id')         
            ->join('receivings','receivings.id','=','credit_suppliers.receiving_id')
            ->join('series','series.id','=','receivings.id_serie')
            ->join('documents','series.id_document','=','documents.id')
            ->where('receivings.cancel_bill',0) /**Facturas activas */
            ->where('credit_suppliers.supplier_id',$id);
            if($status==7){
            $receipts->where('credit_suppliers.status_id',7); /**creditos pendientes de pago */
            }
            if($all==1)
            {
                $receipts->whereBetween('credit_supplier_details.paid_date',[$fecha1,$fecha2]);
            }
            $receipts->select('receivings.id','bank_tx_payments.created_at',
                'credit_supplier_details.paid_date as date',
                'credit_supplier_details.id as correlative','bank_tx_payments.reference',DB::raw('"" as due_date'),
                DB::raw('0 as amount_debit,credit_supplier_details.amount as amount_credit,0 as pending_amout'),
                DB::raw('concat(pagos.name," ",bank_tx_payments.reference) as document'),
                DB::raw('concat("--> Abono a ",documents.name," ",series.name,"-",receivings.correlative) as document_and_correlative,
                pagos.name as payment_type, 1 as vencimiento,bank_tx_payments.id as expense_id'),
                DB::raw('"RECB" as type_doc'));
        if($status!=7){
            /** Pagos a facturas de contado */
            $payments = Payment::join('pagos','pagos.id','=','payment_method')
                ->join('receivings','receivings.id','=','bank_tx_payments.bill_id')
                ->where('bank_tx_payments.supplier_id',$id)
                ->where('status', '!=',2);
                if($all==1)
                {
                    $payments->whereBetween('paid_at',[$fecha1,$fecha2]);
                }
                $payments->select('receivings.id','bank_tx_payments.created_at','paid_at as date',
                'bank_tx_payments.reference as correlative','description as reference',DB::raw('"" as due_date'),
                DB::raw('0 as amount_debit'), 'amount as amount_credit',DB::raw('0 as pending_amount'),
                DB::raw('0 as document'),
                DB::raw('concat("--> Abono a ",pagos.name," ",bank_tx_payments.reference) as document_and_correlative,
                "" as payment_type, 1 as vencimiento, bank_tx_payments.id as expense_id'),
                DB::raw('"RECB" as type_doc'));
                    // dd($payments->get());

                $statement = $receivings->unionAll($receipts)->unionAll($payments)
                ->orderBy('id','asc')
                ->orderBy('created_at','asc')
                ->get();
        }
        else {
            $statement = $receivings->unionAll($receipts)
            ->orderBy('id','asc')
            ->orderBy('created_at','asc')
            ->get();
        }

        return view('credit_supplier.statement')
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('customer', $supplier)
            ->with('status', $status)
            ->with('all', $all)
            ->with('statement', $statement);
    }
}
