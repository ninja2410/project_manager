<?php

namespace App\Http\Controllers;

use App\Almacen;
use App\BodegaProducto;
use App\GeneralParameter;
use App\InventoryClosing;
use App\InventoryClosingDetail;
use App\Item;
use App\Parameter;
use App\StateCellar;
use Doctrine\Common\Cache\ArrayCache;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use DB;
use \Input;
use mysql_xdevapi\Exception;
use phpDocumentor\Reflection\Types\Array_;
use \Redirect;
use DateInterval;
use \Session;
use spec\PhpSpec\CodeAnalysis\VisibilityAccessInspectorSpec;

class InventoryClosingController extends Controller
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
    public function index()
    {
        $fecha1 = Input::get('date1');
        $fecha2 = Input::get('date2');
        $cellar = Input::get('cellar');
        $mesFecha1 = "";
        $mesFecha2 = "";
        $anioFecha1 = "";
        $anioFecha2 = "";
        $all_cellars = Almacen::where('id_state', 1)
            ->get();
        if ($cellar == null){
            $cellar = Almacen::where('id_state', 1)
                ->lists('id');
        }
        else{
            $cellar = (array)$cellar;
        }
        $fechaActual = date("Y-m-d");
        if ($fecha1 == null) {
            $fecha1 = $fechaActual;
            $mesFecha1 = date("m");
            $anioFecha1 = date("Y");
        } else {
            $nuevaFecha1 = explode('/', $fecha1);
            $mesFecha1 = $nuevaFecha1[0];
            $anioFecha1 = $nuevaFecha1[1];
            $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-01';
        }

        if ($fecha2 == null) {
            $fecha2 = $fechaActual;
            $mesFecha2 = date("m");
            $anioFecha2 = date("Y");
        } else {

            $nuevaFecha2 = explode('/', $fecha2);
            $mesFecha2 = $nuevaFecha2[0];
            $anioFecha2 = $nuevaFecha2[1];
            $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-01' ;
        }

        $inventory_closing_list = InventoryClosing::orderBy('date', 'desc')
            ->whereBetween('month', [$mesFecha1, $mesFecha2])
            ->whereBetween('year', [$anioFecha1, $anioFecha2])
            ->whereIn('almacen_id', $cellar)
            ->get();

        return view('inventory_closing.index')
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('cellar', $cellar)
            ->with('url', url('inventory_closing'))
            ->with('inventory_closing_list', $inventory_closing_list)
            ->with('all_cellars', $all_cellars);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**
         * BUSCAR EL HISTORIAL DE CIERRES GENERADOS
         */

        $last = InventoryClosing::orderBy('correlative', 'desc')->first();
        if (isset($last)){
            $history = InventoryClosing::where('correlative', $last->correlative)->get();
            $lDate = $last->date;
        }
        else{
            $history = (array)$last;
            /**
             * LEER PARAMETRO
             */
            $lDate = GeneralParameter::where('name', 'Mes inicial cierres de inventario.')
                ->first()->text_value;
        }
        /**
         * INFORMACION SOBRE LOS MESES A CERRAR
         */
        $today = new \DateTime();
        $last_close = new \DateTime($lDate);
        $dif = date_diff($today, $last_close);
        $meses = $dif->format("%m");
        $anios = $dif->format("%y");
        /**
         * VERIFICAR SI EL MES ESTÁ CERRADO
         */
        if ($meses<=0 && $anios==0){
            Session::flash('message', trans('inventory_closing.cant_create'));
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('inventory_closing');
        }

        $meses += 12*$anios;

        $impf = $last_close;
        $mMeses = "";
        for($i = 1; $i <= $meses; $i++){
            // despliega los meses
            $impf->add(new DateInterval('P1M'));
            $tmp_mes = date('m', strtotime($impf->format('d-m-Y')));
            $tmp_anio = date('Y', strtotime($impf->format('d-m-Y')));
            $mMeses .= trans('months.'.(int)$tmp_mes).'/'.$tmp_anio;
            if ($i<$meses)
                $mMeses.=' - ';
        }
        return view('inventory_closing.create')
            ->with('meses', $meses)
            ->with('mMeses', $mMeses)
            ->with('history', $history);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bodegas = Almacen::where('id_state', 1)->get();
        $items = Item::comodin()->get();
        $correlative = InventoryClosing::orderBy('id', 'desc')->first();
        DB::beginTransaction();
        try {
            /**
             * GUARDAR CIERRE POR CADA BODEGA ACTIVA
             */
            foreach ($bodegas as $bodega){
                $totalCost = 0;
                $total_quantity = 0;
                /**
                 * HEADER DE CIERRE DE INVENTARIO
                 */
                $header = new InventoryClosing();
                $header->date = date('Y-m-d');
                $header->user_id = Auth::user()->id;
                $header->comment = $request->comment;
                $header->status_id = 1;
                $header->month = date('m');
                $header->year = date('Y');
                $header->almacen_id = $bodega->id;
                if (isset($correlative->correlative)){
                    $header->correlative = $correlative->correlative+1;
                    /**
                     * OBTENER LA FECHA
                     */
                    $date_start = date('Y-m-d', strtotime($correlative->year.'-'.$correlative->month.'-01'));
                }
                else{
                    $date_start = GeneralParameter::find(13)->text_value;
                    $header->correlative = 1;
                }
                $monthStart = date('m', strtotime($date_start."+ 1 month"));
                $year = date('Y', strtotime($date_start."+ 1 month"));
                $header->l_month = $monthStart;
                $header->l_year = $year;
                $header->save();
                /**
                 * CREAR CONFIGURACIÓN
                 */
                foreach($items as $item){
                    $tmpQty = BodegaProducto::where('id_product', $item->id)
                        ->where('id_bodega', $bodega->id)
                        ->first();

                    $detail = new InventoryClosingDetail();
                    $detail->item_id = $item->id;
                    $detail->inventory_closing_id = $header->id;
                    $detail->cost = $item->cost_price;
                    if (isset($tmpQty)>0){
                        $detail->quantity = $tmpQty->quantity;
                    }
                    else{
                        $detail->quantity = 0;
                    }
                    $totalCost += $item->cost_price*$detail->quantity;
                    $total_quantity += $detail->quantity;
                    $detail->save();
                }
                $header->amount = $totalCost;
                $header->total_quantity = $total_quantity;
                $header->update();
            }
            \Illuminate\Support\Facades\Session::put('inventory_close', false);
            DB::commit();
        }
        catch(\Exception $ex){
            DB::rollBack();
            Session::flash('message', trans('inventory_closing.error_create'));
            Session::flash('alert-class', 'alert-error');
        }
        return Redirect::to('inventory_closing');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $header = InventoryClosing::find($id);
        $details = InventoryClosingDetail::where('inventory_closing_id', $id)->get();
        return view('inventory_closing.show')
            ->with('header', $header)
            ->with('details', $details);
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
}
