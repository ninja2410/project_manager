<?php

namespace App\Http\Controllers;

use App\Inventory;
use Illuminate\Http\Request;
use App\ItemKit, App\ItemKitItem, App\ItemKitItemTemp;
use App\Item;
use App\Categorie;
use App\Http\Requests;
use DB;
use App\Http\Requests\ItemKitRequest;
use \Auth, \Redirect, \Validator, \Input, \Session, \Response;
use App\Http\Controllers\Controller;
use App\ItemCategory;

class ItemKitController extends Controller
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
        $itemkits = Item::where('type', 2)->get();
        return view('itemkit.index')->with('itemkits', $itemkits);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = ItemCategory::orderBy('id', 'asc')->get();
        return view('itemkit.create')
            ->with('categories', $categories);
    }

    public function getElements($id)
    {
        $kit = Item::find($id);
        if ($kit->is_kit == 1) {
            /* Refactorizacion para enviar la existencia de cada item de un combo */
            $detail = ItemKitItem::leftJoin('bodega_productos', 'item_kit_items.item_Id', '=', 'bodega_productos.id_product')
                ->where('item_kit_id', $id)
                ->select('item_id', 'item_kit_items.quantity', DB::raw('coalesce(bodega_productos.quantity,0) as existencia'))
                ->get();
            // $detail=ItemKitItem::where('item_kit_id', $id)
            // ->select('item_id', 'quantity')
            // ->get();
        } else {
            $detail = -1;
        }
        return $detail;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $ItemKitItemTemps = new ItemKitItemTemp;
        $ItemKitItemTemps->item_id = Input::get('item_id');
        $ItemKitItemTemps->quantity = 1;
        // $ItemKitItemTemps->iskit = 1;
        $ItemKitItemTemps->cost_price = Input::get('cost_price');
        $ItemKitItemTemps->selling_price = Input::get('selling_price');
        $ItemKitItemTemps->save();
        return $ItemKitItemTemps;
        // Session::flash('message', 'Kit  agregado correctamente');

        // return Redirect::to('item-kits');
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
        $ItemKitItemTemps = ItemKitItemTemp::find($id);
        $ItemKitItemTemps->quantity = Input::get('quantity');
        $ItemKitItemTemps->save();
        return $ItemKitItemTemps;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        ItemKitItemTemp::destroy($id);
    }

    public function itemKitApi()
    {
        return Response::json(ItemKitItemTemp::with('item')->get());
    }

    public function itemKits()
    {
        return Response::json(Item::where('is_kit', 0)->get());
        
    }

    public function storeItemKits(ItemKitRequest $request)
    {
        $itemkits = new Item;
        $itemkits->item_name = Input::get('item_kit_name');
        $itemkits->cost_price = Input::get('cost_price');
        $itemkits->selling_price = Input::get('selling_price');
        $itemkits->description = Input::get('description');
        $itemkits->id_categorie = Input::get('categorie_id');
        $itemkits->upc_ean_isbn = Input::get('code');
        $itemkits->type_id = 1;
        $itemkits->type = 2;
        $itemkits->is_kit = 1;
        $itemkits->stock_action = "+";
        $itemkits->save();
        // process receiving items
        $item_kit_items = ItemKitItemTemp::all();
        foreach ($item_kit_items as $value) {
            $item_kit_items_data = new ItemKitItem;
            $item_kit_items_data->item_kit_id = $itemkits->id;
            $item_kit_items_data->item_id = $value->item_id;
            $item_kit_items_data->quantity = $value->quantity;
            $item_kit_items_data->save();
        }

        //delete all data on ReceivingTemp model
        ItemKitItemTemp::truncate();
        Session::flash('message', 'El kit se ha almacenado correctamente');
        return Redirect::to('item-kits');
    }
}
