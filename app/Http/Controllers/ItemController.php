<?php namespace App\Http\Controllers;

use App\GeneralParameter;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use App\ItemCategory;
use App\ItemType;
use App\Inventory;
use App\Almacen;
use App\BodegaProducto;
use App\ItemPrice;
use App\User;
use App\UnitMeasure;
use App\Http\Requests\ItemRequest;
use App\Price;
use App\StateCellar;
use \Auth, \Redirect, \Validator, \Input;
use Illuminate\Support\Facades\Session;
use Image;
use Illuminate\Http\Request;
use DB;
use App\Traits\ItemsTrait;

class ItemController extends Controller
{

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
    public function index($id = 0)
    {
        $prices = Price::active(1)->get();
        $status = StateCellar::general()->get();

        return view('item.index')
            ->with('prices', $prices)
            ->with('status', $status)
            ->with('selected', $id)
            ->with('statusSelected', 1);
    }

    public function index_services($id = 0)
    {
        $prices = Price::active(1)->get();
        return view('item.index-services')
            ->with('prices', $prices)
            ->with('selected', $id);
    }


    public function index_ajax()
    {

        $consulta1 = Item::join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types as tipo', 'items.type_id', '=', 'tipo.id');

        $consulta1->where('items.type_id', '=', 1);/*Productos*/
        $consulta1->where('items.is_kit', '=', 0);
        $consulta1->where('items.status', '=', 1);
        $consulta1->select('items.avatar', 'items.id', 'items.item_name', 'items.selling_price', 'item_categories.id as category_id', 'item_categories.name as category', 'items.expiration_date', 'items.description', 'items.cost_price', 'items.minimal_existence', 'items.upc_ean_isbn', 'items.low_price', 'items.type', 'items.stock_action', 'items.type_id', 'tipo.name as tipo', 'items.size as size', DB::Raw('"" as blanco'));
        $consulta1->orderBy('items.upc_ean_isbn');

        return json_encode($consulta1->get());
    }


    public function index_services_ajax()
    {

        $consulta1 = Item::join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types as tipo', 'items.type_id', '=', 'tipo.id');

        $consulta1->where('items.type_id', '=', 2);/*Servicios*/
        $consulta1->where('items.is_kit', '=', 0);
        $consulta1->where('items.status', '=', 1);
        $consulta1->select('items.avatar', 'items.id', 'items.item_name', 'items.selling_price', 'item_categories.id as category_id', 'item_categories.name as category', 'items.expiration_date', 'items.description', 'items.cost_price', 'items.minimal_existence', 'items.upc_ean_isbn', 'items.low_price', 'items.type', 'items.stock_action', 'items.type_id', 'tipo.name as tipo', 'items.size as size', DB::Raw('"" as blanco'));
        $consulta1->orderBy('items.upc_ean_isbn');

        return json_encode($consulta1->get());
    }

    // public function index_services_ajax_by_storage($id)
    public function index_services_ajax_by_storage(Request $request)
    {
        $id = trim(strtoupper($request->id));

        return json_encode($this->getItemsAndServicesByStorage($id));
    }

    public function index_services_ajax_by_storage_pago(Request $request)
    {
        $id = trim(strtoupper($request->id));
        $pago = trim(strtoupper($request->id_pago));

        return json_encode($this->getItemsAndServicesByStoragePago($id, $pago));
    }

    public function index_services_ajax_by_storage_price(Request $request)
    {
        $id = trim(strtoupper($request->id));
        $price = trim(strtoupper($request->id_price));

        return json_encode($this->getItemsAndServicesByStoragePrice($id, $price));
    }

    public function index_services_ajax_by_price(Request $request)
    {
        $price = trim(strtoupper($request->price_id));

        return json_encode($this->getServicesByPrice($price));
        // return json_encode($this->getItemsAndServicesByPrice($price));

    }

    public function index_ajax_by_price(Request $request)
    {
        $price = trim(strtoupper($request->price_id));
        $status = trim(strtoupper($request->status));

        return json_encode($this->getItemsByPrice($price, $status));
    }

    public function index_services_ajax_by_pago(Request $request)
    {
        $price = trim(strtoupper($request->price_id));

        return json_encode($this->getItemsAndServicesByPago($price));
    }


    public function index_services_ajax_all()
    {

        return json_encode($this->getItemsAndServicesAll());
    }

    public function index_items_ajax_all()
    {

        return json_encode($this->getItemsAll());
    }

    public function getPricesUnits(Request $request){
        $prices = ItemPrice::join('unit_measures as um', 'um.id', '=', 'item_prices.unit_id')
                ->whereItem_id($request->item_id)
                ->wherePrice_id($request->price_id)
                ->select('item_prices.*', 'um.name as unidad')
                ->get();
        return $prices;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categorie_product = ItemCategory::products()->orderBy('id', 'asc')
            ->lists('name', 'id');
        $type = ItemType::products()->lists('name', 'id');
        $prices = Price::active(1)->orderBy('order', 'asc')->get();
        $units = UnitMeasure::whereStatus_id(1)->get();
        return view('item.create')
            ->with('categorie_product', $categorie_product)
            ->with('type', $type)
            ->with('prices', $prices)
            ->with('units', $units)
            // ->with('expiration_date',$expiration_date)
            ->with('title', trans('item.new_item'));

    }

    public function create_service()
    {
        $categorie_product = ItemCategory::services()->orderBy('id', 'asc')
            ->lists('name', 'id');

        $type = ItemType::services()->lists('name', 'id');
        $prices = Price::active(1)->orderBy('order', 'asc')->get();
        $units = UnitMeasure::whereStatus_id(1)->get();
        return view('item.create')
            ->with('categorie_product', $categorie_product)
            ->with('type', $type)
            ->with('units', $units  )
            ->with('prices', $prices)
            ->with('title', trans('item.new_sevice'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ItemRequest $request)
    {
        DB::beginTransaction();
        try {
            $items = new Item;
            $tipo;
            if ($request->item_type == "on") {
                $tipo = true;
            } else {
                $tipo = false;
            }
            if (strlen(trim(Input::get('upc_ean_isbn'))) == 0) {
                $items->upc_ean_isbn = date('YmdHis');
            } else {
                $items->upc_ean_isbn = Input::get('upc_ean_isbn');
            }

            $show_budget_price = GeneralParameter::where('name', 'Precios de presupuestos en articulos')
                ->first();

            $validator = Validator::make($request->all(), [
                'upc_ean_isbn' => 'unique:items',
                'item_name' => 'required|unique:items',
                'cost_price' => 'required',
                'avatar' => 'mimes:jpeg,bmp,png'
            ]);


            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }

            $items->item_name = Input::get('item_name');
            $items->size = Input::get('size');
            $items->description = Input::get('description');
            $items->cost_price = Input::get('cost_price');
            // $items->selling_price = Input::get('selling_price');
            $items->quantity = 0;
            $items->is_kit = 0;
            $items->credit = $tipo;
            $items->id_categorie = Input::get('id_categorie');
            $items->type_id = Input::get('type_id');
            if (Input::get('expiration_date') != "") {
                $items->expiration_date = Input::get('expiration_date');
            }
            if (Input::get('minimal_existence') != "") {
                $items->minimal_existence = Input::get('minimal_existence');
            }
            if (Input::get('waste_percent') != "") {
                $items->waste_percent = Input::get('waste_percent');
            }
            $items->stock_action = Input::get('stock_action');
            $items->status = 1;
            if ($show_budget_price->active) {
                $items->budget_cost = $request->budget_cost;
                $items->days_valid = $request->days_valid;
                $items->monts_valid = $request->months_valid;
                $items->updated_budget_cost_at = date('Y-m-d');
            }
            if (isset($request->wildcard)){
                $items->wildcard = true;
            }
            if (isset($request->approach_type)){
                $items->approach_type = $request->approach_type;
            }
            $items->save();

            /**Armar el array para guardar la relación */
            // $cuantos_precios = Input::get('cuantos_precios');
            // for ($i = 0; $i <= $cuantos_precios; $i++) {
            //
            //     $price_id = Input::get('price_id_' . $i);
            //     $items->prices()->attach($price_id, ['pct' => Input::get('profit' . $price_id),
            //         'selling_price' => Input::get('selling_price' . $price_id),
            //         'low_price' => Input::get('low_price' . $price_id)]);
            //
            // };

            $prices = json_decode($request->prices_details);

            if (!isset($prices) || count($prices)==0) {
              throw new \Exception("Debe agregar precios al Producto");
            }
            $def = false;
            foreach ($prices as $key => $price) {
                  $items->prices()->attach($price->price_id, ['pct' => $price->profit,
                      'selling_price' => $price->selling_price,
                      'low_price' => $price->selling_price,
                      'quantity'=>$price->quantity,
                      'default'=>$price->default,
                      'unit_id'=>$price->unit_id]);
            }

            $image = $request->file('avatar');
            if (!empty($image)) {
                $avatarName = 'item' . $items->id . '.' .
                    $request->file('avatar')->getClientOriginalExtension();

                $request->file('avatar')->move(
                    base_path() . '/public/images/items/', $avatarName
                );
                $img = Image::make(base_path() . '/public/images/items/' . $avatarName);
                $img->save();
                $itemAvatar = Item::find($items->id);
                $itemAvatar->avatar = $avatarName;
                $itemAvatar->save();
            }
            // Session::flash('message', 'You have successfully added item');
            Session::flash('message', 'Producto agregado correctamente');
            DB::commit();
        }
        catch (\Exception $ex){
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

        if (Input::get('type_id') == 2) /*Servicio */ {
            return Redirect::to('services');
        }
        return Redirect::to('items');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id, $price = 0)
    {
        $almacens = Almacen::where('id_state', 1)
            ->asigned()
            ->get();
        $prices = Price::where('active', 1)
            ->get();
        $almacen = $id;
        if ($almacen == 0) {
            $almacen = $almacens[0]->id;
        }

        if ($price == 0) {
            $price = $prices[0]->id;
        }
        $show_budget_price = GeneralParameter::where('name', 'Precios de presupuestos en articulos')
            ->first();
        $item = Item::join('bodega_productos', 'bodega_productos.id_product', '=', 'items.id')
            ->leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types', 'items.type_id', '=', 'item_types.id')
            ->where('bodega_productos.id_bodega', '=', $almacen)
            ->where('item_prices.price_id', $price)
            ->where('items.is_kit', 0)
            ->wildcard()
            ->select(['items.id', 'items.is_kit as stock_action', 'items.upc_ean_isbn', 'items.description', 'item_name', 'cost_price', 'bodega_productos.quantity', 'item_categories.name', 'avatar', 'items.expiration_date', 'items.minimal_existence', 'item_types.name as tipo', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price')]);

        $kits = Item::Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
            ->leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types', 'items.type_id', '=', 'item_types.id')
            ->where('bodega_productos.id_bodega', $almacen)
            ->where('item_prices.price_id', $price)
            ->where('bodega_productos.quantity', '>', 0)
            ->where('type_id', 1)
            ->where('items.is_kit', 1)
            ->where('items.status', '=', 1)
            ->select(['items.id', 'items.is_kit as stock_action', 'items.upc_ean_isbn', 'items.description', DB::Raw('concat(item_name," - [KIT]") as item_name'), 'items.cost_price', DB::Raw('min(coalesce(floor(bodega_productos.quantity/item_kit_items.quantity), 0)) as quantity'), 'item_categories.name', 'avatar', 'items.expiration_date', 'items.minimal_existence', 'item_types.name as tipo', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price')])
            ->groupBy('items.id');

        $items = $item->unionAll($kits)->get();

        return view('report.listProduct')
            ->with('bod', $almacen)
            ->with('prices', $prices)
            ->with('show_budget_price', $show_budget_price)
            ->with('almacen', $almacens)
            ->with('price', $price)
            ->with('item', $items);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $items = Item::find($id);
        // dd($items->prices);
        // dd($items);
        $categorie_product = ItemCategory::category($items->type_id)
            ->orderBy('id', 'asc')
            ->lists('name', 'id');

        $type = ItemType::category($items->type_id)
            ->orderBy('id', 'asc')
            ->lists('name', 'id');

        $title = trans('item.update_item');
        if ($items->type_id == 2) {
            $title = trans('item.update_service');
        }
        if ($items->type_id == 3) {
            $title = trans('item.update_furniture');
        }

        $prices = Price::active(1)->orderBy('order', 'asc')->get();
        $show_budget_price = GeneralParameter::where('name', 'Precios de presupuestos en articulos')
            ->first();
        #region VERIFICAR VALIDEZ DE PRECIO DE INVENTARIO
        $valid_price = false;
        if ($show_budget_price->active) {
            $valid_price = $this->verifyBudgetCost($id);
        }
        #endregion

        $units = UnitMeasure::whereStatus_id(1)->get();

        #region Obtener los precios asignados al artículo
        $item_prices = ItemPrice::whereItem_id($id)
          ->join('unit_measures as um', 'um.id', '=', 'item_prices.unit_id')
          ->Join('prices as pr', 'pr.id', '=', 'item_prices.price_id')
          ->select('item_prices.*', DB::raw('concat(um.name, " | ", um.abbreviation) as unidad'), 'pr.name as price')
          ->get();
        #endregion

        // dd($prices);
        return view('item.edit')
            ->with('item', $items)
            ->with('type', $type)
            ->with('valid_price', $valid_price)
            ->with('show_budget_price', $show_budget_price)
            ->with('categorie_product', $categorie_product)
            ->with('title', $title)
            ->with('item_prices', $item_prices)
            ->with('units', $units)
            ->with('prices', $prices);
    }

    public function detail($id)
    {
        $last_tx = Inventory::where('item_id', '=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->pluck('remarks');
        // dd($last_tx);
        $existencia = BodegaProducto::where('id_product', $id)->sum('quantity');
        $items = Item::find($id);

        // Obtenemos los precios de los items
        $prices = Price::active(1)->leftJoin('item_prices', function ($join) use ($id) {
            $join->on('prices.id', '=', 'item_prices.price_id')
                ->where('item_prices.item_id', '=', $id);
        })
            ->leftJoin('unit_measures', 'unit_measures.id', '=', 'item_prices.unit_id')
            ->select('prices.id', 'prices.name', 'prices.pct', 'prices.pct_min', 'prices.order', 'item_prices.selling_price', 'item_prices.low_price', DB::raw('unit_measures.name as unidad'))
            ->orderBy('order', 'asc')->get();

        $user = User::find(Auth::user()->id);
        $administrador = false;
        foreach ($user->roles as $rol) {
            if ($rol->admin == 1) {
                $administrador = true;
                break;
            }
        }
        $show_budget_price = GeneralParameter::where('name', 'Precios de presupuestos en articulos')
            ->first();
        //  dd($prices);

        #region VERIFICAR VALIDEZ DE PRECIO DE INVENTARIO
        $valid_price = false;
        if ($show_budget_price->active) {
            $valid_price = $this->verifyBudgetCost($id);
        }
        #endregion


        return view('item.show')
            ->with('data', $items)
            ->with('show_budget_price', $show_budget_price)
            ->with('last_tx', $last_tx)
            ->with('valid_price', $valid_price)
            ->with('existencia', $existencia)
            ->with('prices', $prices)
            ->with('admin', $administrador);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(ItemRequest $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $show_budget_price = GeneralParameter::where('name', 'Precios de presupuestos en articulos')
                ->first();
            $typeName = ItemType::find(Input::get('type_id'));
            if (Input::get('type') == "on") {
                $tipo = true;
            } else {
                $tipo = false;
            }
            $validator = Validator::make($request->all(), [
                'upc_ean_isbn' => 'unique:items,upc_ean_isbn,' . $id,
                'item_name' => 'required|unique:items,item_name,' . $id,
                'cost_price' => 'required',
                // 'selling_price' => 'required',
                'avatar' => 'mimes:jpeg,bmp,png'
            ]);


            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }

            $items = Item::find($id);

            // save update
            $items->upc_ean_isbn = Input::get('upc_ean_isbn');
            $items->item_name = Input::get('item_name');
            $items->size = Input::get('size');
            $items->description = Input::get('description');
            $items->cost_price = Input::get('cost_price');
            $items->selling_price = Input::get('selling_price');
            $items->minimal_existence = Input::get('minimal_existence');
            $items->status = Input::get('status');
            $items->quantity = 0;
            $items->id_categorie = Input::get('id_categorie');
            $items->low_price = Input::get('low_price');
            $items->credit = $tipo;
            $items->type_id = Input::get('type_id');
            $items->profit = Input::get('profit');
            if (Input::get('expiration_date') != "") {
                $items->expiration_date = Input::get('expiration_date');
            }
            if (Input::get('minimal_existence') != "") {
                $items->minimal_existence = Input::get('minimal_existence');
            }
            if (Input::get('waste_percent') != "") {
                $items->waste_percent = Input::get('waste_percent');
            }
//            $items->stock_action = Input::get('stock_action');


            if ($show_budget_price->active) {
                $items->budget_cost = $request->budget_cost;
                $items->days_valid = $request->days_valid;
                $items->monts_valid = $request->monts_valid;
                $items->updated_budget_cost_at = date('Y-m-d');
            }
            if (isset($request->wildcard)){
                $items->wildcard = true;
            }
            else{
                $items->wildcard = false;
            }
            if (isset($request->approach_type)){
                $items->approach_type = $request->approach_type;
            }

            $items->update();
            /**
             * Guardar los precios
             */

            // $cuantos_precios = Input::get('cuantos_precios');
            // for ($i = 0; $i <= $cuantos_precios; $i++) {
            //     $price_id = Input::get('price_id_' . $i);
            //     $items->prices()->detach($price_id);
            //     $items->prices()->attach($price_id, ['pct' => Input::get('profit' . $price_id),
            //         'selling_price' => Input::get('selling_price' . $price_id),
            //         'low_price' => Input::get('low_price' . $price_id)]);
            // };

            $prices = json_decode($request->prices_details);


            if (count($prices)==0) {
              throw new \Exception("Debe agregar precios al Producto");
            }
            ItemPrice::whereItem_id($id)->delete();
            foreach ($prices as $key => $price) {
                  $items->prices()->attach($price->price_id, ['pct' => $price->profit,
                      'selling_price' => $price->selling_price,
                      'low_price' => $price->selling_price,
                      'quantity'=>$price->quantity,
                      'default'=>$price->default,
                      'unit_id'=>$price->unit_id]);
            }


            // process avatar
            $image = $request->file('avatar');
            if (!empty($image)) {
                $avatarName = 'item' . $id . '.' .
                    $request->file('avatar')->getClientOriginalExtension();

                $request->file('avatar')->move(
                    base_path() . '/public/images/items/', $avatarName
                );
                $img = Image::make(base_path() . '/public/images/items/' . $avatarName);
                $img->save();
                $itemAvatar = Item::find($id);
                $itemAvatar->avatar = $avatarName;
                $itemAvatar->save();
            }
            // Session::flash('message', 'You have successfully updated item');
            Session::flash('message', $typeName->name . ' actualizado correctamente');
            DB::commit();
        }
        catch (\Exception $ex){
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }
        if (Input::get('type_id') == 2) /*Servicio */ {
            return Redirect::to('services');
        }
        return Redirect::to('items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $items = Item::find($id);
        $tiene_tx = BodegaProducto::where('id_product', $id)->where('quantity', '>', 0)->count();
        if ($tiene_tx > 0) {

            Session::flash('message', trans('item.stock_violation') . ' [' . $items->upc_ean_isbn . ' - ' . $items->item_name . ']');
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('items');
        }

        $items->status = 2;
        $items->save();
        Session::flash('message', trans('item.soft_deleted_ok') . ' ' . $items->item_name);
        return Redirect::to('items');
        // try
        // {

        // 	$tiene_tx = Inventory::where('item_id',$id)->count();
        // 	if($tiene_tx>0)
        // 	{

        // 		Session::flash('message', trans('item.integrity_violation').' ['.$items->upc_ean_isbn.' - '.$items->item_name.']');
        // 		Session::flash('alert-class', 'alert-error');
        // 		return Redirect::to('items');
        // 	}

        // 	$item_price = ItemPrice::where('item_id',$id)->count();
        // 	if($item_price>0) {
        // 		DB::table('item_prices')->where('item_id','=',$id)->delete();
        // 	};

        // 	$items->delete();
        // 	Session::flash('message', trans('item.deleted_ok'));
        // 	return Redirect::to('items');
        // }
        // catch(\Illuminate\Database\QueryException $e)
        // {
        // 	Session::flash('message', trans('item.integrity_violation').' ['.$items->upc_ean_isbn.' - '.$items->item_name.']'.$e->getMessage() );
        // 	Session::flash('alert-class', 'alert-error');
        // 	return Redirect::to('items');
        // }

    }


    public function search()
    {
        // echo($primera_vez);
        set_time_limit(0);

        return view('item.item-search');
    }

}
