<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item, App\ItemKit, App\ItemKitItem;
use App\Almacen;
use App\Sale;
use App\Receiving;
use \Auth, \Redirect, \Validator, \Input, \Session, \Response, \DB;
use Doctrine\DBAL\Cache\ArrayStatement;
use Doctrine\DBAL\Schema\AbstractAsset;
use Illuminate\Http\Request;
use App\Document;
use App\ItemType;
use App\Serie;

class ReceivingApiController extends Controller {

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
		//$items = Item::get();
		//$itemkits = ItemKit::with('itemkititem')->get();
		//$array = array_merge($items->toArray(), $itemkits->toArray());
		//return Response::json($array);
		// return Response::json(Item::where('id','!=',$id)->get());
		$producto = ItemType::where('name','Producto')->pluck('id');

		return Response::json(Item::where('items.type_id','=',$producto)
		->where('is_kit','=','0')
		->orderBy('item_name','asc')
		->get());
	}

	public function autoComplete(Request $request){
        $search = trim($request->id);
        $productos = Item::where('stock_action', '+')
			->where('type', '!=', 2)/* Quitar servicios */
			->where('status',1)
            // ->where('item_name','LIKE',"'%$search%'")
            ->whereRaw("item_name LIKE '%".$search."%'")
            // ->OrwhereRaw("upc_ean_isbn LIKE '%".$search."%'")
            ->select('items.id','items.upc_ean_isbn','items.item_name','items.cost_price','items.low_price','items.is_kit','items.stock_action')
        ->get();
        return $productos;
    }

    public function searchCode(Request $request){
	    $product = Item::where('upc_ean_isbn', $request->id)
			->where('type', '!=', 2)
			->where('status',1)
            ->select('items.id','items.upc_ean_isbn','items.item_name','items.cost_price','items.low_price','items.is_kit','items.stock_action')
            ->first();
	    return $product;
    }



	public function sales($id)
	{
		if ($id>0) {
			/**Producto */
			$producto = ItemType::where('name','Producto')->pluck('id');

			//servicio
			$consulta3=Item::where('stock_action', '=')
			->where('status',1)
			->select('items.selling_price','items.id', 'items.upc_ean_isbn as barcode','items.item_name','items.cost_price',DB::raw('(0) as quantity'),'items.upc_ean_isbn','items.low_price', 'items.type','items.stock_action' );

			//productdos normales con existencia
			$consulta1=Item::Join('bodega_productos', 'bodega_productos.id_product', '=', 'items.id')
			->where('bodega_productos.id_bodega','=',$id)
			->where('bodega_productos.quantity','>',0)
			->where('type_id', $producto)
			->where('is_kit', 0)
			->where('status',1)
			->select('items.selling_price','items.id','items.upc_ean_isbn as barcode','items.item_name','items.cost_price','bodega_productos.quantity','items.upc_ean_isbn','items.low_price', 'items.type', 'items.stock_action');

			// Kits con su existencias
			$consulta2=Item::Join('item_kit_items','items.id','=','item_kit_items.item_kit_id')
			->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
			->where('bodega_productos.id_bodega','=',$id)
			->where('bodega_productos.quantity','>',0)
			->where('type_id', $producto)
			->where('items.is_kit', 1)
			->where('status',1)
			->select('items.selling_price','items.id','items.upc_ean_isbn as barcode',DB::Raw('concat(items.item_name," ","[Kit]") as item_name'),'items.cost_price',DB::Raw('min(bodega_productos.quantity) as quantity'),'items.upc_ean_isbn','items.low_price', 'items.type', 'items.stock_action');

			$sup = $consulta3->unionAll($consulta1)->unionAll($consulta2);
		}

		return Response::json($sup->get());

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}
	//obteniendo el nuevo id mas alto de la venta
	public function ver($id){
		$valores=Sale::join('series','series.id','=','sales.id_serie')
		->where('series.id','=',$id)->max('correlative');
		$valornuevo=$valores;
		// $valores=Sale::join('series','series.id','=','sales.id_serie');
		return $valornuevo;

	}

	public function correlativoCompra($id){
		$valores=Receiving::join('series','series.id','=','receivings.id_serie')
		->where('series.id','=',$id)->max('correlative');
		$valorNuevo=$valores;
		return $valorNuevo;
	}
	public function lists($id)
	{
		//$items = Item::get();
		//$itemkits = ItemKit::with('itemkititem')->get();
		//$array = array_merge($items->toArray(), $itemkits->toArray());
		//return Response::json($array);
		// return Response::json(Item::where('id','!=',$id)->get());

		$result = Response::json(Item::leftJoin('bodega_productos', 'bodega_productos.id_product', '=', 'items.id')
						->where('bodega_productos.id_bodega','=',$id)
						->where('items.credit', 0)
						->select('items.id','items.item_name','items.cost_price','items.selling_price','bodega_productos.quantity')
						->get());

		return $result;

		// return Response::json(Item::get());
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
