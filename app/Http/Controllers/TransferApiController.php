<?php

namespace App\Http\Controllers;

use App\Almacen;
use App\Item;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransferApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function autoCompleteTransfer(Request $request){
        $search = trim($request->id);
        $price_id = trim($request->price);
        $bodega_id =  trim($request->bodega);
        $data_items_query = Almacen::join('bodega_productos', 'bodega_productos.id_bodega', '=', 'almacens.id')
            ->where('almacens.id', '=', $bodega_id)
            ->join('items', 'bodega_productos.id_product', '=', 'items.id');
        if ($price_id == 0) {
            $data_items_query->select('items.id', 'items.item_name',
                'items.selling_price', 'items.cost_price',
                'bodega_productos.quantity', 'items.upc_ean_isbn as code');
        } else {
            $data_items_query->leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->where('prices.id', '=', $price_id)
                ->select('items.id', 'items.item_name',
                    'items.selling_price', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as cost_price'),
                    'bodega_productos.quantity', 'items.upc_ean_isbn as code');
        }

        $data_items = $data_items_query
            ->get();
        return $data_items;
    }

    public function searchCodeTransfer(Request $request){
        $code = trim($request->code);
        $price_id = trim($request->price);
        $bodega_id =  trim($request->bodega);
        $data_items_query = Almacen::join('bodega_productos', 'bodega_productos.id_bodega', '=', 'almacens.id')
            ->where('almacens.id', '=', $bodega_id)
            ->join('items', 'bodega_productos.id_product', '=', 'items.id');
        if ($price_id == 0) {
            $data_items_query->select('items.id', 'items.item_name',
                'items.selling_price', 'items.cost_price',
                'bodega_productos.quantity', 'items.upc_ean_isbn as code');
        } else {
            $data_items_query->leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->where('prices.id', '=', $price_id)
                ->select('items.id', 'items.item_name',
                    'items.selling_price', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as cost_price'),
                    'bodega_productos.quantity', 'items.upc_ean_isbn as code');
        }

        $data_items = $data_items_query
            ->where('items.upc_ean_isbn', $code)
            ->first();
        return $data_items;
    }

    public function searchIdTransfer(Request $request){
        $id = trim($request->id);
        $price_id = trim($request->price);
        $bodega_id =  trim($request->bodega);
        $data_items_query = Almacen::join('bodega_productos', 'bodega_productos.id_bodega', '=', 'almacens.id')
            ->where('almacens.id', '=', $bodega_id)
            ->join('items', 'bodega_productos.id_product', '=', 'items.id');
        if ($price_id == 0) {
            $data_items_query->select('items.id', 'items.item_name',
                'items.selling_price', 'items.cost_price',
                'bodega_productos.quantity', 'items.upc_ean_isbn as code');
        } else {
            $data_items_query->leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->where('prices.id', '=', $price_id)
                ->select('items.id', 'items.item_name',
                    'items.selling_price', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as cost_price'),
                    'bodega_productos.quantity', 'items.upc_ean_isbn as code');
        }

        $data_items = $data_items_query
            ->where('items.id', $id)
            ->first();
        return $data_items;
    }
}
