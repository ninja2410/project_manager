<?php namespace App\Http\Controllers;

use App\Item, App\Customer, App\Sale;
use App\Supplier, App\Receiving, App\User;
use App;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('parameter');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$items = Item::where('type', 1)->where('status',1)->count();
		// $item_kits = Item::where('type', 2)->count();
		$customers = Customer::count();
		$suppliers = Supplier::count();
		// $receivings = Receiving::count();
		// $sales = Sale::count();
		$today = Carbon::now();
		$todaym = Carbon::now();
		$last = $today->copy()->subMonth();
		// $table->whereBetween('date', [$today->startOfYear(), $today->endOfYear])
		// Ventas
		$inicio_anio= $today->startOfDay();
		$fin_anio= $today->copy()->endOfDay();

		$inicio_mes= $todaym->startOfMonth();
		$fin_mes= $todaym->copy()->endOfMonth();

		$inicio_mesa= $last->startOfMonth();
		$fin_mesa= $last->copy()->endOfMonth();


		$sales     =  Sale::where('cancel_bill','=','0')
			->almacen()
          	->whereBetween('sale_date', [$inicio_anio, $fin_anio] )
          	->sum('total_cost');

          $receivings     =  DB::table('receivings')->where('cancel_bill','=','0')
          ->whereBetween('date', [$inicio_anio, $fin_anio] )
          ->sum('total_cost');

          // $sales= $sales[0] === null ? 0 : $sales[0];

	   $sales_current     = Sale::where('cancel_bill','=','0')
			->almacen()
          	->whereBetween('sale_date', [$inicio_mes, $fin_mes] )
          	->sum(DB::raw('coalesce(total_cost,0)'));

        $receivings_current     = DB::table('receivings')->where('cancel_bill','=','0')
          ->whereBetween('date', [$inicio_mes, $fin_mes] )
          ->sum(DB::raw('coalesce(total_cost,0)'));

          // $sales_current= $sales_current[0] === null ? 0 : $sales_current[0];


		$sales_last     = Sale::where('cancel_bill','=','0')
			->almacen()
			->whereBetween('sale_date', [$inicio_mesa, $fin_mesa] )
			->sum('total_cost');

           $receivings_last     = DB::table('receivings')->where('cancel_bill','=','0')
          ->whereBetween('date', [$inicio_mesa, $fin_mesa] )
          ->sum('total_cost');

        $credits=DB::table('credits')
			// ->leftJoin('detail_credits','detail_credits.id_factura','=','credits.id')
			->whereIn('credits.status_id',[6,7])
        	->sum('credit_total');

		// $credits_paid=DB::table('credits')
		// 	->whereIn('credits.status_id',[6])
		// 	->sum('credit_total');

        $credits_unpaid=DB::table('credits')
        	// ->leftJoin('detail_credits','detail_credits.id_factura','=','credits.id')
			->whereIn('credits.status_id',[7])
			->sum(DB::raw('credit_total-paid_amount'));
		// $sales_last = $sales_last[0] === null ? 0 : $sales_last[0];

		$credits_paid = $credits - $credits_unpaid;


          // var_dump($sales);
          // echo "<br>";
          // print_r($sales_current);
          // var_dump($sales_current);
          // echo "<br>".$sales_current."<br>";
          // echo "<br>";
          // var_dump($sales_last);
          // exit();

        $parameters = App\Parameter::first();
		$employees = User::count();
		return view('home')
			->with('items', $items)
			// ->with('item_kits', $item_kits)
			->with('customers', $customers)
			->with('suppliers', $suppliers)
			->with('receivings', $receivings)
				->with('receivings_current', $receivings_current)
				->with('receivings_last', $receivings_last)
			->with('sales', $sales)
                ->with('sales_current', $sales_current)
                ->with('sales_last', $sales_last)
            ->with('credits', $credits)
                ->with('credits_paid', $credits_paid)
                ->with('credits_unpaid', $credits_unpaid)
			->with('employees', $employees);
	}

}