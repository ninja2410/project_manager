<?php

namespace App\Traits;


use App\BodegaProducto;
use App\GeneralParameter;
use App\Inventory;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\Item;
use App\Price;
use DB;
use \Auth;

trait ItemsTrait
{

    public function getItemsAndServicesByStorage($storage_id)
    {
        $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->where('stock_action', '=')
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'), DB::Raw('"" as blanco'));

        $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->Join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
            ->where('items.status', '=', 1)
            ->where('bodega_productos.id_bodega', $storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity', DB::Raw('"" as blanco'));


        $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
            ->where('bodega_productos.id_bodega', $storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->where('type_id', 1)
            ->where('items.is_kit', 1)
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', DB::Raw('min(bodega_productos.quantity) as quantity'), DB::Raw('"" as blanco'))
            ->groupBy('items.id');


        return $servicios->unionAll($productos)->unionAll($kits)->get();

    }

    public function verifyBudgetCost($item_id)
    {
        $items = Item::find($item_id);
        $price_valid = true;
        $today = new \DateTime();
        $last = new \DateTime($items->updated_budget_cost_at);
        $dif = date_diff($last, $today);
        if ($items->days_valid > 0) {
            if ($dif->d > $items->days_valid) {
                $price_valid = false;
            }
        }
        if ($items->monts_valid > 0) {
            if ($dif->m > $items->monts_valid) {
                $price_valid = false;
            }
        } else {
            $default_monts = GeneralParameter::where('name', 'Validez de precios de presupuesto')
                ->first();
            if (($dif->m + ($dif->y * 12)) > $default_monts->text_value) {
                $price_valid = false;
            }
        }
        return $price_valid;
    }

    public function getItemsAndServicesByStoragePago($storage_id, $pago_id)
    {
        // dd($storage_id,$pago_id);
        if ($pago_id == 0) {
            $storage_id = 0;
            $price_id = 0;
        } else {
            $price_id = Price::active(1)->join('module_prices', 'prices.id', '=', 'module_prices.price_id')
                ->where('module_prices.pago_id', $pago_id)
                ->orderBy('order', 'asc')
                ->max('prices.id');
        }


        $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->where('prices.id', '=', $price_id)
            ->where('stock_action', '=')
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));

        $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->Join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
            ->where('prices.id', '=', $price_id)
            ->where('items.status', '=', 1)
            ->where('bodega_productos.id_bodega', $storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));


        $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
            ->where('prices.id', '=', $price_id)
            ->where('bodega_productos.id_bodega', $storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->where('type_id', 1)
            ->where('items.is_kit', 1)
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', DB::Raw('min(bodega_productos.quantity) as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))
            ->groupBy('items.id');

        return $servicios->unionAll($productos)->unionAll($kits)->get();

    }

    public function getItemsAndServicesByStoragePrice($storage_id, $price_id)
    {


        $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->where('prices.id', '=', $price_id)
            ->where('stock_action', '=')
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));

        $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->Join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
            ->where('prices.id', '=', $price_id)
            ->where('items.status', '=', 1)
            ->whereDefault(1)
            ->wildcard()
            ->where('bodega_productos.id_bodega', $storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));


        $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            ->leftJoin('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
            ->where('prices.id', '=', $price_id)
            ->whereRaw('coalesce(bodega_productos.id_bodega, 0) in (?, ?)', [0, $storage_id])
            ->where('type_id', 1)
            ->where('items.is_kit', 1)
            ->where('items.status', '=', 1)
            ->having('quantity', '>', 0)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', DB::Raw('min(coalesce(floor(bodega_productos.quantity/item_kit_items.quantity), 0)) as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))
            ->groupBy('items.id');
//        dd($servicios->unionAll($productos)->unionAll($kits)->get());
        return $servicios->unionAll($productos)->unionAll($kits)->get();

    }

    public function getItemsAndServicesByPrice($price_id)
    {


        $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types as tipo', 'items.type_id', '=', 'tipo.id')
            ->where('prices.id', '=', $price_id)
            ->where('stock_action', '=')
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'item_categories.id as category_id', 'item_categories.name as category', 'items.item_name', 'items.description', 'items.size', 'items.minimal_existence', 'items.type', 'items.type_id', 'tipo.name as tipo', 'items.is_kit', 'items.stock_action', DB::Raw('"" as blanco'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));

        $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types as tipo', 'items.type_id', '=', 'tipo.id')
            ->where('prices.id', '=', $price_id)
            ->where('items.status', '=', 1)
            ->where('items.type_id', '=', 1)/*Productos*/
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'item_categories.id as category_id', 'item_categories.name as category', 'items.item_name', 'items.description', 'items.size', 'items.minimal_existence', 'items.type', 'items.type_id', 'tipo.name as tipo', 'items.is_kit', 'items.stock_action', DB::Raw('"" as blanco'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));


        $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types as tipo', 'items.type_id', '=', 'tipo.id')
            ->where('prices.id', '=', $price_id)
            ->where('type_id', 1)/*Productos*/
            ->where('items.is_kit', 1)/*Kits*/
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'item_categories.id as category_id', 'item_categories.name as category', 'items.item_name', 'items.description', 'items.size', 'items.minimal_existence', 'items.type', 'items.type_id', 'tipo.name as tipo', 'items.is_kit', 'items.stock_action', DB::Raw('"" as blanco'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))
            ->groupBy('items.id');

        return $servicios->unionAll($productos)->unionAll($kits)->get();

    }

    public function getServicesByPrice($price_id)
    {


        $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types as tipo', 'items.type_id', '=', 'tipo.id')
            ->where('prices.id', '=', $price_id)
            ->where('stock_action', '=')
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'item_categories.id as category_id', 'item_categories.name as category', 'items.item_name', 'items.description', 'items.size', 'items.minimal_existence', 'items.type', 'items.type_id', 'tipo.name as tipo', 'items.is_kit', 'items.stock_action', DB::Raw('"" as blanco'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));


        return $servicios->get();

    }

    public function getItemsByPrice($price_id, $status)
    {

        $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types as tipo', 'items.type_id', '=', 'tipo.id')
            ->where('prices.id', '=', $price_id)
            ->where('items.status', '=', $status)
            ->where('items.type_id', '=', 1)/*Productos*/
            ->where('items.is_kit', 0)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'item_categories.id as category_id', 'item_categories.name as category', 'items.item_name', 'items.description', 'items.size', 'items.minimal_existence', 'items.type', 'items.type_id', 'tipo.name as tipo', 'items.is_kit', 'items.stock_action', 'items.status', DB::Raw('"" as blanco'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), DB::Raw('(select sum(quantity) from bodega_productos where id_product =items.id) as existencia'));


        // $kits=Item::leftJoin('item_prices','items.id','=','item_prices.item_id')
        // ->Join('prices','item_prices.price_id','=','prices.id')
        // ->Join('item_kit_items','items.id','=','item_kit_items.item_kit_id')
        // ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
        //  ->join('item_types as tipo','items.type_id', '=', 'tipo.id')
        // ->where('prices.id', '=',$price_id)
        // ->where('type_id', 1)/*Productos*/
        // ->where('items.is_kit', 1) /*Kits*/
        // ->where('items.status','=',$status)
        // ->select('items.id','items.upc_ean_isbn','items.avatar','item_categories.id as category_id','item_categories.name as category','items.item_name','items.description','items.size','items.minimal_existence', 'items.type','items.type_id','tipo.name as tipo','items.is_kit','items.stock_action','items.status',DB::Raw('"" as blanco'),DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'),DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))
        // ->groupBy('items.id');

        // return $productos->unionAll($kits)->get();
        return $productos->get();

    }

    public function getItemsAndServicesByPago($price_id)
    {


        $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->where('prices.id', '=', $price_id)
            ->where('stock_action', '=')
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));

        $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            // ->Join('bodega_productos','items.id','=','bodega_productos.id_product')
            ->where('prices.id', '=', $price_id)
            ->where('items.status', '=', 1)
            ->wildcard()
            // ->where('bodega_productos.id_bodega',$storage_id)
            // ->where('bodega_productos.quantity','>',0)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', DB::raw('"0" as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));


        $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            // ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
            ->where('prices.id', '=', $price_id)
            // ->where('bodega_productos.id_bodega',$storage_id)
            // ->where('bodega_productos.quantity','>',0)
            ->where('type_id', 1)
            ->where('items.is_kit', 1)
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.is_kit', 'items.stock_action', DB::raw('"0" as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))
            ->groupBy('items.id');

        return $servicios->unionAll($productos)->unionAll($kits)->get();

    }

    public function getAutocomItemsAndServicesByStoragePago($search, $bodega_id, $pago_id)
    {
        if ($pago_id == 0) {
            $storage_id = 0;
            $price_id = 0;
        } else {
            $price_id = Price::active(1)->join('module_prices', 'prices.id', '=', 'module_prices.price_id')
                ->where('module_prices.pago_id', $pago_id)
                ->orderBy('order', 'asc')
                ->max('prices.id');
        }
        // dd($search.' '.$bodega_id.' - '.$pago_id.' '.$price_id );
        if ((strlen($search) > 0) || ($bodega_id != "") || ($price_id != 0)) {
            $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->where('prices.id', '=', $price_id)
                ->where('stock_action', '=')
                ->where('items.status', '=', 1)
                ->whereRaw("item_name LIKE '%" . $search . "%'")
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));
            // dd($servicios);

            $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
                ->where('prices.id', '=', $price_id)
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('items.status', '=', 1)
                ->whereRaw("item_name LIKE '%" . $search . "%'")
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))->limit(10);
            // ->select('items.id','items.upc_ean_isbn',DB::raw('concat(upc_ean_isbn," | ",items.item_name) as iten_name'),'items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity')->limit(10);
            // dd($items);

            $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
                ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
                ->where('prices.id', '=', $price_id)
                ->where('items.status', '=', 1)
                ->whereRaw("item_name LIKE '%" . $search . "%'")
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('type_id', 1)
                ->where('items.is_kit', 1)
                ->select('items.id', 'items.upc_ean_isbn', DB::Raw('concat(items.item_name," ","[Kit]") as item_name'), 'items.is_kit', 'items.stock_action', DB::Raw('min(bodega_productos.quantity) as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))
                ->groupBy('items.id');


            $items = $servicios->unionAll($productos)->unionAll($kits)->get();

            return $items;
        } else {
            return 'No se encontro información';
        }

    }

    public function getAutocomItemsAndServicesByStoragePrice($search, $bodega_id, $price_id)
    {
        // dd($search.' '.$bodega_id.' - '.$pago_id.' '.$price_id );

        if ((strlen($search) > 0) || ($bodega_id != "") || ($price_id != 0)) {
            $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->where('prices.id', '=', $price_id)
                ->where('stock_action', '=')
                ->where('items.status', '=', 1)
                ->whereDefault(1)
                ->whereRaw("item_name LIKE '%" . $search . "%'")
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'));
            // dd($servicios);

            $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
                ->where('prices.id', '=', $price_id)
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('items.status', '=', 1)
                ->whereDefault(1)
                ->whereRaw("item_name LIKE '%" . $search . "%'")
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))->limit(10);
            // ->select('items.id','items.upc_ean_isbn',DB::raw('concat(upc_ean_isbn," | ",items.item_name) as iten_name'),'items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity')->limit(10);
            // dd($items);

            $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
                ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
                ->where('prices.id', '=', $price_id)
                ->where('items.status', '=', 1)
                ->whereRaw("item_name LIKE '%" . $search . "%'")
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('type_id', 1)
                ->where('items.is_kit', 1)
                ->select('items.id', 'items.upc_ean_isbn', DB::Raw('concat(items.item_name," ","[Kit]") as item_name'), 'items.is_kit', 'items.stock_action', DB::Raw('min(bodega_productos.quantity) as quantity'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'))
                ->groupBy('items.id');

            $items = $servicios->unionAll($productos)->unionAll($kits)->get();

            return $items;
        } else {
            return 'No se encontro información';
        }

    }

    public function getSearchCodeItemsAndServicesByStoragePago($search, $bodega_id, $pago_id)
    {
        if ($pago_id == 0) {
            $storage_id = 0;
            $price_id = 0;
        } else {
            $price_id = Price::active(1)->join('module_prices', 'prices.id', '=', 'module_prices.price_id')
                ->where('module_prices.pago_id', $pago_id)
                ->orderBy('order', 'asc')
                ->max('prices.id');
        }
        // dd($search.' '.$bodega_id.' - '.$pago_id.' '.$price_id );
        if ((strlen($search) > 0) || ($bodega_id != "") || ($price_id != 0)) {

            $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->where('prices.id', '=', $price_id)
                ->where('stock_action', '=')
                ->where('items.status', '=', 1)
                ->where('upc_ean_isbn', '=', $search)
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'));
            // dd($servicios);

            $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
                ->where('prices.id', '=', $price_id)
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('items.status', '=', 1)
                ->where('upc_ean_isbn', '=', $search)
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity');
            // ->select('items.id','items.upc_ean_isbn',DB::raw('concat(upc_ean_isbn," | ",items.item_name) as iten_name'),'items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity')->limit(10);
            // dd($items);

            $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
                ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
                ->where('prices.id', '=', $price_id)
                ->where('items.status', '=', 1)
                ->where('upc_ean_isbn', '=', $search)
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('type_id', 1)
                ->where('items.is_kit', 1)
                ->select('items.id', 'items.upc_ean_isbn', DB::Raw('concat(items.item_name," ","[Kit]") as item_name'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', DB::Raw('min(bodega_productos.quantity) as quantity'))
                ->groupBy('items.id');


            $items = $servicios->unionAll($productos)->unionAll($kits)->first();

            return $items;
        } else {
            return 'No se encontro información';
        }

    }

    public function getSearchCodeItemsAndServicesByStoragePrice($search, $bodega_id, $price_id)
    {

        if ((strlen($search) > 0) || ($bodega_id != "") || ($price_id != 0)) {

            $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->where('prices.id', '=', $price_id)
                ->where('stock_action', '=')
                ->where('items.status', '=', 1)
                ->whereDefault(1)
                ->where('upc_ean_isbn', '=', $search)
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'));

            // dd($servicios);

            $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
                ->where('prices.id', '=', $price_id)
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('items.status', '=', 1)
                ->whereDefault(1)
                ->where('upc_ean_isbn', '=', $search)
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity');
            // ->select('items.id','items.upc_ean_isbn',DB::raw('concat(upc_ean_isbn," | ",items.item_name) as iten_name'),'items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity')->limit(10);
            // dd($items);

            $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
                ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
                ->where('prices.id', '=', $price_id)
                ->where('items.status', '=', 1)
                ->where('upc_ean_isbn', '=', $search)
                ->whereDefault(1)
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('type_id', 1)
                ->where('items.is_kit', 1)
                ->select('items.id', 'items.upc_ean_isbn', DB::Raw('concat(items.item_name," ","[Kit]") as item_name'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', DB::Raw('min(bodega_productos.quantity) as quantity'))
                ->groupBy('items.id');


            $items = $servicios->unionAll($productos)->unionAll($kits)->first();

            return $items;
        } else {
            return 'No se encontro información';
        }

    }

    /**Busqueda por id */
    public function getSearchIdItemsAndServicesByStoragePrice($search, $bodega_id, $price_id)
    {

        if ((strlen($search) > 0) || ($bodega_id != "") || ($price_id != 0)) {

            $servicios = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->where('prices.id', '=', $price_id)
                ->where('stock_action', '=')
                ->where('items.status', '=', 1)
                ->where('items.id', '=', $search)
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', DB::raw('"=" as quantity'));

            // dd($servicios);

            $productos = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
                ->where('prices.id', '=', $price_id)
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('items.status', '=', 1)
                ->where('items.id', '=', $search)
                ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity');
            // ->select('items.id','items.upc_ean_isbn',DB::raw('concat(upc_ean_isbn," | ",items.item_name) as iten_name'),'items.selling_price','items.low_price','items.is_kit','items.stock_action','bodega_productos.quantity')->limit(10);
            // dd($items);

            $kits = Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
                ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
                ->Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
                ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
                ->where('prices.id', '=', $price_id)
                ->where('items.status', '=', 1)
                ->where('items.id', '=', $search)
                ->where('bodega_productos.id_bodega', $bodega_id)
                ->where('bodega_productos.quantity', '>', 0)
                ->where('type_id', 1)
                ->where('items.is_kit', 1)
                ->select('items.id', 'items.upc_ean_isbn', DB::Raw('concat(items.item_name," ","[Kit]") as item_name'), DB::raw('round(coalesce(item_prices.selling_price,items.cost_price+(items.cost_price*(prices.pct/100))),2 ) as selling_price'), DB::raw('round(coalesce(item_prices.low_price,items.cost_price+(items.cost_price*(prices.pct_min/100))),2 ) as low_price'), 'items.is_kit', 'items.stock_action', DB::Raw('min(coalesce(bodega_productos.quantity, 0)) as quantity'))
                ->groupBy('items.id');


            $items = $servicios->unionAll($productos)->unionAll($kits)->first();

            return $items;
        } else {
            return 'No se encontro información';
        }

    }

    public function getItemsAndServicesAll()
    {
        $servicios = Item::where('stock_action', '=')
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.selling_price', 'items.low_price', 'items.is_kit', 'items.stock_action', DB::Raw('"" as blanco'));

        $productos = Item::where('items.status', '=', 1)
            ->wildcard()
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.selling_price', 'items.low_price', 'items.is_kit', 'items.stock_action', DB::Raw('"" as blanco'));


        $kits = Item::Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            ->where('type_id', 1)
            ->where('items.is_kit', 1)
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.selling_price', 'items.low_price', 'items.is_kit', 'items.stock_action', DB::Raw('"" as blanco'))
            ->groupBy('items.id');


        return $servicios->unionAll($productos)->unionAll($kits)->get();

    }

    public function getItemsAll()
    {
        $productos = Item::join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
            ->where('items.status', '=', 1)
            // ->where('bodega_productos.id_bodega',$storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.selling_price', 'items.low_price', 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity', DB::Raw('"" as blanco'));


        $kits = Item::Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
            // ->where('bodega_productos.id_bodega',$storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->where('type_id', 1)
            ->where('items.is_kit', 1)
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.avatar', 'items.item_name', 'items.description', 'items.size', 'items.selling_price', 'items.low_price', 'items.is_kit', 'items.stock_action', DB::Raw('min(bodega_productos.quantity) as quantity'), DB::Raw('"" as blanco'))
            ->groupBy('items.id');


        return $productos->unionAll($kits)->get();

    }

    public function getItemsByCode($code)
    {

        return Item::leftJoin('item_prices', 'items.id', '=', 'item_prices.item_id')
            ->Join('prices', 'item_prices.price_id', '=', 'prices.id')
            ->join('item_categories', 'items.id_categorie', '=', 'item_categories.id')
            ->join('item_types', 'items.type_id', '=', 'item_types.id')
            ->leftJoin('bodega_productos', 'bodega_productos.id_product', '=', 'items.id')
            ->leftJoin('almacens', 'almacens.id', '=', 'bodega_productos.id_bodega')
            ->whereRaw("items.upc_ean_isbn LIKE '%" . $code . "%'")
            ->select(['items.id', 'items.stock_action', 'items.upc_ean_isbn', 'items.description', 'item_name', 'cost_price', DB::raw('round(coalesce(item_prices.selling_price,cost_price+(cost_price*(prices.pct/100)),2) ) as selling_price'), 'bodega_productos.quantity', 'bodega_productos.id_bodega', DB::raw('round(coalesce(item_prices.low_price,cost_price+(cost_price*(prices.pct_min/100)),2) ) as low_price'), 'avatar', 'items.expiration_date', 'items.minimal_existence', 'item_categories.name as category', 'item_types.name as type', 'almacens.name as almacen_name'])->get();

    }

    public function getItemsByStorage($storage_id)
    {

        $productos = Item::join('bodega_productos', 'items.id', '=', 'bodega_productos.id_product')
            ->where('bodega_productos.id_bodega', $storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', 'items.selling_price', 'items.low_price', 'items.is_kit', 'items.stock_action', 'bodega_productos.quantity')->limit(10);

        $kits = Item::Join('item_kit_items', 'items.id', '=', 'item_kit_items.item_kit_id')
            ->Join('bodega_productos', 'bodega_productos.id_product', '=', 'item_kit_items.item_id')
            ->where('bodega_productos.id_bodega', $storage_id)
            ->where('bodega_productos.quantity', '>', 0)
            ->where('type_id', 1)
            ->where('items.is_kit', 1)
            ->where('items.status', '=', 1)
            ->select('items.id', 'items.upc_ean_isbn', 'items.item_name', 'items.selling_price', 'items.low_price', 'items.is_kit', 'items.stock_action', DB::Raw('min(bodega_productos.quantity) as quantity'))
            ->groupBy('items.id');


        return $productos->unionAll($kits)->get();
    }

    /**
     * @param $bodega_id
     * @param $item_id
     * @param $quantity
     * @param $nombreDeTransaccion
     */
    public function updateItemQuantity($bodega_id, $item_id, $quantity, $nombreDeTransaccion)
    {
        $item = Item::find($item_id);
        if ($item->stock_action == "="){
            return;
        }
        $exists = BodegaProducto::where('id_bodega', '=', $bodega_id)
            ->where('id_product', '=', $item_id)
            ->value('id');


        if (!$exists) {
            $data_transfer = new BodegaProducto;
            $data_transfer->id_bodega = $bodega_id;
            $data_transfer->id_product = $item_id;
            $data_transfer->quantity = $quantity;
            $data_transfer->save();

        } else {
            $data_transfer_update = BodegaProducto::find($exists);
            $data_transfer_update->quantity = $data_transfer_update->quantity + $quantity;
            $data_transfer_update->save();
        }
        $inventories = new Inventory;
        $inventories->almacen_id = $bodega_id;
        $inventories->item_id = $item_id;
        $inventories->user_id = Auth::user()->id;
        $inventories->in_out_qty = $quantity;
        $inventories->remarks = $nombreDeTransaccion;
        $inventories->save();
    }

    /**
     * Verifica la existencia de producto en el sistema
     * @param $item_id
     * @param $bodega_id
     * @param $quantity
     */
    public function verifyQuantity($item_id, $bodega_id, $quantity)
    {
        $resp = true;
        $message = '';
        $items = Item::find($item_id);
        $valorEncontrado = BodegaProducto::where('id_product', '=', $item_id)
            ->where('id_bodega', '=', $bodega_id)
            ->value('id');
        if (!isset($valorEncontrado) && $items->stock_action != '=') {
            $message = 'El producto ' . $items->item_name . ' no tiene existencias suficientes para completar la venta, no debe sobrepasar la cantidad de 0 articulos.';
            $resp = false;
        } else {
            //Obtenemos los valores para poder actualizar la cantidad de productos
            $actualizar = BodegaProducto::find($valorEncontrado);
            if (isset($actualizar->quantity) && $actualizar->quantity < $quantity && $items->stock_action != '=') {
                $message = 'El producto ' . $items->item_name . ' no tiene existencias suficientes para completar la venta, no debe sobrepasar la cantidad de ' . $actualizar->quantity . ' articulos.';
                $resp = false;
            }
        }
        return json_encode(array("flag"=>$resp, "message"=>$message));
    }
}
