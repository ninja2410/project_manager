<?php

namespace App\Http\Controllers;

use App\Account;
use App\Almacen;
use App\BodegaProducto;
use App\Document;
use App\GeneralParameter;
use App\Inventory;
use App\InventoryClosing;
use App\Parameter;
use App\Price;
use App\ProductTransfer;
use App\ProductTransferDetail;
use App\ProductTransferPayment;
use App\Receiving;
use App\ReceivingItem;
use App\Serie;
use App\StateCellar;
use App\Traits\ItemsTrait;
use App\Traits\TransactionsTrait;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use \Auth;
use \Input;
use \Redirect;
use \Session;

class TransferController extends Controller
{
    use ItemsTrait;
    use TransactionsTrait;

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
        $status = Input::get('status');
        $all_status = StateCellar::where('type', 'transfer')
            ->get();
        if ($status == null) {
            $status = StateCellar::where('type', 'transfer')
                ->lists('id');
        } else {
            $status = (array)$status;
        }
        $fechaActual = date("Y-m-d");
        if ($fecha1 == null) {
            $fecha1 = $fechaActual;
        } else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1 = $nuevaFecha1[0];
            $mesFecha1 = $nuevaFecha1[1];
            $anioFecha1 = $nuevaFecha1[2];
            $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1;
        }

        if ($fecha2 == null) {
            $fecha2 = $fechaActual;
        } else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2 = $nuevaFecha2[0];
            $mesFecha2 = $nuevaFecha2[1];
            $anioFecha2 = $nuevaFecha2[2];
            $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2;
        }
        $current_user = Auth::user()->id;
        $transfers = ProductTransfer::whereIn('status_id', $status)
            ->whereBetween('date', [$fecha1, $fecha2])
            ->orderBy('created_at', 'desc')->get();
        // return $receivingsReport;
        return view('transfer_to_storage.list_transfer')
            ->with('current_user', $current_user)
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('status', $status)
            ->with('all_status', $all_status)
            ->with('transfers', $transfers);
    }

    public function transfer_details($id)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        #region lectura parámetros y datos generales
        $accounts = [];
        $param = GeneralParameter::where('name', 'Transferencia bancaria por traslados')
            ->first()->active;
        $precio_default = GeneralParameter::where('name', 'Precio default.')->first()->text_value;
        $_price_id = Input::get('price_id');
        if ($_price_id == null) {
            $price_id = $precio_default;
        } else {
            $price_id = $_price_id;
        }
        if (!$param) {
            $price_id = 0;
            $name_view = 'transfer_to_storage.create_basic';
        } else {
            $name_view = 'transfer_to_storage.create';
            $accounts = Account::where('status', 1)->get();
        }
        $prices = Price::where('active', 1)->get();

        $id_storage_origins = Input::get('id_storage_origins');
        $data_documents = Document::join('series', 'documents.id', '=', 'series.id_document')
            ->where('documents.sign', '=', '=')
            ->select(['series.id', DB::raw('concat(documents.name," ",series.name) as document')])->get();
        #endregion

        if ($id_storage_origins == '') {
            $data_storage = Almacen::join('almacen_users', 'almacens.id', '=', 'almacen_users.id_bodega')
                ->where('id_usuario', Auth::user()->id)
                ->where('almacens.id_state', 1)
                ->select('almacens.*')
                ->get();

            return view($name_view)
                ->with('data_storage', $data_storage)
                ->with('selected_storage', 0)
                ->with('prices', $prices)
                ->with('precio_default', $precio_default)
                ->with('price_id', $price_id)
                ->with('last_items', [])
                ->with('type_change', Input::get('type_change'))
                ->with('param', $param)
                ->with('accounts', $accounts)
                ->with('data_documents', $data_documents);
        } else {
            if (input::get('type_change')) {
                $last_items = json_decode(Input::get('last_items'));
            } else {
                $last_items = [];
            }
            //buscamos los productos de la bodega que se esta sele
            $data_items_query = Almacen::join('bodega_productos', 'bodega_productos.id_bodega', '=', 'almacens.id')
                ->where('almacens.id', '=', $id_storage_origins)
                ->join('items', 'bodega_productos.id_product', '=', 'items.id')
                ->whereWildcard(0);
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

            $data_items = $data_items_query->get();

            $data_storage = Almacen::join('almacen_users', 'almacens.id', '=', 'almacen_users.id_bodega')
                ->where('almacen_users.id_usuario', Auth::user()->id)
                ->where('almacens.id_state', 1)
                ->select('almacens.*')
                ->get();
            return view($name_view)
                ->with('data_storage', $data_storage)
                ->with('selected_storage', $id_storage_origins)
                ->with('data_items', $data_items)
                ->with('param', $param)
                ->with('last_items', $last_items)
                ->with('prices', $prices)
                ->with('type_change', Input::get('type_change'))
                ->with('accounts', $accounts)
                ->with('precio_default', $precio_default)
                ->with('price_id', $price_id)
                ->with('data_documents', $data_documents);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            #region VERIFICACIONES DE INVENTARIO
            /**
             * VERIFICAR SI ES NECESARIO EL CIERRE DE INVENTARIO
             */
            if (\Illuminate\Support\Facades\Session::get('inventory_close', false)) {
                throw new \Exception("Se requiere el cierre de inventario del mes en curso.", 6);
            }

            /**
             * VERIFICAR SI SE QUIERE REALIZAR UA VENTA EN UN MES CON EL INVENTARIO CERRADO
             */
            #region VERIFICAR ULTIMOM CIERRE
            $dlast = InventoryClosing::orderby('id', 'desc')->first();
            $refDate = "";
            if (isset($dlast->date)) {
                $refDate = date('m/Y', strtotime($dlast->date));
            } else {
                $paramD = GeneralParameter::find(13)->text_value;
                $refDate = date('m/Y', strtotime($paramD));
            }

            $sDate = date('m/Y');
            if ($refDate == $sDate && !isset($paramD)) {
                throw  new \Exception("No se puede realizar transacciones de inventario en un mes cerrado.", 6);
            }
            #endregion
            #endregion


            $item_quantity = $request->item_quantity;
            $logged_user = Auth::user();
            if ($item_quantity == 0) {
                throw new \Exception("Agregue los productos", 6);
            } else {
                $param = GeneralParameter::where('name', 'Transferencia bancaria por traslados')
                    ->first()->active;

                // echo 'correlativo '.$correlativo.'<br>';
                // print_r($correlativo);
                // exit($correlativo);

                $serie = $request->id_document;

                $correlativo = ProductTransfer::where('serie_id', '=', $serie)
                    ->max('correlative');
                if ((!isset($correlativo)) || ($correlativo == ""))
                    $correlativo = 0;

                //guardamos el emcabezado dell documento
                $transfer = new ProductTransfer();
                $transfer->date = date('Y-m-d');
                $transfer->correlative = $correlativo + 1;
                $transfer->amount = $request->totalCost;
                $transfer->quantity_items = $item_quantity;
                $transfer->comment = $request->tsComment;
                $transfer->serie_id = $serie;
                $transfer->created_by = $logged_user->id;
                $transfer->almacen_origin = $request->bodega_origen;
                $transfer->almacen_destination = $request->bodega_destino;
                $transfer->status_id = 8;

                if ($param) {
                    $transfer->account_credit_id = $request->account_origin;
                    $transfer->price_id = ($request->price_id == 0 ? null : $request->price_id);
                }
                $transfer->save();
                $id_transaccion = $transfer->id;

                if ($param) {
                    $payments = json_decode($request->payments);
                    foreach ($payments as $payment) {
                        #region REALIZAR GASTP DE EFECTIVO POR TRASLADO
                        $gastos = [];
                        $gastos['account_id'] = $payment->account_id;
                        $gastos['paid_at'] = date('d/m/Y');
                        $gastos['amount'] = $payment->amount;
                        $gastos['description'] = $request->tsComment;
                        $gastos['category_id'] = 3;
                        $gastos['reference'] = $transfer->serie->document->name . ' ' . $transfer->serie->name . '-' . $transfer->correlative;
                        $gastos['user_id'] = Auth::user()->id;
                        $gastos['status'] = 1;
                        $gastos['payment_method'] = 1;
                        $gastos['supplier_id'] = null;
                        $gastos['invoice_id'] = $transfer->id;
                        $gastos['amount_applied'] = $request->totalCost;
                        $request_expense = new Request($gastos);

                        $guardarPago = $this->saveExpense($request_expense);
                        if ($guardarPago[0] < 0) {
                            /**
                             * Si hubo errores al guardar Forma de pago
                             * Hacemos Rollback de la transaccion
                             * Definimos la bandera de error, guardamos el error.
                             */
                            $message = "Error registrando el traslado de efectivo:" . $guardarPago[1];
                            throw new \Exception($message, 6);
                        }
                        #endregion
                        #region GUARDAR DETALLE DE PAGO
                        $detail_payment = new ProductTransferPayment();
                        $detail_payment->amount = $payment->amount;
                        $detail_payment->account_id = $payment->account_id;
                        $detail_payment->confirm_amount =0;
                        $detail_payment->product_transfer_id = $transfer->id;
                        $detail_payment->transaction_id = $guardarPago[0];
                        $detail_payment->save();
                        #endregion
                    }
                }


                $qty_total = 0;
                for ($i = 0; $i < $item_quantity; $i++) {
                    // $cantidad=$request->cantidad_.$i;
                    $cantidad = Input::get('cantidad_' . $i);
                    $id_producto = Input::get('id_product_' . $i);
                    $costo_producto = Input::get('id_productcost_' . $i);
                    $qty_total += $cantidad;

                    //guardamos hacemos el traslado a la bodega correspondiente
                    /*
                     * SE REGISTRA UNICAMENTE LA SALIDA DE LA BODEGA
                     * */
//                $data_transfer = new BodegaProducto;
//                $exists = BodegaProducto::where('id_bodega', '=', $request->bodega_destino)
//                    ->where('id_product', '=', $id_producto)->value('id');
//                if (!$exists) {
//                    $data_transfer->id_bodega = $request->bodega_destino;
//                    $data_transfer->id_product = $id_producto;
//                    $data_transfer->quantity = $cantidad;
//                    $data_transfer->save();
//
//                } else {
//                    $data_transfer_update = BodegaProducto::find($exists);
//                    $data_transfer_update->quantity = $data_transfer_update->quantity + $cantidad;
//                    $data_transfer_update->save();
//                }
//
                    //guardamos el detalle de la transaccion
                    $detail = new ProductTransferDetail();
                    $detail->product_transfer_id = $id_transaccion;
                    $detail->item_id = $id_producto;
                    $detail->quantity = $cantidad;
                    $detail->cost = $costo_producto;
                    $detail->save();

                    //hacemos el descuento en la bodega correspondiente
                    $id_product_storage = BodegaProducto::where('id_bodega', '=', $request->bodega_origen)
                        ->where('id_product', '=', $id_producto)->value('id');

                    $data_items = BodegaProducto::find($id_product_storage);
                    $data_items->quantity = $data_items->quantity - $cantidad;
                    $data_items->save();

                    //guardamos en el Kardex
//                $inventories = new Inventory;
//                $inventories->almacen_id = $request->bodega_destino;
//                $inventories->item_id = $id_producto;
//                $inventories->user_id = Auth::user()->id;
//                $inventories->in_out_qty = $cantidad;

                    $invent = new Inventory;
                    $invent->almacen_id = $request->bodega_origen;
                    $invent->item_id = $id_producto;
                    $invent->user_id = Auth::user()->id;
                    $invent->in_out_qty = $cantidad * -1;

                    //Obtenemos la serie
                    $nombreSerie = Serie::find($serie);
                    //Obtenemos el nombre del documento
                    $nombreDocument = Document::where('id', '=', $nombreSerie->id_document)->value('name');
                    $nombreDeTransaccion = $nombreDocument . ' ' . $nombreSerie->name . '-' . ($correlativo + 1);
                    //$inventories->remarks = $nombreDeTransaccion;
                    $invent->remarks = $nombreDeTransaccion;
                    //$inventories->save();
                    $invent->save();
                }
                $transfer->quantity_items = $qty_total;
                $transfer->update();
            }
            DB::commit();
            // echo "Transaccion completada";
            Session::flash('message', 'Traslado realizado correctamente');
            Session::flash('alert-type', 'success');

        } catch (\Exception $ex) {
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-type', 'alert-danger');

        }
        return Redirect::to('/transfer_to_storage');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $current_user = Auth::user()->id;
        $header = ProductTransfer::find($id);
        $details = ProductTransferDetail::where('product_transfer_id', $id)->get();
        $payments = ProductTransferPayment::where('product_transfer_id', $id)->get();
        return view('transfer_to_storage.show')
            ->with('details', $details)
            ->with('payments', $payments)
            ->with('current_user', $current_user)
            ->with('header', $header);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $header = ProductTransfer::find($id);
        $details = ProductTransferDetail::where('product_transfer_id', $id)->get();
        $payments = ProductTransferPayment::where('product_transfer_id', $id)->get();
        return view('transfer_to_storage.receive')
            ->with('details', $details)
            ->with('payments', $payments)
            ->with('header', $header);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $confirmed_cost = 0;
        $pending_cost = 0;
        DB::beginTransaction();
        try {
            $header = ProductTransfer::find($id);
            $details = json_decode($request->details_received);
            $payments = json_decode($request->details_payment);
            /*
             * ACTUALIZANDO CANTIDADES RECIBIDAS
             * */
            foreach ($details as $key => $value) {
                $det = ProductTransferDetail::find($value->detail_id);
                $det->quantity_received = $value->quantity_recived;
                $det->update();

                /**
                 * ACTUALIZAR BODEGA DESTINO
                 */
                $this->updateItemQuantity($header->almacen_destination, $det->item_id,
                    $value->quantity_recived, $header->serie->document->name . ' ' . $header->serie->name . '-' . $header->correlative);

                //VERIFICAR SI HACEN FALTA ARTICULOS EN LA ORDEN
                if ($det->quantity > $value->quantity_recived) {
                    $this->updateItemQuantity($header->almacen_origin, $det->item_id,
                        ($det->quantity - $value->quantity_recived), 'Producto no confirmado: ' . $header->serie->document->name . ' ' . $header->serie->name . '-' . $header->correlative);
                    $pending_cost += $det->cost * ($det->quantity - $value->quantity_recived);
                } else {
                    $confirmed_cost += ($det->cost * $det->quantity);
                }
            }
            $header->status_id = 9;
            $header->updated_by = Auth::user()->id;
            $header->date_received = date('Y-m-d');
            $header->update();
            $param = GeneralParameter::where('name', 'Transferencia bancaria por traslados')
                ->first()->active;
            if ($param) {

                #region ACTUALIZAR EL PAGO
                foreach ($payments as $payment){
                    $pay = ProductTransferPayment::find($payment->payment_id);
                    $pay->confirm_amount = $payment->amount_confirm;
                    $pay->update();

                    $new_revenue = [];
                    $new_revenue['account_id'] = $header->account_credit_id;
                    $new_revenue['paid_at'] = date('d/m/Y');
                    $new_revenue['amount'] = $payment->amount_confirm;
                    $new_revenue['description'] = $header->comments;
                    $new_revenue['category_id'] = 4;
                    $new_revenue['reference'] = $header->serie->document->name . ' ' . $header->serie->name . '-' . $header->correlative;
                    $new_revenue['same_bank'] = 1;
                    $new_revenue['user_id'] = Auth::user()->id;
                    $new_revenue['status'] = 1;
                    // $new_revenue['customer_id'] = $request->customer_id;
                    $new_revenue['payment_method'] = 1;
                    $request_revenue = new Request($new_revenue);
                    $guardar = $this->saveRevenue($request_revenue);

                    if ($guardar[0] < 0) {
                        throw new \Exception($guardar[1], 6);
                    }


                    if ($pay->amount > $pay->confirm_amount) {
                        #region DEVOLVER EL MONTO MONETARIO DE LO QUE NO ENTRÓ EN EL TRASLADO
                        $new_revenue = [];
                        $new_revenue['account_id'] = $pay->account_id;
                        $new_revenue['paid_at'] = date('d/m/Y');
                        $new_revenue['amount'] = ($pay->amount - $pay->confirm_amount);
                        $new_revenue['description'] = 'Saldo traslado de bodega no confirmado.';
                        $new_revenue['category_id'] = 4;
                        $new_revenue['reference'] = $header->serie->document->name . ' ' . $header->serie->name . '-' . $header->correlative;
                        $new_revenue['same_bank'] = 1;
                        $new_revenue['user_id'] = Auth::user()->id;
                        $new_revenue['status'] = 1;
                        // $new_revenue['customer_id'] = $request->customer_id;
                        $new_revenue['payment_method'] = 1;
                        $request_revenue = new Request($new_revenue);
                        $guardar = $this->saveRevenue($request_revenue);

                        if ($guardar[0] < 0) {
                            throw new \Exception($guardar[1], 6);
                        }
                        #endregion
                    }
                    #endregion
                }
            }

            Session::flash('message', 'El traslado se ha actualizado correctamente');
            Session::flash('alert-type', trans('success'));
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            Session::flash('message', 'No se han podido actualizar los datos del traslado. [' . $ex->getMessage() . ']');
            Session::flash('alert-class', 'alert-error');
        }

        return Redirect::to('transfer_to_storage/' . $header->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
