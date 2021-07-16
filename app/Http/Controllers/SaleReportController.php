<?php namespace App\Http\Controllers;

use App\AlmacenUser;
use DB;
use App\Item;
use App\Pago;
use App\Sale;
use App\User;
use App\Route;
use PdfReport;
use App\Almacen;
use ExcelReport;
use App\Customer;
use App\SaleItem;
use App\RouteUser;
use App\StateCellar;


use \Auth, \Redirect;
use App\DetailCredit;
use App\Http\Requests;

use App\BodegaProducto;
use App\GeneralParameter;
use App\WeeklySettlement;
use App\Traits\DatesTrait;
use App\Traits\ItemsTrait;
use Illuminate\Http\Request;
use App\WeeklySettlementDetail;
use App\Http\Controllers\Controller;
use Validator, \Input, \Session, \Response;

class SaleReportController extends Controller {
	use DatesTrait;
	use ItemsTrait;

	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('parameter');
	}



/**
* Display a listing of the resource.
*
* @return Response
*/
public function index()
{
	$all = Input::get('all')==null?1:0;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;


	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$customers = Customer::join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas)->get();
	} else {
		$customers = Customer::all();
	}
	$items = $this->getItemsAndServicesAll();
	$users = User::all();

	$dataDocuments=DB::table('series')
	->leftJoin('documents','series.id_document','=','documents.id')
	->where('documents.sign','=','-')
	->where('documents.ajuste_inventario','=','0')
	->select('series.id as id_serie','documents.id  as id_document','documents.sign','series.name as serie','documents.name as document')
	->get();


	return view('report.sale')
	->with('fecha1', $fecha1)
	->with('fecha2', $fecha2)
	->with('dataDocuments',$dataDocuments)
	->with('document',$document)
	->with('admin',$administrador)
	->with('customers',$customers)
	->with('items',$items)
	->with('users',$users)
	->with('all',$all);
}


public function salesDetAdmin(Request $request)
{

	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));

	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;




	$salesReport_q=Sale::select(['sales.id as id_sales'
	,'customers.name as customer_name'
	,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
	,DB::raw('(select sum(quantity) from sale_items where sale_id = sales.id) as qty')
	,DB::raw('(select sum(total_selling)-sum(total_cost) from sale_items where sale_id = sales.id) as utilidad')
	,'sales.sale_date','sales.comments', 'sales.pagare_id'
	,'sales.show_header','users.name as user_name'
	,'pagos.name as pago','sales.total_cost']);
	$salesReport_q->leftJoin('series','sales.id_serie','=','series.id');
	$salesReport_q->leftJoin('documents','series.id_document','=','documents.id');
	$salesReport_q->leftJoin('users','users.id','=','sales.user_relation');
	$salesReport_q->leftJoin('customers','sales.customer_id','=','customers.id');

	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$salesReport_q->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
	};
	$salesReport_q->leftJoin('pagos','sales.id_pago','=','pagos.id');
	$salesReport_q->where('documents.sign','=','-');
	$salesReport_q->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($all==1) {
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q->orderBy('sales.sale_date','ASC');
	$salesReport_q->orderBy('series.name','ASC');
	$salesReport_q->orderBy('correlative','ASC');
	$salesReport_q->almacen();
	$salesReport=$salesReport_q->get();
	// dd($salesReport);
	// ejemplo
	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Fecha, Documento, Correlativo';

	$title = 'Reporte de Ventas detalladas - administrador'; // Report title
	if($all!=1) {
		$meta = [ // For displaying filters description on header
			'Fecha' => 'Sin restriccion de fecha',
			'Ordenado por' => $sortBy
		];
	} else {
		$meta = [ // For displaying filters description on header
			'Fechas de' => $fromDate . ' a ' . $toDate,
			'Ordenado por' => $sortBy
		];
	}
	

	$columns = [ // Set Column to be displayed
		'Documento' => 'document_and_correlative',
		'Fecha' =>'sale_date',
		'Cant.' => 'qty',
		'Vendedor' => 'user_name',
		'Cliente' => 'customer_name',
		'Total' => 'total_cost',
		'Utilidad' => 'utilidad',
		'Forma Pago' => 'pago',
		'Comentario' => 'comments',
		// 'Status' => function($result) { // You can do if statement or any action do you want inside this closure
			// 	return ($result->balance > 100000) ? 'Rich Man' : 'Normal Guy';
			// }
		];

		if(isset($request->excel)) {
			return ExcelReport::of($title, $meta, $salesReport, $columns)
			->editColumns(['Cliente'],
			['class'=>'left'])
			->editColumns(['Total', 'Utilidad'], [ // Mass edit column
				'class' => 'right bold'
				])
			->editColumns(['Forma Pago'], [ // Mass edit column
				'class' => 'right'
				])
			->editColumn('Utilidad', [
				'class' => 'right bolder italic-blue'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;',
				'.italic-blue' => 'color: blue;font-style: italic;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Total' => 'Q', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				'Utilidad' => 'Q'
				])
				// ->limit(20) // Limit record to be showed
				->setOrientation('landscape')
				// ->withoutManipulation()
				->download('Reporte de Ventas detalladas - administrador');
			}

	if(isset($request->pdf)) {
		return PdfReport::of($title, $meta, $salesReport, $columns)
		->editColumns(['Cliente'],
		['class'=>'left customer'])
		->setCss([
		'.customer'=>'font-size:10px;'
		])
		->editColumns(['Total', 'Utilidad'], [ // Mass edit column
		'class' => 'right bold'
		])
		->editColumns(['Forma Pago'], [ // Mass edit column
		'class' => 'right'
		])
		->editColumn('Utilidad', [
		'class' => 'right bolder'
		])
		->setCss([
		'.bolder' => 'font-weight: 800;',
		'.italic-blue' => 'color: blue;font-style: italic;'
		])
		->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
			'Total' => 'Q', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
			'Utilidad' => 'Q'
			])
			// ->limit(20) // Limit record to be showed
		->setOrientation('landscape')
		// ->withoutManipulation()
		->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
		// ->download('Reporte de ventas');
		// ->make();
	}


}

/**Ventas por cliente detalladas */
public function salesCustDet(Request $request)
{
	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;


	$salesReport_q=Sale::select(['sales.id as id_sales'
	,'customers.name as customer_name'
	,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
	,DB::raw('(select sum(quantity) from sale_items where sale_id = sales.id) as qty')
	,DB::raw('(select sum(total_selling)-sum(total_cost) from sale_items where sale_id = sales.id) as utilidad')
	,'sales.sale_date','sales.comments', 'sales.pagare_id'
	,'sales.show_header','users.name as user_name'
	,'pagos.name as pago','sales.total_cost'])
	->leftJoin('series','sales.id_serie','=','series.id')
	->leftJoin('documents','series.id_document','=','documents.id')
	->leftJoin('users','users.id','=','sales.user_relation')
	->leftJoin('customers','sales.customer_id','=','customers.id');

	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$salesReport_q->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
	};

	$salesReport_q->leftJoin('pagos','sales.id_pago','=','pagos.id')
	->where('documents.sign','=','-')
	->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($all==1) {
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q->orderBy('customers.name');
	$salesReport_q->orderBy('sales.sale_date','asc');
	$salesReport_q->almacen();
	$salesReport=$salesReport_q->get();

	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Fecha, Documento, Correlativo';

	$title = 'Reporte de Ventas por cliente por factura'; // Report title

	if($all!=1) {
		$meta = [ // For displaying filters description on header
			'Fecha' => 'Sin restriccion de fecha',
			'Ordenado por' => $sortBy
		];
	} else {
		$meta = [ // For displaying filters description on header
			'Fechas de' => $fromDate . ' a ' . $toDate,
			'Ordenado por' => $sortBy
		];
	}

	$columns = [ // Set Column to be displayed
		'Cliente' => 'customer_name',
		'Fecha' =>'sale_date',
		'Documento' => 'document_and_correlative',
		'Vendedor' => 'user_name',
		'Forma Pago' => 'pago',
		'Total' => 'total_cost',
		];

		if(isset($request->excelcustdet)) {
			return ExcelReport::of($title, $meta, $salesReport, $columns)
			->editColumns(['Cliente'],
			['class'=>'left'])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Total' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
			->groupBy('Cliente')

				->download($title);
			}

	if(isset($request->pdfcustdet)) {
		return PdfReport::of($title, $meta, $salesReport, $columns)
		->editColumns(['Cliente'],
		['class'=>'left customer'])
		->setCss([
		'.customer'=>'font-size:10px;'
		])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Total' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
			->groupBy('Cliente')
			->setOrientation('landscape')
		->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
	}
}

/* Ventas por cliente por forma de pago por factura */
public function salesCustInv(Request $request)
{
	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;

	$customer_id = Input::get('customer_id');

	$title = 'Reporte de Ventas por cliente por factura'; // Report title

	$salesReport_q=Sale::select(['sales.id as id_sales'
	,'customers.name as customer_name'
	,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
	,'sales.sale_date','sales.comments', 'sales.pagare_id'
	,'items.item_name'
	,'sale_items.low_price as selling_price'
	,'sale_items.quantity'
	,DB::raw('(sale_items.low_price *sale_items.quantity) as total_selling ')
	,'pagos.name as pago','sales.total_cost'])
	->leftJoin('series','sales.id_serie','=','series.id')
	->leftJoin('documents','series.id_document','=','documents.id');

	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$salesReport_q->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
	};

	$salesReport_q->leftJoin('customers','sales.customer_id','=','customers.id')
	->leftJoin('pagos','sales.id_pago','=','pagos.id')
	->leftJoin('sale_items','sale_items.sale_id','=','sales.id')
	->join('items','items.id','=','sale_items.item_id')
	->where('documents.sign','=','-')
	->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($customer_id!=0) {
		$salesReport_q->where('sales.customer_id',$customer_id);
		$customer_name = Customer::find($customer_id);
		$title = 'Reporte de ventas cliente: '.$customer_name->name; // Report title
	}
	if($all==1) {
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q->orderBy('customers.name');
	$salesReport_q->orderBy('pagos.name');
	$salesReport_q->orderBy('sales.id','asc');
	$salesReport_q->orderBy('sales.sale_date','asc');
	$salesReport_q->almacen();
	$salesReport=$salesReport_q->get();

	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Fecha, Documento, Correlativo';



	if($all!=1) {
		$meta = [ // For displaying filters description on header
			'Fecha' => 'Sin restriccion de fecha',
			'Ordenado por' => $sortBy
		];
	} else {
		$meta = [ // For displaying filters description on header
			'Fechas de' => $fromDate . ' a ' . $toDate,
			'Ordenado por' => $sortBy
		];
	}

	$columns = [ // Set Column to be displayed
		'Cliente' 	=> 'customer_name',
		'Forma Pago'=> 'pago',
		'Fecha' 	=>'sale_date',
		'Documento' => 'document_and_correlative',
		'Producto' 	=> 'item_name',
		'Cantidad' 	=> 'quantity',
		'Pventa' 	=> 'selling_price',
		'Total' 	=> 'total_selling',
		];
	if($customer_id!=0) {
		$columns = [ // Set Column to be displayed
			'Forma Pago'=> 'pago',
			'Fecha' 	=>'sale_date',
			'Documento' => 'document_and_correlative',
			'Producto' 	=> 'item_name',
			'Cantidad' 	=> 'quantity',
			'Pventa' 	=> 'selling_price',
			'Total' 	=> 'total_selling',
			];

			if(isset($request->excelcustdetinv)) {
				return ExcelReport::of($title, $meta, $salesReport, $columns)
				->editColumns(['Cliente'],
				['class'=>'left'])
				->editColumns(['Total'], [ // Mass edit column
					'class' => 'right bolder'
					])
				->setCss([
					'.bolder' => 'font-weight: 800;'
					])
				->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
					'Total' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
					])
				->groupBy('Forma Pago')

					->download($title);
				}

		if(isset($request->pdfcustdetinv)) {
			return PdfReport::of($title, $meta, $salesReport, $columns)
			->editColumns(['Cliente'],
			['class'=>'left customer'])
			->setCss([
			'.customer'=>'font-size:10px;'
			])
				->editColumns(['Total'], [ // Mass edit column
					'class' => 'right bolder'
					])
				->setCss([
					'.bolder' => 'font-weight: 800;'
					])
				->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
					'Total' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
					])
				->groupBy('Forma Pago')
				->setOrientation('landscape')
			->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
		}
	}
		if(isset($request->excelcustdetinv)) {
			return ExcelReport::of($title, $meta, $salesReport, $columns)
			->editColumns(['Cliente'],
			['class'=>'left'])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Total' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
			->groupBy('Cliente')
			->groupBy('Forma Pago')

				->download($title);
			}

	if(isset($request->pdfcustdetinv)) {
		return PdfReport::of($title, $meta, $salesReport, $columns)
		->editColumns(['Cliente'],
		['class'=>'left customer'])
		->setCss([
		'.customer'=>'font-size:10px;'
		])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Total' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
			->groupBy('Cliente')
			->groupBy('Forma Pago')
			->setOrientation('landscape')
		->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
	}
}

/**Ventas por cliente sumarizado */
public function salesCustSum(Request $request)
{

	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;



	$salesReport_q=Sale::select(['sales.id as id_sales'
	,'customers.name as customer_name'
	,DB::raw('count(sales.id) as trx')
	,DB::raw('sum(sales.total_cost) as total_cost')]);
	$salesReport_q->leftJoin('series','sales.id_serie','=','series.id');
	$salesReport_q->leftJoin('users','users.id','=','sales.user_relation');
	$salesReport_q->leftJoin('customers','sales.customer_id','=','customers.id');

	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$salesReport_q->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
	};

	$salesReport_q->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($all==1) {
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q->groupBy('customers.name');
	$salesReport_q->orderBy('customers.name');
	$salesReport_q->almacen();


	$salesReport=$salesReport_q->get();
	// dd($salesReport);
	// ejemplo
	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));


	$title = 'Reporte de Ventas por cliente sumarizadas'; // Report title

	$meta = [ // For displaying filters description on header
		'Fechas de' => $fromDate ,
		'a' => $toDate
	];

	$columns = [ // Set Column to be displayed
		'Cliente' => 'customer_name',
		'Transacciones' => 'trx',
		'Ticket Promedio' => function($result) { // You can do if statement or any action do you want inside this closure
			return round(($result->total_cost / $result->trx),2);
		},
		'Total' => 'total_cost'

		];

		if(isset($request->excelcustsum)) {
			return ExcelReport::of($title, $meta, $salesReport, $columns)
			->editColumns(['Cliente'],
			['class'=>'left'])
			->editColumns(['Transacciones','Ticket Promedio'],
			['class'=>'right'])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder italic-blue'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;',
				'.italic-blue' => 'color: blue;font-style: italic;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Transacciones' => 'point',
				'Total' => 'Q', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
				// ->limit(20) // Limit record to be showed
				// ->setOrientation('landscape')
				// ->withoutManipulation()
				->download($title);
			}

	if(isset($request->pdfcustsum)) {
		return PdfReport::of($title, $meta, $salesReport, $columns)
		->editColumns(['Cliente'],
			['class'=>'left'])
		->editColumns(['Transacciones','Ticket Promedio'],
		['class'=>'right'])
		->editColumns(['Total'], [ // Mass edit column
			'class' => 'right bolder'
			])
		->setCss([
			'.bolder' => 'font-weight: 800;',
			])
		->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
			'Transacciones' => 'point',
			'Total' => 'Q', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
			])
			// ->limit(20) // Limit record to be showed
		// ->setOrientation('landscape')
		// ->withoutManipulation()
		->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
		// ->download('Reporte de ventas');
		// ->make();
	}
}

/**Ventas por forma de pago detalladas */
public function salesPagoDet(Request $request)
{
	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;


	$salesReport_q=Sale::select(['sales.id as id_sales'
	,'customers.name as customer_name'
	,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
	,DB::raw('(select sum(quantity) from sale_items where sale_id = sales.id) as qty')
	,DB::raw('(select sum(total_selling)-sum(total_cost) from sale_items where sale_id = sales.id) as utilidad')
	,'sales.sale_date','sales.comments', 'sales.pagare_id'
	,'sales.show_header','users.name as user_name'
	,'pagos.name as pago','sales.total_cost'])
	->leftJoin('series','sales.id_serie','=','series.id')
	->leftJoin('documents','series.id_document','=','documents.id')
	->leftJoin('customers','sales.customer_id','=','customers.id');

	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$salesReport_q->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
	};

	$salesReport_q->leftJoin('users','users.id','=','sales.user_relation')
	->leftJoin('pagos','sales.id_pago','=','pagos.id')
	->where('documents.sign','=','-')
	->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($all==1){
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q->orderBy('pagos.name');
	$salesReport_q->almacen();
	$salesReport=$salesReport_q->get();

	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Fecha, Documento, Correlativo';

	$title = 'Reporte de Ventas por forma de pago detallado'; // Report title

	if($all!=1) {
		$meta = [ // For displaying filters description on header
			'Fecha' => 'Sin restriccion de fecha',
			'Ordenado por' => $sortBy
		];
	} else {
		$meta = [ // For displaying filters description on header
			'Fechas de' => $fromDate . ' a ' . $toDate,
			'Ordenado por' => $sortBy
		];
	}

	$columns = [ // Set Column to be displayed
		'Forma Pago' => 'pago',
		'Cliente' => 'customer_name',
		'Fecha' =>'sale_date',
		'Documento' => 'document_and_correlative',
		'Vendedor' => 'user_name',
		'Total' => 'total_cost',
		];

		if(isset($request->excelpagodet)) {
			return ExcelReport::of($title, $meta, $salesReport, $columns)
			->editColumns(['Cliente'],
			['class'=>'left'])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Total' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
			->groupBy('Forma Pago')

				->download($title);
			}

	if(isset($request->pdfpagodet)) {
		return PdfReport::of($title, $meta, $salesReport, $columns)
		->editColumns(['Cliente'],
		['class'=>'left customer'])
		->setCss([
		'.customer'=>'font-size:10px;'
		])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Total' => 'Q' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
			->groupBy('Forma Pago')
			->setOrientation('landscape')
		->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
	}
}

/**Ventas por forma de pago sumarizado */
public function salesPagoSum(Request $request)
{
	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;



	$salesReport_q=Sale::select(['sales.id as id_sales'
	,'pagos.name as pago'
	,DB::raw('count(sales.id) as trx')
	,DB::raw('sum(sales.total_cost) as total_cost')])
	->leftJoin('pagos','sales.id_pago','=','pagos.id')
	->where('sales.cancel_bill','=',0);
	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$salesReport_q->leftJoin('customers','sales.customer_id','=','customers.id')
		->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
	};

	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($all==1){
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q->groupBy('pagos.name');
	$salesReport_q->orderBy('pagos.name');
	$salesReport_q->almacen();


	$salesReport=$salesReport_q->get();
	// dd($salesReport);
	// ejemplo
	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));


	$title = 'Reporte de Ventas por forma de pago sumarizado'; // Report title

	$meta = [ // For displaying filters description on header
		'Fechas de' => $fromDate ,
		'a' => $toDate
	];

	$columns = [ // Set Column to be displayed
		'Forma pago' => 'pago',
		'Transacciones' => 'trx',
		'Ticket Promedio' => function($result) { // You can do if statement or any action do you want inside this closure
			return round(($result->total_cost / $result->trx),2);
		},
		'Total' => 'total_cost'

		];

		if(isset($request->excelpagosum)) {
			return ExcelReport::of($title, $meta, $salesReport, $columns)
			->editColumns(['Forma pago'],
			['class'=>'left'])
			->editColumns(['Transacciones','Ticket Promedio'],
			['class'=>'right'])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder italic-blue'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;',
				'.italic-blue' => 'color: blue;font-style: italic;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Transacciones' => 'point',
				'Total' => 'Q', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
				->download($title);
			}

	if(isset($request->pdfpagosum)) {
		return PdfReport::of($title, $meta, $salesReport, $columns)
		->editColumns(['Forma pago'],
			['class'=>'left'])
			->editColumns(['Transacciones','Ticket Promedio'],
			['class'=>'right'])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder italic-blue'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;',
				'.italic-blue' => 'color: blue;font-style: italic;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Transacciones' => 'point',
				'Total' => 'Q', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
		->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()

	}
}
/**Ventas por vendedor sumarizado */
public function salesSalesRepSum(Request $request)
{
	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;



	$salesReport_q=Sale::select(['users.name'
	,'users.last_name'
	,DB::raw('count(sales.id) as trx')
	,DB::raw('sum(sales.total_cost) as total_cost')])
	->leftJoin('users','sales.user_relation','=','users.id')
	->where('sales.cancel_bill','=',0);
	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$salesReport_q->leftJoin('customers','sales.customer_id','=','customers.id')
		->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
	};
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($all==1) {
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q->groupBy('users.name');
	$salesReport_q->orderBy('users.name');
	$salesReport_q->almacen();


	$salesReport=$salesReport_q->get();
	// dd($salesReport);
	// ejemplo
	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));


	$title = 'Reporte de Ventas por vendedor'; // Report title

	$meta = [ // For displaying filters description on header
		'Fechas de' => $fromDate ,
		'a' => $toDate
	];

	$columns = [ // Set Column to be displayed
		'Vendedor' => function($result) {
			return $result->name.' '.$result->last_name;
		},
		'Transacciones' => 'trx',
		'Ticket Promedio' => function($result) { // You can do if statement or any action do you want inside this closure
			return round(($result->total_cost / $result->trx),2);
		},
		'Total' => 'total_cost'

		];

		if(isset($request->excelsalesrep)) {
			return ExcelReport::of($title, $meta, $salesReport, $columns)
			->editColumns(['Vendedor'],
			['class'=>'left'])
			->editColumns(['Transacciones','Ticket Promedio'],
			['class'=>'right'])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder italic-blue'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;',
				'.italic-blue' => 'color: blue;font-style: italic;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Transacciones' => 'point',
				'Total' => 'Q', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
				->download($title);
			}

	if(isset($request->pdfsalesrep)) {
		return PdfReport::of($title, $meta, $salesReport, $columns)
		->editColumns(['Vendedor'],
			['class'=>'left'])
			->editColumns(['Transacciones','Ticket Promedio'],
			['class'=>'right'])
			->editColumns(['Total'], [ // Mass edit column
				'class' => 'right bolder italic-blue'
				])
			->setCss([
				'.bolder' => 'font-weight: 800;',
				'.italic-blue' => 'color: blue;font-style: italic;'
				])
			->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
				'Transacciones' => 'point',
				'Total' => 'Q', // if you want to show dollar sign ($) then use 'Total Balance' => '$'
				])
		->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()

	}
}


public function reporte_venta()
{
	$fecha1=Input::get('date1');
	$fecha2=Input::get('date2');
	if($fecha1 =="" && $fecha2==""){
		$fecha1= $fecha2= date("d/m/Y");
	}

	$nuevaFecha1 = explode('/', $fecha1);
	$nuevaFecha2 = explode('/', $fecha2);
	$diaFecha1=$nuevaFecha1[0];
	$mesFecha1=$nuevaFecha1[1];
	$anioFecha1=$nuevaFecha1[2];
	$diaFecha2=$nuevaFecha2[0];
	$mesFecha2=$nuevaFecha2[1];
	$anioFecha2=$nuevaFecha2[2];
	$rFecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
	$rFecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';

	$valor=Sale::join('series','series.id','=','sales.id_serie')
	->join('documents','documents.id','=','series.id_document')
	->join('pagos','sales.id_pago','=','pagos.id');

	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$valor->leftJoin('customers','sales.customer_id','=','customers.id')
		->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
	};

	$valor->where('documents.sign','=','-')
	->whereBetween('sales.sale_date',[$rFecha1,$rFecha2])
	->where('cancel_bill','=',0)
	->select('sales.id','sales.total_cost','documents.name as document','sales.correlative','series.name as serie','sales.sale_date','pagos.name')
	->orderBy('pagos.id','asc')
	->orderBy('sales.sale_date','asc')
	->orderBy('sales.id','asc')
	->almacen('sales.id','asc');

	$valores= $valor->get();

	// dd($valores);

	return view('report.report_sale_today')
	->with('dataPagos', $valores)
	->with('fecha1', $rFecha1)
	->with('fecha2', $rFecha2);
	// }
}

/*
SELECT *from detail_credits
where date_payments <='2017-11-08'
and payment_real_date is NULL
ORDER BY  id_factura asc;

*/
public function customers_pending_to_pay(){

	$fecha1=Input::get('date1');
	$fechaActual=date("Y-m-d");
	if($fecha1 ==""){
		$valoresObtenidos=DetailCredit::where('date_payments','<=',$fechaActual)
		->WhereNull('payment_real_date')->orderBy('id_factura', 'asc')->get();
		return view('report.report_pending_to_pay')
		->with('fecha1', $fechaActual)
		->with('valoresObtenidos', $valoresObtenidos);
	}else{
		$nuevaFecha1 = explode('/', $fecha1);
		$diaFecha1=$nuevaFecha1[0];
		$mesFecha1=$nuevaFecha1[1];
		$anioFecha1=$nuevaFecha1[2];
		$rFecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1;

		$valoresObtenidos=DetailCredit::where('date_payments','<=',$rFecha1)
		->WhereNull('payment_real_date')->orderBy('id_factura', 'asc')->get();
		return view('report.report_pending_to_pay')
		->with('fecha1', $rFecha1)
		->with('valoresObtenidos', $valoresObtenidos);
	}
}

public  function items_quantity_sales(){

	$fecha1=Input::get('date1');
	$fecha2=Input::get('date2');
	$cantidadLimite=(Input::get('cantidadLimite')>0)?Input::get('cantidadLimite'):10;;

	if($fecha1 =="" && $fecha2=="" ){
		$fecha1= $fecha2 = date("d/m/Y");
	}
	$nuevaFecha1 = explode('/', $fecha1);
	$nuevaFecha2 = explode('/', $fecha2);
	$diaFecha1=$nuevaFecha1[0];
	$mesFecha1=$nuevaFecha1[1];
	$anioFecha1=$nuevaFecha1[2];
	$diaFecha2=$nuevaFecha2[0];
	$mesFecha2=$nuevaFecha2[1];
	$anioFecha2=$nuevaFecha2[2];
	$rFecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
	$rFecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';

	$valor=SaleItem::join('items','sale_items.item_id','=','items.id');

	$administrador =Session::get('administrador');
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$valor->leftJoin('sales','sales.id','=','sale_items.sale_id')
		->leftJoin('customers','sales.customer_id','=','customers.id')
		->join('route_costumers','customers.id','=','route_costumers.customer_id')
		->whereIn('route_costumers.route_id',$rutas);
        if (!$administrador) {
            //BUSCAR EL ALMACEN ASIGNADO AL USUARIO
            $almacens = AlmacenUser::whereId_usuario(\Illuminate\Support\Facades\Auth::user()->id)->lists('id_bodega');
            $valor->whereIn('almacen_id', $almacens);
        }
	}
	else{
        if (!$administrador) {
            //BUSCAR EL ALMACEN ASIGNADO AL USUARIO
            $almacens = AlmacenUser::whereId_usuario(\Illuminate\Support\Facades\Auth::user()->id)->lists('id_bodega');
            $valor->leftJoin('sales','sales.id','=','sale_items.sale_id')->whereIn('almacen_id', $almacens);
        }
    }

	$valor->whereBetween('sale_items.created_at',[$rFecha1,$rFecha2])
	->groupBy('items.id')
	->select('items.id','items.item_name','sale_items.low_price',DB::raw('SUM(sale_items.total_selling) as total'),DB::raw('count(distinct sale_items.quantity) as tx'), DB::raw('SUM(sale_items.quantity) as cantidad'))->limit($cantidadLimite)
	->orderBy('cantidad','desc');

	$datosObtenidos = $valor->get();

	return view('report.report_item_sales')
	->with('datosObtenidos', $datosObtenidos)
	->with('cantidadLimite', $cantidadLimite)
	->with('fecha1', $rFecha1)
	->with('fecha2', $rFecha2);






}
// SELECT i.item_name,b.quantity, a.name From bodega_productos
//                                    b inner join items i on b.id_product = i.id
// inner join almacens a on b.id_bodega=a.id order by a.name;
public function product_and_cellars(Request $request){

	$idStorage=isset($request->idStorage) ? $request->idStorage : -1;

	$dataStorage=Almacen::join('state_cellars','almacens.id_state','=','state_cellars.id')
	->where('state_cellars.id','=',1)
	->select('almacens.id','almacens.name','almacens.adress')
        ->asigned()
	->get();
	$datosObtenidosx=BodegaProducto::join('items','bodega_productos.id_product','=','items.id')
	->join('almacens','bodega_productos.id_bodega','=','almacens.id')
	->join('item_categories','items.id_categorie','=','item_categories.id')
	->where('type_id', 1)/*Productos*/
	->where('items.is_kit', 0) /*No Kits*/
	->whereWildcard(0) /*No Comodines*/;
	if($idStorage!=0){
		$datosObtenidosx->where('bodega_productos.id_bodega','=',$idStorage);
	}
	$datosObtenidosx->select('items.id','items.item_name','bodega_productos.quantity','item_categories.name as categoria','items.cost_price','almacens.name',DB::raw('(items.cost_price*bodega_productos.quantity) as subtotal'))
	->orderBy('almacens.name','asc');
	$datosObtenidos= $datosObtenidosx->get();
	// return $datosObtenidos;
	return view('report.listProduct_and_bodegas')
	->with('datosObtenidos', $datosObtenidos)
	->with('dataStorage',$dataStorage)
	->with('idStorage',$idStorage);
}

public function product_and_cellars_sinPrice(Request $request){

	$idStorage=isset($request->idStorage) ? $request->idStorage : 0;
    $almacens = AlmacenUser::whereId_usuario(Auth::user()->id)->lists('id_bodega');
	$dataStorage=Almacen::join('state_cellars','almacens.id_state','=','state_cellars.id')
	->where('state_cellars.id','=',1)
        ->whereIn('almacens.id', $almacens)
	->select('almacens.id','almacens.name','almacens.adress')
	->get();

	$datosObtenidos=BodegaProducto::join('items','bodega_productos.id_product','=','items.id')
	->join('almacens','bodega_productos.id_bodega','=','almacens.id')
	->join('item_categories','items.id_categorie','=','item_categories.id')
	->where('bodega_productos.id_bodega','=',$idStorage)
	->select('items.id','items.item_name','bodega_productos.quantity','item_categories.name as categoria','items.cost_price','almacens.name')
	->orderBy('almacens.name','asc')->get();
	// return $datosObtenidos;
	return view('report.listProduct_and_bodegasNotPrice')
	->with('datosObtenidos', $datosObtenidos)->with('dataStorage',$dataStorage)->with('idStorage',$idStorage);
}
public function expired_report()
{
	// echo "Hola que haces";
	$expiration_date=Input::get('expiration_date');
	if($expiration_date=='')
	{
		$fecha = date('Y-m-j');
		$nuevafecha = strtotime ( '+3 month' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
		$data_items=Item::join('bodega_productos','items.id','=', 'bodega_productos.id_product')
		->where('items.expiration_date','>',$nuevafecha)
		->select('items.id'
		,'items.item_name'
		,'items.selling_price'
		,'items.cost_price'
		,'items.minimal_existence'
		,'bodega_productos.quantity'
		,'items.expiration_date')
		->get();
		//cambiamos el formato de la fecha
		$array_Fecha= explode('-',$nuevafecha);
		$fecha_formateada=$array_Fecha[2].'/'.$array_Fecha[1].'/'.$array_Fecha[0];
		return view('report.listProduct_expirate')->with('data_items',$data_items)->with('date_parameters',$fecha_formateada);
	}
	else
	{
		$date = explode('/', $expiration_date);
		$ndate = $date[2].'-'.$date[1].'-'.$date[0];
		$data_items=Item::join('bodega_productos','items.id','=', 'bodega_productos.id_product')
		->where('items.expiration_date','>',$ndate)
		->select('items.id'
		,'items.item_name'
		,'items.selling_price'
		,'items.cost_price'
		,'items.minimal_existence'
		,'bodega_productos.quantity'
		,'items.expiration_date')
		->get();
		return view('report.listProduct_expirate')->with('data_items',$data_items)
		->with('date_parameters',$expiration_date);
	}

}

public function report_cancel_bill()
{
	$fecha1=Input::get('date1');
	$fecha2=Input::get('date2');
	$fechaActual=date("Y-m-d");
	if($fecha1 =="" && $fecha2==""){
		$fecha11=$fechaActual.' 00:00:00';
		$fecha22=$fechaActual.' 23:59:59';
		// echo "Fecha 1 = ".$fecha11.'<br>';
		// echo "Fecha 1 = ".$fecha22.'<br>';
		$data_sales=Sale::where('cancel_bill','=',1)->whereBetween('created_at',[$fecha11,$fecha22])
            ->almacen()
		->get();
		return view('report.report_canceled_sales')
		->with('data_sales',$data_sales)
		->with('fecha1', $fecha11)
		->with('fecha2', $fecha22);
	}else{
		$nuevaFecha1 = explode('/', $fecha1);
		$nuevaFecha2 = explode('/', $fecha2);
		$diaFecha1=$nuevaFecha1[0];
		$mesFecha1=$nuevaFecha1[1];
		$anioFecha1=$nuevaFecha1[2];
		$diaFecha2=$nuevaFecha2[0];
		$mesFecha2=$nuevaFecha2[1];
		$anioFecha2=$nuevaFecha2[2];
		$rFecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
		$rFecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';
		$data_sales=Sale::where('cancel_bill','=',1)->whereBetween('created_at',[$rFecha1,$rFecha2])
            ->almacen()
		->get();
		return view('report.report_canceled_sales')
		->with('data_sales',$data_sales)
		->with('fecha1', $rFecha1)
		->with('fecha2', $rFecha2);
	}
}
public function get_details($id){
	$idSale=0;
	$dataSale=Sale::find($id);
	if($dataSale->show_header!=1){
		$idSale=$dataSale->show_header;
	}else {
		$idSale=$id;
	}
	$detailsItems=DB::table('sale_items')->leftJoin('items','sale_items.item_id','=','items.id')->where('sale_items.sale_id',$idSale)
	->select('sale_items.id','items.id as item_id','items.item_name','sale_items.quantity','sale_items.low_price','sale_items.total_selling')->get();
	return $detailsItems;
}

public function routeSale(){
	// OBTENEMOS LAS FECHAS
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));

	$totalTx=0;
	$totalTxPromedio=0;
	$totalCliente=0;
	$totalMonto=0;
	$data=Route::rightJoin('route_costumers','routes.id','=','route_costumers.route_id')
	->leftJoin('sales','route_costumers.customer_id','=','sales.customer_id');

	$administrador =Session::get('administrador');
    if (!$administrador) {
        //BUSCAR EL ALMACEN ASIGNADO AL USUARIO
        $almacens = AlmacenUser::whereId_usuario(\Illuminate\Support\Facades\Auth::user()->id)->lists('id_bodega');
        $data->whereIn('sales.almacen_id', $almacens);
    }
	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$data->whereIn('route_costumers.route_id',$rutas);
	};

	$data->select('routes.id','routes.name',DB::raw("COALESCE(SUM(sales.total_cost),0.00) AS monto"),
	DB::raw("COUNT(sales.id) AS tx"),
	DB::raw("COALESCE(ROUND(SUM(sales.total_cost)/(COUNT(sales.id)),2),0.00) AS txPromedio"),
	DB::raw('COUNT(DISTINCT sales.customer_id) AS clientes'))
	->whereBetween('sales.created_at',[$fecha1,$fecha2])
	->groupBy('routes.name');

	$dataRuta = $data->get();
	foreach($dataRuta as $item){
		$totalTx+=$item->tx;
		$totalTxPromedio+=$item->txPromedio;
		$totalCliente+=$item->clientes;
		$totalMonto+=$item->monto;
	}
	return view('report.route')
	->with('data',$dataRuta)
	->with('fecha1',$fecha1)
	->with('fecha2',$fecha2)
	->with('totalTx',$totalTx)
	->with('totalTxPromedio',$totalTxPromedio)
	->with('totalCliente',$totalCliente)
	->with('totalMonto',$totalMonto)
	->with('ruta',Route::with('users')->get());
}
public function routeSumarizado(){
	// OBTENEMOS LAS FECHAS
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));


	$totalMonto=0;
	$totalCosto=0;
	$totalGasto=0;
	$totalUtilidad=0;
	$data=Route::leftJoin('route_costumers','routes.id','=','route_costumers.route_id')
	->Join('sales','route_costumers.customer_id','=','sales.customer_id');

	$administrador =Session::get('administrador');
    if (!$administrador) {
        //BUSCAR EL ALMACEN ASIGNADO AL USUARIO
        $almacens = AlmacenUser::whereId_usuario(\Illuminate\Support\Facades\Auth::user()->id)->lists('id_bodega');
        $data->whereIn('sales.almacen_id', $almacens);
    }

	$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
	/** Si la ruta es requerida y no es administrador */
	if( (isset($ruta_requerida)) && ($administrador ==false)) {
		$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
		if (count($rutas) == 0) {
		  $rutas = [0, 0];
		}
		$data->whereIn('route_costumers.route_id',$rutas);
	};

	$data->join('sale_items','sale_items.sale_id','=','sales.id')
	->select('routes.id'
	,'routes.name'
	,DB::raw('sum(sale_items.cost_price) as costo')
	,DB::raw('sum(sale_items.low_price) as monto'))
	->whereBetween('sales.created_at',[$fecha1,$fecha2])
	->groupBy('routes.name');
	$dataRuta = $data->get();
	foreach($dataRuta as $item){
		$totalMonto+=$item->monto;
		$totalCosto+=$item->costo;
	}

	$dataGasto=Route::leftJoin('route_users','routes.id','=','route_users.route_id')
	->leftjoin('expenses','route_users.user_id','=','expenses.assigned_user_id')
	->groupBy('routes.name')
	->select('routes.id','routes.name',DB::raw("COALESCE(SUM(expenses.amount),0.00) AS gasto"))
	->whereBetween('expenses.created_at',[$fecha1,$fecha2])
	->get();

	foreach($dataGasto as $item){
		$totalGasto+=$item->gasto;
	}

	$totalUtilidad=$totalMonto-$totalGasto-$totalCosto;
	return view('report.route_sumarizado')
	->with('data',$dataRuta)
	->with('fecha1',$fecha1)
	->with('fecha2',$fecha2)
	->with('totalMonto',$totalMonto)
	->with('totalGasto',$totalGasto)
	->with('totalCosto',$totalCosto)
	->with('totalUtilidad',$totalUtilidad)
	->with('dataGasto',$dataGasto)
	->with('ruta',Route::with('users')->select('id','name')->get());
}

public function inventory_week(){
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));

	$bodega = Input::get('bodega');
	$administrador =Session::get('administrador');
	if($administrador){
		$bodegas = Almacen::where('id_state', 1)->get();
	}
	else {
		$bodegas = Almacen::join('almacen_users','almacen_users.id_bodega','=','almacens.id')
		->select('almacens.id','almacens.name')
		->where('almacen_users.id_usuario',Auth::user()->id)
		->where('id_state', 1)->orderBy('almacens.id')->get();
	}

	if ($bodega == null){
		$bodega = $bodegas[0]->id;
	}


	$query = WeeklySettlement::join('weekly_settlement_details',
		'weekly_settlement_details.weekly_id', '=', 'weekly_settlements.id')
		->join('items', 'items.id', '=','weekly_settlement_details.item_id')
		->where('weekly_settlements.bodega_id', '=',$bodega);
	$query->leftjoin('sale_items', function($join) use($bodega){
			$join->on('sale_items.item_id', '=', 'weekly_settlement_details.item_id')
				->where('sale_items.id_bodega', '=', $bodega);
		});
	$query->leftjoin('sales', function($join) use($fecha1,$fecha2){
			$join->on('sale_items.sale_id','=', 'sales.id');
		})
		->whereBetween('sales.sale_date', [$fecha1, $fecha2]);

	$query->select('items.item_name as NOMBRE', 'items.id as CODIGO',DB::raw('coalesce(weekly_settlement_details.quantity, 0) as EXISTENCIAS'),
			DB::raw('coalesce(sum(sale_items.quantity), 0) as VENTAS'))
		->groupby('weekly_settlement_details.item_id');
	$items = $query->get();
	return view('report.weekly_settlement')
		->with('bodegas', $bodegas)
		->with('bodega', $bodega)
		->with('fecha1', $fecha1)
		->with('fecha2', $fecha2)
		->with('items', $items);
}
}

