<?php namespace App\Http\Controllers;

use DB;
use \Auth;
use \Input;
use \Session;
use App\Item;
use App\Pago;
use App\Sale;
use App\User;
use \Redirect;
use App\Price;
use App\Route;
use App\Serie;
use App\Credit;
use App\Almacen;
use App\Revenue;
use App\Customer;
use App\Document;
use App\SaleItem;
use App\SaleTemp;
use App\UserRole;
use App\Inventory;
// use Exception;
use App\Parameter;
use App\Quotation;
use App\Receiving;
use App\RouteUser;
use Carbon\Carbon;
use App\CreditNote;
use App\ItemKitItem;
use App\StateCellar;
use App\ReceivingItem;
use App\BodegaProducto;
use App\TransactionLog;
use App\GeneralParameter;
use App\InventoryClosing;
use App\Traits\DatesTrait;
use App\Traits\ItemsTrait;
use Illuminate\Http\Request;
use App\Classes\NumeroALetras;
use App\Traits\TransactionsTrait;
use App\Http\Requests\SaleRequest;

use App\Http\Controllers\Controller;
use App\Classes\NumberToLetterConverter;
use App\Traits\AlmacenTrait;
use App\ItemPrice;
use INFILE;

class SaleController extends Controller
{
  use ItemsTrait;
  use TransactionsTrait;
  use DatesTrait;
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
  public function index(Request $request)
  {

    $fecha1=$this->fixFecha(Input::get('date1'));
    $fecha2=$this->fixFechaFin(Input::get('date2'));

    $document = Input::get('document');
    $document = $document ==null ? 'Todo':$document;
    $status = Input::get('status') ==null ? '0':Input::get('status');
    $tipo = ($request->tipo_===""||$request->tipo_==null) ? 'lista' : $request->tipo_;

    $administrador =Session::get('administrador');
		$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
		/** Si la ruta es requerida y no es administrador */
		if( (isset($ruta_requerida)) && ($administrador ==false)) {
			$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
			if (count($rutas) == 0) {
			  $rutas = [0, 0];
			}
		}


    $dataDocuments=DB::table('series')
      ->leftJoin('documents','series.id_document','=','documents.id')
      ->where('documents.sign','=','-')
      ->where('documents.ajuste_inventario','=','0')
      ->where('documents.id_state','=','1')
      ->select('series.id as id_serie','documents.id  as id_document','documents.sign','series.name as serie','documents.name as document')
      ->get();


      $dataStatus = StateCellar::inventory()->get();

    if($tipo=='lista')
    {



      $salesReport_q =Sale::with('revenues','pago')->leftJoin('series','sales.id_serie','=','series.id');
      $salesReport_q->leftJoin('documents','series.id_document','=','documents.id');
      $salesReport_q->leftJoin('users','users.id','=','sales.user_relation');
      $salesReport_q->leftJoin('customers','sales.customer_id','=','customers.id');
      if( (isset($ruta_requerida)) && ($administrador ==false)) {
        $salesReport_q->join('route_costumers','customers.id','=','route_costumers.customer_id')
			    ->whereIn('route_costumers.route_id',$rutas);
      }
      $salesReport_q->leftJoin('pagos','sales.id_pago','=','pagos.id');
      $salesReport_q->where('documents.sign','=','-');
      $salesReport_q->where('documents.ajuste_inventario','=','0');
      if($document!='Todo'){
        $salesReport_q->where('sales.id_serie',$document);
      }
      if($status!='Todo'){
        $salesReport_q->where('sales.cancel_bill',$status);
      }
      $salesReport_q->whereBetween('sales.sale_date',[$fecha1,$fecha2]);
      $salesReport_q->select(['sales.id as id','customers.id as customer_id'
      ,'customers.name as customer_name'
      ,DB::raw('concat(documents.name," ",series.name,"-",sales.correlative) as document_and_correlative')
      ,'sales.sale_date','sales.comments', 'sales.pagare_id','sales.payment_status'
      ,'sales.show_header','users.name as user_name','sales.id_pago','sales.cancel_bill'
      ,'pagos.name as pago','pagos.type as pago_type','sales.total_cost','sales.total_paid',DB::raw('"FACT" as status')]);
      $salesReport_q->orderBy('sales.created_at','ASC');
      $salesReport_q->orderBy('series.name','ASC');
      $salesReport_q->almacen();
      $salesReport_q->orderBy('correlative','ASC');

      $credits_note_q = CreditNote::join('series', 'series.id', '=', 'credit_notes.serie_id')
          ->join('documents', 'documents.id', '=', 'series.id_document')
          ->join('customers', 'customers.id', '=', 'credit_notes.customer_id')
          ->join('state_cellars', 'state_cellars.id', '=', 'credit_notes.status_id')
          ->join('users', 'users.id', '=', 'credit_notes.created_by')
          ->whereBetween('credit_notes.date',[$fecha1,$fecha2])
          ->orderBy('credit_notes.date','ASC')
          ->almacen()
          ->select(['credit_notes.id as id','customers.id as customer_id'
              ,'customers.name as customer_name'
              ,DB::raw('concat(documents.name," ",series.name,"-",credit_notes.correlative) as document_and_correlative')
              ,'credit_notes.date as sale_date','credit_notes.comment', DB::raw('null as pagare_id'), DB::raw('null as payment_status')
              ,DB::raw('null as show_header'),'users.name as user_name',DB::raw('null as id_pago'),DB::raw('null as cancel_bill')
              ,'state_cellars.name as pago',DB::raw('null as pago_type'),DB::raw('(credit_notes.amount * -1) as amount'),DB::raw('0 as total_paid'),DB::raw('"NCRE" as status')]);

        if($status=='Todo'){
            $credits_note_q->whereIn('credit_notes.status_id', [11, 12, 13]);
        }
        elseif($status == 0){
            $credits_note_q->whereIn('credit_notes.status_id', [11, 12]);
        }
        else{
            $credits_note_q->whereIn('credit_notes.status_id', [13]);
        }



      $salesReport=$salesReport_q->unionAll($credits_note_q)
          ->orderby('sale_date','asc')
          ->get();
//       dd($salesReport);
    }
    else
    // if(isset($request->forma_pago))
    {
        $tipo = 'forma_pago';
        $pagos = Pago::sale()->get();
        $sales=[];
        foreach ($pagos as $key => $value) {
          if($key==0){
            $sales = $this->getSalesByPaymentForm($value->id,$document,$status,$fecha1,$fecha2);
            $sales = $sales->unionAll($this->getTotalSalesByPaymentForm($value->id,$document,$status,$fecha1,$fecha2));
          } else {
            // $before_key = $key-1;
            // ${'sales_'.$key} = $this->getSalesByPaymentForm($value->id,$document,$status,$fecha1,$fecha2);
            $sales = $sales->unionAll($this->getSalesByPaymentForm($value->id,$document,$status,$fecha1,$fecha2));
            $sales = $sales->unionAll($this->getTotalSalesByPaymentForm($value->id,$document,$status,$fecha1,$fecha2));
          }

        }
        $salesReport=$sales->get();

    }



    return view('sale.list')
    ->with('sales', $salesReport)
    ->with('tipo',$tipo)
    ->with('fecha1', $fecha1)
    ->with('fecha2', $fecha2)
    ->with('dataDocuments',$dataDocuments)
    ->with('dataStatus',$dataStatus)
    ->with('document',$document)
    ->with('status',$status);


  }

  public function getTotalSalesByPaymentForm($payment_id,$document,$status,$fecha1,$fecha2)
  {
    $administrador =Session::get('administrador');
		$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
		/** Si la ruta es requerida y no es administrador */
		if( (isset($ruta_requerida)) && ($administrador ==false)) {
			$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
			if (count($rutas) == 0) {
			  $rutas = [0, 0];
			}
    }

    $salesReport_q=Sale::join('series','series.id','=','sales.id_serie')
    ->join('documents','documents.id','=','series.id_document')
    ->Join('customers','sales.customer_id','=','customers.id');
    if( (isset($ruta_requerida)) && ($administrador ==false)) {
      $salesReport_q->join('route_costumers','customers.id','=','route_costumers.customer_id')
        ->whereIn('route_costumers.route_id',$rutas);
    }

    $salesReport_q->join('pagos','sales.id_pago','=','pagos.id')
    ->where('documents.sign','=','-');
    if($document!='Todo'){
      $salesReport_q->where('sales.id_serie',$document);
    }
    if($status!='Todo'){
      $salesReport_q->where('sales.cancel_bill',$status);
    }
    $salesReport_q->where('sales.id_pago','=',$payment_id)
    ->whereBetween('sales.sale_date',[$fecha1,$fecha2])
    ->where('cancel_bill','=',0)
    ->select(DB::Raw("0 as id, 0 customer_id, '' as  customer_name,sum(sales.total_cost) as total_cost,'' as  document, count(*) as correlative, '' as  serie, '00/00/0000' as sale_date, pagos.name "))
    ->groupBy('pagos.name')
        ->almacen();
    return $salesReport_q;
  }

  public function getSalesByPaymentForm($payment_id,$document,$status,$fecha1,$fecha2)
  {
    $administrador =Session::get('administrador');
		$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
		/** Si la ruta es requerida y no es administrador */
		if( (isset($ruta_requerida)) && ($administrador ==false)) {
			$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
			if (count($rutas) == 0) {
			  $rutas = [0, 0];
			}
    }

    $salesReport_q=Sale::join('series','series.id','=','sales.id_serie')
    ->join('documents','documents.id','=','series.id_document')
    ->Join('customers','sales.customer_id','=','customers.id');

    if( (isset($ruta_requerida)) && ($administrador ==false)) {
      $salesReport_q->join('route_costumers','customers.id','=','route_costumers.customer_id')
        ->whereIn('route_costumers.route_id',$rutas);
    }


    $salesReport_q->join('pagos','sales.id_pago','=','pagos.id')
    ->where('documents.sign','=','-');
    if($document!='Todo'){
      $salesReport_q->where('sales.id_serie',$document);
    }
    if($status!='Todo'){
      $salesReport_q->where('sales.cancel_bill',$status);
    }
    $salesReport_q->where('sales.id_pago','=',$payment_id)
    ->whereBetween('sales.sale_date',[$fecha1,$fecha2])
    // ->where('cancel_bill','=',0)
    ->select('sales.id','customers.id as customer_id'
    ,'customers.name as customer_name','sales.total_cost','documents.name as document','sales.correlative','series.name as serie','sales.sale_date','pagos.name')
    ->orderBy('pagos.id','asc')
    ->orderBy('sales.sale_date','asc')
    ->orderBy('sales.id','asc')
    ->almacen();
    return $salesReport_q;
  }

  public function create($quotation = 0, $cellar = 0)
  {

    $id_bodega=Input::get('id_bodegas');
    $logged_user  = Auth::user();
    $dataUsers=User::where('show_in_tx',0)->lists('name','id');

    $idUserActive = $logged_user->id;
    $role_user=UserRole::join('roles','user_roles.role_id','=','roles.id')->where('user_roles.user_id','=',$idUserActive)->select('roles.role','roles.id')->get();;
    $sales     = Sale::orderBy('id', 'desc')->first();

    $administrador =Session::get('administrador');
		$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
		/** Si la ruta es requerida y no es administrador */
		if( (isset($ruta_requerida)) && ($administrador ==false)) {
			$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
			if (count($rutas) == 0) {
			  $rutas = [0, 0];
      };

      $customers = Customer::join('route_costumers','customers.id','=','route_costumers.customer_id')
			->whereIn('route_costumers.route_id',$rutas)
      ->select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'),'customers.id',DB::Raw('(max_credit_amount-balance) as max_credit_amount'),'balance','days_credit')->get();
    }
    else {
      $customers = Customer::select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'),'id',DB::Raw('(max_credit_amount-balance) as max_credit_amount'),'balance','days_credit')->get();
    }

    /**
    * Tipos de pago para ventas
    */
    $pagos = Pago::sale()
    ->select('id','name','type')
    ->get();

    //obtenemos los valores dependido de su
    $ValorSeries = Serie::join('state_cellars as c','series.id_state', '=', 'c.id')
    ->join('documents', 'series.id_document', '=', 'documents.id')
    ->join('state_cellars as d', 'documents.id_state', '=', 'd.id')
    ->where('c.name', '=', 'Activo')
    ->where('series.credit', 0)
    ->where('d.name', '=', 'Activo')
    //condicion para los de signo negativo
    ->where('documents.sign', '=', '-')
    ->select('series.name', 'series.id', 'documents.name as nombre')
    ->orderBy('series.name', 'Desc')->get();
    // dd($ValorSeries);

    /**
    * Solo los almacenes a los que el usuario tiene permisos.
    */
    $almacen = $this->getAlmacenByUser($idUserActive);


    /**
    * Leer de los parametros generales
    * la serie default
    */
    $idFac=1;

    /**
    * Leer de los parametros generales
    * La bodega default
    */
    if($id_bodega=='')
    {
      if(count($almacen)>0)
      {
        $id_bodega=$almacen[0]->id;
      }else
      {
        $id_bodega=0;
      }
    }

    // $list_products = $this->getItemsAndServicesByStorage($id_bodega);
    $param = array();
    $parameters = GeneralParameter::All();
    $param['desc_vta']=0;
    $param['max_desc_vta']=0;
    // dd($parameters);
    // echo '<br>';
    foreach ($parameters as $key => $value) {
      // echo $value->name.' - '.$value->active.'<br>';
      if($value->name === 'Mostrar campo de pedido'){
        $param['mostrar_pedido']=intval($value->active);
      }
      // else {
      //   $param['mostrar_pedido']=0;
      // }
      if($value->name == 'Mostrar campo de transporte') {
        $param['mostrar_transporte']=intval($value->active);
      }
      // else {
      //   $param['mostrar_transporte']=0;
      // }

      if ($value->name == 'Mostrar campo de Comentario imprimible') {
        $param['mostrar_imprimible']=intval($value->active);
      }
      // else {
      //   $param['mostrar_imprimible']=0;
      // }

      // if (($value->name == 'Validar precio mínimo.' ) && ((int)$value->active==(int)1)){
        if (($value->name == 'Validar precio mínimo.' ) ){
          // echo 'active '.$value->active===1;
        $param['validar_precio_minimo']=intval($value->active);
      }
      // else {
      //   $param['validar_precio_minimo']=0;
      // }

      if ($value->name == 'Permitir varias veces el mismo item.') {
        $param['permitir_varios_items']=intval($value->active);
      }
      // else {
      //   $param['permitir_varios_items']=0;
      // }

      if ($value->name == 'Dias de pago por defecto.') {
        $param['dias_de_pago_default']=intval($value->active);
      }
      // else {
      //   $param['dias_de_pago_default']=0;
      // }

      if($value->name == 'ID Documento por defecto.'){
        if (intval($value->active)==1){
          $param['idFac']=intval($value->text_value);
        }
        else {
          $param['idFac']=0;
        }
      }


      if($value->name == 'Precio default.')  {
        if(intval($value->active)==1) {
          $param['precio_default']=intval($value->text_value);
        } else {
        $param['precio_default']=0;
        }
      }

      if($value->name == 'Cuenta default.'){
        if(intval($value->active)==1){
          $param['cuenta_default']=intval($value->text_value);
        } else {
          $param['cuenta_default']=0;
        }
      }
      $user =Auth::user();
        $administrador = false;
        /* VERIFICAR ROL DE USUARIO, BUSCAR ROL ADMINISTRADOR* */
        foreach($user->roles as $rol){
            if ($rol->admin==1){
                $administrador = true;
              break;
            }
        }

      if($value->name == 'Cambia precios solo Admin.'){

        if(intval($value->active)==1) {
          // $param['precios_solo_admin']= $administrador===true?1:0;
          if($administrador===true){
            $param['precios_solo_admin']=1;
          } else {
            $param['precios_solo_admin']=0;
          }
        } else {
          $param['precios_solo_admin']=1;
        }
      }

      if (($value->name == 'Campo ruta requerido.') && (intval($value->active)==1)){
        $param['rutas']=Route::where('status_id','1')->asigned()->get();
      }

      if($value->name == 'Número máximo de produtos en venta.'){
        if(intval($value->active)==1){
          $param['max_lineas']=intval($value->max_amount);
        } else {
          $param['max_lineas'] = 0;
        }
      }

      if($value->name == 'Imprimir propietario y negocio en proforma.'){
        if(intval($value->active)==1){
          $param['imprimir_propietario']=intval($value->active);
        } else {
          $param['imprimir_propietario']=0;
        }
      }

      if($value->name == 'Descuento en venta.')  {
        if (intval($value->active)==1) {
          $param['desc_vta']=intval($value->text_value);
          $param['max_desc_vta']=floatval($value->max_amount);
        }
        else {
          $param['desc_vta']=0;
          $param['max_desc_vta']=0;
        }
      }

      if($value->name == 'Aplica descuentos solo Admin.'){
        if( (intval($value->active)==1) && $param['desc_vta']==1) {
          if($administrador===true){
            $param['descto_admin']=1;
          } else {
            $param['descto_admin']=0;
          }
          // $param['descto_admin']= $administrador===true?1:0;
          // $param['descto_admin']=intval($value->active);
        } else {
          $param['descto_admin']=1;
        }
      }




    }

    $prices = Price::active(1)->select('id','name')->get();



    return view('sale.create_sale_modal')
    ->with('idFac',$idFac)
    ->with('idUserActive',$idUserActive)
    ->with('sale', $sales)
    ->with('payments', $pagos)
    ->with('customer', $customers)
    ->with('quotation_id', $quotation)
    ->with('cellar_id', $cellar)
    ->with('almacen', $almacen)
    ->with('serieFactura', $ValorSeries)
    ->with('id_almacen',$id_bodega)
    ->with('role_user',$role_user)
    ->with('dataUsers',$dataUsers)
    ->with('prices',$prices)
    ->with($param);
  }


  /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
  // public function store(SaleRequest $request)
  public function store(Request $request)
  {
    // dd($request->all());

    /**
    * Parseo del request con los 2 formularios serializados
    * Ventas (venta) y Forma de pago (pago)
    */
    parse_str($request->venta,$ventas);
    parse_str($request->pago,$forma_pagos);
    $venta= json_decode(json_encode($ventas));
    $forma_pago= json_decode((json_encode($forma_pagos)));

    // dd($forma_pago);

    $descuento_pct = $venta->discount_pct;
    $max_desc_vta =$venta->max_desc_vta;
    if ($descuento_pct>$max_desc_vta) {
      throw new \Exception('El máximo % de descuento permitido es :'.$max_desc_vta, 6);
    }

    if ($descuento_pct<0) {
      throw new \Exception('El % de descuento ('.$max_desc_vta.' debe ser mayor a 0',6);
    }
    /**
    * Variables para determinar si hay o no error
    * Y el error que se generó
    */
    $flag=1;
    $message='';
    $custMessage='';

    /**
    * Obtener los datos de la forma de pago
    */
    $id_pago = $forma_pago->id_pago;
    $pago = Pago::find($id_pago);

    /**
    * Si la forma de pago es "CREDITO"
    * Marcar la factura como "NO PAGADA"
    */
    $factura_pagada = 1;
    $monto_pagado = $forma_pago->amount;
    // if($pago->type==6 && $id_pago==6)
    if($pago->type==6)
    {
      $factura_pagada=0;
      $monto_pagado=0;
    }

    #region Obtener Parámetros generales
      $empresa = Parameter::first();
    #endregion

    DB::beginTransaction();
    try {

      $verify_correlative = Sale::where('id_serie', $venta->serie_id)
      ->where('correlative', $venta->correlativo_num)
      ->count();

      if ($verify_correlative>0) {
        throw new \Exception('El correlativo:'.$venta->correlativo_num.' ya esta utilizado en un documento de la serie seleccionada.', 6);
      }

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

        $nuevaFecha1 = explode('/', $venta->date_tx);
        $diaFecha1 = $nuevaFecha1[0];
        $mesFecha1 = $nuevaFecha1[1];
        $anioFecha1 = $nuevaFecha1[2];
        $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1;

        $sDate = date('m/Y', strtotime($fecha1));
        if ($refDate == $sDate && !isset($paramD)){
            throw  new \Exception("No se puede realizar transacciones de inventario en un mes cerrado.", 6);
        }
        #endregion

      /**
      * Guardar el encabezado de la factura
      */
      $sales = new Sale();

      $sales->customer_id=$venta->customer_id;
      $sales->user_id=Auth::user()->id;
      $sales->id_pago=$id_pago;
      $sales->payment_status= $factura_pagada;
      $sales->comments=$venta->comments;
      $sales->sale_date=$venta->date_tx;
      $sales->discount_pct=$descuento_pct;
      $sales->discount_amount=$venta->discount_amount;
      $sales->total_cost = $forma_pago->amount;
      $sales->total_paid = $monto_pagado;
      $sales->transport = $venta->transport;
      $sales->printable_comment = $venta->printable_comment;
      $sales->order = $venta->order;
      $sales->id_serie=$venta->serie_id;
      $sales->almacen_id = $venta->id_bodegas;
      $sales->correlative=$venta->correlativo_num;
      $sales->user_relation=$venta->user_relation; /*Vendedor */
      $sales->change=$forma_pago->change; /*Vuelto */
      $pagado = isset($forma_pago->paid)?$forma_pago->paid:$forma_pago->amount;
      $sales->paid=$pagado; /*pagado */


      $sales->save();

      #region GUARDAR LOG DE CLIENTE
        $log = new TransactionLog();
        $log->sale_id = $sales->id;
        $log->_client_info = $_SERVER['HTTP_USER_AGENT'];
        $log->save();
      #endregion

        //Obtenemos la serie
        $nombreSerie = Serie::find($venta->serie_id);
        //Obtenemos el nombre del documento
        $nombreDocument = Document::where('id', '=', $nombreSerie->id_document)->value('name');
        // Armamos el nombre de documento para Kardex
        $nombreDeTransaccion  = $nombreDocument . ' ' . $nombreSerie->name . '-' . $venta->correlativo_num;

        $customer = Customer::find($venta->customer_id);
        #region CREAR ENCABEZADOS FEL
        $header = json_decode(json_encode(array(
            "date"=>date('Y-m-d\TH:i:sP'),
            "email"=>'',
            "nit"=>$empresa->nit,
            "nombre_comercial"=>$empresa->fel_username,
            "nombre_emisor"=>$empresa->fel_username,
            "address"=>$empresa->address,
            "postal_code"=>"01011",
            "municipality"=>"Guatemala",
            "departament"=>"Guatemala"
        )));
        $receiver = json_decode(json_encode(array(
            "email"=>'',
            "nit"=>strtoupper(str_replace(["/", "-", " "], "",$customer->nit_customer)),
            "name"=>$customer->name,
            "address"=>$customer->address,
            "postal_code"=>"01011",
            "municipality"=>"Guatemala",
            "departament"=>"Guatemala"
        )));
        $details = array();
        #endregion

      //ACTUALIZAR ESTADO DE COTIZACIÓN
      if ($venta->quotation_id!=0){
        $quotation = Quotation::find($venta->quotation_id);
        $quotation->status = 2;
        $quotation->sale_id = $sales->id;
        $quotation->update();
      }
      $id_sales=$sales->id;

      $indice=0;
      $totalAcumulado=0;
      $productos = $venta->item_quantity;
      $pct_desc = round($descuento_pct/100,2);
      /**
      * Ciclo para recorrer con respecto
      * a productos/servicios/etc
      */
      $json_sale_items = json_decode($venta->new_details);
      foreach ($json_sale_items as $key => $value) {

        $cantidad=$value->quantity;
        $id_items=$value->item_id;
        $precio_base=$value->price;
        $unit_id = $value->unit_id;

        $unit = ItemPrice::whereUnit_id($unit_id)
                  ->whereItem_id($id_items)
                  ->first();


        // if($descuento_pct>0){
          $precio=round($precio_base - round($pct_desc*$precio_base,2),3);
        // }
        // else {
        //   $precio=$items_array[$indice]['selling'];
        // }
        /**Si ese el último producto, le asignamos lo que resta del total */
        if($productos==1){
          $precio=round(($forma_pago->amount-$totalAcumulado)/$cantidad,3);
        }


        // $costItem=Item::find($id_items);
        $items = Item::find($id_items);
        /*
        * Si el producto no es un combo, no realizar la verificación de existencias del mismo
        * */
        if (!$items->is_kit){
            $_verify =json_decode($this->verifyQuantity($id_items, $venta->id_bodegas, $cantidad));
          if (!$_verify->flag){
              throw new \Exception($_verify->message, 6);
          }
        }

        /**
        * Guardar el DETALLE de la factura
        */
        $saleItemsData                = new SaleItem;
        $saleItemsData->sale_id       = $id_sales;
        $saleItemsData->item_id       = $id_items;
        $saleItemsData->cost_price    = $items->cost_price;
        $saleItemsData->selling_price = $precio_base;
        $saleItemsData->quantity      = $cantidad;
        $saleItemsData->total_cost    = $items->cost_price*$cantidad;
        $saleItemsData->total_selling = $precio*$cantidad;
        $saleItemsData->low_price     = $precio;
        $saleItemsData->id_bodega     = $venta->id_bodegas;
        $saleItemsData->unit_id       = $unit_id;
        $saleItemsData->save();
        $totalAcumulado=$totalAcumulado+($precio*$cantidad);

        #region Crear detalle para envío de FEL
          $tmp = array("type"=>$items->type_id==1?'B':'S',
              "quantity"=>$cantidad,
              "unity"=>'UND',
              "description"=>$items->description,
              "unit_price"=>$precio_base,
              "discount"=>0);
          array_push($details, $tmp);
        #endregion

        /**
        * Guardar el KARDEX
        */
        if ($items->type > 0 && $items->stock_action!='=') {
          /**
          * KARDEX & ACTUALIZAR BODEGA
          */
          $this->updateItemQuantity($venta->id_bodegas, $id_items,-(($cantidad * $unit->quantity)), $nombreDeTransaccion);

          /**
          * SI ES COMBO, REGISTRAR KARDEX DE SUS HIJOS
          * */
          if ($items->is_kit) {
            $detailKit=ItemKitItem::where('item_kit_id', $items->id)->get();
            foreach ($detailKit as $key => $x) {
                /**
                 * VERIFICAR EXISTENCIAS DE PRODUCTOS
                 */
                $_tmp = json_decode($this->verifyQuantity($x->item_id, $venta->id_bodegas, (($cantidad * $unit->quantity)*$x->quantity)));
                if (!$_tmp->flag) {
                    throw new \Exception('Procesando combo ['.$items->item_name.'] '.$_tmp->message, 6);
                }
              /** KARDEX & ACTUALIZAR BODEGA  */
              $this->updateItemQuantity($venta->id_bodegas, $x->item_id,-(($cantidad * $unit->quantity)*$x->quantity), $nombreDeTransaccion.' Combo');
            }
          }
        }
        $indice++;
        $productos--;
      }

      $details = json_decode(json_encode($details));



      #region VERIFICAR QUE LOS MONTOS COINCIDAN
        if (($forma_pago->amount-$totalAcumulado)>0.01){
            throw new \Exception("El monto total de la factura no coincide con el monto del detalle: ".$forma_pago->amount.' det '.$totalAcumulado, 6);
        }
      #endregion

      /**
      * Buscamos y actualizamos
      * la factura con el monto total
      */
      // $findSale=Sale::find($id_sales);
      // $findSale->total_cost=$totalAcumulado;
      // $findSale->save();

      /* *********************************************
      *  SI FORMA PAGO ES CREDITO GUARDAMOS
      ** *********************************************
      */
      // if($pago->type==6 && $id_pago==6) {
      if($pago->type==6 ) {

        $credito = [];
        $credito['total_pagos']     = 1;
        $credito['total_eganche']   = 0;
        $credito['total_interes']   = 0;
        $credito['montoCredito']    = $forma_pago->amount;
        $credito['date_payments']   = $forma_pago->date_payments;
        $credito['id_cliente']      = $venta->customer_id;
        $credito['id_factura']      = $id_sales;

        $nuevo = new Request($credito);
        $guardarPago = $this->saveCredit($nuevo);
      }
      else {
        /* *********************************************
        *   SI  FORMA DE PAGO DISTINTO A CREDITO
        *      GUARDAMOS TRANSACCION BANCARIA
        ** *********************************************
        */
        $receipt_number = intval(Revenue::max('receipt_number'))+1;
        $ingresos = [];
        $ingresos['account_id'] = $forma_pago->account_id;
        $ingresos['paid_at'] = $venta->date_tx;
        $ingresos['amount'] = $forma_pago->amount;
        $ingresos['description'] = $forma_pago->description;
        $ingresos['category_id'] = null;
        $ingresos['receipt_number'] = $receipt_number;

        $ingresos['reference'] = $forma_pago->reference;
        $ingresos['user_id'] = $forma_pago->user_id;
        $ingresos['status'] = $forma_pago->status;
        $ingresos['payment_method'] = $id_pago;
        $ingresos['customer_id'] = $venta->customer_id;
        $ingresos['invoice_id'] = $id_sales;

        $ingresos['bank_name'] = $forma_pago->bank_name;
        $ingresos['same_bank'] = $forma_pago->same_bank;
        $ingresos['card_name'] = $forma_pago->card_name;
        $ingresos['card_number'] = $forma_pago->card_number;
        $ingresos['amount_applied'] = $totalAcumulado;

        $nuevo = new Request($ingresos);
        $guardarPago = $this->saveRevenue($nuevo);
      }

        #region VERIFICAR SI FEL ESTA ACTIVO EN LA EMPRSA, EVIAR DATOS A INFILE DE SER TRUE
        if (isset($empresa->fel)&&$empresa->fel && $nombreSerie->document->type_fel !=''){
            $fel_response = INFILE::certifyFact($nombreSerie->document->type_fel, $header, $receiver, $details,
                $nombreDeTransaccion, $empresa->fel_username, $empresa->fel_cert, $empresa->fel_firm);
            $json_fel = (json_decode(json_encode($fel_response)));
            if ($json_fel->resultado == false){
                throw new \Exception("Error certificando documento: ".json_encode($json_fel->descripcion_errores));
            }
            else{
                $sales->api_info = $json_fel->informacion_adicional;
                $sales->api_uuid = $json_fel->uuid;
                $sales->api_serie = $json_fel->serie;
                $sales->api_fecha = $json_fel->fecha;
                $sales->xml_certificado = $json_fel->xml_certificado;
                $sales->api_numero = $json_fel->numero;
                $sales->update();
            }
        }
        #endregion

      if ($guardarPago[0] < 0) {
        /**
        * Si hubo errores al guardar FOrma de pago
        * Hacemos Rollback de la transaccion
        * Definimos la bandera de error, guardamos el error.
        */
          $message = "Error pago:".$guardarPago[1];
          throw new \Exception($message);
      }
      else {
        /**
        * Si no hubo errores, hacemos commit de la transaccion
        * luego seteamos la URL
        * Para imprimir la factura
        */
        $url = 'sales/complete/'.$id_sales;
        $flag=1;
        DB::commit();
        Session::flash('message', trans('sale.save_ok'));
        Session::flash('alert-type', trans('success'));
      }

    } catch (\Exception $e) {
      /**
      * Si hubo errores generales
      * Hacemos Rollback de la transaccion
      * Definimos la bandera de error, guardamos el error.
      */
      DB::rollBack();
      $custMessage = $e->getMessage();
      $message = "Error ultimo:".$e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
      $flag=2;
      $url = '#';
    }

    $resp = array('flag'=>$flag, 'mensaje'=>$message, 'custMessage'=>$custMessage,'url'=>$url);

    return json_encode($resp);
  }

  public function complete($id)
  {
    // echo "Venta completada: ".$id;
    $sales=Sale::find($id);

    // return $sales;
    if($sales->show_header!=1){
      $itemssale = SaleItem::where('sale_id', $sales->show_header)->get();
    }else {
      $itemssale = SaleItem::where('sale_id', $id)->get();
    }
    $bandera=Input::get('bandera');
    //buscamos el documento correspondiente a la venta
    $document=Sale::join('series','sales.id_serie', '=', 'series.id')
    ->join('pagos','pagos.id','=','sales.id_pago')
    ->join('documents','series.id_document','=', 'documents.id')
    ->leftJoin('credits','credits.id_factura','=','sales.id')
    ->where('sales.id','=',$id)
    ->select([DB::raw('concat(documents.name," ",series.name, "-",sales.correlative) as documento,pagos.name as forma_pago,credits.date_payments,sales.change')])->get();

    $dataUsers=User::find($sales->user_relation);

    $letras = NumeroALetras::convertir($sales->total_cost, 'quetzales', 'centavos');
    $precio_letras = ucfirst(strtolower($letras));

    $parameters = GeneralParameter::All();
    $imprimir_codigo_cliente=0;
    foreach ($parameters as $key => $value) {
      if($value->name === 'Imprimir código de cliente.'){
        $imprimir_codigo_cliente = intval($value->active);
      }
    }
    // dd($imprimir_codigo_cliente);
    $parameters = Parameter::first();
    $serie = Serie::find($sales->id_serie);

    $imprimir_ticket = GeneralParameter::active()->where('name','Imprimir ticket')->first();
    // $imprimir_ticket_p =0;
    if(isset($imprimir_ticket)){
      $imprimir_ticket_p = $imprimir_ticket->active==null?0:$imprimir_ticket->active;
    } else{
      $imprimir_ticket=0;
    }

    if ($serie->proforma){
      $imprimir_propietario = GeneralParameter::where('name','Imprimir propietario y negocio en proforma.')
      ->first()->active;
      $version_proforma = GeneralParameter::active()->where('name','Versión de proforma')->first();


      $vista = 'documents.proforma_full';
      if(isset($version_proforma)){
        if ($version_proforma->text_value==2)
        {
          $vista = 'documents.proforma';
        }
      }

      return view($vista)
      ->with('sale', $sales)
      ->with('serie', $serie)
      ->with('dataUsers',$dataUsers)
      ->with('precio_letras',$precio_letras)
      ->with('details', $itemssale)
      ->with('documento',$document)
      ->with('customer', Customer::find($sales->customer_id))
      ->with('parameters', $parameters)
      ->with('imprimir_codigo_cliente',$imprimir_codigo_cliente)
      ->with('imprimir_propietario',$imprimir_propietario)
      ->with('imprir_ticket',$imprimir_ticket_p);
    }
    else{
      return view('sale.complete')
      ->with('sales', $sales)
      //->with('saleItemsData', $saleItemsData)
      ->with('saleItems', $itemssale)
      ->with('dataUsers',$dataUsers)
      ->with('bandera', $bandera)
      ->with('documento',$document)
      ->with('precio_letras',$precio_letras)
      ->with('imprimir_codigo_cliente',$imprimir_codigo_cliente)
      ->with('imprir_ticket',$imprimir_ticket_p);
      //->with('nombreDeTransaccion', $nombreDeTransaccion);
    }

  }

  public function print_invoice($id)
  {
    // echo "Venta completada: ".$id;
    $sales=Sale::find($id);

    // return $sales;
    if($sales->show_header!=1){
      $itemssale = SaleItem::where('sale_id', $sales->show_header)->get();
    }else {
      $itemssale = SaleItem::where('sale_id', $id)->get();
    }
    $bandera=Input::get('bandera');
    //buscamos el documento correspondiente a la venta
    $document=Sale::join('series','sales.id_serie', '=', 'series.id')
    ->join('pagos','pagos.id','=','sales.id_pago')
    ->join('documents','series.id_document','=', 'documents.id')
    ->leftJoin('credits','credits.id_factura','=','sales.id')
    ->where('sales.id','=',$id)
    ->select([DB::raw('concat(documents.name," ",series.name, "-",sales.correlative) as documento,pagos.name as forma_pago,credits.date_payments,sales.change')])->get();

    $dataUsers=User::find($sales->user_relation);

    $letras = NumeroALetras::convertir($sales->total_cost, 'quetzales', 'centavos');
    $precio_letras = ucfirst(strtolower($letras));

    $parameters = GeneralParameter::All();
    $imprimir_codigo_cliente=0;
    foreach ($parameters as $key => $value) {
      if($value->name === 'Imprimir código de cliente.'){
        $imprimir_codigo_cliente = intval($value->active);
      }
    }
    // dd($imprimir_codigo_cliente);
    $parameters = Parameter::first();
    $serie = Serie::find($sales->id_serie);

    $imprimir_ticket = GeneralParameter::active()->where('name','Imprimir ticket')->first();
    // $imprimir_ticket_p =0;
    if(isset($imprimir_ticket)){
      $imprimir_ticket_p = $imprimir_ticket->active==null?0:$imprimir_ticket->active;
    } else{
      $imprimir_ticket=0;
    }

      return view('sale.complete')
      ->with('sales', $sales)
      //->with('saleItemsData', $saleItemsData)
      ->with('saleItems', $itemssale)
      ->with('dataUsers',$dataUsers)
      ->with('bandera', $bandera)
      ->with('documento',$document)
      ->with('precio_letras',$precio_letras)
      ->with('imprimir_codigo_cliente',$imprimir_codigo_cliente)
      ->with('imprir_ticket',$imprimir_ticket_p);
      //->with('nombreDeTransaccion', $nombreDeTransaccion);

  }

  public function print_ticket($id)
  {
    // echo "Venta completada: ".$id;
    $sales=Sale::find($id);

    // return $sales;
    if($sales->show_header!=1){
      $itemssale = SaleItem::where('sale_id', $sales->show_header)->get();
    }else {
      $itemssale = SaleItem::where('sale_id', $id)->get();
    }
    $bandera=Input::get('bandera');
    //buscamos el documento correspondiente a la venta
    $document=Sale::join('series','sales.id_serie', '=', 'series.id')
    ->join('pagos','pagos.id','=','sales.id_pago')
    ->join('documents','series.id_document','=', 'documents.id')
    ->leftJoin('credits','credits.id_factura','=','sales.id')
    ->where('sales.id','=',$id)
    ->select([DB::raw('concat(documents.name," ",series.name, "-",sales.correlative) as documento,pagos.name as forma_pago,credits.date_payments,sales.change,sales.paid,pagos.type')])->get();

    $dataUsers=User::find($sales->user_relation);

    $letras = NumeroALetras::convertir($sales->total_cost, 'quetzales', 'centavos');
    $precio_letras = ucfirst(strtolower($letras));

    $parameters = GeneralParameter::All();
    $imprimir_codigo_cliente=0;
    foreach ($parameters as $key => $value) {
      if($value->name === 'Imprimir código de cliente.'){
        $imprimir_codigo_cliente = intval($value->active);
      }
    }
    // dd($imprimir_codigo_cliente);
    $parameters = Parameter::first();
    $serie = Serie::find($sales->id_serie);
      $imprimir_propietario = GeneralParameter::where('name','Imprimir propietario y negocio en proforma.')
      ->first()->active;

      $vista = 'documents.ticket';

      return view($vista)
      ->with('sale', $sales)
      ->with('serie', $serie)
      ->with('precio_letras',$precio_letras)
      ->with('details', $itemssale)
      ->with('documento',$document)
      ->with('customer', Customer::find($sales->customer_id))
      ->with('parameters', $parameters)
      ->with('imprimir_codigo_cliente',$imprimir_codigo_cliente)
      ->with('imprimir_propietario',$imprimir_propietario)
      ->with('usuario',$dataUsers);
    // }


  }

  public function sales_ajax_active(){

    $today = date('Y-m-d');
    /**
     * FILTRAR LAS VENTAS QUE TENGAN UNA FECHA MENOR A LOS 2 MESES
     */
    $reference = date('Y-m-d', strtotime($today.'- 2 months'));
    $administrador =Session::get('administrador');
		$ruta_requerida = GeneralParameter::active()->where('name','Campo ruta requerido.')->first();
		/** Si la ruta es requerida y no es administrador */
		if( (isset($ruta_requerida)) && ($administrador ==false)) {
			$rutas = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
			if (count($rutas) == 0) {
			  $rutas = [0, 0];
      }
    }
    $sale = Sale::join('customers', 'customers.id', '=', 'sales.customer_id');
     if( (isset($ruta_requerida)) && ($administrador ==false)) {
      $sale->join('route_costumers','customers.id','=','route_costumers.customer_id')
        ->whereIn('route_costumers.route_id',$rutas);
      }

      $sale->join('series', 'series.id', '=', 'sales.id_serie')
        ->join('documents', 'documents.id', '=', 'series.id_document')
        ->join('pagos', 'sales.id_pago', '=', 'pagos.id')
        ->where('cancel_bill',0)
          ->almacen()
        ->whereDate('sale_date', '>=', $reference)
        ->whereRaw('nc_amount < total_cost')
        ->select(DB::raw('concat(documents.name, " ",series.name, "-", correlative) as document'), 'sale_date','total_cost', DB::raw('(total_cost - total_paid) as balance'), 'pagos.name as pago'
            , 'customers.name as customer', 'sales.id as sale_id', 'nc_amount');
        $sales = $sale->get();
    return json_encode($sales);
  }

  public function complete_invoice($id)
  {
    $sales=Sale::join('series','sales.id_serie', '=', 'series.id')
    ->join('documents','series.id_document','=', 'documents.id')
    ->join('pagos','pagos.id','=','sales.id_pago')
    ->join('customers','customers.id','=','sales.customer_id')
    ->where('sales.id','=',$id)
    ->select([DB::raw('concat(documents.name," | Serie ",series.name) as documento'),'sales.correlative as correlativo','pagos.name as pago','sales.created_at','sales.total_cost','sales.show_header','user_relation','customers.name as customer_name','customers.address as customer_address','customers.nit_customer as customer_nit'])
    ->get();
    // echo "Venta completada: ".$id;
    // $sales=Sale::find($id);
    // return $sales;
    if($sales[0]->show_header!=1){
      $itemssale = SaleItem::where('sale_id', $sales->show_header)->get();
    }else {
      $itemssale = SaleItem::where('sale_id', $id)->get();
    }
    $bandera=Input::get('bandera');
    //buscamos el documento correspondiente a la venta
    $dataUsers=User::find($sales[0]->user_relation);
    $letras = NumeroALetras::convertir($sales[0]->total_cost, 'quetzales', 'centavos');
    $precio_letras = ucfirst(strtolower($letras));

    return view('partials.inventory_document')
    ->with('docheader', $sales)
    //->with('saleItemsData', $saleItemsData)
    ->with('docdetail', $itemssale)
    ->with('docuser',$dataUsers)
    ->with('bandera', $bandera)
    // ->with('docname',$document)
    ->with('precio_letras',$precio_letras);
    //->with('nombreDeTransaccion', $nombreDeTransaccion);

  }
  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function show($id)
  {
    //
  }



  public function verifyCorrelative($serie, $correlative){
    $flag=Sale::where('id_serie', $serie)
    ->where('correlative',$correlative)
    ->count();
    return $flag;
  }

  public function cancel_bill()
  {



    $data_sales=Sale::where('sales.cancel_bill','=',0)

    ->select(['sales.id',
    'sales.customer_id',
    'sales.user_id',
    'sales.created_at',
    'sales.id_pago',
    'sales.total_cost',
    'sales.id_serie',
    'sales.correlative'])->get();

    return view('cancel_bill.list_cancel_bill')
    ->with('data_sales', $data_sales);

  }

  public  function anular_vieja(Request $request)
  {

    $data_items=SaleItem::where('sale_id','=',$request->id_elemento)->get();

    for ($i=0; $i <count($data_items) ; $i++)
    {
      $data_search=BodegaProducto::where('id_bodega','=',$data_items[$i]->id_bodega)
      ->where('id_product','=',$data_items[$i]->item_id)
      ->value('id');
      $data_update=BodegaProducto::find($data_search);
      $data_update->quantity=$data_update->quantity+$data_items[$i]->quantity;
      $data_update->save();
    }
    $data_sales=Sale::find($request->id_elemento);
    $data_sales->cancel_bill=1;
    $data_sales->save();

    Session::flash('message', 'Anulación completada correctamente');
    return Redirect::to('/cancel_bill');

  }

  public  function anular(Request $request)
  {
    // dd($request->all());
    $s=Sale::find($request->id_elemento);
    $empresa = Parameter::first();
    if($s->cancel_bill==1) {
      Session::flash('message', 'Documento ya esta anulado');
      return Redirect::to('/sales');
    }
    DB::beginTransaction();
      try {
          $nombreSerie = Serie::find($s->id_serie);
          $pago = Pago::find($s->id_pago);
          //Obtenemos el nombre del documento
          $nombreDocument = Document::where('id', '=', $nombreSerie->id_document)->value('name');

          $data_items=SaleItem::where('sale_id',$request->id_elemento)->get();
          $contador = SaleItem::where('sale_id', $request->id_elemento)->count();
          // echo 'contador '.$contador;
          // dd($data_items);

          for ($i=0; $i < $contador; $i++)
          {
              $evalua=Item::find($data_items[$i]->item_id);
              $bodega=$data_items[$i]->id_bodega;
              // echo 'bodega '.$bodega.'<br>';
              if ((int)$evalua->is_kit==0) {
                  if ($evalua->stock_action != "="){
                      $data_search=BodegaProducto::where('id_bodega',$bodega)
                          ->where('id_product',$data_items[$i]->item_id)
                          ->get();
                      // dd($data_search[0]->id);
                      $data_update=BodegaProducto::find($data_search[0]->id);
                      $data_update->quantity=$data_update->quantity+$data_items[$i]->quantity;
                      $data_update->save();
                      $inventorie             = new Inventory;
                      $inventorie->item_id    = $data_items[$i]->item_id;
                      $inventorie->user_id    = Auth::user()->id;
                      $inventorie->in_out_qty = ($data_items[$i]->quantity);
                      $inventorie->almacen_id = $bodega;
                      $inventorie->remarks = 'Anulación venta '.$nombreDocument.' '.$nombreSerie->name.'-'.$s->correlative;
                      $inventorie->save();
                  }
              }
              else{
                  //ANULAR PRODUCTOS DE COMBO
                  $detailKit=ItemKitItem::where('item_kit_id', $data_items[$i]->item_id)->get();

                  // echo 'bodega '.$bodega.'<br>';
                  // dd($detailKit);
                  foreach ($detailKit as $key => $x) {

                      $inventorie             = new Inventory;
                      $inventorie->item_id    = $x->item_id;
                      $inventorie->almacen_id = $bodega;
                      $inventorie->user_id    = Auth::user()->id;
                      $inventorie->in_out_qty = ($data_items[$i]->quantity*$x->quantity);
                      $inventorie->remarks = 'Anulación venta '.$nombreDocument.' '.$nombreSerie->name.'-'.$s->correlative.' Combo';
                      $inventorie->save();
                      // echo 'inventorie '.$inventorie->id.'<br>';
                      // dd($x->item_id.' '.$data_items[$i]->quantity.' '.$x->quantity.' / '.'Anulación venta '.$nombreDocument.' '.$nombreSerie->name.'-'.$s->correlative.' Combo'. ' user '.Auth::user()->id);
                      $tmp=Item::find($x->item_id);
                      $tmp->quantity+=($data_items[$i]->quantity*$x->quantity);
                      $tmp->save();
                      $valorE = BodegaProducto::where('id_product', '=', $x->item_id)
                          ->where('id_bodega', '=', $bodega)
                          ->value('id');
                      $act = BodegaProducto::find($valorE);
                      $act->quantity += ($data_items[$i]->quantity*$x->quantity);
                      $act->save();
                  }
              }
          }
          $data_sales=Sale::find($request->id_elemento);
          $data_sales->cancel_bill=1;
          $data_sales->save();

          if($pago->type==6) /**Credito  */
          {
              /* => Actualizar saldo cliente */
              $cliente = Customer::find($s->customer_id);
              $s->total_cost;
              $cliente->balance = $cliente->balance -$s->total_cost;
              $cliente->save();
              /** => Anular registro crédito */
              $credito = Credit::where('id_factura',$request->id_elemento)->first();
              $credito->status_id=10;
              $credito->save();
          }
          else{
              #region Marcar como anulada la transacción bancaria asociada a la venta
               $tx = $this->cancelTransaction($data_sales->revenues->id, 'revenue', true);
               if ($tx[0]<0){
                   throw new \Exception($tx[1], 6);
               }
              #endregion
          }

          if ($s->api_uuid!=''){
              $json_fel = INFILE::certifyAnul($s->api_uuid, $s->api_fecha, $empresa->nit, str_replace("/", "",$s->customer->nit_customer), date('Y-m-d\TH:i:sP'),
                  'Anulación venta '.$nombreDocument.' '.$nombreSerie->name.'-'.$s->correlative, $empresa->fel_username,
                  $empresa->fel_cert, $empresa->fel_firm, $nombreDocument);
              $json_fel = (json_decode(json_encode($json_fel)));
              if ($json_fel->resultado == false){
                  throw new \Exception("Error certificando anulación: ".json_encode($json_fel->descripcion_errores));
              }
              else{
                  $s->fecha_anulacion = $json_fel->fecha;
                  $s->update();
              }
          }

          DB::commit();
          Session::flash('message', 'Anulación completada correctamente');

      }
      catch(\Exception $ex){
          DB::rollback();
//          dd('ERROR:'.$ex->getMessage().'|'.$ex->getLine().'|'.$ex->getFile());
          Session::flash('message', 'No se pudo completar la anulación '. $ex->getMessage());
      }

    return Redirect::to('/sales');

  }
  public function getModalDelete($id = null)
  {
    $error = '';
    $model = '';
    $confirm_route =  route('admin.sales.confirm-delete',['id'=>$id]);
    return View('layouts/modal_confirmation', compact('error','model', 'confirm_route'));

  }
  public function getCorrelativeSale($id){
    $valores=Sale::join('series','series.id','=','sales.id_serie')
    ->where('series.id','=',$id)->max('correlative');
    $valornuevo=$valores;
    // $valores=Sale::join('series','series.id','=','sales.id_serie');
    return $valornuevo+1;
  }
  public function existCorrelative(Request $request){
    $response=0;
    $exists=Sale::where('id_serie',$request->id_serie)->where('correlative',$request->correlative)->select('id')->get();
    if(count($exists)==0){
      $response=0;
    }else {
      $response=1;
    }
    return $response;
  }
  public function getSaleDetails($id)
  {
      $sale = Sale::join('series', 'series.id', '=', 'sales.id_serie')
          ->join('documents', 'documents.id', '=','series.id_document')
          ->join('customers', 'customers.id', '=', 'sales.customer_id')
          ->join('pagos', 'pagos.id', '=', 'sales.id_pago')
          ->select('sales.*', 'series.name as serie', 'documents.name as documento', 'pagos.name as pago', 'customers.name as cliente')
          ->where('sales.id', $id)
          ->first();
      $details = SaleItem::join('items', 'items.id', '=', 'sale_items.item_id')
          ->where('sale_id', $id)
          ->select('sale_items.*', 'items.upc_ean_isbn', 'item_name')
          ->get();
      $acum = CreditNote::where('sale_id', $id)
          ->where('status_id', 1)
          ->sum('amount');
      $json = array("header"=>$sale, "details"=>$details, "acum"=>$acum);
      return json_encode($json);
  }
}
