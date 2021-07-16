<?php

namespace App\Http\Controllers;

use App\Expense;
use DB;

use App\Pago;
use App\Sale;
use App\User;
use App\Serie;
use App\Credit;
use App\Account;
use App\Revenue;
use App\Customer;
use App\Document;
use App\SaleItem;
use App\Parameter;
use App\RouteUser;
use App\CreditNote;
use App\DetailCredit;
use App\CreditPayment;
use App\Http\Requests;
use App\GeneralParameter;
use App\Traits\DatesTrait;
use Illuminate\Http\Request;
use App\Classes\NumeroALetras;
use App\Traits\TransactionsTrait;
use App\Traits\SaleTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditRequest;
use App\Classes\NumberToLetterConverter;
use \Auth, \Redirect, \Validator, \Input, \Session;

class CreditController extends Controller
{
    use TransactionsTrait;
    use DatesTrait;
    use SaleTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('parameter');
    }

    public function index()
    {
        $stado = Input::get('estado');
        $stado = $stado == null ? 'Pendientes' : $stado;



        $Credits = Credit::join('sales', 'credits.id_factura', '=', 'sales.id')
            ->leftJoin('customers', 'customers.id', '=', 'credits.id_cliente');
        $administrador = Session::get('administrador');
        $ruta_requerida = GeneralParameter::active()->where('name', 'Campo ruta requerido.')->first();
        /** Si la ruta es requerida y no es administrador */
        if ((isset($ruta_requerida)) && ($administrador == false)) {
            $rutas = RouteUser::where('user_id', Auth::user()->id)->select('route_id')->get();
            if (count($rutas) == 0) {
                $rutas = [0, 0];
            };
            $Credits->join('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')
                ->whereIn('route_costumers.route_id', $rutas);
        }
        $Credits->select(['customers.id', 'customers.name', 'credits.status_id', DB::Raw('sum(credits.credit_total) as credit_total,sum(credits.paid_amount) as paid,min(credits.created_at) as first_credit,min(credits.date_payments) as min_due_date,datediff(min(credits.date_payments),now()) as vencimiento, count(distinct credits.id) as facturas')])
            ->where('sales.cancel_bill', 0)
            ->orderBy('credits.created_at', 'asc')
            ->groupBy('customers.id');
        // dd($Credits);

        if ($stado == 'Todos') {
            $dataCredits = $Credits->get();
        } elseif ($stado == 'Pendientes') {
            $dataCredits = $Credits->where('status_id', 7)->get();
        } elseif ($stado == 'Cancelados') {
            $dataCredits = $Credits->where('status_id', 6)->get();
        } elseif ($stado == 'Anulados') {
            $dataCredits = $Credits->where('status_id', 10)->get();
        }

        return view('credit.index', ['datosCredito' => $dataCredits, 'stado' => $stado]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $sale = Sale::find($id);
        $customer = Customer::find($sale->customer_id);
        return view('credit.create')->with('idFactura', $id)
            ->with('name', $customer->name)
            ->with('sales', $sale)
            ->with('id_cliente', $customer->id)
            ->with('monto', $sale->total_cost);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        return $request->all();
        $creditData = new Credit;
        $totalCuotas = Input::get('total_pagos');
        $totalEnganche = Input::get('total_eganche');
        $totalInteres = Input::get('total_interes');
        $totalMonto = Input::get('montoCredito');


        // $fecha = "2017-08-15";
        $fecha = Input::get('date_payments');
        $idcliente = Input::get('id_cliente');
        $idFactura = Input::get('id_factura');
        $nuevaFecha = explode('/', $fecha);
        $diaMes = $nuevaFecha[0];
        $mes = $nuevaFecha[1];
        $anio = $nuevaFecha[2];
        $totalPagoCuotas = $totalMonto / $totalCuotas;
        $creditData->id_cliente = $idcliente;
        $creditData->id_factura = $idFactura;
        $creditData->number_payments = $totalCuotas;
        $creditData->enganche = $totalEnganche;
        $creditData->credit_total = $totalMonto;
        //return $totalMonto;
        $creditData->total_interes = $totalInteres;
        $creditData->date_payments = $fecha;
        // $creditData->date_payments=Input::get('FechaPago');
        $creditData->save();
        $idCreditoMaximo = Credit::max('id');
        $num = 0;
        $mes_insertar2;
        //Ciclo para guardar los creditos
        $valor_acumulado = 0;
        $nuevo_valor_restante = 0;
        for ($i = 0; $i < $totalCuotas; $i++) {
            $mes_insertar2 = $mes + $i;
            $detailCredits = new DetailCredit;
            $detailCredits->id_factura = $idCreditoMaximo;
            if ($mes_insertar2 >= 13) {
                $mes_insertar2 = $num + 1;
                $detailCredits->date_payments = ($anio + 1) . '-' . $mes_insertar2 . '/' . $diaMes;
                $num++;
            } else {
                $detailCredits->date_payments = $anio . '-' . $mes_insertar2 . '/' . $diaMes;
            }
            // si es el ultimo pago se genera la bandera
            $valor_acumulado = $valor_acumulado + round($totalPagoCuotas);
            if ($i == $totalCuotas - 1) {
                $detailCredits->last_payment = 1;
                $nuevo_valor_restante = ($totalMonto) - ($valor_acumulado - round($totalPagoCuotas));
                $detailCredits->total_payments = $nuevo_valor_restante;
            } else {
                $detailCredits->total_payments = round($totalPagoCuotas);
            }
            //se guarda el credito
            $detailCredits->save();
        }
        return redirect()->action('CreditController@completeCredit', ['id' => $idFactura]);
    }

    public  function completeCredit($id)
    {
        $venta = Sale::find($id);
        $series = Serie::leftJoin('documents', 'series.id_document', '=', 'documents.id')->where('series.id', $venta->id_serie)->select('series.name', 'documents.name as document')->get();
        // dd($series);
        $clienteBuscado = Customer::find($venta->customer_id);
        $obtenerElUltimoCredito = Credit::max('id');
        $enviarDatosCredito = Credit::where('id_factura', '=', $venta->id)->get();
        $objetosVendidos = SaleItem::where('sale_id', '=', $venta->id)
            ->get();

        // $pricesClass = new NumberToLetterConverter();
        // $prices = $pricesClass->to_word($venta->total_cost);
        // $precio_letras = ucfirst(strtolower($prices));
        $letras = NumeroALetras::convertir($venta->total_cost, 'quetzales', 'centavos');
        $precio_letras = ucfirst(strtolower($letras));
        $dataUser = User::find($venta->user_relation);
        Session::flash('message', 'Venta realizada de manera correcta');
        $detalleCredito = DetailCredit::where('id_factura', '=', $enviarDatosCredito[0]->id)->get();
        // return $objetosVendidos;
        return view('sale.completeCredit')
            ->with('venta', $venta)
            ->with('cliente', $clienteBuscado)
            ->with('dataUser', $dataUser)
            ->with('detalleVenta', $objetosVendidos)
            ->with('detalleCredito', $enviarDatosCredito)
            ->with('detallePagosCredito', $detalleCredito)->with('precio_letras', $precio_letras)->with('series', $series);
        // echo "Hola";
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function printDetail($id)
    {
        $enviarDatosCredito = Credit::find($id);
        $detalleVenta = Sale::find($enviarDatosCredito->id_factura);
        $series = Serie::leftJoin('documents', 'series.id_document', '=', 'documents.id')->where('series.id', $detalleVenta->id_serie)->select('series.name', 'documents.name as document')->get();
        $documento = $this->getSaleDocument($enviarDatosCredito->id_factura);
        $detalleCliente = Customer::find($detalleVenta->customer_id);
        $detalleCredito = DetailCredit::where('id_factura', '=', $id)->get()->connection($dbmaster);

        return view('sale.completeCreditDetail')
            ->with('cliente', $detalleCliente)
            ->with('detalleCredito', $enviarDatosCredito)
            ->with('detalleVenta', $detalleVenta)
            ->with('detallePagosCredito', $detalleCredito)
            ->with('series', $series)
            ->with('documento', $documento);
    }

    public function savePayment(Request $request)
    {
        // Usuario
        // return $request->all();
        DB::beginTransaction();
        try {
            $logged_user  = Auth::user();

            // $bandera=Input::get('bandera');


            /* *********************************************
            *
            *      GUARDAMOS TRANSACCION BANCARIA
            ** *********************************************
            */
            $ingresos = [];
            $ingresos['account_id'] = $request->account_id;
            $ingresos['paid_at'] = $request->paid_at;
            $ingresos['amount'] = $request->amount;
            $ingresos['description'] = $request->description;
            $ingresos['category_id'] = null;

            $ingresos['reference'] = $request->reference;
            $ingresos['user_id'] = $request->user_id;
            $ingresos['status'] = $request->status;
            $ingresos['payment_method'] = $request->payment_method;
            $ingresos['customer_id'] = $request->customer_id;
            $ingresos['invoice_id'] = null;
            $ingresos['receipt_number'] = $request->receipt_number;

            $ingresos['bank_name'] = $request->bank_name;
            $ingresos['same_bank'] = $request->same_bank;
            $ingresos['card_name'] = $request->card_name;
            $ingresos['card_number'] = $request->card_number;
            $ingresos['amount_applied'] = $request->amount;
            $ingresos['serie_id'] = $request->serie_id;
            // dd($ingresos);

            $nuevo = new Request($ingresos);
            $pago_id = $this->saveRevenue($nuevo);

            if ($pago_id[0] < 0) {
                /**
                 * Si hubo errores al guardar Forma de pago
                 * Hacemos Rollback de la transaccion
                 */
                $message = "Error pago:" . $pago_id[1];
                throw new \Exception($message, 6);
            }


            /* *********************************************
            *
            *      GUARDAMOS PAGOS INDIVIDUALES
            ** *********************************************
            */
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
                        $pago_factura['paid_date']   = $request->paid_at;
                        $pago_factura['credit_id']   = $id_credito;
                        $pago_factura['payment_id']  = $pago_id[0];
                        $pago_factura['amount']      = $abono;
                        $pago_factura['comment']     = 'Pago ' . $comentario;
                        $pago_factura['created_by']  = $logged_user->id;
                        $reques_pago = new Request($pago_factura);
                        // var_dump($reques_pago->all());
                        $pago_factura = $this->saveCreditPayment($reques_pago);
                        // echo 'pago exitoso '.'<br>';
                        // var_dump($pago_factura);
                        if ($pago_factura[0] < 0) {
                            /**
                             * Si hubo errores al guardar pagos indiviudales
                             * Hacemos Rollback de la transaccion
                             */
                            $message = "Error abono:" . $pago_factura[1];
                            throw new \Exception($message, 6);
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
            $findCustomer = Customer::find($request->customer_id);
            $findCustomer->balance = $findCustomer->balance - $request->amount;
            $findCustomer->save();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }
        // dd($findCustomer);

        // return Redirect::to('/credit/completeCredit_print_payment/'+$pago_id[0]);
        return redirect()->action('CreditController@completeCredit_print_payment', ['id' => $pago_id[0]]);
    }

    public function completeCredit_print_payment($id)
    {
        $parameters = Parameter::first();
        $detalleCredito = CreditPayment::with('credit', 'revenue', 'credit.customer')->where('revenue_id', $id)->get();
        if (count($detalleCredito) == 0) {
            return redirect()->action('CreditController@index');
        }

        return view('credit.completeCreditDetail_voucher')
            // return view('credit.receipt2')
            ->with('parameters', $parameters)
            ->with('detalleCredito', $detalleCredito);
    }

    public function statement($id)
    {
        $customer = Customer::find($id);
        $all = Input::get('all') == null ? 0 : 1;
        $status = Input::get('status') == null ? '7' : Input::get('status');
        $fecha1 = $this->fixFecha(Input::get('date1'));
        $fecha2 = $this->fixFechaFin(Input::get('date2'));
        if ($status == 7) {
            /**Facturas pendientes de pago */
            $sales = Credit::join('sales', 'sales.id', '=', 'credits.id_factura')
                ->join('series', 'series.id', '=', 'sales.id_serie')
                ->join('documents', 'series.id_document', '=', 'documents.id')
                ->join('customers', 'customers.id', '=', 'credits.id_cliente')
                ->Join('pagos', 'pagos.id', '=', 'sales.id_pago')
                ->select(
                    'sales.id',
                    'sales.id as document_id',
                    'sales.created_at',
                    'sale_date as date',
                    'correlative',
                    'sales.comments as reference',
                    'credits.date_payments as due_date',
                    'credits.credit_total as amount_debit',
                    DB::raw('credits.credit_total-credits.paid_amount as pending_amount'),
                    DB::raw('1 as document'),
                    DB::raw('UPPER(concat(documents.name," ",series.name,"-",sales.correlative)) as document_and_correlative,pagos.name as payment_type, coalesce(datediff(credits.date_payments,now()),1) as vencimiento,0 as revenue_id,"" as receipt_number,0 as payment,"" as bank_name'),
                    DB::raw('"FACT" as type_doc')
                )
                ->where('credits.status_id', 7)
                /**creditos pendientes de pago */
                ->where('sales.cancel_bill', 0)
                /**Facturas activas */
                ->where('credits.id_cliente', $id);
            if ($all == 1) {
                $sales->whereBetween('sales.sale_date', [$fecha1, $fecha2]);
            }
            // ->get();

            /**Abonos a las Facturas pendientes de pago */
            $receipts = CreditPayment::join('bank_tx_revenues', 'credit_payments.revenue_id', '=', 'bank_tx_revenues.id')
                ->join('pagos', 'pagos.id', '=', 'payment_method')
                ->join('credits', 'credits.id', '=', 'credit_payments.credit_id')
                ->join('sales', 'sales.id', '=', 'credits.id_factura')
                ->join('series', 'series.id', '=', 'sales.id_serie')
                ->join('documents', 'series.id_document', '=', 'documents.id')
                ->where('credits.status_id', 7)
                /**creditos pendientes de pago */
                ->where('sales.cancel_bill', 0)
                /**Facturas activas */
                ->where('credits.id_cliente', $id);
            if ($all == 1) {
                $receipts->whereBetween('sales.sale_date', [$fecha1, $fecha2]);
            }
            $receipts->select(
                'sales.id',
                'bank_tx_revenues.id as document_id',
                'bank_tx_revenues.created_at',
                'credit_payments.paid_date as date',
                'reference as correlative',
                'bank_tx_revenues.reference',
                DB::raw('"" as due_date'),
                DB::raw('0 as amount_debit,0 as pending_amount'),
                DB::raw('concat(pagos.name," ",reference) as document'),
                DB::raw('concat("--> Abono a ",documents.name," ",series.name,"-",sales.correlative) as document_and_correlative,"" as payment_type, 1 as vencimiento,bank_tx_revenues.id as revenue_id,concat("Recibo ",bank_tx_revenues.receipt_number) as receipt_number,credit_payments.amount as payment,bank_tx_revenues.bank_name'),
                DB::raw('"RECB" as type_doc')
            );

            /** Notas de crédito */
            $credit_notes = CreditNote::join('series', 'credit_notes.serie_id', '=', 'series.id')
                ->join('documents', 'documents.id', '=', 'series.id_document')
                ->join('sales', 'sales.id', '=', 'credit_notes.sale_id')
                ->join('series as sv', 'sv.id', '=', 'sales.id_serie')
                ->join('documents as dv', 'dv.id', '=', 'sv.id_document')
                ->where('credit_notes.customer_id', $id)
                ->where('status_id', '!=', 13);
            if ($all == 0) {
                $credit_notes->whereBetween('date', [$fecha1, $fecha2]);
            }
            $credit_notes->select(
                'sales.id',
                'credit_notes.id as document_id',
                'credit_notes.created_at',
                'date as date',
                'credit_notes.correlative as correlative',
                DB::raw('concat(dv.name, " ", sv.name, "-", sales.correlative) as reference'),
                DB::raw('"" as due_date'),
                DB::raw('0 as amount_debit, 0 as pending_amount'),
                DB::raw('credit_notes.comment as document'),
                DB::raw('concat("--> ",documents.name," ",series.name,"-",credit_notes.correlative) as document_and_correlative,null as payment_type, 1 as vencimiento, 0 as revenue_id,concat(documents.name," ",series.name,"-",credit_notes.correlative) as receipt_number,credit_notes.amount as payment,"" as bank_name'),
                DB::raw('"NCRE" as type_doc')
            );



            $statement = $sales
                ->unionAll($receipts)
                ->unionAll($credit_notes)
                ->orderBy('id', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            /**Todas las Facturas */
            $sales = Sale::leftJoin('credits', 'sales.id', '=', 'credits.id_factura')
                ->join('series', 'series.id', '=', 'sales.id_serie')
                ->join('documents', 'series.id_document', '=', 'documents.id')
                ->join('customers', 'customers.id', '=', 'sales.customer_id')
                ->Join('pagos', 'pagos.id', '=', 'sales.id_pago')
                ->select(
                    'sales.id',
                    'sales.id as document_id',
                    'sales.created_at',
                    'sale_date as date',
                    'correlative',
                    'sales.comments as reference',
                    'credits.date_payments as due_date',
                    'sales.total_cost as amount_debit',
                    DB::raw('credits.credit_total-credits.paid_amount as pending_amount'),
                    DB::raw('1 as document'),
                    DB::raw('UPPER(concat(documents.name," ",series.name,"-",sales.correlative)) as document_and_correlative,pagos.name as payment_type, coalesce(datediff(credits.date_payments,now()),1) as vencimiento,0 as revenue_id,"" as receipt_number,0 as payment,"" as bank_name'),
                    DB::raw('"FACT" as type_doc')
                )
                ->where('sales.cancel_bill', 0)
                /**Facturas activas */
                ->where('sales.customer_id', $id);

            // ->get();

            /**Abonos a todas las Facturas  */
            $receipts = CreditPayment::join('bank_tx_revenues', 'credit_payments.revenue_id', '=', 'bank_tx_revenues.id')
                ->join('pagos', 'pagos.id', '=', 'payment_method')
                ->join('credits', 'credits.id', '=', 'credit_payments.credit_id')
                ->join('sales', 'sales.id', '=', 'credits.id_factura')
                ->join('series', 'series.id', '=', 'sales.id_serie')
                ->join('documents', 'series.id_document', '=', 'documents.id')
                ->where('sales.cancel_bill', 0)
                /**Facturas activas */
                ->where('credits.id_cliente', $id)
                ->select(
                    'sales.id',
                    'bank_tx_revenues.id as document_id',
                    'bank_tx_revenues.created_at',
                    'credit_payments.paid_date as date',
                    'reference as correlative',
                    'bank_tx_revenues.reference',
                    DB::raw('"" as due_date'),
                    DB::raw('0 as amount_debit,0 as pending_amount'),
                    DB::raw('concat(pagos.name," ",reference) as document'),
                    DB::raw('concat("--> Abono a ",documents.name," ",series.name,"-",sales.correlative) as document_and_correlative,"" as payment_type, 1 as vencimiento,bank_tx_revenues.id as revenue_id,concat("Recibo ",bank_tx_revenues.receipt_number) as receipt_number,credit_payments.amount as payment,bank_tx_revenues.bank_name'),
                    DB::raw('"RECB" as type_doc')
                );

            /** Todas las Notas de crédito */
            $credit_notes = CreditNote::join('series', 'credit_notes.serie_id', '=', 'series.id')
                ->join('documents', 'documents.id', '=', 'series.id_document')
                ->join('sales', 'sales.id', '=', 'credit_notes.sale_id')
                ->join('series as sv', 'sv.id', '=', 'sales.id_serie')
                ->join('documents as dv', 'dv.id', '=', 'sv.id_document')
                ->where('credit_notes.customer_id', $id)
                ->where('status_id', '!=', 13);
            $credit_notes->select(
                'sales.id',
                'credit_notes.id as document_id',
                'credit_notes.created_at',
                'date as date',
                'credit_notes.correlative as correlative',
                DB::raw('concat(dv.name, " ", sv.name, "-", sales.correlative) as reference'),
                DB::raw('"" as due_date'),
                DB::raw('0 as amount_debit, 0 as pending_amount'),
                DB::raw('credit_notes.comment as document'),
                DB::raw('concat("--> ",documents.name," ",series.name,"-",credit_notes.correlative) as document_and_correlative,null as payment_type, 1 as vencimiento, 0 as revenue_id,concat(documents.name," ",series.name,"-",credit_notes.correlative) as receipt_number,credit_notes.amount as payment,"" as bank_name'),
                DB::raw('"NCRE" as type_doc')
            );

            /**
             * TODOS LOS PAGOS DE FACTURAS
             */
            $revenues = Revenue::join('sales', 'sales.id', '=', 'bank_tx_revenues.invoice_id')
                ->where('bank_tx_revenues.customer_id', $id)
                ->where('status', '!=', 2)
                ->whereNull('serie_id')
                ->select(
                    'sales.id',
                    'bank_tx_revenues.id as document_id',
                    'bank_tx_revenues.created_at',
                    'paid_at as date',
                    'receipt_number as correlative',
                    DB::raw('description as reference'),
                    DB::raw('"" as due_date'),
                    DB::raw('0 as amount_debit, 0 as pending_amount'),
                    DB::raw('description as document'),
                    DB::raw('concat("Recibo","-",receipt_number) as document_and_correlative,null as payment_type, 1 as vencimiento, bank_tx_revenues.id as revenue_id,receipt_number,amount as payment,"" as bank_name'),
                    DB::raw('"REV" as type_doc')
                );

            /**
             * TODOS LOS DESEMBOLSOS A FAVOR DEL CLIENTE ASOCIADAS A NOTAS DE CRÉDITO
             */
            $expenses = Expense::join('credit_notes', 'credit_notes.id', '=', 'expenses.credit_note_id')
                ->join('pagos', 'pagos.id', '=', 'expenses.payment_type_id')
                ->whereCustomer_id($id)
                ->whereState_id(1)
                ->select(
                    'expenses.id',
                    'expenses.id as document_id',
                    'expenses.created_at',
                    'expense_date as date',
                    'expenses.id as correlative',
                    DB::raw('description as reference'),
                    DB::raw('"" as due_date'),
                    DB::raw('expenses.amount as amount_debit, 0 as pending_amount'),
                    DB::raw('description as document'),
                    DB::raw('concat("Desembolso","-",expenses.id) as document_and_correlative,pagos.name as payment_type, 1 as vencimiento, 0 as revenue_id,null as receipt_number,0 as payment,"" as bank_name'),
                    DB::raw('"EXP" as type_doc')
                );

            $statement = $sales
                ->unionAll($receipts)
                ->unionAll($credit_notes)
                ->unionAll($revenues)
                ->unionAll($expenses)
                ->orderBy('id', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        $imagen_header = GeneralParameter::active()->where('name', 'Imagen para exportar')->first();

        if (isset($imagen_header)) {
            $imagen_header = $imagen_header->description;
        } else {
            $imagen_header = 'data:image/png;base64,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';
        }

        //       dd($imagen_header);
        return view('credit.statement')
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('customer', $customer)
            ->with('statement', $statement)
            ->with('all', $all)
            ->with('status', $status)
            ->with('imagen_header', $imagen_header);
    }

    public function completeCredit_print($id)
    {
        $id_credito = Input::get('id_credito');
        if ($id_credito) {
            $credit = Credit::find($id_credito);
            // $salesNew=
            // return $id;
            $dataSalesCurrent = Sale::find($id);
            $detailsCredit = DetailCredit::where('id_factura', $id_credito)->where('last_payment', 1)->get();
            $dateCurrentPayment = $detailsCredit[0]->payment_real_date;
            $sales = Sale::find($credit->id_factura);
            // return $sales;

            $series = Serie::leftJoin('documents', 'series.id_document', '=', 'documents.id')->where('series.id', $dataSalesCurrent->id_serie)->select('series.name', 'documents.name as document')->get();

            $saleItems = SaleItem::where('sale_id', '=', $credit->id_factura)->get();
            // return $saleItems;
            $sale_credit_anterior = Sale::find($credit->id_factura);

            $letras = NumeroALetras::convertir($sale_credit_anterior->total_cost, 'quetzales', 'centavos');
            $precio_letras = ucfirst(strtolower($letras));

            return view('credit.voucher')->with('saleItems', $saleItems)->with('sales', $sales)->with('dataSalesCurrent', $dataSalesCurrent)->with('dateCurrent', $dateCurrentPayment)
                ->with('sale_credit_anterior', $sale_credit_anterior)->with('precio_letras', $precio_letras)->with('series', $series);
        } else {

            $sales = Sale::find($id);
            $series = Serie::leftJoin('documents', 'series.id_document', '=', 'documents.id')->where('series.id', $sales->id_serie)->select('series.name', 'documents.name as document')->get();

            $saleItems = SaleItem::where('sale_id', '=', $id_credito)->get();
            $sale_credit_anterior = Sale::find($id_credito);

            $letras = NumeroALetras::convertir($sale_credit_anterior->total_cost, 'quetzales', 'centavos');
            $precio_letras = ucfirst(strtolower($letras));

            return view('credit.voucher')->with('saleItems', $saleItems)->with('sales', $sales)
                ->with('sale_credit_anterior', $sale_credit_anterior)->with('precio_letras', $precio_letras)->with('series', $series);
        }
    }

    public function editPayment($id)
    {
        $dataDetailCredit = DetailCredit::find($id);
        $cliente = DB::table('credits')
            ->join('customers', 'credits.id_cliente', '=', 'customers.id')
            ->where('credits.id', '=', $dataDetailCredit->id_factura)
            ->value('customers.name');



        return view('credit.editPayment')
            ->with('dataDetailCredit', $dataDetailCredit)
            ->with('credit_id', $dataDetailCredit->id_factura)
            ->with('customer_name', $cliente);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dataCredit = Credit::with('invoice', 'customer')
            ->join('sales', 'sales.id', '=', 'credits.id_factura')
            ->select('credits.id', 'credits.status_id', 'credits.credit_total', 'credits.paid_amount', 'credits.created_at', 'credits.date_payments', 'credits.id_factura', DB::raw('credits.credit_total-credits.paid_amount as balance,datediff(credits.date_payments,now()) as vencimiento'))
            ->where('credits.status_id', 7)
            /**pendientes de pago */
            ->where('sales.cancel_bill', 0)
            /**facturas activas */
            ->where('credits.id_cliente', $id)
            ->orderBy('credits.created_at', 'ASC')->get();
        // dd($dataCredit);
        /**
         * Tipos de pago para ingreso
         */
        $payments = Pago::bankin()
            ->select('id', 'name', 'type')
            ->get();


        /**
         * Cuentas
         */
        $accounts = Account::all();


        $totalSaldo = Credit::where('credits.status_id', 7)
            ->where('credits.id_cliente', $id)
            ->sum(DB::Raw('credits.credit_total-credits.paid_amount'));



        $cliente = Customer::find($id);

        $series = Serie::where('id_document', 11)
            ->get();

        $last = Revenue::where('serie_id', 11)
            ->max('receipt_number');
        if (isset($last)) {
            $receipt_number = $last + 1;
        } else {
            $receipt_number = 1;
        }


        return view('credit.createpayment')
            ->with('dataCredit', $dataCredit)
            ->with('payments', $payments)
            ->with('totalSaldo', $totalSaldo)
            ->with('cliente', $cliente)
            ->with('accounts', $accounts)
            ->with('series', $series)
            ->with('receipt_number', $receipt_number);
    }

    public function statement_invoice($id)
    {
        $credit = Credit::where('id_factura', $id)->first();
        $details = CreditPayment::with('revenue')->where('credit_id', $credit->id)->get();
        $name_sale = $this->getSaleDocument($id);
        return view('credit.statement_invoice')
            ->with('credit', $credit)
            ->with('details', $details)
            ->with('name_sale', $name_sale);
    }


    public function addPayment($id)
    {
        $dataDetailCredit = DetailCredit::find($id);
        // return $dataDetailCredit;
        $bandera = 0;
        if ($dataDetailCredit->last_payment == 0) {
            $bandera = 0;
        } else {
            $bandera = 1;
        }
        $documentos = Document::join('series', 'series.id_document', '=', 'documents.id')
            ->where('documents.sign', '=', '-')
            ->where('series.id', '=', 3)
            ->select([DB::raw('concat(documents.name,"-",series.name) as documento'), 'series.id'])->get();
        $cliente = DB::table('credits')
            ->join('customers', 'credits.id_cliente', '=', 'customers.id')
            ->where('credits.id', '=', $dataDetailCredit->id_factura)
            ->value('customers.name');

        return view('credit.addPayment')
            ->with('dataDetailCredit', $dataDetailCredit)
            ->with('bandera', $bandera)
            ->with('documentos', $documentos)
            ->with('customer_name', $cliente)
            ->with('credit_id', $dataDetailCredit->id_factura);
    }
    //imprimiendo los detalles del credito
    public function printDetail2($id)
    {
        $enviarDatosCredito = Credit::find($id);
        $detalleVenta = Sale::find($enviarDatosCredito->id_factura);
        // return $detalleVenta;
        $detalleCliente = Customer::find($detalleVenta->customer_id);
        $detalleCredito = DetailCredit::where('id_factura', '=', $id)->get();
        return view('credit.completeCreditDetail')
            ->with('cliente', $detalleCliente)
            ->with('detalleCredito', $enviarDatosCredito)
            ->with('detallePagosCredito', $detalleCredito)
            ->with('detalleVenta', $detalleVenta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
