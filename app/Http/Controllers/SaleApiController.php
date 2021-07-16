<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;
use DB;
use App\Traits\ItemsTrait;

class SaleApiController extends Controller {

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
	public function index(Request $request)
	{
		$search = trim(strtoupper($request->id));
		$bodega_id = $request->bodega_id;
		// echo 'search |'.$search.'| bodega |'.$bodega_id.'| strlen |'.strlen($search).'|';
		// if ( (strlen($search)>0) || ($bodega_id!="") )
        if ( ($search!=='') && ($bodega_id!="") )
        {
			$servicios = Item::where('stock_action', '=')
			->where('items.status','=',1)
			->whereRaw("item_name LIKE '%".$search."%'")
			->select('items.id','items.upc_ean_isbn','items.item_name','items.selling_price','items.low_price','items.is_kit','items.stock_action',DB::raw('(0) as quantity'));
			// dd($servicios);

			$productos = Item::join('bodega_productos','items.id','=','bodega_productos.id_product')			
			->where('bodega_productos.id_bodega',$bodega_id)
			->where('bodega_productos.quantity','>',0)
			->where('items.status','=',1)
			->whereRaw("item_name LIKE '%".$search."%'")
			->select('items.id','items.upc_ean_isbn','items.item_name','items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity')->limit(10);
			// dd($items);

			$kits=Item::Join('item_kit_items','items.id','=','item_kit_items.item_kit_id')
			->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
			->where('items.status','=',1)
			->whereRaw("item_name LIKE '%".$search."%'")
			->where('bodega_productos.id_bodega','=',$bodega_id)
			->where('bodega_productos.quantity','>',0)
			->where('type_id', 1)
			->where('items.is_kit', 1)
			->select('items.id','items.upc_ean_isbn',DB::Raw('concat(items.item_name," ","[Kit]") as item_name'),'items.selling_price','items.low_price','items.is_kit','items.stock_action',DB::Raw('min(bodega_productos.quantity) as quantity'))
			->groupBy('items.id');

			
			$items = $servicios->unionAll($productos)->unionAll($kits)->get();

            return view('sale.searchajax')->with('items',$items);
		}
		else  {
			return 'No se encontro información';	
		}
	}

	public function autocomplete(Request $request)
	{
		$search = trim($request->id);
		$bodega_id = $request->bodega_id;
		// exit ('search |'.$search.'| bodega |'.$bodega_id.'| strlen |'.strlen($search).'|');

		if ( (strlen($search)>0) || ($bodega_id!="") )
        {
			$servicios = Item::where('stock_action', '=')
			->where('items.status','=',1)
			->whereRaw("item_name LIKE '%".$search."%'")
			// ->OrwhereRaw("upc_ean_isbn LIKE '%".$search."%'")
			->select('items.id','items.upc_ean_isbn','items.item_name','items.selling_price','items.low_price','items.is_kit','items.stock_action',DB::raw('"=" as quantity'));
			// dd($servicios);

			$productos = Item::join('bodega_productos','items.id','=','bodega_productos.id_product')			
			->where('bodega_productos.id_bodega',$bodega_id)
			->where('bodega_productos.quantity','>',0)
			->where('items.status','=',1)
			->whereRaw("item_name LIKE '%".$search."%'")
			// ->OrwhereRaw("upc_ean_isbn LIKE '%".$search."%'")
			->select('items.id','items.upc_ean_isbn','items.item_name','items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity')->limit(10);
			// ->select('items.id','items.upc_ean_isbn',DB::raw('concat(upc_ean_isbn," | ",items.item_name) as iten_name'),'items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity')->limit(10);
			// dd($items);

			$kits=Item::Join('item_kit_items','items.id','=','item_kit_items.item_kit_id')
			->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
			->where('items.status','=',1)
			->whereRaw("item_name LIKE '%".$search."%'")
			// ->OrwhereRaw("upc_ean_isbn LIKE '%".$search."%'")
			->where('bodega_productos.id_bodega',$bodega_id)
			->where('bodega_productos.quantity','>',0)
			->where('type_id', 1)
			->where('items.is_kit', 1)
			->select('items.id','items.upc_ean_isbn','items.item_name','items.selling_price','items.low_price','items.is_kit','items.stock_action',DB::Raw('min(bodega_productos.quantity) as quantity'))
			->groupBy('items.id');

			
			$items = $servicios->unionAll($productos)->unionAll($kits)->get();

            return $items;
		}
		else  {
			return 'No se encontro información';	
		}
	}
	/* Busqueda de items por bodega, id forma de pago y autocomplete */
	public function autocompleteStoragePago(Request $request)
	{
		$search = trim($request->id);
		$bodega_id = $request->bodega_id;
		$pago = trim($request->id_pago);
		
		return $this->getAutocomItemsAndServicesByStoragePago($search,$bodega_id,$pago);		
	}
	/* Busqueda de items por bodega, id precio y autocomplete */
	public function autocompleteStoragePrice(Request $request)
	{
		$search = trim($request->id);
		$bodega_id = $request->bodega_id;
		$id_price = trim($request->id_price);
		
		return $this->getAutocomItemsAndServicesByStoragePrice($search,$bodega_id,$id_price);		
	}
	

	public function search_code(Request $request)
	{
		$search = trim(strtoupper($request->id));
		$bodega_id = $request->bodega_id;

        if ( ($search!="") || ($bodega_id!="") )
        {
			$servicios = Item::where('stock_action', '=')
			->where('items.status','=',1)
			->where('upc_ean_isbn','=',$search)
			->select('items.id','items.upc_ean_isbn','items.item_name','items.selling_price','items.low_price','items.is_kit','items.stock_action',DB::raw('(0) as quantity'));
			// dd($servicios);

			$productos = Item::join('bodega_productos','items.id','=','bodega_productos.id_product')			
			->where('bodega_productos.id_bodega',$bodega_id)
			->where('items.status','=',1)
			->where('upc_ean_isbn','=',$search)
			->where('bodega_productos.quantity','>',0)
			->select('items.id','items.upc_ean_isbn','items.item_name','items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity');
			// dd($items);

			$kits=Item::Join('item_kit_items','items.id','=','item_kit_items.item_kit_id')
			->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
			->where('items.status','=',1)
			->where('upc_ean_isbn','=',$search)
			->where('bodega_productos.id_bodega','=',$bodega_id)
			->where('bodega_productos.quantity','>',0)
			->where('type_id', 1)
			->where('items.is_kit', 1)
			->select('items.id','items.upc_ean_isbn',DB::Raw('concat(items.item_name," ","[Kit]") as item_name'),'items.selling_price','items.low_price','items.is_kit','items.stock_action',DB::Raw('min(bodega_productos.quantity) as quantity'))
			->groupBy('items.id');

			
			$items = $servicios->unionAll($productos)->unionAll($kits)->get();
			// dd($items);

			// return view('sale.searchajax')->with('items',$items);
			return $items->first();
		}
		else  {
			return 'No se encontro información';	
		}
	}

	/* Buscar articulos por : codigo, bodega y id de forma de pago */
	public function search_code_storage_pago(Request $request)
	{
		$search = trim(strtoupper($request->id));
		$bodega_id = $request->bodega_id;
		$pago = trim($request->id_pago);

		return $this->getSearchCodeItemsAndServicesByStoragePago($search,$bodega_id,$pago);       
	}
	/* Buscar articulos por : codigo, bodega y id precio */
	public function search_code_storage_price(Request $request)
	{
		$search = trim(strtoupper($request->id));
		$bodega_id = $request->bodega_id;
		$id_price = trim($request->id_price);

		return $this->getSearchCodeItemsAndServicesByStoragePrice($search,$bodega_id,$id_price);       
	}

	public function search_id_storage_price(Request $request)
	{
		$search = trim(strtoupper($request->id));
		$bodega_id = $request->bodega_id;
		$id_price = trim($request->id_price);

		return $this->getSearchIdItemsAndServicesByStoragePrice($search,$bodega_id,$id_price);       
	}

	

}
