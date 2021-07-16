<?php

namespace App\Http\Controllers;

use App\AlmacenUser;
use App\Item;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ItemsTrait;
use DB, Session;
use Illuminate\Support\Facades\Auth;

class ItemApiController extends Controller
{
    use ItemsTrait;
    public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('parameter');
	}

    public function code(Request $request)
    {
        $search = trim(strtoupper($request->codigo));
        if  ($search!=='')
        {
            if ($request->type_search=='precio'){
                $items=Item::leftJoin('item_prices','items.id','=','item_prices.item_id')
                    ->Join('prices','item_prices.price_id','=','prices.id')
                    // join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
                    ->join('item_types','items.type_id', '=', 'item_types.id')
                    ->whereRaw("items.upc_ean_isbn LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name','cost_price',DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'),'avatar','items.expiration_date','items.minimal_existence','item_types.name as type', 'prices.name as price_name', 'items.is_kit'])
                    ->orderBy('items.id','asc')
                    ->orderBy('prices.id','asc')
                    ->get();
            }
            else{
                $almacens = AlmacenUser::whereId_usuario(Auth::user()->id)->lists('id_bodega');
                $administrador =Session::get('administrador');
                $productos=Item::leftJoin('bodega_productos','bodega_productos.id_product','=','items.id')
                    ->join('almacens','almacens.id','=','bodega_productos.id_bodega')
                    ->whereRaw("items.upc_ean_isbn LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name', 'bodega_productos.quantity','bodega_productos.id_bodega' ,'items.minimal_existence','almacens.name as almacen_name', 'items.is_kit'])
                    ->orderBy('items.id','asc')
                    ->where('items.is_kit', 0)
                    ->orderBy('almacens.id','asc');

                $kits=Item::join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
                    ->leftJoin('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
                    ->join('almacens','almacens.id','=','bodega_productos.id_bodega')
                    ->whereRaw("items.upc_ean_isbn LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name', DB::Raw('min(coalesce(floor(bodega_productos.quantity/item_kit_items.quantity), 0)) as quantity'),'bodega_productos.id_bodega' ,'items.minimal_existence','almacens.name as almacen_name', 'items.is_kit'])
                    ->orderBy('items.id','asc')
                    ->where('items.is_kit', 1)
                    ->orderBy('almacens.id','asc');
                if ($administrador){
                    $items = $productos->unionAll($kits)->get();
                }
                else{
                    $items = $productos->unionAll($kits)->whereIn('almacens.id', $almacens)->get();
                }

            }
            // existencias & kardex


            // $items = $this->getItemsByCode($search);
            return view('item.search_resultset')
                ->with('type', $request->type_search)
                ->with('items',$items);
        }
        else  {
            return 'No se encontro información';
        }
    }


    public function name(Request $request)
    {
        $search = trim(strtoupper($request->item_name));
        // echo 'search |'.$search.'| bodega |'.$bodega_id.'| strlen |'.strlen($search).'|';
        // if ( (strlen($search)>0) || ($bodega_id!="") )
        if  ($search!=='')
        {
            if ($request->type_search=='precio'){
                $items=Item::leftJoin('item_prices','items.id','=','item_prices.item_id')
                    ->Join('prices','item_prices.price_id','=','prices.id')
                    // join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
                    ->join('item_types','items.type_id', '=', 'item_types.id')
                    ->whereRaw("items.item_name LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name','cost_price',DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'),'avatar','items.expiration_date','items.minimal_existence','item_types.name as type', 'prices.name as price_name'])
                    ->orderBy('items.id','asc')
                    ->orderBy('prices.id','asc')
                    ->get();
            }
            else{
                $almacens = AlmacenUser::whereId_usuario(Auth::user()->id)->lists('id_bodega');
                $administrador =Session::get('administrador');

                $kits=Item::join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
                    ->leftJoin('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
                    ->join('almacens','almacens.id','=','bodega_productos.id_bodega')
                    ->whereRaw("items.item_name LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name', DB::Raw('min(coalesce(floor(bodega_productos.quantity/item_kit_items.quantity), 0)) as quantity'),'bodega_productos.id_bodega' ,'items.minimal_existence','almacens.name as almacen_name'])
                    ->orderBy('items.id','asc')
                    ->where('items.is_kit', 1)
                    ->orderBy('almacens.id','asc');
                $products=Item::leftJoin('bodega_productos','bodega_productos.id_product','=','items.id')
                    ->leftJoin('almacens','almacens.id','=','bodega_productos.id_bodega')
                    ->whereRaw("items.item_name LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name', 'bodega_productos.quantity','bodega_productos.id_bodega' ,'items.minimal_existence','almacens.name as almacen_name'])
                    ->orderBy('items.id','asc')
                    ->where('items.is_kit', 0)
                    ->orderBy('almacens.id','asc');
                if ($administrador){
                    $items = $products->unionAll($kits)->get();
                }
                else{
                    $items = $products->unionAll($kits)->whereIn('almacens.id', $almacens)->get();
                }
            }
    // dd($items);
            return view('item.search_resultset')
                ->with('type', $request->type_search)
                ->with('items',$items);
        }
        else  {
            return 'No se encontro información';
        }
    }

    public function other(Request $request)
    {
        $search = trim(strtoupper($request->other));
        // echo 'search |'.$search.'| bodega |'.$bodega_id.'| strlen |'.strlen($search).'|';
        // if ( (strlen($search)>0) || ($bodega_id!="") )
        if  ($search!=='')
        {
            if ($request->type_search=='precio'){
                $items=Item::leftJoin('item_prices','items.id','=','item_prices.item_id')
                    ->Join('prices','item_prices.price_id','=','prices.id')
                    // join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
                    ->join('item_types','items.type_id', '=', 'item_types.id')
                    ->whereRaw("items.upc_ean_isbn LIKE '%".$search."%'")
                    ->orWhereRaw("items.item_name LIKE '%".$search."%'")
                    ->orWhereRaw("items.description LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name','cost_price',DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'),'avatar','items.expiration_date','items.minimal_existence','item_types.name as type', 'prices.name as price_name']);
            }
            else{
                $almacens = AlmacenUser::whereId_usuario(Auth::user()->id)->lists('id_bodega');
                $administrador =Session::get('administrador');

                $kits=Item::join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
                    ->leftJoin('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
                    ->join('almacens','almacens.id','=','bodega_productos.id_bodega')
                    ->whereRaw("items.upc_ean_isbn LIKE '%".$search."%'")
                    ->orWhereRaw("items.item_name LIKE '%".$search."%'")
                    ->orWhereRaw("items.description LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name', DB::Raw('min(coalesce(floor(bodega_productos.quantity/item_kit_items.quantity), 0)) as quantity'),'bodega_productos.id_bodega' ,'items.minimal_existence','almacens.name as almacen_name'])
                    ->orderBy('items.id','asc')
                    ->where('items.is_kit', 1)
                    ->orderBy('almacens.id','asc');

                $products=Item::leftJoin('bodega_productos','bodega_productos.id_product','=','items.id')
                    ->leftJoin('almacens','almacens.id','=','bodega_productos.id_bodega')
                    ->where('items.is_kit', 0)
                    ->whereRaw("items.upc_ean_isbn LIKE '%".$search."%'")
                    ->orWhereRaw("items.item_name LIKE '%".$search."%'")
                    ->orWhereRaw("items.description LIKE '%".$search."%'")
                    ->select(['items.id','items.stock_action','items.upc_ean_isbn','items.description','item_name', 'bodega_productos.quantity','bodega_productos.id_bodega' ,'items.minimal_existence','almacens.name as almacen_name'])
                    ->orderBy('almacens.id','asc');
                if ($administrador){
                    $items = $products->unionAll($kits)->get();
                }
                else{
                    $items = $products->unionAll($kits)->whereIn('almacens.id', $almacens)->get();
                }

            }

            return view('item.search_resultset')
                ->with('type', $request->type_search)
                ->with('items',$items);
        }
        else  {
            return 'No se encontro información';
        }
    }

    public function get_code_by_id($id)
    {
        if  ($id!=='')
        {
            // return json_encode(Item::findOrFail($id)->upc_ean_isbn);
            return Item::where('id',$id)->max('upc_ean_isbn');
        }
        else  {
            return json_encode('-1');
        }
    }

}
