<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use App\Inventory;
use App\Almacen;
use App\BodegaProducto;
use App\Http\Requests\InventoryRequest;
use \Auth, \Redirect, \Validator, \Input, \Session, \DB;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

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
        //
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
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
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
        //
        // 		$inventories = Inventory::all();
        // 		return view('inventory.edit')
        //             ->with('item', $items)
        //             ->with('inventory', $inventories);

        $items_data = Inventory::join('items', 'inventories.item_id', '=', 'items.id')
            ->join('users', 'inventories.user_id', '=', 'users.id')
            ->where('items.id', '=', $id)
            ->select('items.item_name', 'users.name', 'inventories.in_out_qty', 'inventories.remarks', 'inventories.created_at')
            ->orderBy('inventories.created_at', 'ASC')->get();
        return view('inventory.edit')
            ->with('items_data', $items_data)
            ->with('item_name', $items->item_name);
    }

    public function inventory($item, $almacen)
    {
        $items = Item::find($item);
        $alm = Almacen::find($almacen);
        if ($items->is_kit) {
            $existencia = BodegaProducto::join('item_kit_items','item_kit_items.item_id','=','bodega_productos.id_product')
                ->where('item_kit_items.item_kit_id',$item)
                ->where('bodega_productos.id_bodega', $almacen)
                ->min(DB::raw('coalesce(floor(bodega_productos.quantity/item_kit_items.quantity), 0)'));
        } else {
            $existencia = BodegaProducto::where('id_bodega', $almacen)
                ->where('id_product', $item)
                ->max('quantity');
        }

        $items_data = Inventory::join('items', 'inventories.item_id', '=', 'items.id')
            ->join('users', 'inventories.user_id', '=', 'users.id')
            ->where('items.id', '=', $item)
            ->where('inventories.almacen_id', $almacen)
            ->select('items.item_name', 'users.name', 'inventories.in_out_qty', 'inventories.remarks', 'inventories.created_at')
            ->orderBy('inventories.created_at', 'ASC')->get();

        return view('inventory.edit')
            ->with('items_data', $items_data)
            ->with('almacen_name', $alm->name)
            ->with('items', $items)
            ->with('item_name', $items->item_name)
            ->with('avatar', $items->avatar)
            ->with('existencia', $existencia);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(InventoryRequest $request, $id)
    {
        $items = Item::find($id);
        $items->quantity = $items->quantity + Input::get('in_out_qty');
        $items->save();

        $inventories = new Inventory;
        $inventories->item_id = $id;
        $inventories->user_id = Auth::user()->id;
        $inventories->in_out_qty = Input::get('in_out_qty');
        $inventories->remarks = Input::get('remarks');
        $inventories->save();


        Session::flash('message', 'You have successfully updated item');
        return Redirect::to('inventory/' . $id . '/edit');
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

}
