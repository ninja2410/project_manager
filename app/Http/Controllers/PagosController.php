<?php namespace App\Http\Controllers;

use App\Account;
use App\BankAccountsPagos;
use App\BankAccountType;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Pago;
use App\Http\Requests\PagoRequest;
use Illuminate\Http\Request;
use \Auth, \Redirect, \Validator, \Input, \Session;
use DB;

class PagosController extends Controller
{
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
        $page = Pago::all();
        // dd($page);
        return view('pagos.index')->with('pagoss', $page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = BankAccountType::all()->pluck('name', 'id');
        $counter = Pago::all();
        return view('pagos.create')
        ->with('accounts',$accounts)
        ->with('counter',$counter);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PagoRequest $request)
    {
        $pago = new Pago();
        $pago->name = Input::get('name');
        $pago->type = input::get('type');
        $pago->venta = input::get('venta');
        $pago->orden_venta = input::get('orden_venta');
        $pago->compra = input::get('compra');
        $pago->orden_compra = input::get('orden_compra');
        $pago->banco_in = input::get('banco_in');
        $pago->orden_banco_in = input::get('orden_banco_in');
        $pago->banco_out = input::get('banco_out');
        $pago->orden_banco_out = input::get('orden_banco_out');

        $pago->save();

        // $pago->accountsin()->sync($request->input('accounts_in', []));

        $pago->accountsin()->attach($request->input('accounts_in', []),['ingreso' => 1]);
        $pago->accountsout()->attach($request->input('accounts_out', []),['ingreso' => 0]);

        // $pago->accountsout()->sync($request->input('accounts_out', []));

        Session::flash('message', 'Pago insertado correctamente');
        Session::flash('alert-type', trans('success'));
        return Redirect::to('pago');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pagos = Pago::find($id);
        $accounts = BankAccountType::all()->pluck('name', 'id');
        return view('pagos.edit')
            ->with('pagoss', $pagos)
            ->with('accounts',$accounts);
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
        // dd($request->all());
        $pagos = Pago::find($id);
        $pagos->name = input::get('name');
        $pagos->type = input::get('type');
        $pagos->venta = input::get('venta');
        $pagos->orden_venta = input::get('orden_venta');
        $pagos->compra = input::get('compra');
        $pagos->orden_compra = input::get('orden_compra');
        $pagos->banco_in = input::get('banco_in');
        $pagos->orden_banco_in = input::get('orden_banco_in');
        $pagos->banco_out = input::get('banco_out');
        $pagos->orden_banco_out = input::get('orden_banco_out');
        
        $pagos->save();

        $pagos->accountsin()->detach();
        $pagos->accountsin()->attach($request->input('accounts_in', []),['ingreso' => 1]);
        // $pagos->accountsout()->detach($request->input('accounts_out', []));
        $pagos->accountsout()->detach();
        $pagos->accountsout()->attach($request->input('accounts_out', []),['ingreso' => 0]);


        Session::flash('message', 'Actualizado correctamente');
        Session::flash('alert-type', trans('success'));
        return Redirect::to('pago');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $acc_pagos = BankAccountsPagos::where('pago_id',$id)->count();
            if($acc_pagos>0) {
                DB::table('bank_accounts_pagos')->where('pago_id','=',$id)->delete();
            };
                    
            $pagos = Pago::find($id);
            $pagos->delete();
            // redirect
            // Session::flash('message', 'You have successfully deleted employee');
            Session::flash('message', 'Registro eliminado correctamente');
            Session::flash('alert-type', trans('success'));
        } catch (\Illuminate\Database\QueryException $e) {
            Session::flash('message', 'Tipo de pago en al menos una transacción: No se puede eliminar');
            Session::flash('alert-class', 'alert-error');

        }
        return Redirect::to('pago');

    }

    public function getPaymentTypeByPrice($price_id)
    {
        $pagos = Pago::sale()
        ->join('module_prices','pagos.id','=','module_prices.pago_id')
        ->join('prices','prices.id','=','module_prices.price_id')
        ->where('prices.id',$price_id)
        // ->where('bank_accounts_pagos.ingreso',$ingreso)
        ->select('pagos.id','pagos.name','pagos.type')->get();

        return response()->json($pagos);
    }
}
