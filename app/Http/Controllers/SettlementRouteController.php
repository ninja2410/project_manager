<?php

namespace App\Http\Controllers;

use App\CreditPayment;
use App\Expense;
use App\Pago;
use App\Revenue;
use App\Route;
use App\RouteUser;
use App\Sale;
use App\SettlementRoute;
use App\SettlementRouteDetail;
use http\Url;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Auth, \Redirect, \Validator, \Input, \Session;
use mysql_xdevapi\Exception;

class SettlementRouteController extends Controller
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
    public function index($id)
    {
        #region FILTROS DE FECHA
        $fecha1=Input::get('date1');
        $fecha2=Input::get('date2');

        $fechaActual=date("Y-m-d");
        if($fecha1==null){
            $fecha1= date("Y-m-d", strtotime($fechaActual."- 1 week")) .' 00:00:00';
            $mesFecha1 = date("m");
            $anioFecha1 = date("Y");
        }else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
        }

        if($fecha2==null){
            $fecha2=$fechaActual.' 23:59:59';
        }else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2=$nuevaFecha2[0];
            $mesFecha2=$nuevaFecha2[1];
            $anioFecha2=$nuevaFecha2[2];
            $fecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';
        }
        #endregion
        $detail = SettlementRoute::where('route_id', $id)
            ->whereBetween('date_begin', [$fecha1, $fecha2])
            ->get();
        $url = url('/routes/settlement/'.$id);
        $route = Route::find($id);
        return view('route.settlement.index')
            ->with('url', $url)
            ->with('route', $route)
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('detail', $detail);
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
        $gastos = json_decode($request->json_gastos);
        $ventas = json_decode($request->json_ventas);
        $pagos = json_decode($request->json_payments);
        $detalle_manual = json_decode($request->detail_payments);
        $total_gastos = 0;
        $total_ventas = 0;
        $total_pagos = 0;
        DB::beginTransaction();
        try {
            #region FILTROS DE FECHA
            $fecha1=Input::get('date1');
            $fecha2=Input::get('date2');

            $fechaActual=date("Y-m-d");
            if($fecha1==null){
                $fecha1= date("Y-m-d", strtotime($fechaActual."- 1 week")) .' 00:00:00';
                $mesFecha1 = date("m");
                $anioFecha1 = date("Y");
            }else {
                $nuevaFecha1 = explode('/', $fecha1);
                $diaFecha1=$nuevaFecha1[0];
                $mesFecha1=$nuevaFecha1[1];
                $anioFecha1=$nuevaFecha1[2];
                $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
            }

            if($fecha2==null){
                $fecha2=$fechaActual.' 23:59:59';
            }else {

                $nuevaFecha2 = explode('/', $fecha2);
                $diaFecha2=$nuevaFecha2[0];
                $mesFecha2=$nuevaFecha2[1];
                $anioFecha2=$nuevaFecha2[2];
                $fecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';
            }
            #endregion


            #region VERIFICAR SI NO EXISTE UNA LIQUIDACIÓN QUE ESTE ENTRE LAS FECHAS SELECCIONADAS
            $f1 = date('Y-m-d', strtotime($fecha1));
            $f2 = date('Y-m-d', strtotime($fecha2));
            if ($f1>$f2){
                throw new \Exception("La fecha de inicio no debe ser mayor que la fecha final.");
            }

            $counter = SettlementRoute::where('date_end', '>=', $f1)
                ->where('route_id', $request->route_id)
                ->count();
            if ($counter>0){
                throw new \Exception("Ya existe una liquidación de ruta dentro del rango de fechas seleccionada.");
            }
            #endregion

            #region CREAR ENCABEZADO
            $header = new SettlementRoute();
            $header->tour = $request->tour;
            $header->week = $request->week;
            $header->date_begin = $request->date1;
            $header->date_end = $request->date2;
            $header->comment_expenses = $request->comment_expense;
            $header->comment_sales = $request->comment_sales;
            $header->comment_payments = $request->comment_payments;
            $header->diference = $request->diference;
            $header->comission = str_replace(",", "", $request->comission);
            $header->user_asigned = $request->user_asigned;
            $header->route_id = $request->route_id;
            $header->created_by = Auth::user()->id;
            $header->updated_by = Auth::user()->id;
            $header->save();
            #endregion

            #region GUARDAR DETALLE DE GASTOS
            foreach ($gastos as $gasto){
                $detalle = new SettlementRouteDetail();
                $detalle->amount = $gasto->amount;
                $detalle->expense_category_id = $gasto->category_id;
                $detalle->settlement_route_id = $header->id;
                $detalle->type = 1;
                $detalle->save();
                $total_gastos += $gasto->amount;
            }
            #endregion

            #region GUARDAR DETALLE DE VENTAS
            foreach ($ventas as $venta){
                $detalle = new SettlementRouteDetail();
                $detalle->amount = $venta->monto;
                $detalle->serie_id = $venta->serie_id;
                $detalle->quantity = $venta->Facturas;
                $detalle->settlement_route_id = $header->id;
                $detalle->type = 2;
                $detalle->save();
                $total_ventas += $venta->monto;
            }
            #endregion

            #region GUARDAR DETALLE DE PAGOS
            foreach ($pagos as $pago){
                $detalle = new SettlementRouteDetail();
                $detalle->amount = $pago->monto;
                $detalle->quantity = $pago->contador;
                $detalle->serie_id = $pago->serie_id;
                $detalle->settlement_route_id = $header->id;
                $detalle->type = 3;
                $detalle->save();
                $total_pagos += $pago->monto;
            }
            #endregion

            #region GUARDAR DETALLE DE DESGLOCE MANUAL
            foreach ($detalle_manual as $det){
                $detalle = new SettlementRouteDetail();
                $detalle->payment_type_id = $det->pago_id;
                $detalle->amount = str_replace(",", "", $det->value);
                $detalle->type = 4;
                $detalle->settlement_route_id = $header->id;
                $detalle->save();
            }
            #endregion

            #region ACTUALIZAR TOTALES CALCULADOS
            $header->amount_expenses = $total_gastos;
            $header->amount_sales = $total_ventas;
            $header->amount_payments = $total_pagos;
            $header->update();
            #endregion
            DB::commit();
            Session::flash('message', "Registro almacenado correctamente");
            return Redirect::to('routes/settlement/show/'. $header->id);
        }
        catch (\Exception $e){
            DB::rollback();
            Session::flash('message', 'Error: '.$e->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('routes/settlement/'. $request->route_id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $header = SettlementRoute::find($id);
        $gastos = SettlementRouteDetail::where('settlement_route_id', $id)
            ->where('type', 1)
            ->get();
        $ventas = SettlementRouteDetail::where('settlement_route_id', $id)
            ->where('type', 2)
            ->get();
        $cobros = SettlementRouteDetail::where('settlement_route_id', $id)
            ->where('type', 3)
            ->get();
        $detalle_manual = SettlementRouteDetail::where('settlement_route_id', $id)
            ->where('type', 4)
            ->get();
        $array = explode('/', $header->date_begin);
        $month = $array[1]+0;
        $year = $array[2]+0;
        return view('route.settlement.show')
            ->with('month', $month)
            ->with('year', $year)
            ->with('gastos', $gastos)
            ->with('ventas', $ventas)
            ->with('cobros', $cobros)
            ->with('detalle_manual', $detalle_manual)
            ->with('header', $header);
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
        $enc = SettlementRoute::find($id);
        $route = $enc->route_id;
        $enc->delete();
        Session::flash('message', "Liquidación eliminada correctamente");
        return Redirect::to('routes/settlement/'. $route);
    }

    public function settRoute($route_id){
        #region FILTROS DE FECHA
        $fecha1=Input::get('date1');
        $fecha2=Input::get('date2');

        $fechaActual=date("Y-m-d");
        if($fecha1==null){
            $fecha1= date("Y-m-d", strtotime($fechaActual."- 1 week")) .' 00:00:00';
            $mesFecha1 = date("m");
            $anioFecha1 = date("Y");
        }else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
        }

        if($fecha2==null){
            $fecha2=$fechaActual.' 23:59:59';
        }else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2=$nuevaFecha2[0];
            $mesFecha2=$nuevaFecha2[1];
            $anioFecha2=$nuevaFecha2[2];
            $fecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';
        }
        #endregion

        $route = Route::find($route_id);
        $gastos = Expense::join('expense_categories', 'expense_categories.id', '=', 'expenses.category_id')
            ->where('route_id', $route_id)
            ->whereBetween('expenses.expense_date',[$fecha1,$fecha2])
            ->groupBy('expenses.category_id')
            ->select('expenses.category_id as category_id','expense_categories.name', DB::raw('sum(amount) as amount'))
            ->orderby('expense_categories.name', 'asc')
            ->get();
        $ventas = Sale::join('customers', 'customers.id', '=', 'sales.customer_id')
            ->join('series', 'sales.id_serie', '=', 'series.id')
            ->join('documents', 'documents.id', '=', 'series.id_document')
            ->join('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')
            ->where('route_costumers.route_id', $route_id)
            ->whereBetween('sales.sale_date',[$fecha1,$fecha2])
            ->groupBy('sales.id_serie')
            ->select('series.id as serie_id','documents.name as Document','series.name as Serie', DB::raw('sum(total_cost) as monto'), DB::raw('count(sales.id) as Facturas'))
            ->get();

        $cobros = CreditPayment::join('credits', 'credits.id', '=', 'credit_payments.credit_id')
            ->join('customers', 'customers.id', '=', 'credits.id_cliente')
            ->Join('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')
            ->join('bank_tx_revenues', 'bank_tx_revenues.id', '=', 'credit_payments.revenue_id')
            ->join('series', 'bank_tx_revenues.serie_id', '=', 'series.id')
            ->join('documents', 'documents.id', '=', 'series.id_document')
            ->where('route_costumers.route_id', $route_id)
            ->whereBetween('bank_tx_revenues.paid_at',[$fecha1,$fecha2])
            ->groupBy('bank_tx_revenues.serie_id')
            ->select('series.id as serie_id','documents.name as Document','series.name as Serie', DB::raw('count(bank_tx_revenues.id) as contador'), DB::raw('sum(bank_tx_revenues.amount) as monto'))
            ->get();
        $users = RouteUser::join('users', 'route_users.user_id', '=', 'users.id')
            ->where('route_id', $route_id)
            ->select('users.*')
            ->first();
        $pagos = Pago::where('banco_in', 1)->get();
        $month = $mesFecha1 + 0;
        $year = $anioFecha1;
        return view('route.settlement.create')
            ->with('user', $users)
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('month', $month)
            ->with('year', $year)
            ->with('gastos', $gastos)
            ->with('pagos', $pagos)
            ->with('ventas', $ventas)
            ->with('cobros', $cobros)
            ->with('route', $route);
    }
}
