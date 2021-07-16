<?php

namespace App\Http\Controllers;

use App\GeneralParameter;
use App\InventoryClosing;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Document;
use App\Serie;
use App\Supplier;
use App\Receiving;
use App\Almacen;
use App\ReceivingItem;
use App\Inventory;
use App\BodegaProducto;
use App\Customer;
use App\Sale;
use App\SaleItem;
use App\Item;
use \Auth, \Redirect, \Validator, \Input, \Session;
use \Response;
use Illuminate\Support\Str;
use App\InventoryAdjustment;
use App\InventoryAdjustmentDetail;
use App\Traits\ItemsTrait;
use App\Traits\TransactionsTrait;
use App\Parameter;

class InventoryAdjustmentController extends Controller
{
    use ItemsTrait;
    use TransactionsTrait;
    public function __construct()
	  {
	  	$this->middleware('auth');
        $this->middleware('parameter');
    }
    public function getExistence(Request $request){
      $bodega=Str::upper($request->bodega);
      $item=$request->id;
      $items = Item::leftJoin('bodega_productos','bodega_productos.id_product','=','items.id')
        ->where('bodega_productos.id_bodega',$bodega)
        ->where('items.id',$item)
        ->select(DB::raw('COALESCE(bodega_productos.quantity,0) as quantity'))
        ->first();
      return $items;
    }
    public function selectCorrelative(Request $request){
      $correlativo=InventoryAdjustment::where('serie_id',$request->serie)->orderBy('correlative','desc')->select(DB::raw('COALESCE(correlative,0)+1 as correlative'))->first();
      if($correlativo)
        return $correlativo;
      else
        return Response::json(['correlative'=>'1']);

    }
    public function searchItems(Request $request){
      $item=Str::upper($request->item);
      $bodega=Str::upper($request->bodega);
      $sign=Str::upper($request->estado);
      if (empty($bodega)) {
        $items = Item::where(function($query) use($item){
          $query->where('items.upc_ean_isbn','LIKE',"%{$item}%")
          ->orWhere('items.item_name','LIKE',"%{$item}%");
        })
            ->wildcard()
        ->select('items.upc_ean_isbn','items.item_name','items.id',DB::raw('0 as quantity'),'items.cost_price')
        ->get();
      }else {
        $items = Item::leftJoin('bodega_productos','bodega_productos.id_product','=','items.id')
        ->where('bodega_productos.id_bodega',$bodega)
          ->where(function($query) use($item){
          $query->where('items.upc_ean_isbn','LIKE',"%{$item}%")
          ->orWhere('items.item_name','LIKE',"%{$item}%");
        })
            ->wildcard()
        ->select('items.upc_ean_isbn','items.item_name','items.id',DB::raw('COALESCE(bodega_productos.quantity,0) as quantity'),'items.cost_price')
        ->get();
        if(count($items)==0&&$sign=="POSITIVO"){
          $items = Item::where(function($query) use($item){
            $query->where('items.upc_ean_isbn','LIKE',"%{$item}%")
            ->orWhere('items.item_name','LIKE',"%{$item}%");
          })
          ->select('items.upc_ean_isbn','items.item_name','items.id',DB::raw('0 as quantity'),'items.cost_price')
          ->get();
        }
      }
      return $items;
    }
    public function selectBodega(){
      return Almacen::join('almacen_users', 'almacens.id', '=', 'almacen_users.id_bodega')
      ->where('almacen_users.id_usuario', '=',  Auth::id())
      ->where('id_state', '=', '1')
      ->orderBy('almacens.created_at','ASC')
      ->select('almacens.name', 'almacens.id')->get();

    }
    public function selectSerie(Request $request ){
      // return $request->sign;
      if($request->sign==1)
        return Serie::join('documents','documents.id','=','series.id_document')->where('documents.name','Ajuste ingreso de inventario')->where('series.id_state',1)->select(DB::raw('CONCAT(documents.name," ",series.name) as name'),'series.id')->get();
      else
      return Serie::join('documents','documents.id','=','series.id_document')->where('documents.name','Ajuste salida de inventario')->where('series.id_state',1)->select(DB::raw('CONCAT(documents.name," ",series.name) as name'),'series.id')->get();
    }
    public function create()
    {
      return view('inventory_adjustment.add');
    }
    public function input()
    {
      return view('inventory_adjustment.add');
    }
    public function output()
    {
      return view('inventory_adjustment.quit');
    }
    public function store(Request $request)
    {
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

        $nuevaFecha1 = explode('/', $request->inventory_adjustment_date);
        $diaFecha1 = $nuevaFecha1[0];
        $mesFecha1 = $nuevaFecha1[1];
        $anioFecha1 = $nuevaFecha1[2];
        $fecha = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1;

        $sDate = date('m/Y', strtotime($fecha));
        if ($refDate == $sDate && !isset($paramD)){
            throw  new \Exception("No se puede realizar transacciones de inventario en un mes cerrado.", 6);
        }
        #endregion

        // $error = array();
        // /**
        //  * VERIFICAR SI ES NECESARIO EL CIERRE DE INVENTARIO
        //  */
        // if (\Illuminate\Support\Facades\Session::get('inventory_close', false)){
        //   array_push($error,'Se requiere el cierre de inventario del mes en curso.');
        // }
        // // REFACTORIZACION DE FECHAS
        // $nuevaFecha = explode('/', $request->inventory_adjustment_date);
        // $dia=$nuevaFecha[0];
        // $mes=$nuevaFecha[1];
        // $anio=$nuevaFecha[2];
        // $fecha=$anio.'-'.$mes.'-'.$dia;

        // // /**
        // //  * VERIFICAR SI SE QUIERE REALIZAR UA VENTA EN UN MES CON EL INVENTARIO CERRADO
        // //  */
        // // #region VERIFICAR ULTIMO CIERRE
        // $dlast = InventoryClosing::orderby('id', 'desc')->first();
        // if (isset($dlast->date)){
        //   $refDate = date('m/Y', strtotime($dlast->date));
        // }
        // else{
        //   $paramD = GeneralParameter::find(13)->text_value;
        //   $refDate = date('m/Y', strtotime($paramD));
        // }

        // $sDate = date('m/Y', strtotime($fecha));
        // if ($refDate == $sDate){
        //   array_push($error,'No se puede realizar transacciones de inventario en un mes cerrado.');
        // }
        // // FIN DE VERIFICACION
        // VERIFICAR SI EXISTE EL CORRELATIVO
        if(count(InventoryAdjustment::where('serie_id',$request->serie_id)->where('correlative',$request->correlative)->get())>0){
          array_push($error,'El correlativo: '.$request->correlative. ' ya esta utilizado en un documento de la serie seleccionada.');
        }
        DB::beginTransaction();
        try{
          $iA=new InventoryAdjustment;

          if(!empty($error)){
            $returnData=array(
                'status' => 'error',
                'message' => $error
            );
            DB::rollback();
              return Response::json($returnData, 500);
          }
          $iA->created_by=Auth::user()->id;
          $iA->updated_by=Auth::user()->id;
          $iA->correlative=$request->correlative;
          $iA->serie_id=$request->serie_id;
          $iA->comments=$request->comments;
          $iA->sign=$request->sign;
          $iA->almacen_id=$request->almacen_id;
          $iA->inventory_adjustament_date=$fecha;
          $iA->total=$request->total;
          $iA->cantidad=$request->cantidad;
          $iA->save();

          foreach($request->items as $item){
            $iADetalle=new InventoryAdjustmentDetail;
            $iADetalle->inventory_adjustment_id=$iA->id;
            $iADetalle->item_id=$item['id'];
            $iADetalle->new_quantity=$item['newExistence'];
            $iADetalle->quantity=$item['quantity'];
            $iADetalle->previous_quantity=$item['existence'];
            $iADetalle->save();
            //Obtenemos la serie
            $nombreSerie = Serie::find($iA->serie_id);
            //Obtenemos el nombre del documento
            $nombreDocument = Document::where('id', '=', $nombreSerie->id_document)->value('name');
            // Armamos el nombre de documento para Kardex
            $nombreDeTransaccion  = $nombreDocument . ' ' . $nombreSerie->name . '-' . $iA->correlative;

            if($iA->sign=="+")
              $this->updateItemQuantity($iA->almacen_id,$iADetalle->item_id,$iADetalle->quantity, $nombreDeTransaccion);
            else
              $this->updateItemQuantity($iA->almacen_id,$iADetalle->item_id,-($iADetalle->quantity), $nombreDeTransaccion);
          }
          DB::commit();
          $returnData = array(
            'status' => 'success',
            'message' => $iA->id
        );
        return Response::json($returnData, 200);

        }catch(\Exception $e){
          DB::rollback();
          $returnData = array(
            'status' => 'error',
            'message' => array($e->getMessage())
          );
          return Response::json($returnData, 500);
        }
    }
    public function indexInput(Request $request){
      $fecha1=Input::get('date_1');
      $fecha2=Input::get('date_2');
      $document = Input::get('documentos');
      if($document==null)
        $document=0;
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
      if($document>0)
      {
        $reporte=InventoryAdjustment::
        leftJoin('series','inventory_adjustments.serie_id','=','series.id')
        ->leftJoin('documents','series.id_document' ,'=', 'documents.id')
        ->leftJoin('users','inventory_adjustments.created_by' ,'=', 'users.id')
        ->where('inventory_adjustments.sign','+')
        ->where('inventory_adjustments.serie_id',$document)
        ->whereBetween('inventory_adjustments.inventory_adjustament_date',[$fecha1,$fecha2])
        ->select('inventory_adjustments.id','users.name',DB::raw('concat(documents.name," ",series.name," - ",inventory_adjustments.correlative) as document_and_correlative')
        ,'inventory_adjustments.inventory_adjustament_date as date')
        ->get();
      }else{
        $reporte=InventoryAdjustment::
        leftJoin('series','inventory_adjustments.serie_id','=','series.id')
        ->leftJoin('documents','series.id_document' ,'=', 'documents.id')
        ->leftJoin('users','inventory_adjustments.created_by' ,'=', 'users.id')
        ->where('inventory_adjustments.sign','+')
        ->whereBetween('inventory_adjustments.inventory_adjustament_date',[$fecha1,$fecha2])
        ->select('inventory_adjustments.id','users.name',DB::raw('concat(documents.name," ",series.name," - ",inventory_adjustments.correlative) as document_and_correlative')
        ,'inventory_adjustments.inventory_adjustament_date as date')
        ->get();
      }
      $cantidad=InventoryAdjustment::
      join('inventory_adjustment_details',
      'inventory_adjustment_details.inventory_adjustment_id',
      '=',
      'inventory_adjustments.id')
      ->select('inventory_adjustments.id',DB::raw('COUNT(inventory_adjustment_details.id) as cantidad'))
      ->groupBy('inventory_adjustments.id')
      ->get();
      $listDocuments=Document::leftJoin('series','series.id_document','=','documents.id')->where('documents.name','Ajuste ingreso de inventario')->select( DB::raw('CONCAT(documents.name," ",series.name) as name'), 'series.id')->get();
      return view('inventory_adjustment.index',
      [
        'fecha1'=>$fecha1,
        'fecha2'=>$fecha2,
        'lista'=>$reporte,
        'listDocuments'=>$listDocuments,
        'document'=>$document,
        'cantidad'=>$cantidad
      ]);
    }
    public function indexOutput(Request $request){
      $document=0;
      $fecha1=Input::get('date_1');
      $fecha2=Input::get('date_2');
      $document = Input::get('documentos');
      if($document==null)
        $document=0;
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
      if($document>0)
      {
        $reporte=InventoryAdjustment::
        leftJoin('series','inventory_adjustments.serie_id','=','series.id')
        ->leftJoin('documents','series.id_document' ,'=', 'documents.id')
        ->leftJoin('users','inventory_adjustments.created_by' ,'=', 'users.id')
        ->where('inventory_adjustments.sign','-')
        ->where('inventory_adjustments.serie_id',$document)
        ->whereBetween('inventory_adjustments.inventory_adjustament_date',[$fecha1,$fecha2])
        ->select('inventory_adjustments.id','users.name',DB::raw('concat(documents.name," ",series.name," - ",inventory_adjustments.correlative) as document_and_correlative')
        ,'inventory_adjustments.inventory_adjustament_date as date')
        ->get();
      }else{
        $reporte=InventoryAdjustment::
        leftJoin('series','inventory_adjustments.serie_id','=','series.id')
        ->leftJoin('documents','series.id_document' ,'=', 'documents.id')
        ->leftJoin('users','inventory_adjustments.created_by' ,'=', 'users.id')
        ->where('inventory_adjustments.sign','-')
        ->whereBetween('inventory_adjustments.inventory_adjustament_date',[$fecha1,$fecha2])
        ->select('inventory_adjustments.id','users.name',DB::raw('concat(documents.name," ",series.name," - ",inventory_adjustments.correlative) as document_and_correlative')
        ,'inventory_adjustments.inventory_adjustament_date as date')
        ->get();
      }
      $cantidad=InventoryAdjustment::
      join('inventory_adjustment_details',
      'inventory_adjustment_details.inventory_adjustment_id',
      '=',
      'inventory_adjustments.id')
      ->select('inventory_adjustments.id',DB::raw('COUNT(inventory_adjustment_details.id) as cantidad'))
      ->groupBy('inventory_adjustments.id')
      ->get();
      $listDocuments=Document::leftJoin('series','series.id_document','=','documents.id')->where('documents.name','Ajuste salida de inventario')->select( DB::raw('CONCAT(documents.name," ",series.name) as name'), 'series.id')->get();
      return view('inventory_adjustment.index_output',
      [
        'fecha1'=>$fecha1,
        'fecha2'=>$fecha2,
        'lista'=>$reporte,
        'listDocuments'=>$listDocuments,
        'document'=>$document,
        'cantidad'=>$cantidad
      ]);
    }
    public function detailInput($id){
      $reporte=InventoryAdjustment::
        leftJoin('series','inventory_adjustments.serie_id','=','series.id')
        ->leftJoin('documents','series.id_document' ,'=', 'documents.id')
        ->leftJoin('users','inventory_adjustments.created_by' ,'=', 'users.id')
        ->leftJoin('almacens','almacens.id','=','inventory_adjustments.almacen_id')
        ->select('almacens.name as almacen','inventory_adjustments.id','users.name',DB::raw('concat(documents.name," ",series.name," - ",inventory_adjustments.correlative) as document_and_correlative')
        ,'inventory_adjustments.inventory_adjustament_date as date','inventory_adjustments.comments','inventory_adjustments.total','inventory_adjustments.cantidad')
        ->find($id);

        $productos=InventoryAdjustmentDetail::
        join('items',
        'items.id',
        '=',
        'inventory_adjustment_details.item_id')
        ->where('inventory_adjustment_details.inventory_adjustment_id',$id)
        ->select('items.cost_price','items.item_name','items.upc_ean_isbn','inventory_adjustment_details.id','inventory_adjustment_details.new_quantity','inventory_adjustment_details.quantity','inventory_adjustment_details.previous_quantity')
        ->get();
      return view('inventory_adjustment.print')
      ->with('reporte',$reporte)
      ->with('productos',$productos)
      ->with('parameters',Parameter::first());
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
    public function sale(){
      $listDocuments=Serie::leftJoin('documents','series.id_document','=','documents.id')
      ->where('documents.ajuste_inventario',1)->where('documents.sign','-')
      ->select('documents.id as id_document','series.id as id_serie','series.name as serie','documents.name as document')->get();

      $customers=Customer::all();
      $logged_user = Auth::user();
      $idUserActive=  $logged_user->id;

      $almacen=Almacen::join('almacen_users','almacens.id','=','almacen_users.id_bodega')->where('almacen_users.id_usuario','=',$idUserActive)
      ->where('id_state','=','1')
      ->select('almacens.name','almacens.id')->get();

      return view('inventory_adjustment.adjustment_sale',['listDocuments'=>$listDocuments,'customers'=>$customers,'almacen'=>$almacen]);
    }
    public function saleSave(Request $request){
        //save
        #region VERIFICACIONES DE INVENTARIO
        /**
         * VERIFICAR SI ES NECESARIO EL CIERRE DE INVENTARIO
         */
        if (\Illuminate\Support\Facades\Session::get('inventory_close', false)){
            Session::flash('message', "Se requiere el cierre de inventario del mes en curso.");
            return Redirect::to('/transfer_to_storage/sale');
        }

        /**
         * VERIFICAR SI SE QUIERE REALIZAR UA VENTA EN UN MES CON EL INVENTARIO CERRADO
         */
        #region VERIFICAR ULTIMOM CIERRE
        $dlast = InventoryClosing::orderby('id', 'desc')->first();
        if (isset($dlast->date)){
            $refDate = date('m/Y', strtotime($dlast->date));
        }
        else{
            $paramD = GeneralParameter::find(13)->text_value;
            $refDate = date('m/Y', strtotime($paramD));
        }

        $sDate = date('m/Y');
        if ($refDate == $sDate){
            Session::flash('message', "No se puede realizar transacciones de inventario en un mes cerrado.");
            return Redirect::to('/transfer_to_storage/sale');
        }
        #endregion
        #endregion
        $sales = new Sale();
        if($request->customer_id!=''){
          $sales->customer_id=$request->customer_id;
        }
        $sales->user_id=Auth::user()->id;
        // $sales->id_pago=1;
        $sales->comments=$request->comentario;
        // $sales->total_cost=$total;
        $sales->id_serie=$request->serie_id;
        $sales->correlative=$request->correlativo_num;
        $sales->user_relation=Auth::user()->id;
        $sales->save();
        $id_sales=$sales->id;
        $indice=0;
        foreach ($request->all() as $key => $value) {
            if(strpos($key,'item_id')!==false){
                $items_array[$indice]['item_id']=$value;
            }elseif(strpos($key,'qt_')!==false){
                $items_array[$indice]['qt_']=$value;
                $id_items=$items_array[$indice]['item_id'];
                $cantidad=$items_array[$indice]['qt_'];
                $saleItemsData                = new SaleItem;
                $saleItemsData->sale_id       = $id_sales;
                $saleItemsData->item_id       = $id_items;
                $saleItemsData->quantity      = $cantidad;
                $saleItemsData->id_bodega     = $request->bodega_id;
                $saleItemsData->save();

                $nombreSerie = Serie::find($request->serie_id);
                $nombreDocument = Document::where('id', '=', $nombreSerie->id_document)->value('name');
                $nombreDeTransaccion  = $nombreDocument . ' ' . $nombreSerie->name . '-' . $request->correlativo_num;
                // $this->updateItemQuantity($venta->id_bodegas, $id_items,-($cantidad), $nombreDeTransaccion);

                $valorEncontrado = BodegaProducto::where('id_product', '=', $id_items)
                ->where('id_bodega', '=', $request->bodega_id)
                ->value('id');
                $actualizar = BodegaProducto::find($valorEncontrado);
                $inventories             = new Inventory;
                $inventories->almacen_id = $request->bodega_id;
                $inventories->item_id    = $id_items;
                $inventories->user_id    = Auth::user()->id;
                $inventories->in_out_qty = -($cantidad);

                $inventories->remarks = $nombreDeTransaccion;
                $inventories->save();
                $actualizar->quantity = $actualizar->quantity - $cantidad;
                $actualizar->save();

                $indice++;
            }
        }
        return redirect()->action('InventoryAdjustmentController@detailsSale',['id'=>$id_sales]);
    }
    public function detailsSale($id){
      // echo 'id '.$id.'<br>';
      $salesReport=$this->headerSale($id);
      $dataItems=$this->detailsItemsSale($id);
      // return $dataItems;
      return view('inventory_adjustment.completeSale',['salesReport'=>$salesReport,'dataItems'=>$dataItems]);
    }

    public function detailsSale_document($id){
    // $receivings=Receiving::find($id);
    // $salesReport=$this->headerSale($id);
      $salesReport_q=Sale::leftJoin('series','sales.id_serie','=','series.id')
      ->leftJoin('documents','series.id_document','=','documents.id')
      ->leftJoin('users','users.id','=','sales.user_id')
      ->leftJoin('customers','sales.customer_id','=','customers.id')
      ->where('sales.id',$id)
      ->select([DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative'),'sales.id as id_sales','customers.name as customer_name','sales.created_at','sales.comments','sales.show_header','users.name as user_name','sales.total_cost','sales.correlative'])
      ->get();

      // $itemsreceiving=ReceivingItem::where('receiving_id','=',$id)->get();
      $dataItems=$this->detailsItemsSale($id);
      // $dataDocuments=DB::table('v_series_documentos')
      //   ->where('id_serie','=',$receivings->id_serie)
      //   ->select('nombre_documento','nombre_serie')->get();
      // dd($itemsreceiving);
       // return $salesReport_q[0];
      return view('partials.inventory_document2')
        ->with('docheader', $salesReport_q)
        ->with('docdetail', $dataItems)
        ->with('document_name',$salesReport_q[0]->document_and_correlative)
        ->with('module_name','Salidas de Inventario(Ajustes)')
        ->with('document_number',$salesReport_q[0]->correlative)
        ->with('persona','Cliente')
        ->with('data_persona',isset($salesReport_q->customer->name)?$salesReport->customer->name:'N/A')
        ->with('forma_pago',isset($salesReport_q->pago->name)?$salesReport->pago->name:'N/A' )
        ->with('docuser',$salesReport_q[0]->user_name)
        ->with('cancel_url','/listAdjustmentSale');
    }


    public function detailsAdd($id){
      $receivingsReport=$this->headerAdd($id);
      $dataItems=$this->detailsItemsAdd($id);
      return view('inventory_adjustment.completeAdd',['receivingsReport'=>$receivingsReport,'dataItems'=>$dataItems]);
    }
    public function listAdjustmentAdd(Request $request){
      $id_document=$request->id_document;
      $id_serie=$request->id_serie;
      $fecha1=Input::get('date_1');
      $fecha2=Input::get('date_2');
      $document = Input::get('document');
      $document = $document ==null ? 'Todo':$document;

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
        $receivingsReport = InventoryAdjustment::whereSign('+')
            ->whereBetween('inventory_adjustament_date', [$fecha1, $fecha2])
            ->get();
      // return $receivingsReport;
      //listado de documentos
      $listDocuments=Document::where('documents.ajuste_inventario',1)->where('documents.sign','+')->get();
//      dd($receivingsReport);
      return view('inventory_adjustment.listAdjustmentAdd',['fecha1'=>$fecha1,'fecha2'=>$fecha2,'receivingsReport'=>$receivingsReport,'listDocuments'=>$listDocuments]);
    }
    public function listAdjustmentSale(){
      // return 'que ondas';
      $fecha1=Input::get('date_1');
      $fecha2=Input::get('date_2');
      $document = Input::get('document');
      $document = $document ==null ? 'Todo':$document;

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
      $salesReport=InventoryAdjustment::whereSign('-')
          ->whereBetween('inventory_adjustament_date', [$fecha1, $fecha2])
          ->get();

      // return $salesReport;
      $listDocuments=Document::where('documents.ajuste_inventario',1)->where('documents.sign','-')->get();

      return view('inventory_adjustment.listAdjustmentSale',['fecha1'=>$fecha1,'fecha2'=>$fecha2,'salesReport'=>$salesReport,'listDocuments'=>$listDocuments]);
    }

    public function printPDFAdd($id){
      $receivingsReport=$this->headerAdd($id);
      $dataItems=$this->detailsItemsAdd($id);
      $view =  \View::make('inventory_adjustment.pdfAdd',['dataItems'=>$dataItems,'receivingsReport'=>$receivingsReport])->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream('Ingreso a inventario-'.$receivingsReport[0]->document.' '.$receivingsReport[0]->serie.'-'.$receivingsReport[0]->correlative.'.pdf');
    }
    function headerAdd($id){
      $receivingsReport_q=DB::table('receivings');
      $receivingsReport_q->leftJoin('series','receivings.id_serie','=','series.id');
      $receivingsReport_q->leftJoin('documents','series.id_document' ,'=', 'documents.id');
      $receivingsReport_q->leftJoin('users','receivings.user_id' ,'=', 'users.id');
      $receivingsReport_q->leftJoin('suppliers','receivings.supplier_id' ,'=', 'suppliers.id');
      $receivingsReport_q->leftJoin('almacens','receivings.storage_origins' ,'=', 'almacens.id');
      $receivingsReport_q->where('receivings.id',$id);
      $receivingsReport_q->select(['receivings.id','receivings.supplier_id','receivings.user_id','receivings.comments','receivings.created_at','receivings.total_cost','receivings.id_serie','receivings.id_pago','receivings.correlative','users.name as nameUser','suppliers.company_name','series.name as serie','documents.name as document','receivings.creation_date','almacens.name as storage_name']);
      $receivingsReport=$receivingsReport_q->get();
      return $receivingsReport;
    }
    function detailsItemsAdd($id){
      $dataItems=Item::leftJoin('receiving_items','receiving_items.item_id','=','items.id')->where('receiving_items.receiving_id',$id)->select('items.id','items.item_name','receiving_items.quantity')->get();
      return $dataItems;
    }

    public function printPDFSale($id){
     $salesReport=$this->headerSale($id);
     $dataItems=$this->detailsItemsSale($id);
     $view =  \View::make('inventory_adjustment.pdfSale',['dataItems'=>$dataItems,'salesReport'=>$salesReport])->render();
     $pdf = \App::make('dompdf.wrapper');
     $pdf->loadHTML($view);
     return $pdf->stream('Salida de inventario-'.$salesReport[0]->document_and_correlative.'.pdf');
   }
   function detailsItemsSale($id){
     $dataItems=Item::leftJoin('sale_items','sale_items.item_id','=','items.id')->leftJoin('almacens','sale_items.id_bodega','=','almacens.id')->where('sale_items.sale_id',$id)->select('items.id','items.item_name','sale_items.quantity','almacens.name as storage_name')->get();
     return $dataItems;
   }
   function headerSale($id){
    $salesReport_q=DB::table('sales');
    $salesReport_q->leftJoin('series','sales.id_serie','=','series.id');
    $salesReport_q->leftJoin('documents','series.id_document','=','documents.id');
    $salesReport_q->leftJoin('users','users.id','=','sales.user_id');
    $salesReport_q->leftJoin('customers','sales.customer_id','=','customers.id');
    $salesReport_q->where('sales.id',$id);
    $salesReport_q->select(['sales.id as id_sales','customers.name as customer_name',DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative'),'sales.created_at','sales.comments','sales.show_header','users.name as user_name','sales.total_cost',
      'sales.correlative']);
    $salesReport=$salesReport_q->get();
    return $salesReport;
   }
   public function getSeriesAdd($id){
    $listSeries=Serie::leftJoin('documents','series.id_document','=','documents.id')->where('documents.id',$id)->select('series.id as id_serie','documents.id as id_document','series.name as serie','documents.name as document')->get();
    return Response::json($listSeries);
   }
   public function getReportAdd(Request $request){
    $id_serie=$request->id_serie==null?0:$request->id_serie;

    $date_1=$request->date_1;
    $date_2=$request->date_2;
     $nuevaFecha1 = explode('/', $date_1);
     $fecha1=$nuevaFecha1[2].'-'.$nuevaFecha1[1].'-'.$nuevaFecha1[0].' 00:00:00';
     $nuevaFecha2=explode('/', $date_2);
     $fecha2=$nuevaFecha2[2].'-'.$nuevaFecha2[1].'-'.$nuevaFecha2[0].' 23:59:59';
     $receivingsReport_q=DB::table('receivings');
      $receivingsReport_q->leftJoin('series','receivings.id_serie','=','series.id');
      $receivingsReport_q->leftJoin('documents','series.id_document' ,'=', 'documents.id');
      $receivingsReport_q->leftJoin('users','receivings.user_id' ,'=', 'users.id');
      $receivingsReport_q->leftJoin('suppliers','receivings.supplier_id' ,'=', 'suppliers.id');
      if($id_serie!=0)
        $receivingsReport_q->where('series.id',$id_serie);
      $receivingsReport_q->whereBetween('receivings.created_at',[$fecha1,$fecha2]);
      $receivingsReport_q->select(['receivings.id','receivings.supplier_id','receivings.user_id','receivings.comments','receivings.created_at','receivings.total_cost','receivings.id_serie','receivings.id_pago','receivings.correlative','users.name as nameUser','suppliers.company_name','series.name as serie','documents.name as document','receivings.created_at as creation_date']);
      $receivingsReport=$receivingsReport_q->get();
      // return var_dump($receivingsReport);
      return Response::json($receivingsReport);
   }

   //sale
   function getReportSale(Request $request){
     // $id_serie=$request->id_serie;
     $id_serie=$request->id_serie==null?0:$request->id_serie;
      $date_1=$request->date_1;
     $date_2=$request->date_2;
     $nuevaFecha1 = explode('/', $date_1);
     $fecha1=$nuevaFecha1[2].'-'.$nuevaFecha1[1].'-'.$nuevaFecha1[0].' 00:00:00';
     $nuevaFecha2=explode('/', $date_2);
     $fecha2=$nuevaFecha2[2].'-'.$nuevaFecha2[1].'-'.$nuevaFecha2[0].' 23:59:59';
     $salesReport_q=DB::table('sales');
      $salesReport_q->leftJoin('series','sales.id_serie','=','series.id');
      $salesReport_q->leftJoin('documents','series.id_document','=','documents.id');
      $salesReport_q->leftJoin('users','users.id','=','sales.user_id');
      $salesReport_q->leftJoin('customers','sales.customer_id','=','customers.id');
      if($id_serie!=0)
        $salesReport_q->where('series.id','=',$id_serie);
      $salesReport_q->where('documents.ajuste_inventario','=','1');
      $salesReport_q->where('sales.cancel_bill','=',0);
      $salesReport_q->whereBetween('sales.created_at',[$fecha1,$fecha2]);
      $salesReport_q->select(['sales.id as id_sales','customers.name as customer_name',DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative'),'sales.created_at','sales.comments','sales.show_header','users.name as user_name','sales.total_cost',
        'sales.created_at as creation_date']);
      $salesReport_q->orderBy('sales.created_at','ASC');
      $salesReport_q->orderBy('series.name','ASC');
      $salesReport_q->orderBy('correlative','ASC');
      $salesReport=$salesReport_q->get();
      return Response::json($salesReport);

   }
   public function addHeaderAndDetails($id){
    $dataHead=$this->headerAdd($id);
    $dataDetails=$this->detailsItemsAdd($id);
    return array($dataHead, $dataDetails);
   }
  public function saleHeaderAndDetails($id){
    $dataHead=$this->headerSale($id);
    $dataDetails=$this->detailsItemsSale($id);
    return array($dataHead, $dataDetails);
   }

}
