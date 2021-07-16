<?php

namespace App\Http\Controllers;

use App\Inventory;
use Illuminate\Http\Request;
use App\ItemKit, App\ItemKitItem, App\ItemKitItemTemp;
use App\Item;
use App\Categorie;
use App\Http\Requests;
use DB;
use Image;
use App\Http\Requests\ItemKitRequest;
use \Auth, \Redirect, \Validator, \Input, \Session, \Response;
use App\Http\Controllers\Controller;
use App\ItemCategory;
use App\Price;
use App\ItemPrice;
use App\BodegaProducto;


class ItemKitVueController extends Controller
{
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
    public function getItemEdit(Request $request)
    {
        return Response::json(Item::where('id', $request->id)->with('price')->with('itemCategory')->get());
    }
    public function getItemsEdit(Request $request)
    {
        // if (Auth::check()) {

        return Response::json(
        Item::join('item_kit_items','item_kit_items.item_id','=','items.id')
        ->where('item_kit_items.item_kit_id',$request->id)
        ->select('items.id','items.upc_ean_isbn','items.item_name','items.cost_price'
        ,'items.selling_price','item_kit_items.quantity')
        ->get());
        // }
        // else return 
    }
    public function index()
    {
        $itemkits = Item::where('type', 2)
            ->where('status', 1)
            ->get();
        return view('itemkitvue.index')->with('itemkits', $itemkits);
    }
    public function index_ajax(){
        return Response::json(
            Item::rightJoin('item_kit_items as ik','ik.item_kit_id','=','items.id')
            ->join('item_categories','item_categories.id','=','items.id_categorie')
            ->join('item_types','item_types.id','=','items.type_id')
            ->select('items.id','items.item_name','size','items.upc_ean_isbn',DB::raw('COUNT(items.item_name) as products'),'item_types.name as type','item_categories.name as categorie')
            ->where('items.status', 1)
            ->groupBy('items.item_name')
            ->get());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('itemkitvue.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $error = array();
            DB::beginTransaction();
            try {
                $itemkits = new Item();

                if(!empty(Item::where('item_name',$request->nameKit)->first()))
                {
                    array_push($error,'El nombre del kit ya esta en uso');
                }
                if (strlen(trim($request->code))==0)
                {
                    $itemkits->upc_ean_isbn = date('YmdHis');
                }
                else {
                    if(!empty(Item::where('upc_ean_isbn',$request->code)->first()))
                    {
                        array_push($error,'El código del kit ya esta en uso');
                    }
                    else
                    {
                        $itemkits->upc_ean_isbn = $request->code;
                    }
                }
                if(!empty($error)){
                    $returnData=array(
                        'status' => 'error',
                        'message' => $error
                    );
                    DB::rollback();
                    return Response::json($returnData, 500);
                }
                
                $itemkits->item_name = $request->nameKit;
                $itemkits->cost_price = $request->costPrice;
                $itemkits->selling_price = $request->sellingPrice;
                $itemkits->description = $request->description;
                $itemkits->id_categorie = $request->idCategoria;
                $itemkits->size = $request->size;
                $itemkits->type_id = 1;
                $itemkits->type = 2;
                $itemkits->status = 1;
                $itemkits->is_kit = 1;
                $itemkits->stock_action = "+";
                $itemkits->status = 1;
                if(!empty($request->foto))
                    if($request->foto!='no-foto.png')
                        $itemkits->avatar=$request->foto;
                $itemkits->save();
                // process receiving items
                $item_kit_items = json_decode($request->kitItems);
                foreach ($item_kit_items as $value) {
                    $item_kit_items_data = new ItemKitItem;
                    $item_kit_items_data->item_kit_id = $itemkits->id;
                    $item_kit_items_data->item_id = $value->id;
                    $item_kit_items_data->quantity = $value->quantity;
                    $item_kit_items_data->save();
                }
                $prices = json_decode($request->prices);
                $subtotal = json_decode($request->subtotal);
                $utility = json_decode($request->utility);
                $priceSale = json_decode($request->priceSale);

                foreach ($prices as $clave => $valor) {
                    $itemPrices = new ItemPrice;
                    $itemPrices->price_id = $valor->id;
                    $itemPrices->item_id = $itemkits->id;
                    $itemPrices->selling_price = $priceSale[$clave];
                    $itemPrices->low_price = $priceSale[$clave];
                    $itemPrices->pct = $utility[$clave] / 100;
                    $itemPrices->save();
                }
                $image = $request->picture;
                if (!empty($image)) {
                    $avatarName = 'item' . $itemkits->id . '.' . $request->picture->getClientOriginalExtension();

                    $request->picture->move(
                        base_path() . '/public/images/items/',
                        $avatarName
                    );
                    $img = Image::make(base_path() . '/public/images/items/' . $avatarName);
                    $img->save();
                    $itemAvatar = Item::find($itemkits->id);
                    $itemAvatar->avatar = $avatarName;
                    $itemAvatar->save();
                }
                DB::commit();
                return 'Se realizo el kit';
            } catch (\Exception $e) {
                DB::rollback();
                $returnData = array(
                    'status' => 'error',
                    'message' => array($e->getMessage())
                );
                return Response::json($returnData, 500);
                // something went wrong
            }
        }else{
            $returnData = array(
                'status' => 'error',
                'message' => array('Recarga')
            );
            return Response::json($returnData, 500);
        }
    }
    public function getCategory(Request $request)
    {
        return Response::json(ItemCategory::select('id', 'name')->orderBy('id', 'asc')->get());
    }
    public function getItems(Request $request)
    {
        return Response::json(Item::where('is_kit', 0)->wildcard()->get());
    }
    public function getPrices(Request $request)
    {
        return Response::json(Price::where('active', 1)->orderBy('order', 'asc')->with('pagos')->get());
    }
    public function getItemPrice(Request $request)
    {
        return Response::json(ItemPrice::join('prices', 'prices.id', '=', 'item_prices.price_id')
            ->join('items', 'items.id', '=', 'item_prices.item_id')
            ->where('item_id', $request->item)
            ->select(
                'item_prices.item_id',
                'item_prices.price_id',
                'prices.pct',
                'items.cost_price',
                'items.low_price',
                DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100)),2),2 ) as selling_price'),
                DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100)),2),2 ) as low_price')
            )
            ->orderBy('prices.order', 'asc')->get());
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $last_tx = Inventory::where('item_id','=',$id)
		->orderBy('created_at','desc')
		->limit(1)
		->pluck('remarks');
        $existencia = BodegaProducto::join('item_kit_items','item_kit_items.item_id','=','bodega_productos.id_product')
        ->where('item_kit_items.item_kit_id',$id)->min(DB::raw('coalesce(floor(bodega_productos.quantity/item_kit_items.quantity), 0)'));
		$items = Item::find($id);
			
		$prices = Price::active(1)->leftJoin('item_prices',function ($join) use ($id) {
			$join->on('prices.id','=','item_prices.price_id')
			->where('item_prices.item_id','=',$id);
		})									
		->select('prices.id','prices.name','prices.pct','prices.pct_min','prices.order','item_prices.selling_price','item_prices.low_price')
        ->orderBy('order','asc')->get();
        
        $productos=Item::join('item_kit_items','item_kit_items.item_id','=','items.id')
        ->leftJoin('bodega_productos','bodega_productos.id_product','=','items.id')
        ->where('item_kit_items.item_kit_id',$id)
        ->select('items.id','items.upc_ean_isbn','items.item_name','items.cost_price'
        ,'items.selling_price','item_kit_items.quantity',DB::Raw('coalesce(max(bodega_productos.quantity),0) as existencia'))
        ->groupBy('items.id')
        ->get();
			//  dd($prices);
		return view('itemkitvue.show')
		->with('data', $items)
		->with('last_tx', $last_tx)
		->with('prices',$prices)
        ->with('items',$productos)
        ->with('existencia',$existencia);
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('itemkitvue.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (Auth::check()) {
            $error = array();
            DB::beginTransaction();

            try {
                $itemkits = Item::find($request->id);;

                if(!empty(Item::where('item_name',$request->nameKit)->where('id','!=',$request->id)->first()))
                {
                    array_push($error,'El nombre del kit ya esta en uso');
                }
                if (strlen(trim($request->code))==0)
                {
                    $itemkits->upc_ean_isbn = date('YmdHis');
                }
                else {
                    if(!empty(Item::where('upc_ean_isbn',$request->code)->where('id','!=',$request->id)->first()))
                    {
                        array_push($error,'El código del kit ya esta en uso');
                    }
                    else
                    {
                        $itemkits->upc_ean_isbn = $request->code;
                    }
                }
                if(!empty($error)){
                    $returnData=array(
                        'status' => 'error',
                        'message' => $error
                    );
                    DB::rollback();
                    return Response::json($returnData, 500);
                }
                $itemkitItemEliminar = ItemKitItem::where('item_kit_id', '=', $request->id)->delete();
                $itemPricesItemEliminar = ItemPrice::where('item_id', '=', $request->id)->delete();

                
                $itemkits->item_name = $request->nameKit;
                $itemkits->cost_price = $request->costPrice;
                $itemkits->selling_price = $request->sellingPrice;
                $itemkits->description = $request->description;
                $itemkits->id_categorie = $request->idCategoria;
                $itemkits->upc_ean_isbn = $request->code;
                $itemkits->size = $request->size;
                $itemkits->type_id = 1;
                $itemkits->type = 2;
                $itemkits->is_kit = 1;
                $itemkits->status = 1;
                $itemkits->stock_action = "+";
                $itemkits->status = 1;
                $itemkits->save();
                // process receiving items
                $item_kit_items = json_decode($request->kitItems);
                foreach ($item_kit_items as $value) {
                    $item_kit_items_data = new ItemKitItem;
                    $item_kit_items_data->item_kit_id = $itemkits->id;
                    $item_kit_items_data->item_id = $value->id;
                    $item_kit_items_data->quantity = $value->quantity;
                    $item_kit_items_data->save();
                }
                $prices = json_decode($request->prices);
                $subtotal = json_decode($request->subtotal);
                $utility = json_decode($request->utility);
                $priceSale = json_decode($request->priceSale);

                foreach ($prices as $clave => $valor) {
                    $itemPrices = new ItemPrice;
                    $itemPrices->price_id = $valor->id;
                    $itemPrices->item_id = $itemkits->id;
                    $itemPrices->selling_price = $priceSale[$clave];
                    $itemPrices->low_price = $priceSale[$clave];
                    $itemPrices->pct = $utility[$clave] / 100;
                    $itemPrices->save();
                }
                $image = $request->picture;
                if (!empty($image)) {
                    $avatarName = 'item' . $itemkits->id . '.' . $request->picture->getClientOriginalExtension();

                    $request->picture->move(
                        base_path() . '/public/images/items/',
                        $avatarName
                    );
                    $img = Image::make(base_path() . '/public/images/items/' . $avatarName);
                    $img->save();
                    $itemAvatar = Item::find($itemkits->id);
                    $itemAvatar->avatar = $avatarName;
                    $itemAvatar->save();
                }
                DB::commit();
                return 'Se actualizo el kit';
            } catch (\Exception $e) {
                DB::rollback();
                $returnData = array(
                    'status' => 'error',
                    'message' => array($e->getMessage())
                );
                return Response::json($returnData, 500);
                // something went wrong
            }
        }else{
            $returnData = array(
                'status' => 'error',
                'message' => array('Recarga')
            );
            return Response::json($returnData, 500);
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $itemkitItem = ItemKitItem::where('item_kit_id', '=', $id)->delete();
            $itemPrices = ItemPrice::where('item_id', '=', $id)->delete();
            $itemFirst = Item::find($id);
            $copyItem = $itemFirst;
            $itemFirst->delete();
            DB::commit();
            try {
                unlink(base_path() . '/public/images/items/' . $copyItem->avatar);
            } catch (\Exception $e) {
            }

            Session::flash('message', trans('itemkit.kit_delete'));
            return Redirect::to('item-kits-vue');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            try {
                $itemFirst = Item::find($id);
                $itemFirst->status = 0;
                $itemFirst->save();
                Session::flash('message', trans('itemkit.kit_delete'));
                return Redirect::to('item-kits-vue');
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('message', trans('itemkit.integrity_violation') . ' [' . $itemFirst->item_name . ']');
                Session::flash('alert-class', 'alert-error');
                return Redirect::to('item-kits-vue');
            }
        }
    }
    public function duplicate(){
        return view('itemkitvue.duplicate');
    }   
}
