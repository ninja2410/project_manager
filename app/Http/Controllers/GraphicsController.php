<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Charts\CobroChart;

use App\Route;
use App\target;
use App\Pagare;
use App\Traits\TraitPagares;
use App\Customer;
use App\PagareDetail;
use \Input;
use Illuminate\Support\Facades\DB;


class GraphicsController extends Controller
{
  use TraitPagares;
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
        //
  }

  public function cobrochartajax(Request $request)
  {

    $fecha1 = $request->input('date1');
    $fecha2 = $request->input('date2');


    $fechaActual = date("Y-m-d");
    if ($fecha1 == null) {
      $fecha1 = $fechaActual . ' 00:00:00';
    } else {
      $nuevaFecha1 = explode('/', $fecha1);
      $diaFecha1 = $nuevaFecha1[0];
      $mesFecha1 = $nuevaFecha1[1];
      $anioFecha1 = $nuevaFecha1[2];
      $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1 . ' 00:00:00';
    }

    if ($fecha2 == null) {
      $fecha2 = date("Y-m-t") . ' 23:59:59';
    } else {

      $nuevaFecha2 = explode('/', $fecha2);
      $diaFecha2 = $nuevaFecha2[0];
      $mesFecha2 = $nuevaFecha2[1];
      $anioFecha2 = $nuevaFecha2[2];
      $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2 . ' 23:59:59';
    }
        // echo 'fecha1 ' . $fecha1 . '<br>';
        // echo 'fecha2 ' . $fecha2 . '<br>';
        // dd($request->all());
    $data = Route::join('targets', 'routes.id', '=', 'targets.route_id')
      ->leftjoin('pagares', function ($join) {
        $join->on('pagares.route_id', '=', 'routes.id')
          ->where('pagares.status', '=', 1);
      })
      ->leftjoin('pagare_details', function ($join) use ($fecha1, $fecha2) {
        $join->on('pagares.id', '=', 'pagare_details.pagare_id')
          ->where('pagare_details.date_real', '>=', $fecha1)
          ->where('pagare_details.date_real', '<=', $fecha2);
                    // ->whereBetween('pagare_details.date_real', array($fecha1, $fecha2));
      })
      ->select(DB::raw('routes.name,convert(avg(targets.goal),DECIMAL(12,2)) as meta, sum(pagare_details.total_payment) cobrado, sum(pagare_details.surcharge_recived) mora
'))
      ->groupBy('routes.name')
      ->get();
        // dd($data);

    return $data;
  }


  public function cobrochart(Request $request)
  {

    $fecha1 = Input::get('date1');
    $fecha2 = Input::get('date2');

    $fechaActual = date("Y-m-1");
    if ($fecha1 == null) {
      $fecha1 = $fechaActual . ' 00:00:00';
    } else {
      $nuevaFecha1 = explode('/', $fecha1);
      $diaFecha1 = $nuevaFecha1[0];
      $mesFecha1 = $nuevaFecha1[1];
      $anioFecha1 = $nuevaFecha1[2];
      $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1 . ' 00:00:00';
    }

    if ($fecha2 == null) {
      $fecha2 = date("Y-m-t") . ' 23:59:59';
    } else {

      $nuevaFecha2 = explode('/', $fecha2);
      $diaFecha2 = $nuevaFecha2[0];
      $mesFecha2 = $nuevaFecha2[1];
      $anioFecha2 = $nuevaFecha2[2];
      $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2 . ' 23:59:59';
    }

    $data_graph = Route::join('targets', 'routes.id', '=', 'targets.route_id')
      ->leftjoin('pagares', function ($join) {
        $join->on('pagares.route_id', '=', 'routes.id')
          ->where('pagares.status', '=', 1);
      })
      ->leftjoin('pagare_details', function ($join) use ($fecha1, $fecha2) {
        $join->on('pagares.id', '=', 'pagare_details.pagare_id')
          ->where('pagare_details.date_real', '>=', $fecha1)
          ->where('pagare_details.date_real', '<=', $fecha2);
      })
      ->select(DB::raw('routes.name,coalesce(convert(avg(targets.goal),DECIMAL(12,2)),0) as meta, coalesce(sum(pagare_details.total_payment),0) cobrado, (select sum(d.amount) from pagares p join pagare_details d on (p.id = d.pagare_id) where datediff(curdate(),date_payment) > 3 and total_payment=0 and p.status = 1 and p.route_id=routes.id)  mora'))
      ->groupBy('routes.name')
      ->get();
        // dd($data_graph);
    return view('charts/cobro-chart', compact('data_graph'))
      ->with('fecha1', $fecha1)
      ->with('fecha2', $fecha2);
        // return view('charts/cobrochart', compact('chart'));
  }



  public function renovationReal(Request $request)
  {
    $rutas = Route::where('state_id', 1)->get();
    $r = 0;
    if (isset($request->date1)) {
      $fecha = $request->date1;
      $array = explode("/", $request->date1);
      $month = $array[0];
      $year = $array[1];
    } else {
      $fecha = date("m/Y");
      $month = date("m");
      $year = date("Y");
    }
    $r = $request->route;

    $totalCobrado = 0;
    $totalLiquido = 0;
    $totalRenovado = 0;
    $query = PagareDetail::join('pagares', 'pagares.id', '=', 'pagare_details.pagare_id');
    $query->join('routes', 'routes.id', '=', 'pagares.route_id');
    $query->whereMonth('date_real', '=', $month)
      ->whereYear('date_real', '=', $year);
    if ($r != 0) {
      $query->where('pagares.route_id', $r);
      $query->groupBy('pagares.id')
        ->select(
          'pagares.number_card',
          DB::raw('SUM(total_payment) as COBRADO'),
          DB::raw('SUM(surcharge_recived) as MORA'),
          DB::raw('pagares.pending_payment')
        );
    } else {
      $query->groupBy('pagares.route_id')
        ->select(
          'routes.id',
          'routes.name',
          DB::raw('SUM(total_payment) as COBRADO'),
          DB::raw('SUM(surcharge_recived) as MORA'),
          DB::raw('pagares.pending_payment')
        );
    }
    $data_graph = $query->get();
    foreach ($data_graph as $key => $i) {
      $totalCobrado += $i->COBRADO + $i->MORA;
      $totalRenovado += $i->pending_payment;
      $totalLiquido += $i->COBRADO + $i->MORA - $i->pending_payment;
    }
    return view('charts/renovation-real', compact('data_graph'))
      ->with('totalRenovado', $totalRenovado)
      ->with('fecha', $fecha)
      ->with('rutas', $rutas)
      ->with('ruta', $r)
      ->with('totalLiquido', $totalLiquido)
      ->with('totalCobrado', $totalCobrado);
  }

  public function morachart(Request $request)
  {
    $fecha1 = Input::get('date1');
    $fecha2 = Input::get('date2');

    $fechaActual = date("Y-m-1");
    if ($fecha1 == null) {
      $fecha1 = $fechaActual . ' 00:00:00';
    } else {
      $nuevaFecha1 = explode('/', $fecha1);
      $diaFecha1 = $nuevaFecha1[0];
      $mesFecha1 = $nuevaFecha1[1];
      $anioFecha1 = $nuevaFecha1[2];
      $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1 . ' 00:00:00';
    }

    if ($fecha2 == null) {
      $fecha2 = date("Y-m-t") . ' 23:59:59';
    } else {

      $nuevaFecha2 = explode('/', $fecha2);
      $diaFecha2 = $nuevaFecha2[0];
      $mesFecha2 = $nuevaFecha2[1];
      $anioFecha2 = $nuevaFecha2[2];
      $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2 . ' 23:59:59';
    }
    /* obtengo el listado de clientes morosos */
    $listado_morosos = Pagare::join('pagare_details', 'pagares.id', '=', 'pagare_details.pagare_id')
      ->select('pagares.id')
      ->where('pagares.status', '=', 1)
      ->whereRaw('datediff(curdate(),date_payment) > 3 and total_payment=0')
      // ->whereRaw('datediff(curdate(),date_payment) > pagares.days_mora and total_payment=0')
      ->distinct()->get();

     /* Valido si esta vacio para que no den error los queries posteriores */
    if (count($listado_morosos) == 0) {
      $listado_morosos = [0, 0];
    }
    $listado_morosos = json_decode(json_encode($listado_morosos), true);


    /* Obtengo saldo de 'todos' los clientes menos los morosos */
    $all = Route::leftjoin('pagares', function ($join) use ($listado_morosos) {
      $join->on('pagares.route_id', '=', 'routes.id')
        ->where('pagares.status', '=', 1)
        ->whereNotIn('pagares.id', $listado_morosos);
    })
      ->leftjoin('pagare_details', function ($join) {
        $join->on('pagares.id', '=', 'pagare_details.pagare_id')
          ->Where('total_payment', '=', 0);
      })
      ->select(DB::raw('routes.id,routes.name, coalesce(sum(pagare_details.amount),0) as amount, coalesce(sum(pagare_details.total_payment),0) as total_payment, 0 as amount_mora, 0 pagado_mora'))
      ->groupBy('routes.id', 'routes.name')->get();
    // dd($all);

    // dd($listado_morosos);
    /* Obtengo saldo solo los clientes  morosos */
    $morosos = Route::leftjoin('pagares', function ($join) use ($listado_morosos) {
      $join->on('pagares.route_id', '=', 'routes.id')
        ->where('pagares.status', '=', 1)
        ->whereIn('pagares.id', $listado_morosos);
    })
      ->leftjoin('pagare_details', function ($join) {
        $join->on('pagares.id', '=', 'pagare_details.pagare_id')
          ->Where('total_payment', '=', 0);
      })
      ->select(DB::raw('routes.name, coalesce(sum(pagare_details.amount),0) as amount, coalesce(sum(pagare_details.total_payment),0) pagado'))
      ->groupBy('routes.name')->get();

        // var_dump($all);
        // echo '<br><br>';
        // dd($morosos);
    return view('charts/mora-chart', compact('all', 'morosos'))
      ->with('fecha1', $fecha1)
      ->with('fecha2', $fecha2);
        // return view('charts/cobrochart', compact('chart'));
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
        //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
        //
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
  public function earnings(Request $request)
  {
    $routes = Route::where('state_id', 1)
      ->get();
    if (isset($request->date1)) {
      $fecha = $request->date1;
      $array = explode("/", $request->date1);
      $month = $array[0];
      $year = $array[1];
    } else {
      $fecha = date("m/Y");
      $month = date("m");
      $year = date("Y");
    }
    if (isset($request->route)) {
      $r = $request->route;
    } else {
      $r = 0;
    }
    $query = PagareDetail::join('pagares', 'pagares.id', '=', 'pagare_details.pagare_id');
    $query->join('customers', 'customers.id', '=', 'pagares.customer_id');
    $query->join('routes', 'routes.id', '=', 'pagares.route_id');
    if ($r != 0) {
      $query->where('pagares.route_id', $r);
    }
    $query->whereMonth('date_real', '=', $month)
      ->whereYear('date_real', '=', $year);
      //$query->where('total_payment', '>', 0)->orWhere('surcharge_recived', '>', 0);
    if ($r == 0) {
      $query->select(
        'route_id',
        'routes.name as Route',
        DB::raw('((SUM(total_payment)/((100+pagares.ptc_interes)/100))*pagares.ptc_interes/100) as Interes'),
        DB::raw('(SUM(surcharge_recived)) AS Mora'),
        DB::raw('(SUM(total_payment)) AS PAGADO')
      );
      $query->groupBy('pagares.route_id');
    } else {
      $query->select(
        'date_real',
        'pagares.id',
        'customers.name',
        'pagares.number_card',
        DB::raw('((SUM(total_payment)/((100+pagares.ptc_interes)/100))*pagares.ptc_interes/100) as Interes'),
        DB::raw('(SUM(surcharge_recived)) AS Mora'),
        DB::raw('(SUM(total_payment)) AS PAGADO')
      );
      $query->groupBy('pagares.id');
    }
    $data_graph = $query->get();
    $totalInteres = 0;
    $totalMora = 0;
    foreach ($data_graph as $key => $value) {
      $totalInteres += $value->Interes;
      $totalMora += $value->Mora;
    }
    $totalGanancia = $totalInteres + $totalMora;
    return view('charts.earnings')
      ->with('data_graph', $data_graph)
      ->with('totalInteres', $totalInteres)
      ->with('rutas', $routes)
      ->with('ruta', $r)
      ->with('fecha', $fecha)
      ->with('totalGanancia', $totalGanancia)
      ->with('totalMora', $totalMora);
  }
  public function liquidityIndex(Request $request)
  {
    $routes = Route::where('state_id', 1)
      ->get();
    if (isset($request->route)) {
      $r = $request->route;
    } else {
      $r = 0;
    }

    $query = PagareDetail::join('pagares', 'pagares.id', '=', 'pagare_details.pagare_id');
    $query->join('customers', 'customers.id', '=', 'pagares.customer_id');
    if ($r != 0) {
      $query->where('route_id', $r);
    }
    $query->whereIn('pagares.status', array(0, 1, 4));
    $query->groupBy('customers.id');
    $query->select('customers.id', 'customers.name', DB::raw('COUNT(pagare_details.id) as CUOTAS'), DB::raw('(SELECT COUNT(pagare_details.id) FROM pagare_details where delay=1 and pagare_id=pagares.id) as ATRASOS'));
    $data = $query->get();


    return view('report.liquidityIndex')
      ->with('rutas', $routes)
      ->with('ruta', $r)
      ->with('data', $data);
  }

  function defailter(Request $request)
  {
      //ACTUALIZAR TODOS LOS CRÉDITOS ACTIVOS
    $fecha = date('Y-m-d');
    $creditos = Pagare::where('status', 1)->get();
    try {
      foreach ($creditos as $key => $value) {
        $this->updateStatus($value->id, $fecha);
      }
    } catch (\Exception $e) {
      dd($e->getMessage());
    }
    $r = 0;
    $routes = Route::where('state_id', 1)
      ->get();
    $query = PagareDetail::join('pagares', 'pagares.id', '=', 'pagare_details.pagare_id');
    $query->join('customers', 'customers.id', '=', 'pagares.customer_id');
    $query->where('pagares.status', 1);
    if (isset($request->route) && $request->route != 0) {
      $r = $request->route;
      $query->where('route_id', $request->route);
    }
    $query->where('delay', 1);
    $query->where('pagare_details.surcharge_recived', 0);
    $query->where('pagare_details.total_payment', 0);
    $query->select(
      'customers.id',
      'customers.name',
      'pagares.number_card',
      DB::raw('(COUNT(pagare_details.id)) AS ATRASOS')
    );
    $query->groupBy('customers.id');
    $query->having('ATRASOS', '>', 3);
    $data = $query->get();
    return view('report.customers_pending_pay')
      ->with('rutas', $routes)
      ->with('ruta', $r)
      ->with('data', $data);
  }
}
