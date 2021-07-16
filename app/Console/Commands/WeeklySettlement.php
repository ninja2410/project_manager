<?php

namespace App\Console\Commands;

use App\Almacen;
use App\BodegaProducto;
use App\Item;
use App\WeeklySettlementDetail;
use Illuminate\Console\Command;

class WeeklySettlement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron para generar liquidación semanal de ventas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = date('Y-m-d');
        $bodegas = Almacen::where('id_state', 1)
            ->get();
        $items = Item::where('type_id', 1)
            ->where('status', 1)
            ->wildcard()
            ->get();
        foreach($bodegas as $bodega){
            #region CREAR ENCABEZADO DE CIERRE
            $header = new \App\WeeklySettlement();
            $header->date = $today;
            $header->bodega_id = $bodega->id;
            $header->save();
            #endregion
            #region CREAR DETALLE DE EXISTENCIAS DE PRODUCTOS
             foreach($items as $item){
                 $qty = BodegaProducto::where('id_product', $item->id)
                     ->where('id_bodega', $bodega->id)
                     ->select('quantity as existencia')
                     ->first();
                 if (isset($qty)){
                     $quantity = $qty->existencia;
                 }
                 else{
                     $quantity = 0;
                 }
                 $detail = new WeeklySettlementDetail();
                 $detail->item_id = $item->id;
                 $detail->quantity = $quantity;
                 $detail->weekly_id = $header->id;
                 $detail->save();
             }
            #endregion
        }
    }
}
