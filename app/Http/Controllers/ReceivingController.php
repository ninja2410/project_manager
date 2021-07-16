<?php namespace App\Http\Controllers;

use App\CreditSupplier;
use App\CreditSupplierDetail;
use App\GeneralParameter;
use App\InventoryClosing;
use App\Item;
use App\Pago;
use App\Sale;
use App\User;
use App\Serie;
use App\Almacen;
use App\Document;
use App\Supplier;
use App\Inventory;
use App\Receiving;
use App\AlmacenUser;
use App\ItemKitItem;
use App\StateCellar;
use App\Http\Requests;
use App\ReceivingItem;
use App\ReceivingTemp;
use App\BodegaProducto;
use App\ReceivingOutlay;
use Illuminate\Http\Request;
use App\Traits\TransactionsTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReceivingRequest;
use App\Payment;
use Illuminate\Database\Schema\Blueprint;
use Doctrine\DBAL\Driver\IBMDB2\DB2Driver;
use \Auth, \Redirect, \Validator, \Input, \Session;

class ReceivingController extends Controller
{
    use TransactionsTrait;

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
        $fecha1=Input::get('date1');
        $fecha2=Input::get('date2');
        $document = Input::get('document');
        $document = $document ==null ? 'Todo':$document;
        $status = Input::get('status') ==null ? 'Todo':Input::get('status');

        $fechaActual=date("Y-m-d");
        if($fecha1==null){
            $fecha1=$fechaActual.' 00:00:00';
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

        // $fecha2 =$fecha2== null ? $fechaActual.' 23:59:59' : $fecha2;
        // return $fecha1.'  '.$fecha2;
        // dd($fecha1.'  '.$fecha2);
        $dataDocuments=DB::table('series')
        ->leftJoin('documents','series.id_document','=','documents.id')
        ->where('documents.sign','=','+')
        ->where('documents.ajuste_inventario','=','0')
        ->select('series.id as id_serie','documents.id  as id_document','documents.sign','series.name as serie','documents.name as document')
        ->get();

        $dataStatus = StateCellar::inventory()->get();
        $receivings_q =Receiving::with('payment')->leftJoin('series','receivings.id_serie','=','series.id');
        $receivings_q->leftJoin('documents','series.id_document','=','documents.id');
        $receivings_q->leftJoin('users','users.id','=','receivings.created_by');
        $receivings_q->leftJoin('suppliers','receivings.supplier_id','=','suppliers.id');
        $receivings_q->leftJoin('pagos','receivings.id_pago','=','pagos.id');
        $receivings_q->where('documents.sign','=','+');
        //$receivings_q->where('receivings.cancel_bill','=',0);
        if($document!='Todo'){
            $receivings_q->where('receivings.id_serie',$document);
        }
        if($status!='Todo'){
            $receivings_q->where('receivings.cancel_bill',$status);
        }
        $receivings_q->whereBetween('receivings.date',[$fecha1,$fecha2]);
        $receivings_q->select(['receivings.id as id'
        ,DB::raw('concat(documents.name," ",series.name,"-",receivings.correlative) as document_and_correlative')
        ,'receivings.date','receivings.comments', 'receivings.payment_status', 'receivings.cancel_bill'
        ,'users.name as user_name','receivings.id_pago','suppliers.id as supplier_id'
        ,'pagos.name as pago','receivings.total_cost', 'receivings.total_paid',DB::raw('"Pagado" as status')]);
        $receivings_q->orderBy('receivings.created_at','ASC');
        $receivings_q->orderBy('series.name','ASC');
        $receivings_q->orderBy('correlative','ASC');
        $receivings=$receivings_q->get();
        // dd($receivings);

        // $pagos=Pago::all();

        return view('receiving.list')
        ->with('receiving', $receivings)
        // ->with('pagoss',$pagos)
        ->with('fecha1', $fecha1)
        ->with('fecha2', $fecha2)
        ->with('dataDocuments',$dataDocuments)
        ->with('dataStatus',$dataStatus)
        ->with('document',$document)
        ->with('status',$status);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create()
    {
        $logged_user = Auth::user();
        $idUserActive = $logged_user->id;

        $idUserActive = $logged_user->id;
        $receivings = Receiving::orderBy('id', 'desc')->first();

        $pagos = Pago::receiving()
        ->select('id','name','type')
        ->get();

        $almacen = Almacen::join('almacen_users', 'almacens.id', '=', 'almacen_users.id_bodega')->where('almacen_users.id_usuario', '=', $idUserActive)
        ->where('id_state', '=', '1')
        ->select('almacens.name', 'almacens.id')->get();

        $ValorSeries = Serie::join('state_cellars as c', 'series.id_state', '=', 'c.id')
        ->join('documents', 'series.id_document', '=', 'documents.id')
        ->join('state_cellars as d', 'documents.id_state', '=', 'd.id')
        ->where('c.name', '=', 'Activo')
        ->where('d.name', '=', 'Activo')
        //condicion para los de signo negativo
        ->where('documents.sign', '=', '+')
        ->where('documents.ajuste_inventario','=','0')
        ->select('series.name', 'series.id', 'documents.name as nombre')
        ->orderBy('series.name', 'asc')->get();
        // $almacen=Almacen::where('id_state', '=', 1)->get();
        $suppliers = Supplier::select(DB::Raw('concat(nit_supplier," | ",company_name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'), 'id')->get(); //all();

        //$dataUsers = User::where('show_in_tx', 0)->lists('name', 'id');
        $dataUsers = User::where('show_in_tx', 0)->get();
        $list_products = Item::where('stock_action', '+')
            ->where('type', '!=', 2)
            ->where('items.status','=',1)
            ->wildcard()
            ->select('upc_ean_isbn', 'avatar','item_name', 'description', 'size', 'cost_price', 'id')
            ->get();
        return view('receiving.index')
        ->with('receiving', $receivings)
        ->with('supplier', $suppliers)
        ->with('idUserActive', $idUserActive)
        ->with('pagos', $pagos)
        ->with('list_products', $list_products)
        ->with('dataUsers', $dataUsers)
        ->with('serieFactura', $ValorSeries)
        ->with('almacendata', $almacen);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
    //En esta parte se hace lo de las ventas.

    public function store(Request $request)
    {
        $logged_user = Auth::user();
        $idUserActive = $logged_user->id;
        /**
        * Parseo del request con los 2 formularios serializados
        * Ventas (venta) y Forma de pago (pago)
        */
        $nombreDeTransaccion='';
        parse_str($request->venta, $ventas);
        parse_str($request->pago, $forma_pagos);
        $compra = json_decode(json_encode($ventas));
        $forma_pago = json_decode((json_encode($forma_pagos)));
        /**
        * Obtener los datos de la forma de pago
        */
        $pago = Pago::find($compra->id_pago);

        $gastos_json = json_decode($compra->outlays);

        $indice = 0;
        $sumTotal = 0;
        $totalAcumulado = 0;

        /**
        * Si la forma de pago es "CREDITO"
        * Marcar la factura como "NO PAGADA"
        */
        $factura_pagada = 1;

        /* Si forma de pago no es Crédito */
        // if ($pago->type == 6 && $compra->id_pago == 6) {
        if ($pago->type == 6 ) {
            $factura_pagada = 0;
        }
        //        $outlays = json_decode($request->outlays);
        DB::beginTransaction();
        try {
            $nuevaFecha1 = explode('/', $compra->date_tx);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1;

            $verify_correlative = Receiving::where('id_serie', $compra->serie_id)
            ->where('correlative', $compra->correlativo_num)
            ->count();
            if ($verify_correlative > 0) {
                throw new \Exception('El correlativo:' . $compra->correlativo_num . ' ya esta utilizado en un documento de la serie seleccionada.', 6);
            }
            #region VERIFICACIONES DE INVENTARIO
            /**
             * VERIFICAR SI ES NECESARIO EL CIERRE DE INVENTARIO
             */
            if (\Illuminate\Support\Facades\Session::get('inventory_close', false)){
                throw new \Exception("Se requiere el cierre de inventario del mes en curso.", 6);
            }

            /**
             * VERIFICAR SI SE QUIERE REALIZAR UA VENTA EN UN MES CON EL INVENTARIO CERRADO
             */
            #region VERIFICAR ULTIMOM CIERRE
            $dlast = InventoryClosing::orderby('id', 'desc')->first();
            $refDate = "";
            if (isset($dlast->date)){
                $refDate = date('m/Y', strtotime($dlast->date));
            }
            else{
                $paramD = GeneralParameter::find(13)->text_value;
                $refDate = date('m/Y', strtotime($paramD));
            }

            $sDate = date('m/Y', strtotime($fecha1));
            if ($refDate == $sDate && !isset($paramD)){
                throw  new \Exception("No se puede realizar transacciones de inventario en un mes cerrado.", 6);
            }
            #endregion
            #endregion

            $receivings = new Receiving;
            $receivings->supplier_id = $compra->supplier_id;
            $receivings->user_id = $idUserActive;
            $receivings->payment_type = $compra->id_pago;
            $receivings->comments = $compra->comments;
            $receivings->date = $fecha1;
            //$receivings->total_cost=Input::get('total_cost');
            $receivings->id_pago = $compra->id_pago;
            $receivings->id_serie = $compra->serie_id;
            $receivings->storage_origins = $compra->id_bodegas;
            $receivings->correlative = $compra->correlativo_num;
            $receivings->deposit = $compra->id_pago;
            $receivings->expenses = 0;
            $receivings->receiving_date = $compra->date_tx;
            $receivings->created_by = $idUserActive;
            $receivings->payment_status = $factura_pagada;
            $receivings->reference = $compra->reference;
            $receivings->save();
            $receivings_id = $receivings->id;

            // process receiving items

            foreach ($compra as $key => $value) {
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
                    $nCosto = $items_array[$indice]['newcosto'];

                    $receivingItemsData = new ReceivingItem();
                    $receivingItemsData->receiving_id = $receivings->id;
                    $receivingItemsData->item_id = $id_items;
                    //Para tener acceso al producto
                    $receivingItemsData->cost_price = $precio;
                    $receivingItemsData->quantity = $cantidad;
                    $receivingItemsData->total_cost = $precio * $cantidad;
                    $sumTotal = $sumTotal + $receivingItemsData->total_cost;
                    $receivingItemsData->last_cost = $precio;
                    //Buscamos el producto para tener acceso a todas su propiedades
                    $items2 = Item::find($id_items);
                    //realizamos el calculo del la cantidad de productos de las bodegas
                    $cantBodeProd = BodegaProducto::where('id_product', '=', $items2->id)->sum('quantity');
                    //realizamos el calculo del nuevo costo a insertar
                    $NuevoCosto = (($items2->cost_price * $cantBodeProd) + ($nCosto * $cantidad)) / ($cantBodeProd + $cantidad);
                    $receivingItemsData->save();

                    $items = Item::find($id_items);
                    $inventories = new Inventory;
                    $inventories->almacen_id = $compra->id_bodegas;
                    $inventories->item_id = $id_items;
                    $inventories->user_id = Auth::user()->id;
                    $inventories->in_out_qty = $cantidad;

                    //Obtenemos la serie
                    $nombreSerie = Serie::find($compra->serie_id);
                    //Obtenemos el nombre del documento
                    $nombreDocument = Document::where('id', '=', $nombreSerie->id_document)->value('name');
                    $nombreDeTransaccion = $nombreDocument . ' ' . $nombreSerie->name . '-' . $compra->correlativo_num;;

                    $inventories->remarks = $nombreDeTransaccion;
                    $inventories->save();

                    //verificacion de artiulo
                    $existe = BodegaProducto::where('id_bodega', '=', $compra->id_bodegas)
                    ->where('id_product', '=', $id_items)->value('id');
                    // $articulo = BodegaProducto::where('id_bodega','=',1)
                    // ->where('id_product','=',3)->value('id');
                    if ($items->type == 1) {
                        $ingreso = new BodegaProducto;
                        if (!$existe) {
                            $ingreso->id_bodega = $compra->id_bodegas;
                            $ingreso->id_product = $id_items;
                            $ingreso->quantity = $cantidad;
                            $ingreso->save();
                        } else {
                            $ingreso2 = BodegaProducto::find($existe);
                            $ingreso2->quantity = $ingreso2->quantity + $cantidad;
                            $ingreso2->save();
                        }
                        //process item quantity
                        $items->quantity = $items->quantity + $cantidad;
                    }
                    //Guardamos el nuevo costo calculado
                    $items->cost_price = $NuevoCosto;
                    $items->save();
                }
            }
            $update_receivings = Receiving::find($receivings_id);
            $update_receivings->total_cost = $sumTotal;
            $update_receivings->save();
            $amount_expenses=0;
            //            REGISTRAR LOS GASTOS DE LA COMPRA
            if (isset($gastos_json)){
                foreach ($gastos_json as $key => $value){
                    $nw = new ReceivingOutlay();
                    $nw->description = $value->description;
                    $nw->amount = $value->amount;
                    $nw->document_id = $receivings_id;
                    $nw->save();

                    //CONTADOR DE TOTAL DE GASTOS
                    $amount_expenses += $value->amount;

                    //REGISTRAR EL GASTO CON EL TIPO DE PAGO SELECCIONADO
                    $gastos = [];
                    $gastos['account_id'] = $forma_pago->account_id;
                    $gastos['paid_at'] = $compra->date_tx;
                    $gastos['amount'] = $value->amount;
                    $gastos['description'] = $value->description;
                    $gastos['category_id'] = 11;
                    $gastos['reference'] = $nombreDeTransaccion;
                    $gastos['user_id'] = $forma_pago->user_id;
                    $gastos['status'] = $forma_pago->status;
                    $gastos['payment_method'] = $compra->id_pago;
                    $gastos['supplier_id'] = $compra->supplier_id;
                    $gastos['invoice_id'] = $receivings_id;
                    $gastos['amount_applied'] = $value->amount;
                    //DATOS DE GASTOS GENERALES
                    $gastos['assigned_user_id'] = Auth::user()->id;
                    $gastos['route_id'] = null;
                    $gastos['cant'] = 0;
                    $gastos['unit_price'] = 0;
                    $gastos['payment_status'] = 0;

                    $nuevo = new Request($gastos);

                    /* Si forma de pago no es Crédito */
                    // if ($pago->type != 6 && $compra->id_pago != 6) {
                    if ($pago->type != 6) {
                        $guardarPago = $this->saveExpense($nuevo);
                        if ($guardarPago[0] < 0) {
                            /**
                            * Si hubo errores al guardar FOrma de pago
                            * Hacemos Rollback de la transaccion
                            * Definimos la bandera de error, guardamos el error.
                            */
                            $message = "Error registro de gasto sobre compra C:" . $guardarPago[1];
                            throw new \Exception($message, 6);
                            $url = '#';
                            $flag = 2;
                        }
                    }

                    $guardar2 = $this->saveExpenseGeneral($nuevo);
                    if ($guardar2[0] < 0) {
                        /**
                        * Si hubo errores al guardar FOrma de pago
                        * Hacemos Rollback de la transaccion
                        * Definimos la bandera de error, guardamos el error.
                        */
                        $message = "Error registro de gasto sobre compra G:" . $guardar2[1];
                        throw new \Exception($message, 6);
                        $url = '#';
                        $flag = 2;
                    }
                }
                $update_receivings->expenses = $amount_expenses;
                $update_receivings->update();
            }

            // if ($pago->type == 6 && $compra->id_pago == 6) {
            if ($pago->type == 6) {
                $credito = [];
                $credito['total_pagos']     = 1;
                $credito['total_eganche']   = 0;
                $credito['total_interes']   = 0;
                $credito['montoCredito']    = $forma_pago->amount;
                $credito['date_payments']   = $forma_pago->date_payments;
                $credito['id_supplier']      = $compra->supplier_id;
                $credito['id_factura']      = $receivings_id;

                $nuevo = new Request($credito);
                $guardarPagoCredit = $this->saveCreditSupplier($nuevo);
                if ($guardarPagoCredit[0] < 0) {
                    /**
                     * Si hubo errores al guardar FOrma de pago
                     * Hacemos Rollback de la transaccion
                     * Definimos la bandera de error, guardamos el error.
                     */
                    $message = "Error pago:" . $guardarPagoCredit[1];
                    throw new \Exception($message, 6);
                    $url = '#';
                    $flag = 2;
                }
            } else {
                /* *********************************************
                *   SI  FORMA DE PAGO DISTINTO A CREDITO
                *      GUARDAMOS TRANSACCION BANCARIA
                ** *********************************************
                */

                $gastos = [];
                $gastos['account_id'] = $forma_pago->account_id;
                $gastos['paid_at'] = $compra->date_tx;
                $gastos['amount'] = $sumTotal;
                $gastos['description'] = $forma_pago->description;
                $gastos['category_id'] = 3;
                if (isset($forma_pago->reference)){
                    $gastos['reference'] = $forma_pago->reference;
                }
                else{
                    $gastos['reference'] = '';
                }

                $gastos['user_id'] = $forma_pago->user_id;
                $gastos['status'] = $forma_pago->status;
                $gastos['payment_method'] = $compra->id_pago;
                $gastos['supplier_id'] = $compra->supplier_id;
                $gastos['bill_id'] = $receivings_id;
                $gastos['amount_applied'] = $sumTotal;
                $nuevo = new Request($gastos);
                $guardarPago = $this->saveExpense($nuevo);
                if ($guardarPago[0] < 0) {
                    /**
                    * Si hubo errores al guardar FOrma de pago
                    * Hacemos Rollback de la transaccion
                    * Definimos la bandera de error, guardamos el error.
                    */
                    $message = "Error pago:" . $guardarPago[1];
                    throw new \Exception($message, 6);
                    $url = '#';
                    $flag = 2;
                }
            }
            DB::commit();
            $message = 'Compra realizada de manera correcta';
            $flag = 1;
            $url = 'receivings/complete/' . $receivings_id;
            Session::flash('message', 'Compra realizada de manera correcta');
            Session::flash('alert-type', trans('success'));
        } catch (\Exception $e) {
            /**
            * Si hubo errores generales
            * Hacemos Rollback de la transaccion
            * Definimos la bandera de error, guardamos el error.
            */
            DB::rollBack();
            $message = "RB:" . $e->getMessage() .' | '. $e->getLine();
            $flag = 2;
            $url = '#';
        }
        $resp = array('message' => $message, 'flag' => $flag, 'url' => $url);
        return json_encode($resp);
        //        return redirect()->action('ReceivingController@complete', ['id' => $receivingItemsData->receiving_id]);
        //return Redirect::to('receivings');
    }

    public function complete($id)
    {
        $receivings = Receiving::find($id);
        $itemsreceiving = ReceivingItem::where('receiving_id', '=', $id)->get();
        $dataDocuments = DB::table('series')
        ->join('documents', 'series.id_document', '=', 'documents.id')
        ->where('series.id', '=', $receivings->id_serie)
        ->select('documents.name as doc', 'series.name')->get();
        // dd($itemsreceiving);
        $outlays = ReceivingOutlay::select('description', 'amount')
        ->where('document_id', $id)
        ->get();
        return view('receiving.complete')
        ->with('receivings', $receivings)
        ->with('receivingItems', $itemsreceiving)
        ->with('outlays', $outlays)
        ->with('dataDocuments', $dataDocuments);
        //->with('nombreDeTransaccion', $nombreDeTransaccion);

    }


    public function complete_invoice($id)
    {
        $receivings = Receiving::find($id);
        $itemsreceiving = ReceivingItem::where('receiving_id', '=', $id)->get();
        $dataDocuments = DB::table('v_series_documentos')
        ->where('id_serie', '=', $receivings->id_serie)
        ->select('nombre_documento', 'nombre_serie')->get();
        // dd($itemsreceiving);
        return view('partials.inventory_document2')
        ->with('docheader', $receivings)
        ->with('docdetail', $itemsreceiving)
        ->with('document_name', $dataDocuments[0]->nombre_documento . ' ' . $dataDocuments[0]->nombre_serie)
        ->with('module_name', 'Compras')
        ->with('document_number', $receivings->correlative)
        ->with('persona', 'Proveedor')
        ->with('data_persona', isset($receivings->supplier->company_name) ? $receivings->supplier->company_name : 'N/A')
        ->with('forma_pago', isset($receivings->pago->name) ? $receivings->pago->name : 'N/A')
        ->with('docuser', $receivings->user->name)
        ->with('cancel_url', '/receivings');
        //->with('nombreDeTransaccion', $nombreDeTransaccion);

    }

    /**
    * Display the specified resource.
    *
    * @param int $id
    * @return Response
    */
    public function show($id)
    {

    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param int $id
    * @return Response
    */
    public function edit($id)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param int $id
    * @return Response
    */
    public function update($id)
    {
        $items = Item::find($id);
        // process inventory
        $receivingTemps = new ReceivingTemp;
        $inventories->item_id = $id;
        $inventories->quantity = Input::get('quantity');
        $inventories->save();

        Session::flash('message', 'You have successfully add item');
        return Redirect::to('receivings');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    * @return Response
    */
    public function destroy($id)
    {
        //
    }

    public function prueba()
    {
        echo 'hola que haces';
        echo "</br>";
        echo 'esta es una prueba de select con campos iguales';
        $articulo = BodegaProducto::where('id_bodega', '=', 1)
        ->where('id_product', '=', 3)->value('id');

        if (!$articulo) {
            echo "</br>";

            echo "no existe";
        } else {
            echo "</br>";
            echo 'Si existe y es : ' . $articulo;
        }


    }

    public function cancel_bill()
    {

        $data_receivings = Receiving::join('series', 'receivings.id_serie', '=', 'series.id')
        ->join('documents', 'series.id_document', '=', 'documents.id')
        ->where('documents.sign', '=', '+')
        ->where('receivings.cancel_bill', '=', 0)
        ->whereNull('storage_destination')
        ->select([
            'receivings.id',
            'receivings.supplier_id',
            'receivings.user_id',
            'receivings.comments',
            'receivings.created_at',
            'receivings.total_cost',
            'receivings.id_serie',
            'receivings.correlative'
            ])->get();
            return view('cancel_bill.list_cancel_bill_receivings')->with('data_receivings', $data_receivings);

        }

        public function anular(Request $request)
        {


            $data_items = ReceivingItem::where('receiving_id', '=', $request->id_elemento)->get();
            $data_receiving_id = Receiving::find($request->id_elemento);
            if($data_receiving_id->cancel_bill==1) {
                Session::flash('message', 'Documento ya esta anulado');
                return Redirect::to('/receivings');
            }
            /*
            Validación de existencias iguales en bodegas con ventas
            */
            $arrayItems = array();
            $indice = 0;
            for ($i = 0; $i < count($data_items); $i++) {
                $data_search = BodegaProducto::where('id_bodega', '=', $data_receiving_id->storage_origins)
                ->where('id_product', '=', $data_items[$i]->item_id)
                ->value('id');
                $data_update = BodegaProducto::find($data_search);
                if ($data_items[$i]->quantity <= $data_update->quantity) {
                    $bandera_error = "No";

                    // array_push($arrayItems, $data_update->id_product);
                    // array_push($a,"blue","yellow");
                } else {
                    // echo $data_items[$i]."<br/>";
                    $bandera_error = "Si";
                    $productName = Item::find($data_update->id_product);
                    $arrayItems[$indice]['items_name'] = $productName->item_name;
                    $arrayItems[$indice]['current_quantity'] = $data_update->quantity;
                    $arrayItems[$indice]['discount_quantity'] = $data_items[$i]->quantity;
                    $indice++;
                }
            }
            if (count($arrayItems) == 0) {
                DB::beginTransaction();
                try {
                    for ($i = 0; $i < count($data_items); $i++) {
                        $data_search = BodegaProducto::where('id_bodega', '=', $data_receiving_id->storage_origins)
                            ->where('id_product', '=', $data_items[$i]->item_id)
                            ->value('id');
                        $data_update = BodegaProducto::find($data_search);
                        $data_update->quantity = $data_update->quantity - $data_items[$i]->quantity;
                        $data_update->save();

                        $inventorie             = new Inventory;
                        $inventorie->item_id    = $data_items[$i]->item_id;
                        $inventorie->user_id    = Auth::user()->id;
                        $inventorie->in_out_qty = ($data_items[$i]->quantity)*-1;
                        $inventorie->almacen_id = $data_receiving_id->storage_origins;
                        $inventorie->remarks = 'Anulación compra '.$data_receiving_id->serie->document->name.' '.$data_receiving_id->serie->name.'-'.$data_receiving_id->correlative;
                        $inventorie->save();
                    }
                    $data_receivings = Receiving::find($request->id_elemento);
                    $data_receivings->cancel_bill = 1;
                    $data_receivings->save();
                    $expense = Payment::where('bill_id','=',$data_receivings->id)->max('id');

                    // throw new \Exception(' gasto '.$expense, 6);
                    if ($data_receivings->id_pago==6){
                        /* => Actualizar saldo proveedor */
                        $proveedor = Supplier::find($data_receivings->supplier_id);
                        $proveedor->balance = $proveedor->balance -$data_receivings->total_cost;
                        $proveedor->save();
                        /** => Anular registro crédito */
                        $credito = CreditSupplier::where('receiving_id',$request->id_elemento)->first();
                        $credito->status_id=10;
                        $credito->update();
                        /* => ANULAR ABONOS A CRÉDITOS */
                        $pagos = CreditSupplierDetail::whereCredit_supplier_id($credito->id)->get();
                        foreach ($pagos as $pago){
                            $rsp = $this->cancelTransaction($pago->expense_id, 'payment', false);
                            if ($rsp[0]<0){
                                throw new \Exception($rsp[1], 6);
                            }
                        }
                    }
                    else{
                        #region ANULAR TRANSACCIÓN BANCARIA ASOCIAD
                        // Session::flash('error',$expense);
                        // exit();
                        $tx = $this->cancelTransaction($expense, 'payment', true);
                        // $tx[0]=0;
                        if ($tx[0]<0){
                            throw new \Exception($tx[1], 6);
                        }
                        #endregion
                    }
                    Session::flash('message', 'Anulación completada correctamente');
                    DB::commit();
                }
                catch(\Exception $ex){
                    DB::rollback();
                    Session::flash('message', 'Error al realizar anulación de documento'.$ex->getMessage());

                }
                return Redirect::to('receivings');
            } else {
                $dataSeries = Receiving::leftJoin('series', 'receivings.id_serie', '=', 'series.id')
                ->leftJoin('documents', 'series.id_document', '=', 'documents.id')
                ->where('receivings.id', $request->id_elemento)
                ->select('series.name', 'documents.name as document', 'receivings.correlative')->get();

                return view('cancel_bill.items', ['arrayItems' => $arrayItems, 'dataSeries' => $dataSeries]);
                Session::flash('message', 'No se puede hacer la anulación debido a que no hay existencias en los siguientes productos');
                Session::flash('alert-class', 'alert-error');

                return Redirect::to('/cancel_bill_receivings');
            }
        }


        public function existCorrelative($serie)
        {

            $correlative = Receiving::where('id_serie', '=', $serie)->max('correlative');
            if (isset($correlative)) {
                $response = $correlative;
            } else {
                $response = 0;
            }
            return $response + 1;
        }

        public function newSaved(Request $request)
        {
            $items = json_decode(json_encode($request->items));
            foreach ($items as $key => $value) {
                $newReceiving = new ReceivingTemp;
                $newReceiving->item_id = $value->item_id;
                $newReceiving->cost_price = $value->cost_real;
                $newReceiving->quantity = $value->quantity;
                $newReceiving->total_cost = ($value->quantity * $value->cost_real);
                $newReceiving->id_bodega = 0;
                $newReceiving->id_product = 0;
                $newReceiving->last_cost = $value->cost_real;
                $newReceiving->save();
            }
            echo true;
        }

        public function verifyCorrelative($serie, $number)
        {
            $flag = Receiving::where('id_serie', $serie)
            ->where('correlative', $number)->count();
            return $flag;
        }
    }
