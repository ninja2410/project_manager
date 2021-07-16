<?php

namespace App\Http\Controllers;

use App\Account;
use App\BankReconciliation;
use App\Expense;
use App\Parameter;
use App\Payment;
use App\Revenue;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;
use \Session, \Input;
use Illuminate\Support\Facades\DB;

class BankReconciliationController extends Controller
{
    use NotificationTrait;
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
        $reconciliations = BankReconciliation::where('closed', 1)->get();
//        $reconciliations = BankReconciliation::all();
        return view('banking.accounts.reconciliation.index')
            ->with('reconciliations', $reconciliations);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $conciliation = BankReconciliation::find($request->conciliation_id);
            $id = $conciliation->id;
            #region Verificar cheques vencidos
            $cheques_vencidos = $this->verifyOverdueChecksAccount($conciliation->account_id);
            if ($cheques_vencidos>0){
                throw new \Exception("La cuenta tiene $cheques_vencidos cheque(s) vencidos.", 6);
            }
            #endregion

            $ingresos = Revenue::where('reconciliation_id', null)
                ->where('reconcilied', 1)
                ->where('account_id', $conciliation->account_id)
                ->get();

            $ingresos_generales = Revenue::where('reconciliation_id', null)
                ->where('account_id', $conciliation->account_id)
                ->where('paid_at', '<=', date('Y-m-d',strtotime($conciliation->year.'-'.$conciliation->month.'-'.date('d'))))
                ->get();

            foreach ($ingresos_generales as $value){
                if ($value->deposit == null){
                    throw new \Exception("El ingreso de fecha $value->paid_at, recibo No. $value->receipt_number, descripción: $value->description no tiene número de depósito");
                }
            }

            $egresos = Payment::where('reconciliation_id', null)
                ->where('reconcilied', 1)
                ->where('account_id', $conciliation->account_id)
                ->get();
            $cheques_vencidos = Payment::where('reconciliation_id', null)
                ->where('payment_method', 2)
                ->where('account_id', $conciliation->account_id)
                ->where('status', 2)
                ->get();
            foreach($ingresos as $key => $value){
                if ($value->deposit == null){
                    throw new \Exception("El ingreso de fecha $value->paid_at, recibo No. $value->receipt_number, descripción: $value->description no tiene número de depósito");
                }
                $value->reconciliation_id = $conciliation->id;
                $value->update();
            }
            foreach($egresos as $key => $value){
                $value->reconciliation_id = $conciliation->id;
                $value->update();
            }
            foreach($cheques_vencidos as $key => $value){
                $value->reconciliation_id = $conciliation->id;
                $value->update();
            }
            $conciliation->comment = $request->comment;
            $conciliation->bank_balance = $request->bank_print_balance;
            $conciliation->closed = 1;
            $conciliation->update();
            Session::flash('message', trans('reconciliation.ok_save'));
            $message = 'Documentos conciliados con éxito';
            $flag = 1;
            DB::commit();
        }
        catch (\Exception $e){
            DB::rollBack();
            $message = "Error en conciliación de documentos: ".$e->getMessage() ;
            $flag = 0;
        }
        $resp = array('message' => $message, 'flag' => $flag, 'id' => $id);
        return json_encode($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reconciliation = BankReconciliation::find($id);
        $parameters = Parameter::first();
        $account = Account::find($reconciliation->account_id);
        $cheques_vencidos = Payment::where('account_id', $reconciliation->account_id)
            ->where('reconciliation_id', $id)
            ->where('status', 2)
//            ->get();
        ->sum('amount');
        return  view('banking.accounts.reconciliation.show')
            ->with('reconciliation', $reconciliation)
            ->with('account', $account)
            ->with('cheques_vencidos', $cheques_vencidos)
            ->with('parameters', $parameters);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function save_documents(Request $request){
        DB::beginTransaction();
        try{
            $ingresos = json_decode($request->ingresos);
            $egresos = json_decode($request->egresos);
            foreach ($ingresos as $index => $value){
                //ACTUALIZAR INGRESOS CON NUEVO ESTADO
                $revenue = Revenue::find($value->id);
                $revenue->reconcilied = $value->conciliado;
                if ($value->conciliado){
                    if ($revenue->deposit == null){
                        throw new \Exception("El ingreso de fecha $revenue->paid_at, recibo No. $revenue->receipt_number, descripción: $revenue->description no tiene número de depósito");
                    }
                    if ($revenue->status==5){
                        /*ACTUALIZAR SALDO CONCILIADO DE LA CUENTA*/
                        $account = Account::find($revenue->account_id);
                        $account->reconcilied_balance += $revenue->amount;
                        $account->update();
                    }
                    $revenue->status = 4;
                }
                else{
                    if ($revenue->status==4){
                        /*ACTUALIZAR SALDO CONCILIADO DE LA CUENTA*/
                        $account = Account::find($revenue->account_id);
                        $account->reconcilied_balance -= $revenue->amount;
                        $account->update();
                    }
                    $revenue->status = 5;
                }

                $revenue->update();
            }
            foreach ($egresos as $index => $value){
                //ACTUALIZAR EGRESOS CON NUEVO ESTADO
                $expense = Payment::find($value->id);
                $expense->reconcilied = $value->conciliado;
                if ($value->conciliado){
                    if ($expense->status==5){
                        /*ACTUALIZAR SALDO CONCILIADO DE LA CUENTA*/
                        $account = Account::find($expense->account_id);
                        $account->reconcilied_balance -= $expense->amount;
                        $account->update();
                    }
                    $expense->status = 4;
                }
                else{
                    /*ACTUALIZAR SALDO CONCILIADO DE LA CUENTA*/
                    if ($expense->status==4){
                        $account = Account::find($expense->account_id);
                        $account->reconcilied_balance += $expense->amount;
                        $account->update();
                    }
                    $expense->status = 5;
                }
                $expense->update();
            }
            DB::commit();
            Session::flash('message', trans('reconciliation.ok'));
            $message = 'Documentos conciliados con éxito';
            $flag = 1;
        }
        catch (\Exception $e){
            DB::rollBack();
            $message = "Error en conciliación de documentos: ".$e->getMessage();
            $flag = 0;
        }
        $resp = array('message' => $message, 'flag' => $flag);
        return json_encode($resp);
    }

    public function account($id){
        #region DATOS GENERALES
        $account = Account::find($id);
        $ingresos_conciliados = 0;
        $egresos_conciliados = 0;
        $transit_revenue = 0;
        $outstanding_payments = 0;
        #endregion

        #region VERIFICAR EL MES ACTIVO PARA CONCILIACION
        $conciliacion = BankReconciliation::where('account_id', $id)
            ->orderby('id', 'desc')
            ->first();
        if (!isset($conciliacion)){
            $conciliacion = new BankReconciliation();
            $conciliacion->date = date('y-m-d');
            $conciliacion->month = date("m");
            $conciliacion->year = date("Y");
            $conciliacion->closed = 0;
            $conciliacion->account_id = $id;
            $conciliacion->start_balance = 0;
            $conciliacion->countable_balance = 0;
            $conciliacion->bank_balance = 0;
            $conciliacion->user_id = Auth::user()->id;
            $conciliacion->created_by = Auth::user()->id;
            $conciliacion->updated_by = Auth::user()->id;
            $conciliacion->save();
        }
        else{
            //VERIFICAR SI ESTA ACTIVA
            if ($conciliacion->closed==1){
                //CONCILIACION CERRADA, CREAR NUEVA CON MES SIGUIENTE
                $year = $conciliacion->year;
                $start_balance = 0;
                if ($conciliacion->month==12){
                    $month = 1;
                    $year++;
                }
                else{
                    $month=$conciliacion->month+1;
                }
                $start_balance=$conciliacion->countable_balance;

                //CREANDO NUEVA CONCILIACION
                $conciliacion = new BankReconciliation();
                $conciliacion->date = date('y-m-d');
                $conciliacion->month = $month;
                $conciliacion->year = $year;
                $conciliacion->closed = 0;
                $conciliacion->account_id = $id;
                $conciliacion->start_balance = $start_balance;
                $conciliacion->countable_balance = 0;
                $conciliacion->bank_balance = 0;
                $conciliacion->user_id = Auth::user()->id;
                $conciliacion->created_by = Auth::user()->id;
                $conciliacion->updated_by = Auth::user()->id;
                $conciliacion->save();
            }
        }
        #endregion

        #region LISTAR LAS TRANSACCIONES DE LA CUENTA
        $fecha2 = Input::get('date2');
        $fechaActual = date("Y-m-d");

        if ($fecha2 == null) {
            $fecha2 = $fechaActual;
        } else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2 = $nuevaFecha2[0];
            $mesFecha2 = $nuevaFecha2[1];
            $anioFecha2 = $nuevaFecha2[2];
            $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2 ;
        }
        $acc_p = Account::leftJoin('bank_tx_payments as p', 'bank_accounts.id', '=', 'p.account_id')
            ->join('pagos', 'p.payment_method', '=', 'pagos.id')
            ->join('users', 'users.id', '=', 'p.user_id')
            ->where('bank_accounts.id', $id)
            ->where('p.reconciliation_id', null)
            ->where('p.status', '!=', 2)
            ->where('p.paid_at','<=', $fecha2)
            ->select('p.id', 'p.paid_at', 'p.reconcilied', 'p.amount', 'bank_accounts.account_name', 'p.description', 'p.reference', 'users.name as user', 'pagos.name as payment_method', DB::raw("'banks/expenses_accounts' as route"), DB::raw("'Gasto' as tipo"));

        $accounts = Account::leftJoin('bank_tx_revenues as p', 'bank_accounts.id', '=', 'p.account_id')
            ->join('pagos', 'p.payment_method', '=', 'pagos.id')
            ->join('users', 'users.id', '=', 'p.user_id')
            ->where('bank_accounts.id', $id)
            ->where('p.reconciliation_id', null)
            ->where('p.status', '!=', 2)
            ->where('p.paid_at','<=', $fecha2)
            ->select('p.id', 'p.paid_at', 'p.reconcilied', 'p.amount', 'bank_accounts.account_name', 'p.description', 'p.deposit as reference', 'users.name as user', 'pagos.name as payment_method', DB::raw("'banks/revenues' as route"), DB::raw("'Ingreso' as tipo"))
            ->union($acc_p)
            ->orderBy('paid_at', 'desc')
            ->get();
        #endregion

        #region TOTAL DE DOCUMENTOS CONCILIADOS
        foreach($accounts as $key => $value){
            if ($value->reconcilied==1){
                if ($value->tipo=="Ingreso"){
                    $ingresos_conciliados+=$value->amount;
                }
                else{
                    $egresos_conciliados+=$value->amount;
                }
            }
            else{
                if ($value->tipo=="Ingreso"){
                    $transit_revenue+=$value->amount;
                }
                else{
                    $outstanding_payments+=$value->amount;
                }
            }
        }
        //ACTUALIZAR TOTALES DE CONCILIACIÓN
        $conciliacion->transit_revenue = $transit_revenue;
        $conciliacion->outstanding_payments = $outstanding_payments;
        $conciliacion->countable_balance = $account->pct_interes;
        $conciliacion->recon_expenses = $egresos_conciliados;
        $conciliacion->recon_revenues = $ingresos_conciliados;
        $conciliacion->save();

        //APLICAR FILTROS
        if (Input::get('type')==null){
            $tipo = 'all';
        }
        else{
            $tipo = Input::get('type');
        }


        if (Input::get('status')==null){
            $status='0';
        }
        else{
            $status = Input::get('status');
        }
        $datos_filtrados = $accounts->filter(function ($value){
            if (Input::get('type')==null){
                $tipo = 'all';
            }
            else{
                $tipo = Input::get('type');
            }


            if (Input::get('status')==null){
                $status='0';
            }
            else{
                $status = Input::get('status');
            }
            if ($status=='all'){
                if ( $tipo =='all'){
                    return $value;
                }
                else{
                    if ($value->tipo==$tipo){
                        return $value;
                    }
                }
            }
            else{
                if ($value->reconcilied==$status&&($tipo=='all' || $tipo==$value->tipo)){
                    return $value;
                }
            }
        });
        #endregion
        return view('banking.accounts.reconciliation.create')
            ->with('transactions', $accounts)
            ->with('datos_filtrados', $datos_filtrados)
            ->with('account', $account)
            ->with('status', $status)
            ->with('tipo', $tipo)
            ->with('conciliation', $conciliacion)
            ->with('fecha2', $fecha2);
    }
}
