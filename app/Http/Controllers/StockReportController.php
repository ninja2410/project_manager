<?php namespace App\Http\Controllers;

use DB;
use App\Item;
use App\Pago;
use App\Sale;
use App\User;
use App\Route;
use PdfReport;
use App\Credit;
use App\Almacen;
use ExcelReport;
use App\Customer;
use App\SaleItem;
use App\Parameter;
use App\RouteUser;


use App\AlmacenUser;
use App\StateCellar;
use \Auth, \Redirect;

use App\DetailCredit;
use App\CreditPayment;
use App\Http\Requests;
use App\BodegaProducto;
use App\GeneralParameter;
use App\WeeklySettlement;
use App\Traits\DatesTrait;
use App\Traits\ItemsTrait;
use App\Traits\AlmacenTrait;
use Illuminate\Http\Request;
use App\WeeklySettlementDetail;
use App\Http\Controllers\Controller;
use App\ProductTransfer;
use App\Receiving;
use Validator, \Input, \Session, \Response;

class StockReportController extends Controller {
	use DatesTrait;
    use ItemsTrait;
    use AlmacenTrait;

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
	

	$administrador =Session::get('administrador');
	/** Si la ruta es requerida y no es administrador */
	if ($administrador ==false) {
		/* Solo los almacenes a los que el usuario tiene permisos. */        
        $almacen = $this->getAlmacenByUser(Auth::user()->id);
	} else {
		$almacen = Almacen::all();
	}
	$items = $this->getItemsAndServicesAll();


	return view('report.stock')
	->with('fecha1', $fecha1)
	->with('fecha2', $fecha2)
    ->with('items',$items)
    ->with('almacen',$almacen);
}

/**Reporte de existencia general por producto */
public function general_stock_report(Request $request) {
    $fecha1=$this->fixFecha(Input::get('date1'));
	$fecha2=$this->fixFechaFin(Input::get('date2'));


    $query = WeeklySettlement::join('weekly_settlement_details',
		'weekly_settlement_details.weekly_id', '=', 'weekly_settlements.id')
		->join('items', 'items.id', '=','weekly_settlement_details.item_id')
		// ->where('weekly_settlements.bodega_id', '=',$bodega)
		->where('weekly_settlements.date','=', substr($fecha1,0,10));

    $query->select('items.item_name as NOMBRE', 'items.upc_ean_isbn as CODIGO',
            DB::raw('coalesce(weekly_settlement_details.quantity, 0) as EXISTENCIAS'),
            DB::raw('coalesce(weekly_settlement_details.cost, 0) as COSTO'),
			DB::raw('coalesce((select sum(receiving_items.quantity) 
			from receiving_items join receivings on receiving_items.receiving_id = receivings.id
			where receiving_items.item_id = weekly_settlement_details.item_id 
			and receivings.date between "'.$fecha1.'" and "'.$fecha2.'"
			),0) as CARGO'),
			DB::raw('coalesce(( select sum(sale_items.quantity) from 
			sale_items join sales on sale_items.sale_id = sales.id 
			where sales.sale_date between "'.$fecha1.'" and "'.$fecha2.'"
			and  sale_items.item_id = weekly_settlement_details.item_id
            ),0) as VENTAS '))
        ->orderBy('items.upc_ean_isbn')
		->groupBy('weekly_settlement_details.item_id');
	$report = $query->get();


	// dd($salesReport);
	// ejemplo
	$fromDate = date('d/m/Y',strtotime($fecha1));
	$toDate = date('d/m/Y',strtotime($fecha2));
	$sortBy = 'Código de producto';

	$title = 'Reporte general de existencias'; // Report title

	$meta = [ // For displaying filters description on header
		'Fechas de' => $fromDate . ' a ' . $toDate,
		'Ordenado por' => $sortBy
	];

	$columns = [ // Set Column to be displayed
		'Codigo' => 'CODIGO',
		'Producto' =>'NOMBRE',
		'Inv. Inicial' => 'EXISTENCIAS',
		'Ingresos' => 'CARGO',
		'Salidas' => 'VENTAS',
		'Existencia' => function($result) { // You can do if statement or any action do you want inside this closure
            return ($result->EXISTENCIAS + $result->CARGO -$result->VENTAS);
        },
        'Costo' => function($result) { // You can do if statement or any action do you want inside this closure
				return (($result->EXISTENCIAS + $result->CARGO -$result->VENTAS) * $result->COSTO);
			}
		];


			return PdfReport::of($title, $meta, $report, $columns)
			->editColumns(['Cliente'],
			['class'=>'left'])
			->editColumns(['Existencia', 'Costo'], [ // Mass edit column
				'class' => 'right bold'
				])			
            ->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
                'Inv. Inicial',
                'Ingresos',
                'Salidas',
                'Existencia',
				'Costo' => 'Q', 
				])
				// ->limit(20) // Limit record to be showed
				->setOrientation('landscape')
				// ->withoutManipulation()
				->stream();			
}

    /**Reporte de existencia por bodega detallado */    
    public function stock_report_by_storage(Request $request)
    {
        // dd($request->all());
        $fecha1=$this->fixFecha(Input::get('date1'));
        $fecha2=$this->fixFechaFin(Input::get('date2'));
        $almacen_id = Input::get('almacen_id');
        $almacen_id = $almacen_id ==null ? 0:$almacen_id;


        $title = 'Existencias por bodega por producto'; // Report title

        $compras = Receiving::join('receiving_items' ,'receiving_items.receiving_id','=','receivings.id')
        ->join('items', 'items.id', '=','receiving_items.item_id')
        ->join('almacens' ,'almacens.id' ,'=','receivings.storage_origins')
        ->join('series as srec','srec.id', '=','receivings.id_serie')
        ->join('documents as drec' , 'srec.id_document','=','drec.id')
        ->whereBetween('receivings.date',[$fecha1,$fecha2]);
        if($almacen_id!=0){
            $compras->where('almacens.id',$almacen_id);
        }; 
        $compras->select('receivings.id', 'almacens.name as BODEGA', 'receivings.date as FECHA', 'items.item_name as NOMBRE', 'items.upc_ean_isbn as CODIGO',
        DB::Raw('coalesce((select weekly_settlement_details.quantity
        from weekly_settlements join weekly_settlement_details on weekly_settlement_details.weekly_id = weekly_settlements.id 
        where weekly_settlement_details.item_id=receiving_items.item_id
        and weekly_settlements.bodega_id=receivings.storage_origins and weekly_settlements.date = "'.substr($fecha1,0,10).'" ), 0)as QUANTITY'),
        DB::Raw('concat(drec.name," ",srec.name, "-",receivings.correlative) as documento'),
        'receiving_items.quantity as COMPRA',
        DB::Raw('0 AS VENTA'),
        'receivings.created_at');

        if($almacen_id!=0){
            $traslados_in = ProductTransfer::join('product_transfer_details' ,'product_transfer_details.product_transfer_id','=','product_transfers.id')
            ->join('items', 'items.id', '=','product_transfer_details.item_id')
            ->join('almacens' ,'almacens.id' ,'=','product_transfers.almacen_destination')
            ->join('series as srec','srec.id', '=','product_transfers.serie_id')
            ->join('documents as drec' , 'srec.id_document','=','drec.id')
            ->whereBetween('product_transfers.date_received',[$fecha1,$fecha2])
            ->where('product_transfers.status_id',9)
            ->where('almacens.id',$almacen_id)
            ->select('product_transfers.id', 'almacens.name as BODEGA', 'product_transfers.date_received as FECHA', 'items.item_name as NOMBRE', 'items.upc_ean_isbn as CODIGO',
            DB::Raw('coalesce((select weekly_settlement_details.quantity
            from weekly_settlements join weekly_settlement_details on weekly_settlement_details.weekly_id = weekly_settlements.id 
            where weekly_settlement_details.item_id=items.id
            and weekly_settlements.bodega_id=almacens.id and weekly_settlements.date = "'.substr($fecha1,0,10).'" ), 0)as QUANTITY'),
            DB::Raw('concat(drec.name," ",srec.name, "-",product_transfers.correlative) as documento'),
            'product_transfer_details.quantity as COMPRA',
            DB::Raw('0 AS VENTA'),
            'product_transfers.created_at');
        };

        $ventas = Sale::join('sale_items' ,'sale_items.sale_id','=','sales.id')
        ->join('items', 'items.id', '=','sale_items.item_id')
        ->join('almacens' ,'almacens.id' ,'=','sale_items.id_bodega')
        ->join('series as srec','srec.id', '=','sales.id_serie')
        ->join('documents as drec' , 'srec.id_document','=','drec.id')
        ->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
        if($almacen_id!=0){
            $ventas->where('almacens.id',$almacen_id);
        }; 
        $ventas->select('sales.id', 'almacens.name as BODEGA', 'sales.sale_date as FECHA', 'items.item_name as NOMBRE', 'items.upc_ean_isbn as CODIGO',
        DB::Raw('coalesce((select weekly_settlement_details.quantity
        from weekly_settlements join weekly_settlement_details on weekly_settlement_details.weekly_id = weekly_settlements.id 
        where weekly_settlement_details.item_id=sale_items.item_id
        and weekly_settlements.bodega_id=almacens.id and weekly_settlements.date = "'.substr($fecha1,0,10).'" ), 0)as QUANTITY'),
        DB::Raw('concat(drec.name," ",srec.name, "-",sales.correlative) as documento'),
        DB::Raw('0 AS COMPRA'),
        'sale_items.quantity AS VENTA',
        'sales.created_at');
        
        if($almacen_id!=0){
            $traslados_out = ProductTransfer::join('product_transfer_details' ,'product_transfer_details.product_transfer_id','=','product_transfers.id')
            ->join('items', 'items.id', '=','product_transfer_details.item_id')
            ->join('almacens' ,'almacens.id' ,'=','product_transfers.almacen_origin')
            ->join('series as srec','srec.id', '=','product_transfers.serie_id')
            ->join('documents as drec' , 'srec.id_document','=','drec.id')
            ->whereBetween('product_transfers.date_received',[$fecha1,$fecha2])
            ->whereIn('product_transfers.status_id',[8,9])
            ->where('almacens.id',$almacen_id)
            ->select('product_transfers.id', 'almacens.name as BODEGA', 'product_transfers.date as FECHA', 'items.item_name as NOMBRE', 'items.upc_ean_isbn as CODIGO',
            DB::Raw('coalesce((select weekly_settlement_details.quantity
            from weekly_settlements join weekly_settlement_details on weekly_settlement_details.weekly_id = weekly_settlements.id 
            where weekly_settlement_details.item_id=items.id
            and weekly_settlements.bodega_id=almacens.id and weekly_settlements.date = "'.substr($fecha1,0,10).'" ), 0)as QUANTITY'),
            DB::Raw('concat(drec.name," ",srec.name, "-",product_transfers.correlative) as documento'),
            DB::Raw('0 AS COMPRA'),
            'product_transfer_details.quantity as VENTA',
            'product_transfers.created_at');
        };
        
        if($almacen_id!=0){
            $report = $compras
            ->unionAll($traslados_in)
            ->unionAll($ventas)
            ->unionAll($traslados_out)
            ->orderBy('BODEGA')
            ->orderBy('NOMBRE')
            ->orderBy('created_at')
            ->get();
        } else {
            $report = $compras
            ->unionAll($ventas)
            ->orderBy('BODEGA')
            ->orderBy('NOMBRE')
            ->orderBy('created_at')
            ->get();
        }
        

        // dd($report);
        $reporte=$report->groupBy('BODEGA');
            
        
        // dd($reporte);

        $fromDate = date('d/m/Y',strtotime($fecha1));
        $toDate = date('d/m/Y',strtotime($fecha2));
        $sortBy = 'Bodega, Producto';

        $meta = [ // For displaying filters description on header
            'fecha' => $fromDate . ' a ' . $toDate,
            'ordenado' => $sortBy
        ];

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

        return \PDF::loadView('report.grouped-reports.stock-by-storage-det',$data)->stream($title.'.pdf');
    }

    /**Reporte de existencia por bodega detallado */    
    public function stock_report_by_product(Request $request)
    {
        // dd($request->all());
        $fecha1=$this->fixFecha(Input::get('date1'));
        $fecha2=$this->fixFechaFin(Input::get('date2'));
        $item_id = Input::get('item_id');
        $item_id = $item_id ==null ? 0:$item_id;


        $title = 'Existencias por producto'; // Report title

        $compras = Receiving::join('receiving_items' ,'receiving_items.receiving_id','=','receivings.id')
        ->join('items', 'items.id', '=','receiving_items.item_id')
        ->join('series as srec','srec.id', '=','receivings.id_serie')
        ->join('documents as drec' , 'srec.id_document','=','drec.id')
        ->whereBetween('receivings.date',[$fecha1,$fecha2]);
        if($item_id!=0){
            $compras->where('receiving_items.item_id',$item_id);
        }; 
        $compras->select('receivings.id', 'receivings.date as FECHA', 'items.item_name as NOMBRE', 'items.upc_ean_isbn as CODIGO',
        DB::Raw('coalesce((select sum(weekly_settlement_details.quantity)
        from weekly_settlements join weekly_settlement_details on weekly_settlement_details.weekly_id = weekly_settlements.id 
        where weekly_settlement_details.item_id=receiving_items.item_id
        and weekly_settlements.date = "'.substr($fecha1,0,10).'" ), 0)as QUANTITY'),
        DB::Raw('concat(drec.name," ",srec.name, "-",receivings.correlative) as documento'),
        'receiving_items.quantity as COMPRA',
        DB::Raw('0 AS VENTA'),
        'receivings.created_at');

        
        $ventas = Sale::join('sale_items' ,'sale_items.sale_id','=','sales.id')
        ->join('items', 'items.id', '=','sale_items.item_id')
        ->join('series as srec','srec.id', '=','sales.id_serie')
        ->join('documents as drec' , 'srec.id_document','=','drec.id')
        ->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
        if($item_id!=0){
            $ventas->where('sale_items.item_id',$item_id);
        }; 
        $ventas->select('sales.id', 'sales.sale_date as FECHA', 'items.item_name as NOMBRE', 'items.upc_ean_isbn as CODIGO',
        DB::Raw('coalesce((select sum(weekly_settlement_details.quantity)
        from weekly_settlements join weekly_settlement_details on weekly_settlement_details.weekly_id = weekly_settlements.id 
        where weekly_settlement_details.item_id=sale_items.item_id
        and weekly_settlements.date = "'.substr($fecha1,0,10).'" ), 0)as QUANTITY'),
        DB::Raw('concat(drec.name," ",srec.name, "-",sales.correlative) as documento'),
        DB::Raw('0 AS COMPRA'),
        'sale_items.quantity AS VENTA',
        'sales.created_at');
    
        
            $report = $compras
            ->unionAll($ventas)            
            ->orderBy('NOMBRE')
            ->orderBy('created_at')
            ->get();
    
        

        // dd($report);
        $reporte=$report->groupBy('NOMBRE');
            
        
        // dd($reporte);

        $fromDate = date('d/m/Y',strtotime($fecha1));
        $toDate = date('d/m/Y',strtotime($fecha2));
        $sortBy = 'Producto, Fecha';

        $meta = [ // For displaying filters description on header
            'fecha' => $fromDate . ' a ' . $toDate,
            'ordenado' => $sortBy
        ];

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

        return \PDF::loadView('report.grouped-reports.stock-by-product',$data)->stream($title.'.pdf');
    }

}