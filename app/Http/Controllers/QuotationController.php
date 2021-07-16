<?php

namespace App\Http\Controllers;

use App\Item;
use App\Pago;
use App\Route;
use App\User;
use App\Price;
use App\Serie;
use App\Almacen;
use App\Customer;
use App\Supplier;
use App\Parameter;
use App\Quotation;
use App\Receiving;
use App\Http\Requests;
use App\BodegaProducto;
use App\DetailQuotation;
use App\GeneralParameter;
use App\Traits\DatesTrait;

use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use App\Classes\NumeroALetras;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;

class QuotationController extends Controller
{
    use DatesTrait;
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
       
        $status = Input::get('status');
        if ($status==null){
            $status=1;
        }
        $fecha1=$this->fixFecha(Input::get('date1'));
        $fecha2=$this->fixFechaFin(Input::get('date2'));

        $administrador =Session::get('administrador');
		$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
		/** Si la ruta es requerida y no es administrador */
		if( (isset($ruta_requerida)) && ($administrador ==false)) {
			$rutas = Route::where('status_id',1)->asigned()->lists('id');
			if (count($rutas) == 0) {
			  $rutas = [0, 0];
			}
			$quotations = Quotation::join('customers','customers.id','=','quotations.customer_id')
            ->join('route_costumers','customers.id','=','route_costumers.customer_id')
            ->whereIn('route_costumers.route_id',$rutas)
            ->where('status', $status)
                ->select('quotations.*')
                ->whereBetween('date', [$fecha1, $fecha2])
                ->get();
        } else {
            $quotations = Quotation::where('status', $status)
            ->whereBetween('date', [$fecha1, $fecha2])
            ->get();
        }
     

        /**
         * Solo los almacenes a los que el usuario tiene permisos.
         */
        $almacen = Almacen::where('id_state', 1)
            ->asigned()
            ->orderBy('almacens.created_at','ASC')
            ->select('almacens.name', 'almacens.id')->get();

        return view('quotation.index')
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('almacen', $almacen)
            ->with('status', $status)
            ->with('quotations', $quotations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $correlativo = Quotation::max('correlative');
        $correlativo = $correlativo + 1;
        $idUserActive = Auth::user()->id;
        $ValorSeries = Serie::join('state_cellars as c', 'series.id_state', '=', 'c.id')
            ->join('documents', 'series.id_document', '=', 'documents.id')
            ->join('state_cellars as d', 'documents.id_state', '=', 'd.id')
            ->where('c.name', '=', 'Activo')
            ->where('d.name', '=', 'Activo')
            //condicion para los de signo negativo
            ->where('documents.sign', '=', '*')
            ->select('series.name', 'series.id', 'documents.name as nombre')
            ->orderBy('series.name', 'asc')->get();
        // $almacen=Almacen::where('id_state', '=', 1)->get();
        $dataUsers = User::where('show_in_tx', 0)->get();

        $prices = Price::active(1)->get();

        // $pagos = Pago::sale()->get();

        $administrador =Session::get('administrador');
		$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
		/** Si la ruta es requerida y no es administrador */
		if( (isset($ruta_requerida)) && ($administrador ==false)) {
			$rutasFilter = Route::where('status_id','1')->asigned()->lists('id');
			$rutas = Route::where('status_id','1')->asigned()->get();
			if (count($rutas) == 0) {
			  $rutas = [0, 0];
			}
			$customers = Customer::join('route_costumers','customers.id','=','route_costumers.customer_id')
			->whereIn('route_costumers.route_id',$rutasFilter)
			->select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'), 'customers.id', DB::Raw('(max_credit_amount-balance) as max_credit_amount'), 'balance')
			->get();		
		}
		else {
            $rutas = null;
            $customers = Customer::select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'), 'id', DB::Raw('(max_credit_amount-balance) as max_credit_amount'), 'balance')->get(); //all();
        }            
        return view('quotation.create')
            ->with('customer', $customers)
            ->with('prices', $prices)
            ->with('rutas', $rutas)
            ->with('id_correlativo', $correlativo)
            ->with('idUserActive', $idUserActive)
            ->with('dataUsers', $dataUsers)
            ->with('serie', $ValorSeries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $indice = 0;
            $sumTotal = 0;

            $nuevaFecha1 = explode('/', $request->date_tx);
            $diaFecha1 = $nuevaFecha1[0];
            $mesFecha1 = $nuevaFecha1[1];
            $anioFecha1 = $nuevaFecha1[2];
            $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1;

            $verify_correlative = Quotation::where('serie_id', $request->serie_id)
                ->where('correlative', $request->correlativo)
                ->count();
            if ($verify_correlative > 0) {
                throw new \Exception('El correlativo:' . $request->correlativo . ' ya esta utilizado en un documento de la serie seleccionada.', 6);
            }
            $quotation = new Quotation();
            $quotation->customer_id = $request->customer_id;
            $quotation->user_id = $request->user_relation;
            $quotation->comment = $request->comments;
            $quotation->date = $fecha1;
            $quotation->serie_id = $request->serie_id;
            $quotation->days = $request->days;
            $quotation->correlative = $request->correlative;
            $quotation->status = 1;
            $quotation->price_id = $request->price_id;
            $quotation->created_by = Auth::user()->id;
            $quotation->updated_by = Auth::user()->id;
            $quotation->save();
            $quotation_id = $quotation->id;

            // process receiving items

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'cantidad') !== false) {
                    $items_array[$indice]['cantidad'] = $value;
                } elseif (strpos($key, 'selling') !== false) {
                    $items_array[$indice]['selling'] = $value;
                } elseif (strpos($key, 'newcosto') !== false) {
                    $items_array[$indice]['newcosto'] = $value;
                } elseif (strpos($key, 'product') !== false) {
                    $items_array[$indice]['product'] = $value;
                    $cantidad = $items_array[$indice]['cantidad'];
                    $id_items = $items_array[$indice]['product'];
                    $precio = $items_array[$indice]['selling'];

                    $detail = new DetailQuotation();
                    $detail->quotation_id = $quotation_id;
                    $detail->item_id = $id_items;
                    //Para tener acceso al producto
                    $detail->price = $precio;
                    $detail->quantity = $cantidad;
                    $detail->quantity_sale = $cantidad;
                    $detail->total_cost = $precio * $cantidad;
                    $sumTotal += $detail->total_cost;
                    $detail->save();
                }
            }
            $newq = Quotation::find($quotation_id);
            $newq->amount = $sumTotal;
            $newq->save();
            DB::commit();
            $message = 'Cotización guardada correctamente';
            $flag = 1;
            $url = 'quotation/header/' . $quotation_id;
            Session::flash('message', 'Cotización guardada correctamente');
            Session::flash('alert-type', trans('success'));
        } catch (\Exception $ex) {
            DB::rollBack();
            $message = "RB:" . $ex->getMessage();
            $flag = 2;
            $url = '#';
        }
        $resp = array('message' => $message, 'flag' => $flag, 'url' => $url);
        return json_encode($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = Quotation::find($id);
        $details = DetailQuotation::where('quotation_id', $id)->get();
        $parameters = Parameter::first();
        $customer = Customer::find($quotation->customer_id);
        $serie = Serie::find($quotation->serie_id);
        $letras = NumeroALetras::convertir($quotation->amount, 'quetzales', 'centavos');
        $precio_letras = ucfirst(strtolower($letras));
        /**
         * Solo los almacenes a los que el usuario tiene permisos.
         */
        $almacen = Almacen::where('id_state', '=', '1')
            ->orderBy('almacens.created_at', 'ASC')
            ->asigned()
            ->select('almacens.name', 'almacens.id')->get();

        $parameters = GeneralParameter::All();
        $imprimir_codigo_cliente = 0;
        foreach ($parameters as $key => $value) {
            if ($value->name === 'Imprimir código de cliente.') {
                $imprimir_codigo_cliente = intval($value->active);
            }
        }
        // dd($imprimir_codigo_cliente);
        $parameters = Parameter::first();
        $imprimir_ticket = GeneralParameter::active()->where('name', 'Imprimir ticket')->first();


        $imprimir_ticket_p = $imprimir_ticket->active == null ? 0 : $imprimir_ticket->active;
        $imprimir_propietario = GeneralParameter::where('name', 'Imprimir propietario y negocio en proforma.')
            ->first()->active;

        $version_proforma = GeneralParameter::active()->where('name', 'Versión de proforma')->first();

        return view('documents.cotizacion')
            ->with('details', $details)
            ->with('parameters', $parameters)
            ->with('customer', $customer)
            ->with('almacen', $almacen)
            ->with('precio_letras', $precio_letras)
            ->with('serie', $serie)
            ->with('quotation', $quotation)
            ->with('parameters', $parameters)
            ->with('imprimir_codigo_cliente', $imprimir_codigo_cliente)
            ->with('imprimir_propietario', $imprimir_propietario)
            ->with('imprir_ticket', $imprimir_ticket_p);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $dup=false)
    {
        $quotation = Quotation::find($id);
        $details = DetailQuotation::where('quotation_id', $id)->get();
        $idUserActive = Auth::user()->id;
        $ValorSeries = Serie::join('state_cellars as c', 'series.id_state', '=', 'c.id')
            ->join('documents', 'series.id_document', '=', 'documents.id')
            ->join('state_cellars as d', 'documents.id_state', '=', 'd.id')
            ->where('c.name', '=', 'Activo')
            ->where('d.name', '=', 'Activo')
            //condicion para los de signo negativo
            ->where('documents.sign', '=', '*')
            ->select('series.name', 'series.id', 'documents.name as nombre')
            ->orderBy('series.name', 'asc')->get();
        // $almacen=Almacen::where('id_state', '=', 1)->get();
        $dataUsers = User::where('show_in_tx', 0)->get();
        $prices = Price::active(1)->get();
        $administrador =Session::get('administrador');
		$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
		/** Si la ruta es requerida y no es administrador */
		if( (isset($ruta_requerida)) && ($administrador ==false)) {
            $rutasFilter = Route::where('status_id','1')->asigned()->lists('id');
            $rutas = Route::where('status_id','1')->asigned()->get();
			if (count($rutas) == 0) {
			  $rutas = [0, 0];
			}
			$customers = Customer::join('route_costumers','customers.id','=','route_costumers.customer_id')
			->whereIn('route_costumers.route_id',$rutasFilter)
			->select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'), 'customers.id', DB::Raw('(max_credit_amount-balance) as max_credit_amount'), 'balance')
			->get();		
		}
		else {
            $rutas = null;
            $customers = Customer::select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'), 'id', DB::Raw('(max_credit_amount-balance) as max_credit_amount'), 'balance')->get(); //all();
        }
        return view('quotation.edit')
            ->with('customer', $customers)
            ->with('idUserActive', $idUserActive)
            ->with('dataUsers', $dataUsers)
            ->with('quotation', $quotation)
            ->with('rutas', $rutas)
            ->with('prices', $prices)
            ->with('duplicate', $dup)
            ->with('details', $details)
            ->with('serie', $ValorSeries);
    }

    public function load_sale($id, $cellar){
        $flag = false;
        $newdetail = array();
        $quotation = Quotation::find($id);
        $details = DetailQuotation::where('quotation_id', $id)->get();
        foreach ($details as $key => $value){
            $items = Item::find($value->item_id);
            $valorEncontrado = BodegaProducto::where('id_product', '=', $value->item_id)
                ->where('id_bodega', '=', $cellar)
                ->first();
            if (isset($valorEncontrado->quantity)){
                $qTemp = $valorEncontrado->quantity;
            }
            else{
                $qTemp = 0;
            }
            $newdetail[] = array('code'=>$items->upc_ean_isbn,'item_id' => $value->item_id, 'cellar' => $qTemp, 'description'=>$items->item_name, 'quantity'=>$value->quantity_sale, 'detail_id'=>$value->id);
            if (!isset($valorEncontrado->quantity)&&$items->stock_action!='='){
                $flag = true;
            }

            if (isset($valorEncontrado->quantity) && $items->stock_action!='='&&$valorEncontrado->quantity<$value->quantity_sale){
                $flag = true;
            }
        }
        if ($flag){
            $parameters = Parameter::first();
            $cellar = Almacen::find($cellar);
            $customer = Customer::find($quotation->customer_id);
            $serie = Serie::find($quotation->serie_id);
            Session::flash('message', 'No se puede cargar la cotización porque hay productos que no tienen existencia suficiente en la bodega seleccionada');
            Session::flash('alert-class', 'alert-error');
            Session::flash('alert-type', 'error');
            return view('quotation.load_sale_error')
                ->with('details', $details)
                ->with('parameters', $parameters)
                ->with('customer', $customer)
                ->with('cellar', $cellar)
                ->with('serie', $serie)
                ->with('newdetail', $newdetail)
                ->with('quotation', $quotation);
        }
        else{
            Return Redirect::to('sales/create/'.$id.'/'.$cellar);
        }
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
        DB::beginTransaction();
        try {
            $indice = 0;
            $sumTotal = 0;

            $nuevaFecha1 = explode('/', $request->date_tx);
            $diaFecha1 = $nuevaFecha1[0];
            $mesFecha1 = $nuevaFecha1[1];
            $anioFecha1 = $nuevaFecha1[2];
            $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1;
            if ($request->duplicate){
                $verify_correlative = Quotation::where('serie_id', $request->serie_id)
                    ->where('correlative', $request->correlativo)
                    ->where('id', '!=', $id)
                    ->count();
            }
            else{
                $verify_correlative = Quotation::where('serie_id', $request->serie_id)
                    ->where('correlative', $request->correlativo)
                    ->count();
            }

            if ($verify_correlative > 0) {
                throw new \Exception('El correlativo:' . $request->correlativo . ' ya esta utilizado en un documento de la serie seleccionada.', 6);
            }

            if ($request->duplicate){
                $quotation = new Quotation();
                $quotation->customer_id = $request->customer_id;
                $quotation->user_id = $request->user_relation;
                $quotation->comment = $request->comments;
                $quotation->date = $fecha1;
                $quotation->serie_id = $request->serie_id;
                $quotation->days = $request->days;
                $quotation->correlative = $request->correlative;
                $quotation->status = 1;
                $quotation->price_id = $request->price_id;
                $quotation->created_by = Auth::user()->id;
                $quotation->updated_by = Auth::user()->id;
                $quotation->save();
            }
            else{
                $quotation = Quotation::find($id);
                $quotation->customer_id = $request->customer_id;
                $quotation->user_id = $request->user_relation;
                $quotation->comment = $request->comments;
                $quotation->date = $fecha1;
                $quotation->serie_id = $request->serie_id;
                $quotation->days = $request->days;
                $quotation->correlative = $request->correlative;
                $quotation->status = 1;
                $quotation->price_id = $request->price_id;
                $quotation->created_by = Auth::user()->id;
                $quotation->updated_by = Auth::user()->id;
                $quotation->update();
                /*
                 * ELIMINAR LOS DETALLES ANTERIORES DE LA COTIZACIÓN
                 * */
                $details = DetailQuotation::where('quotation_id', $id)->get();
                foreach ($details as $val){
                    $val->delete();
                }
            }

            $quotation_id = $quotation->id;

            // process receiving items

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'cantidad') !== false) {
                    $items_array[$indice]['cantidad'] = $value;
                } elseif (strpos($key, 'selling') !== false) {
                    $items_array[$indice]['selling'] = $value;
                } elseif (strpos($key, 'newcosto') !== false) {
                    $items_array[$indice]['newcosto'] = $value;
                } elseif (strpos($key, 'product') !== false) {
                    $items_array[$indice]['product'] = $value;
                    $cantidad = str_replace(",", "",$items_array[$indice]['cantidad']);
                    $id_items = $items_array[$indice]['product'];
                    $precio = str_replace(",","",$items_array[$indice]['selling']);

                    $detail = new DetailQuotation();
                    $detail->quotation_id = $quotation_id;
                    $detail->item_id = $id_items;
                    //Para tener acceso al producto
                    $detail->price = $precio;
                    $detail->quantity = $cantidad;
                    $detail->quantity_sale = $cantidad;
                    $detail->total_cost = $precio * $cantidad;
                    $sumTotal += $detail->total_cost;
                    $detail->save();
                }
            }
            $newq = Quotation::find($quotation_id);
            $newq->amount = $sumTotal;
            $newq->save();
            DB::commit();
            if ($request->duplicate){
                $message = 'Cotización duplicada correctamente';
            }
            else{
                $message = 'Cotización actualizada correctamente';
            }
            $flag = 1;
            $url = 'quotation/header/' . $quotation_id;

            Session::flash('message', $message);
            Session::flash('alert-type', trans('success'));
        } catch (\Exception $ex) {
            DB::rollBack();
            $message = "RB:" . $ex->getMessage();
            $flag = 2;
            $url = '#';
        }
        $resp = array('message' => $message, 'flag' => $flag, 'url' => $url);
        return json_encode($resp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quotation = Quotation::find($id);
        $quotation->status = 3;
        $quotation->updated_by = Auth::user()->id;
        $quotation->update();
        Session::flash('message', 'Se han actualizado los datos correctamente');
        return Redirect::to('quotation/header');
    }

    public function correlative($serie)
    {
        $correlative = Quotation::where('serie_id', $serie)
            ->max('correlative');
        $resp = $correlative + 1;
        return $resp;
    }

    public function autoComplete(Request $request)
    {
        $search = trim($request->id);
        $price_id = trim(strtoupper($request->price_id));

        $productos = Item::leftJoin('item_prices','items.id','=','item_prices.item_id')
        ->Join('prices','item_prices.price_id','=','prices.id')
        ->where('stock_action', '+')
        ->where('item_prices.price_id',$price_id)
            // ->where('item_name','LIKE',"'%$search%'")
            ->whereRaw("item_name LIKE '%" . $search . "%'")
            // ->OrwhereRaw("upc_ean_isbn LIKE '%".$search."%'")
            ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,cost_price+(cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,cost_price+(cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action')
            ->get();
        return $productos;
    }

    public function searchCode(Request $request)
    {
        $id = trim(strtoupper($request->id));
        $price_id = trim(strtoupper($request->price_id));

        $product = Item::leftJoin('item_prices','items.id','=','item_prices.item_id')
        ->Join('prices','item_prices.price_id','=','prices.id')
        ->where('item_prices.price_id',$price_id)
        ->where('upc_ean_isbn', $id)
            ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,cost_price+(cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,cost_price+(cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action')
            ->first();
        return $product;
    }

    public function searchId(Request $request)
    {
        $id = trim(strtoupper($request->id));
        $price_id = trim(strtoupper($request->price_id));

        $product = Item::leftJoin('item_prices','items.id','=','item_prices.item_id')
            ->Join('prices','item_prices.price_id','=','prices.id')
            ->where('item_prices.price_id',$price_id)
            ->where('items.id', $id)
            ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,cost_price+(cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,cost_price+(cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action')
            ->first();
        return $product;
    }

    public function getdetails($id){
        $quotation = Quotation::find($id);
        $details = DetailQuotation::join('items', 'items.id', '=', 'detail_quotations.item_id')
            ->select('items.upc_ean_isbn as code', 'item_id', 'price', 'detail_quotations.quantity_sale', 'total_cost', 'detail_quotations.id as det_id')
            ->where('quotation_id', $id)
            ->where('quantity_sale', '>',0)
            ->get();
        $resp = array('header' => json_encode($quotation), 'details' => json_encode($details));
        $detfrm = DetailQuotation::where('quotation_id', $id)->get();
        foreach($detfrm as $val){
            $val->quantity_sale = $val->quantity;
            $val->update();
        }
        return json_encode($resp);
    }

    public function updateDetails(Request $request){
        $details = json_decode($request->newDetails);
        $quotation = $request->quotation_id;
        $cellar = $request->cellar_id;
        try{
            foreach($details as $value){
                $det = DetailQuotation::find($value->detail_id);
                $det->quantity_sale = $value->quantity;
                $det->update();
            }
            DB::commit();
            $message = 'Cotización actualizada correctamente';
            $flag = 1;
            $url = 'sales/create/'.$quotation.'/'.$cellar;
        }
        catch (\Exception $ex){
            DB::rollBack();
            $message = "RB:" . $ex->getMessage();
            $flag = 2;
            $url = '#';
        }
        $resp = array('message' => $message, 'flag' => $flag, 'url' => $url);
        return json_encode($resp);
    }
}
