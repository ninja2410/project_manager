<?php

namespace App\Http\Controllers;

use App\Item;

use App\Sale;
use PdfReport;
use App\Parameter;

use App\RouteUser;
use App\Http\Requests;
use App\GeneralParameter;
use App\Traits\DatesTrait;
use App\Traits\ItemsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class ItemReportController extends Controller
{

    use DatesTrait;
    use ItemsTrait;
	public function __construct()
	{
        $this->middleware('auth');
        $this->middleware('parameter');
    }
    public function profitIndex()
    {
        $fecha1=$this->fixFecha(Input::get('date1'));
        $fecha2=$this->fixFechaFin(Input::get('date2'));
        $list_items = $this->getItemsAndServicesAll();

        return view('report.profit_by_product')
        ->with('list_items',$list_items)
        ->with('fecha1',$fecha1)
        ->with('fecha2',$fecha2);
    }

    public function profitByProductByInvoice()
    {
        $fecha1=$this->fixFecha(Input::get('date1'));
        $fecha2=$this->fixFechaFin(Input::get('date2'));
        $product = Input::get('product');
        $product = ($product ==null||$product ==0) ? 'Todo':$product;

        $title = 'Rentabilidad por producto por factura'; // Report title
        $items = Sale::join('sale_items','sale_items.sale_id','=','sales.id');

        $administrador =Session::get('administrador');
        $ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
        /** Si la ruta es requerida y no es administrador */
        if( (isset($ruta_requerida)) && ($administrador ==false)) {
            $rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
            if (count($rutas) == 0) {
            $rutas = [0, 0];
            }
            $items->leftJoin('customers','customers.id','=','sales.customer_id')
            ->join('route_costumers','customers.id','=','route_costumers.customer_id')
            ->whereIn('route_costumers.route_id',$rutas);
        }

        $items->join('items','items.id','=','sale_items.item_id')
        ->join('series','sales.id_serie','=','series.id')
        ->join('pagos','sales.id_pago','=','pagos.id')
        ->join('documents','series.id_document','=','documents.id')
        ->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
        if($product!=='Todo')
        {
            $items->where('sale_items.item_id',$product);
            $item = Item::findOrFail($product);
            $title = 'Rentabilidad por producto :'.$item->upc_ean_isbn.' - '.$item->item_name;
        }

        $items->select(['items.id as item_id','sales.id as sale_id'
        ,DB::raw('concat(items.upc_ean_isbn," ",items.item_name) as item_name')
        ,'sales.sale_date'
        ,'pagos.name as pago'
        ,'items.cost_price'
        ,DB::raw('round(sale_items.low_price,2) as selling_price')
        ,'sale_items.quantity'
        ,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
        ,DB::raw('round(sale_items.low_price-items.cost_price,2) as profit')])
        ->orderBy('item_name')
        ->orderBy('sales.id');
        $items->almacen();

        $report_items = $items->get();
        $reporte = $report_items->groupBy('item_name');


        $fromDate = date('d/m/Y',strtotime($fecha1));
        $toDate = date('d/m/Y',strtotime($fecha2));
        $sortBy = 'Fecha, Documento, Correlativo';

        $meta = [ // For displaying filters description on header
            'fecha' => $fromDate . ' al ' . $toDate,
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

        // return view('report.grouped-reports.profit-item-invoice',$data);

        return \PDF::loadView('report.grouped-reports.profit-item-invoice',$data)->stream($title.'.pdf');
    }

    public function profitByProductSum(Request $request)
    {
        $fecha1=$this->fixFecha(Input::get('date1'));
        $fecha2=$this->fixFechaFin(Input::get('date2'));
        $product = Input::get('product');
        $product = ($product ==null||$product ==0) ? 'Todo':$product;


        $items = Sale::join('sale_items','sale_items.sale_id','=','sales.id');

        $administrador =Session::get('administrador');
        $ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
        /** Si la ruta es requerida y no es administrador */
        if( (isset($ruta_requerida)) && ($administrador ==false)) {
            $rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
            if (count($rutas) == 0) {
            $rutas = [0, 0];
            }
            $items->leftJoin('customers','customers.id','=','sales.customer_id')
            ->join('route_costumers','customers.id','=','route_costumers.customer_id')
            ->whereIn('route_costumers.route_id',$rutas);
        }

        $items->join('items','items.id','=','sale_items.item_id')
        ->where('sales.cancel_bill','=',0)
        ->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
        if($product!=='Todo')
        {
            $items->where('sale_items.item_id',$product);
            $item = Item::findOrFail($product);
            $title = 'Rentabilidad por producto :'.$item->upc_ean_isbn.' - '.$item->item_name;
        }

        $items->select([
        DB::raw('min(sales.sale_date) as min_sales_date')
        ,DB::raw('max(sales.sale_date) as max_sales_date')
        ,DB::raw('concat("[",items.upc_ean_isbn,"]","-",items.item_name) as item_name')
        ,DB::raw('sum(sale_items.quantity) as quantity')
        ,DB::raw('sum(sale_items.cost_price) as cost')
        ,DB::raw('round(sum(sale_items.low_price),2) as selling')
        ,DB::raw('round((sum(sale_items.low_price)-sum(sale_items.cost_price)),2) as profit')])
        ->groupBy('item_name')
        ->orderBy('item_name')
        ->almacen();

        $salesReport=$items->get();
        // dd($salesReport);
        // ejemplo
        $fromDate = date('d/m/Y',strtotime($fecha1));
        $toDate = date('d/m/Y',strtotime($fecha2));


        $title = 'Rentabilidad total por producto'; // Report title

        $meta = [ // For displaying filters description on header
            'Fechas de' => $fromDate ,
            'a' => $toDate
        ];

        $columns = [ // Set Column to be displayed
            'Producto' => 'item_name',
            'Primera venta' => 'min_sales_date',
            'Ultima venta' => 'max_sales_date',
            'Cantidad' => 'quantity',
            'Total venta' => 'selling',
            'Total costo' => 'cost',
            'Renta bruta' => 'profit'

            ];

                return PdfReport::of($title, $meta, $salesReport, $columns)
                ->editColumns(['Producto'],
                ['class'=>'left'])
                ->editColumns(['Total venta','Total costo','Renta bruta'],
                ['class'=>'right'])
                ->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
                    'Cantidad' => 'point',
                    'Total venta' => 'Q',
                    'Total costo' => 'Q',
                    'Renta bruta' => 'Q',
                    ])
                    ->setOrientation('landscape')
                    ->stream();
    }

}
