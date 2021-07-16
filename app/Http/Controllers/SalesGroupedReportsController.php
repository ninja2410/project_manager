<?php namespace App\Http\Controllers;

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
use App\StateCellar;
use \Auth, \Redirect;


use App\DetailCredit;
use App\Http\Requests;
use App\BodegaProducto;

use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Barryvdh\DomPDF\Facade as DomPDF;
use App\WeeklySettlement;
use App\Traits\DatesTrait;
use App\Traits\ItemsTrait;
use Illuminate\Http\Request;
use App\WeeklySettlementDetail;
use App\Http\Controllers\Controller;
use App\Parameter;
use Validator, \Input, \Session, \Response;

class SalesGroupedReportsController extends Controller {
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
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;

	$administrador =Session::get('administrador');
	// $user = User::find(Auth::user()->id);
	// 	$administrador = false;
	// 	foreach($user->roles as $rol){
	// 		if ($rol->admin==1){
	// 			$administrador = true;
	// 		break;
	// 		}
	// 	}

	$customers = Customer::all();
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
	->with('users',$users);
}


/* Ventas por cliente por forma de pago por factura */
public function salesCustInv(Request $request)
{
	$all = Input::get('all')==null?1:0;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;

	$customer_id = Input::get('customer_id');

	$title = 'Ventas por cliente por forma de pago'; // Report title

	$salesReport_q=Sale::join('series','sales.id_serie','=','series.id')
	->join('documents','series.id_document','=','documents.id')
	->join('customers','sales.customer_id','=','customers.id')
	->join('pagos','sales.id_pago','=','pagos.id')
	->join('sale_items','sale_items.sale_id','=','sales.id')
	->join('items','items.id','=','sale_items.item_id')
	->where('documents.sign','=','-')
	->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($customer_id!=0) {
		$salesReport_q->where('sales.customer_id',$customer_id);
		$customer_name = Customer::find($customer_id);
		$title = 'Ventas cliente: '.$customer_name->name; // Report title
	}
	if($all==1)
	{
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
    $salesReport_q->select(['sales.id as id_sales'
	,DB::raw('concat(customers.customer_code," ",customers.name) as customer_name')
	,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
	,'sales.sale_date'
	,DB::raw('concat(items.upc_ean_isbn," ",items.item_name) as item_name')
	,DB::raw('round(sale_items.low_price,2) as selling_price')
	,DB::raw('if(sale_items.bonification=0,sale_items.quantity,0) as quantity')
	,DB::raw('if(sale_items.bonification=1,sale_items.quantity,0) as bonification')
	,DB::raw('round((sale_items.low_price *sale_items.quantity),2) as total_selling ')
	,'pagos.name as pago','sales.total_cost'])
    ->orderBy('customer_name')
    ->orderBy('pago')
    ->orderBy('sales.id')
    ->orderBy('sale_items.item_id');

    $salesReport=$salesReport_q->get();
    // dd($salesReport);
    $reporte = $salesReport->groupBy('customer_name');
    // dd($reporte);

	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Fecha, Documento, Correlativo';

	if($all!=1) {
		$meta = [ // For displaying filters description on header
			'fecha' => 'Sin restriccion de fecha',
			'Ordenado por' => $sortBy
		];
	} else {
		$meta = [ // For displaying filters description on header
			'fecha' => $fromDate . ' a ' . $toDate,
			'Ordenado por' => $sortBy
		];
	}
    $params = Parameter::first();
    $color =$params->navbar_color;
    $company_name =$params->name_company;
    $text_color =$params->primary;



    /**Exportar a PDF usando DOMPDF */
    $data = [
        'title' => $title,
        'meta'=>$meta,
        'data'=>$reporte,
        'color'=>$color,
        'company_name'=>$company_name,
        'text_color' => $text_color
	];
    /** Funciona pero no funcionan bien los estilos  */
    // $view = \View::make('report.sales-cust-payment-type',$data)->render();
    // $pdf = \App::make('dompdf.wrapper');
    // $pdf->loadHTML($view);
    // return $pdf->stream($title.'.pdf');

    /** Pruebas para ver primero el HTML */
    // return view('report.grouped-reports.report-template',$data);

    return \PDF::loadView('report.grouped-reports.sales-cust-payment-type',$data)->stream($title.'.pdf');
    	
}


/* Ventas por cliente por producto */
public function salesCustProd(Request $request)
{
	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;

	$customer_id = Input::get('customer_idx');
	$item_id = Input::get('item_idx');

	$title = 'Ventas por cliente por producto'; // Report title

	$salesReport_q=Sale::join('series','sales.id_serie','=','series.id')
	->join('documents','series.id_document','=','documents.id')
	->join('customers','sales.customer_id','=','customers.id')
	->join('pagos','sales.id_pago','=','pagos.id')
	->join('sale_items','sale_items.sale_id','=','sales.id')
	->join('items','items.id','=','sale_items.item_id')
	->where('documents.sign','=','-')
	->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($customer_id!=0) {
		$salesReport_q->where('sales.customer_id',$customer_id);
		$customer_name = Customer::find($customer_id);
		$title = 'Ventas cliente: '.$customer_name->name; // Report title
	}
	if($item_id!=0){
		$salesReport_q->where('items.id',$item_id);
	}
	if($all==1)
	{
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q
    ->select(['sales.id as id_sales'
	,DB::raw('concat(customers.customer_code," ",customers.name) as customer_name')
	,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
	,'sales.sale_date'
	,DB::raw('concat(items.upc_ean_isbn," ",items.item_name) as item_name')
	,DB::raw('round(sale_items.low_price,2) as selling_price')
	,'sale_items.quantity'
	,DB::raw('round((sale_items.low_price *sale_items.quantity),2) as total_selling ')
	,'pagos.name as pago','sales.total_cost'])
    ->orderBy('customer_name')
    ->orderBy('item_name')
    ->orderBy('sales.id')
    ->orderBy('sale_items.item_id');

    $salesReport=$salesReport_q->get();
    // dd($salesReport);
    $reporte = $salesReport->groupBy('customer_name');
    // dd($reporte);

	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Fecha, Documento, Correlativo';

	if($all!=1) {
		$meta = [ // For displaying filters description on header
			'fecha' => 'Sin restriccion de fecha',
			'Ordenado por' => $sortBy
		];
	} else {
		$meta = [ // For displaying filters description on header
			'fecha' => $fromDate . ' a ' . $toDate,
			'Ordenado por' => $sortBy
		];
	}
    $params = Parameter::first();
    $color =$params->navbar_color;
    $company_name =$params->name_company;
    $text_color =$params->primary;



    /**Exportar a PDF usando DOMPDF */
    $data = [
        'title' => $title,
        'meta'=>$meta,
        'data'=>$reporte,
        'color'=>$color,
        'company_name'=>$company_name,
        'text_color' => $text_color
    ];
    /** Funciona pero no funcionan bien los estilos  */
    // $view = \View::make('report.sales-cust-payment-type',$data)->render();
    // $pdf = \App::make('dompdf.wrapper');
    // $pdf->loadHTML($view);
    // return $pdf->stream($title.'.pdf');

    /** Pruebas para ver primero el HTML */
    // return view('report.grouped-reports.report-template',$data);

    return \PDF::loadView('report.grouped-reports.sales-cust-product',$data)->stream($title.'.pdf');
    	
}


/**Ventas por producto por vendedor por factura */
public function salesItemSrep(Request $request)
{
	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;

	$item_id = Input::get('item_id');

	$title = 'Ventas por producto por vendedor'; // Report title

	$salesReport_q=Sale::join('series','sales.id_serie','=','series.id')
	->join('documents','series.id_document','=','documents.id')
	->join('users','users.id','=','sales.user_relation')
	->join('customers','sales.customer_id','=','customers.id')
	->join('pagos','sales.id_pago','=','pagos.id')
	->join('sale_items','sale_items.sale_id','=','sales.id')
	->join('items','items.id','=','sale_items.item_id')
	->where('documents.sign','=','-')
	->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($item_id!=0) {
		$salesReport_q->where('items.id',$item_id);
		$item = Item::where('id',$item_id)->select('item_name','upc_ean_isbn')->first();
		$title = 'Ventas producto: '.$item->upc_ean_isbn.' '.$item->item_name; // Report title
	}
	if($all==1)
	{
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q
    ->select(['sales.id as id_sales'
	,DB::raw('concat(customers.customer_code," ",customers.name) as customer_name')
	,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
    ,'sales.sale_date'
    ,DB::raw('concat(items.upc_ean_isbn," ",items.item_name) as item_name')
	,DB::raw('round(sale_items.low_price,2) as selling_price')
	,DB::raw('if(sale_items.bonification=0,sale_items.quantity,0) as quantity')
	,DB::raw('if(sale_items.bonification=1,sale_items.quantity,0) as bonification')
	,DB::raw('round((sale_items.low_price *sale_items.quantity),2) as total_selling ')
	,'users.name as user_name'
	,'pagos.name as pago'
	,'sales.total_cost'])
    ->orderBy('item_name')
    ->orderBy('user_name')
    ->orderBy('sales.id');
	
    $salesReport=$salesReport_q->get();
    $reporte = $salesReport->groupBy('item_name');

	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Fecha, Documento, Correlativo';

	

	if($all!=1) {
		$meta = [ // For displaying filters description on header
			'fecha' => 'Sin restriccion de fecha',
			'Ordenado por' => $sortBy
		];
	} else {
		$meta = [ // For displaying filters description on header
			'fecha' => $fromDate . ' a ' . $toDate,
			'Ordenado por' => $sortBy
		];
	}

    $params = Parameter::first();
    $color =$params->navbar_color;
    $company_name =$params->name_company;
    $text_color =$params->primary;

    /**Exportar a PDF usando DOMPDF */
    $data = [
        'title' => $title,
        'meta'=>$meta,
        'data'=>$reporte,
        'color'=>$color,
        'company_name'=>$company_name,
        'text_color' => $text_color
    ];

    return \PDF::loadView('report.grouped-reports.sales-item-salesrep',$data)->stream($title.'.pdf');

}

/** Ventas por vendedor por producto  */

public function salesSalesRepItem(Request $request)
{
	$all = Input::get('all')==null?0:1;
	$fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));
	$document = Input::get('document');
	$document = $document ==null ? 'Todo':$document;

	$user_id = Input::get('user_id');

	$title = 'Ventas por vendedor por producto'; // Report title

	$salesReport_q=Sale::join('series','sales.id_serie','=','series.id')
	->join('documents','series.id_document','=','documents.id')
	->join('users','users.id','=','sales.user_relation')
	->join('pagos','sales.id_pago','=','pagos.id')
	->join('sale_items','sale_items.sale_id','=','sales.id')
	->join('items','items.id','=','sale_items.item_id')
	->where('documents.sign','=','-')
	->where('sales.cancel_bill','=',0);
	if($document!='Todo'){
		$salesReport_q->where('sales.id_serie',$document);
	}
	if($user_id!=0) {
		$salesReport_q->where('users.id',$user_id);
		$user = User::find($user_id);
		$title = 'Ventas por vendedor: '.$user->name; // Report title
	}
	if($all==1)
    {
		$salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
	}
	$salesReport_q
    ->select(['sales.id as id_sales'
	,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
    ,'sales.sale_date'
    ,DB::raw('concat(items.upc_ean_isbn," ",items.item_name) as item_name')
	,DB::raw('round(sale_items.low_price,2) as selling_price')
	,DB::raw('if(sale_items.bonification=0,sale_items.quantity,0) as quantity')
	,DB::raw('if(sale_items.bonification=1,sale_items.quantity,0) as bonification')
	,DB::raw('round((sale_items.low_price *sale_items.quantity),2) as total_selling ')
	,'users.name as user_name'
	,'pagos.name as pago'
	,'sales.total_cost'])
    ->orderBy('user_name')
    ->orderBy('sales.id')
    ->orderBy('sale_items.item_id');
    
    $salesReport=$salesReport_q->get();
    $reporte=$salesReport->groupBy('user_name');
    // dd($reporte);

	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Fecha, Documento, Correlativo';

	if($all!=1) {
		$meta = [ // For displaying filters description on header
			'fecha' => 'Sin restriccion de fecha',
			'Ordenado por' => $sortBy
		];
	} else {
		$meta = [ // For displaying filters description on header
			'fecha' => $fromDate . ' a ' . $toDate,
			'Ordenado por' => $sortBy
		];
	}

	$params = Parameter::first();
    $color =$params->navbar_color;
    $company_name =$params->name_company;
    $text_color =$params->primary;

    /**Exportar a PDF usando DOMPDF */
    $data = [
        'title' => $title,
        'meta'=>$meta,
        'data'=>$reporte,
        'color'=>$color,
        'company_name'=>$company_name,
        'text_color' => $text_color
    ];

    return \PDF::loadView('report.grouped-reports.sales-by-rep',$data)->stream($title.'.pdf');
}

}
