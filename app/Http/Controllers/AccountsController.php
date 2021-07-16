<?php

namespace App\Http\Controllers;

use App\Project;
use App\Retention;
use App\Traits\TransactionsTrait;
use App\Traits\UserTrait;
use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Account;
use App\BankAccountType;
use App\User;
use App\Pago;
use App\StateCellar;
use \Redirect;
use \Auth;
use \Session, \Input;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    use TransactionsTrait;
    use UserTrait;

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
        $status = StateCellar::where('type_number', 1)->get();
        $selected = Input::get('status_id');
        if ($selected== null){
            $selected = $status[0]->id;
        }
        $accounts = Account::NotType(7)
            ->where('status', $selected)
            ->get();
        
        return view('banking.accounts.index', compact('accounts'))
            ->with('view_name','accounts.bank_accounts')
            ->with('status', $status)
            ->with('selected', $selected)
            ->with('create_route','banks/accounts/create')
            ->with('type','accounts');
            // ->with('permisos',$permisos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $pago = Pago::all();
        $state = StateCellar::all();
        $account_type = BankAccountType::bankAccount()->get();

        return view('banking.accounts.create', compact('users', 'pago', 'state','account_type'));
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
        try {
            $request->account_number = ($request->account_number === "" ? null : $request->account_number);
            $request->pago_id = ($request->pago_id === "null" ? null : $request->pago_id);
            $validator = Validator::make($request->all(), [
                'account_name' => 'required',
                'account_number' => 'string|unique:bank_accounts',
            ]);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }

            $val = Account::whereAccount_name($request->account_name)->count();
            if ($val > 0) {
                throw new \Exception('El el nombre de la cuenta ingresada ya esta registrado en el sistema', 6);
            }

            $account = new Account;
            $account->account_name = $request->account_name;
            $account->account_number = $request->account_number;
            $account->bank_name = $request->bank_name;
            $account->bank_id = $request->bank_id;
            $account->account_type_id = $request->account_type_id;
//            $account->opening_balance = $request->opening_balance;
//        $account->balance = $request->opening_balance;
            $account->max_amount = $request->max_amount;
            $account->pago_id = $request->pago_id;
            $account->account_responsible = $request->account_responsible;
            $account->status = $request->status;
            $account->user_id = $request->user_id;

            $account->save();

            /*
             * CREAR INGRESO CON EL BALANCE INICIAL
             * */
            if ($request->opening_balance>0){
                $opening_balance = str_replace(",", "", $request->opening_balance);
                $opening_balance = (double)$opening_balance;
                $ingresos = [];
                $ingresos['account_id'] = $account->id;
                $ingresos['paid_at'] = date('d/m/Y');
                $ingresos['amount'] = $opening_balance;
                $ingresos['description'] = "Saldo de apertura";
                $ingresos['user_id'] = Auth::user()->id;
                $ingresos['status'] = 5;
                $ingresos['payment_method'] = 1;
                $ingresos['bank_name'] = "Saldo de apertura";
                $ingresos['same_bank'] = 1;
                $ingresos['amount_applied'] = $request->opening_balance;
                $nuevo = new Request($ingresos);
                $guardar = $this->saveRevenue($nuevo);
                if ($guardar[0] < 0) {
                    Session::flash('message', trans('revenues.save_error'));
                    Session::flash('alert-type', 'error');
                    return redirect('banks/accounts/create')
                        ->withErrors($guardar[1])
                        ->withInput();
                }
            }
            DB::commit();
            Session::flash('message', trans('accounts.save_ok'));
        }
        catch(\Exception $ex){
            DB::rollBack();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return redirect('banks/accounts/create')
                ->withInput();
        }
        


        return redirect('banks/accounts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::find($id);

        // dd($account);
        return view('banking.accounts.show', compact('account'));
    }


    public function statement($id)
    {
        $all = Input::get('all');
        $fecha1 = Input::get('date1');
        $fecha2 = Input::get('date2');

        $fechaActual = date("Y-m-d");
        if ($fecha1 == null) {
            $fecha1 = $fechaActual;
        } else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1 = $nuevaFecha1[0];
            $mesFecha1 = $nuevaFecha1[1];
            $anioFecha1 = $nuevaFecha1[2];
            $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1;
        }

        if ($fecha2 == null) {
            $fecha2 = $fechaActual;
        } else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2 = $nuevaFecha2[0];
            $mesFecha2 = $nuevaFecha2[1];
            $anioFecha2 = $nuevaFecha2[2];
            $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2 ;
        }
        
        $account = Account::find($id);

            $acc_p = Account::leftJoin('bank_tx_payments as p', 'bank_accounts.id', '=', 'p.account_id')
                ->join('pagos', 'p.payment_method', '=', 'pagos.id')
                ->join('users', 'users.id', '=', 'p.user_id')
                ->join('state_cellars as st', 'st.id', '=', 'p.status')
                ->where('bank_accounts.id', $id);
                if ($all=='false'){
                    $acc_p->whereBetween('p.paid_at', [$fecha1, $fecha2]);
                }
                $acc_p->select('p.id', DB::raw('null as serie_id'),'st.name as status','p.paid_at', 'p.amount', 'p.payment_method as pm', 'bank_accounts.account_name', 'p.description', 'p.reference', 'users.name as user', 'pagos.name as payment_method','p.created_at', DB::raw("'banks/expenses_accounts' as route"), DB::raw("'Gasto' as tipo"));

            $acc_r = Account::leftJoin('bank_tx_revenues as p', 'bank_accounts.id', '=', 'p.account_id')
                ->join('pagos', 'p.payment_method', '=', 'pagos.id')
                ->join('users', 'users.id', '=', 'p.user_id')
                ->join('state_cellars as st', 'st.id', '=', 'p.status')
                ->where('bank_accounts.id', $id);
                if ($all=='false'){
                    $acc_r->whereBetween('p.paid_at', [$fecha1, $fecha2]);
                }
                $acc_r->select('p.id', 'p.serie_id','st.name as status', 'p.paid_at', 'p.amount', 'p.payment_method as pm', 'bank_accounts.account_name', 'p.description', 'p.reference', 'users.name as user', 'pagos.name as payment_method','p.created_at' , DB::raw("'banks/revenues' as route"), DB::raw("'Ingreso' as tipo"));

                $accounts = $acc_r
                ->unionAll($acc_p)
                ->orderby('paid_at','asc')
                ->orderby('created_at','asc')
                ->get();



        $account_list = Account::all();

        // dd($accounts);
        $url = 'banks.accounts.statement';

        //VERIFICAR PERMISOS DE ANULACION
        $anul_expenses = (Auth::user()->verifyPermission('Anular egresos de cuenta [M. bancos]'));
        $anul_revenues = (Auth::user()->verifyPermission('Anular ingresos de cuenta [M. bancos]'));
        return view('banking.accounts.statement', compact('accounts', 'account', 'account_list'))
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('anl_exp', $anul_expenses)
            ->with('anl_rev', $anul_revenues)
            ->with('url',$url);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getResponsible($account)
    {
        $ac = Account::find($account);
        return $ac->responsible->name;
    }
    public function edit($id)
    {
        // $users = User::all()->pluck('id', 'name');
        $users = User::all();
        // $pago = Pago::all()->pluck('id', 'name');
        $pago = Pago::all();

        $account = Account::find($id);
        $state = StateCellar::all();
        $account_type = BankAccountType::bankAccount()->get();

        return view('banking.accounts.edit', compact('account', 'users', 'pago', 'state','account_type'));
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
        DB::beginTransaction();
        try {
            $request->account_number = ($request->account_number === "" ? null : $request->account_number);
            $request->pago_id = ($request->pago_id === "null" ? null : $request->pago_id);
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'account_name' => 'required|unique:bank_accounts,account_name,' . $id,
                'account_number' => 'string|unique:bank_accounts,account_number,' . $id,
            ]);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }
            // $account = Account::find($id);
            // $account->update($request->all());

            $account = Account::find($id);
            $account->account_name = $request->account_name;
            $account->account_number = $request->account_number;
            $account->bank_name = $request->bank_name;
            $account->bank_id = $request->bank_id;
            $account->account_type_id = $request->account_type_id;
            $account->opening_balance = $request->opening_balance;
            $account->max_amount = $request->max_amount;
            $account->pago_id = $request->pago_id;
            $account->account_responsible = $request->account_responsible;
            $account->status = $request->status;
            $account->user_id = $request->user_id;
            $account->save();

            DB::commit();
            Session::flash('message', trans('accounts.update_ok'));
        }
        catch(\Exception $e){
            DB::rollback();
            Session::flash('message', $e->getMessage());
            Session::flash('alert-class', 'alert-error');
            return redirect('banks/accounts/' . $id . '/edit')
                ->withInput();
        }
        return redirect('banks/accounts');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = Account::find($id);

        /**
         * VERIFICAR SI LA CUENTA NO ESTA ASOCIADA A UN PROYECTO ACTIVO
         */
        $project = Project::where('account_id', $id)->count();
        if ($project > 0){
            Session::flash('message', 'No se puede desactivar la cuenta porque esta asociada a un proyecto. Debe inhabilitar el proyecto para que el estado de la cuenta se actualice.');
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('banks/accounts');
        }

        /**
         * Verificar si la cuenta no esta asociada a una retención
         */
        $retention = Retention::where('account_id', $id)->count();
        if ($retention > 0){
            Session::flash('message', 'No se puede desactivar la cuenta porque esta asociada a una retención. Debe inhabilitar la retención para que el estado de la cuenta se actualice.');
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('banks/accounts');
        }

        if ($account->delete()) {

            $message = trans('accounts.deleted_ok');
        } else {
            $message = trans('accounts.integrity_violation');
            Session::flash('alert-class', 'alert-error');
        }

        Session::flash('message', $message);

        return redirect('banks/accounts');
    }

    public function getPaymentTypeIn($account_id)
    {
        $payments = Pago::join('bank_accounts_pagos','pagos.id','=','bank_accounts_pagos.pago_id')
        ->join('bank_accounts','bank_accounts.account_type_id','=','bank_accounts_pagos.bank_account_type_id')
        ->where('bank_accounts.id',$account_id)
        ->where('bank_accounts_pagos.ingreso',1)
        ->select('pagos.id','pagos.type','pagos.name')->get();

        return response()->json($payments);
    }

    public function getPaymentTypeOut($account_id)
    {
        $payments = Pago::join('bank_accounts_pagos','pagos.id','=','bank_accounts_pagos.pago_id')
        ->join('bank_accounts','bank_accounts.account_type_id','=','bank_accounts_pagos.bank_account_type_id')
        ->where('bank_accounts.id',$account_id)
        ->where('bank_accounts_pagos.ingreso',0)
        ->select('pagos.id','pagos.type','pagos.name')->get();

        return response()->json($payments);
    }

    public function getAccountForTransfer($account_id)
    {
        $accounts = Account::whereNotIn('id',[$account_id])
        ->select('bank_accounts.id','bank_accounts.account_name as name','bank_accounts.pct_interes')->get();

        return response()->json($accounts);
    }

    public function getAccountType($pago_id,$ingreso)
    {
        $accounts = Account::join('bank_accounts_pagos','bank_accounts.account_type_id','=','bank_accounts_pagos.bank_account_type_id')
        ->join('pagos','pagos.id','=','bank_accounts_pagos.pago_id')
        ->where('pagos.id',$pago_id)
        ->where('bank_accounts_pagos.ingreso',$ingreso)
        ->select('bank_accounts.id','bank_accounts.account_name as name','bank_accounts.pct_interes')->get();

        return response()->json($accounts);
    }
    public function getAccountTypeDeposit($pago_id)
    {
        $accounts = Account::join('bank_accounts_pagos','bank_accounts.account_type_id','=','bank_accounts_pagos.bank_account_type_id')
            ->join('pagos','pagos.id','=','bank_accounts_pagos.pago_id')
            ->where('pagos.id',$pago_id)
            ->where('bank_accounts_pagos.ingreso',1)
            ->where('account_type_id', '!=', 7)
            ->select('bank_accounts.id','bank_accounts.account_name as name','bank_accounts.pct_interes')->get();

        return response()->json($accounts);
    }

    public function getAccountTypeMoney($pago_id)
    {
        $accounts = Account::join('bank_accounts_pagos','bank_accounts.account_type_id','=','bank_accounts_pagos.bank_account_type_id')
            ->join('pagos','pagos.id','=','bank_accounts_pagos.pago_id')
            ->where('pagos.id',$pago_id)
            ->where('bank_accounts_pagos.ingreso',1)
            ->where('account_type_id', '=', 7)
            ->select('bank_accounts.id','bank_accounts.account_name as name','bank_accounts.pct_interes')->get();
        return response()->json($accounts);
    }

    public function getAccountProject($id){
        $account = Account::where('id', $id)
            ->select('bank_accounts.id','bank_accounts.account_name as name','bank_accounts.pct_interes')
            ->get();
        return response()->json($account);
    }

}
