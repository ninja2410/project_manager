<?php

namespace App\Traits;

use App\Credit;
use App\CreditNote;
use App\CreditNoteDetail;
use App\CreditSupplier;
use App\Customer;
use App\GeneralParameter;
use App\InventoryClosing;
use App\Parameter;
use App\Revenue;
use App\Sale;
use App\SaleItem;
use Carbon\Carbon;
use Cassandra\Custom;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Monolog\Handler\IFTTTHandler;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\HttpFoundation\Session\Session;


trait CreditNoteTrait
{
    use ItemsTrait;

    /**
     * Funcion para ingresar en kardex el detalle de la devolución
     * de una factura proveniente de una nota de crédito
     * @param $detalle_devolucion : lista de devoluciones provenientes de interfaz de nota de crédito
     * @param $name_document : Nombre de documento nota de crédito para registrarlo en kardex
     * @param $credit_note_id : ID de nota de crédito a crear
     */
    function setDevolucion($credit_note_id, $detalle_devolucion, $name_document)
    {
        $details = array();
        foreach ($detalle_devolucion as $value) {
            if ($value->value > 0) {
                $detalle_venta = SaleItem::find($value->detail_id);
                $this->updateItemQuantity($detalle_venta->id_bodega, $detalle_venta->item_id, $value->value, $name_document);
                #region Creación de detalle de nota de crédito
                $nc_detail = new CreditNoteDetail();
                $nc_detail->credit_note_id = $credit_note_id;
                $nc_detail->manual_detail = "Devolución de producto";
                $nc_detail->quantity = $value->value;
                $nc_detail->item_id = $detalle_venta->item_id;
                $nc_detail->price = $detalle_venta->selling_price;
                $nc_detail->bodega_id = $detalle_venta->id_bodega;
                $nc_detail->save();
                #endregion

                #region Crear detalle para envío de FEL
                $tmp = array("type"=>$detalle_venta->item->type_id==1?'B':'S',
                    "quantity"=>$value->value,
                    "unity"=>'UND',
                    "description"=>"DEVOLUCION DE PRODUCTO ".$detalle_venta->item->description,
                    "unit_price"=>$detalle_venta->selling_price,
                    "discount"=>0);
                array_push($details, $tmp);
                #endregion
            }
        }
        return $details;
    }

    /**
     * Registro de descuento aplicado a una factura detallado por artículo
     * @param $credit_note_id : Id de nota de crédito encabezado
     * @param $detalle_descuento : Listado de detalle de descuentos realizados JSON
     */
    function setDescuento($credit_note_id, $detalle_descuento){
        $details = array();
        foreach ($detalle_descuento as $value) {
            if ($value->value > 0) {
                if ($value->detail_id != "N/A"){
                    $detalle_venta = SaleItem::find($value->detail_id);
                    #region Creación de detalle de nota de crédito
                    $nc_detail = new CreditNoteDetail();
                    $nc_detail->credit_note_id = $credit_note_id;
                    $nc_detail->manual_detail =  trans('credit_notes.type1');
                    $nc_detail->quantity = 1;
                    $nc_detail->item_id = $detalle_venta->item_id;
                    $nc_detail->price = $value->value;
                    $nc_detail->bodega_id = $detalle_venta->id_bodega;
                    $nc_detail->discount = 1;
                    $nc_detail->save();
                    #endregion

                    #region Crear detalle para envío de FEL
                    $tmp = array("type"=>$detalle_venta->item->type_id==1?'B':'S',
                        "quantity"=>1,
                        "unity"=>'UND',
                        "description"=>strtoupper(trans('credit_notes.type1'))." ".$detalle_venta->item->description,
                        "unit_price"=>$value->value,
                        "discount"=>0);
                    array_push($details, $tmp);
                    #endregion
                }
                else{
                    #region Creación de detalle de nota de crédito
                    $nc_detail = new CreditNoteDetail();
                    $nc_detail->credit_note_id = $credit_note_id;
                    $nc_detail->manual_detail = trans('credit_notes.type4');
                    $nc_detail->quantity = 1;
                    $nc_detail->discount = 1;
                    $nc_detail->price = $value->value;
                    $nc_detail->save();
                    #endregion

                    #region Crear detalle para envío de FEL
                    $tmp = array("type"=>'B',
                        "quantity"=>1,
                        "unity"=>'UND',
                        "description"=>strtoupper(trans('credit_notes.type4')),
                        "unit_price"=>$value->value,
                        "discount"=>0);
                    array_push($details, $tmp);
                    #endregion
                }

            }
        }
        return $details;
    }

    /**
     * Funcion anulación de documentos de venta
     * @param $credit_note_id
     * @param $sale_id
     */
    function setAnulacion($credit_note_id, $sale_id){
//        $sale = Sale::find($sale_id);
//        $sale->cancel_bill = 1;
//        $sale->update();
        $nc = CreditNote::find($credit_note_id);
        $name_document = $nc->serie->document->name.' '.$nc->serie->name.'-'.$nc->correlative;
        $detalles_venta = SaleItem::where('sale_id', $sale_id)->get();
        $details = array();
        foreach($detalles_venta as $value){
            $this->updateItemQuantity($value->id_bodega, $value->item_id, $value->quantity, $name_document);
            #region Registro
                $nc_detail = new CreditNoteDetail();
                $nc_detail->credit_note_id = $credit_note_id;
                $nc_detail->item_id = $value->item_id;
                $nc_detail->manual_detail = "Anulación de documento";
                $nc_detail->quantity = $value->quantity;
                $nc_detail->price = $value->selling_price;
                $nc_detail->bodega_id = $value->id_bodega;
                $nc_detail->save();
            #endregion

            #region Crear detalle para envío de FEL
            $tmp = array("type"=>$detalles_venta->item->type_id==1?'B':'S',
                "quantity"=>$value->quantity,
                "unity"=>'UND',
                "description"=>$detalles_venta->item->description,
                "unit_price"=>$value->selling_price,
                "discount"=>0);
            array_push($details, $tmp);
            #endregion
        }
        return $details;
    }

    /**
     * Aplica el saldo de la nota de crédito creada dependiendo del documento asociado a la misma.
     * @param $creditNote
     * @return mixed
     */
    public function applyNote($creditNote){
        if ($creditNote->sale->pago->type == 6) {
            /**
             * SE GENERA SALDO A FAVOR DEL CLIENTE
             */
            $credit = Credit::whereId_factura($creditNote->sale_id)->first();

            $pendingCredit = $credit->credit_total-$credit->paid_amount;
            if ($creditNote->amount <= $pendingCredit){
                $creditNote->amount_applied = $creditNote->amount;
                $creditNote->status_id = 11;
                $credit->paid_amount += $creditNote->amount;
                $customer = Customer::find($credit->id_cliente);
                $customer->balance -= $creditNote->amount;
                $customer->update();
            }
            else{
                $creditNote->amount_applied = $pendingCredit;
                $creditNote->status_id = 12;
                $credit->paid_amount = $credit->credit_total;
                $customer = Customer::find($credit->id_cliente);
                $customer->balance -= $creditNote->amount_applied;
                $customer->positive_balance += $creditNote->amount - $creditNote->amount_applied;
                $customer->update();
            }


            if ($credit->paid_amount == $credit->credit_total){
                $credit->status_id = 6;
            }
            $credit->update();

        } else {
            $creditNote->status_id = 12;
            $creditNote->amount_applied = 0;
            $customer = Customer::find($creditNote->customer_id);
            $customer->positive_balance += $creditNote->amount;
            $customer->update();
        }
        return $creditNote;
    }

    public function applyAnul($creditNote){
        $sale = $creditNote->sale;
        $sale->nc_amount -= $creditNote->amount;
        $sale->update();
        if ($sale->pago->type == 6){
            /**
             * QUIERE DECIR QUE LA FACTURA ASOCIADA ESTA AL CRÉDITO
             *
             * Crear un nuevo crédito o reactivar el que la nota de crédito anuló
             */
            $credit = Credit::whereId_factura($sale->id)->first();
            if (isset($credit->id)){
                $credit->status_id = 7;
                $credit->paid_amount -= $creditNote->amount_applied;
                $credit->update();
                $customer = Customer::find($credit->id_cliente);
                $customer->balance += $creditNote->amount_applied;
                $customer->positive_balance -= $creditNote->amount - $creditNote->amount_applied;
                $customer->update();
            }
        }
        else{
            /**
             * QUIERE DECIR QUE LA FACTURA NO FUE AL CRÉDITO
             *
             * VERIFICAR QUE LA NOTA DE CRÉDITO NO ESTE APLICADA
             */
            if ($creditNote->amount_applied>0){
                //CREAR UN CRÉDITO CON EL MONTO ASOCIADO A LA NOTA DE CRÉDITO
//                $credito = [];
//                $credito['total_pagos'] = 1;
//                $credito['total_eganche'] = 0;
//                $credito['total_interes'] = 0;
//                $credito['montoCredito'] = $sale->total_cost;
//                $credito['date_payments'] = date('d/m/Y', strtotime(date('Y-d-m', strtotime($sale->sale_date)) . " + 15 days"));
//                $credito['id_cliente'] = $sale->customer_id;
//                $credito['id_factura'] = $sale->id;
//                $nuevo = new Request($credito);
//                $guardarPago = $this->saveCredit($nuevo);
//                if ($guardarPago[0] < 0) {
//                    /**
//                     * Si hubo errores al guardar FOrma de pago
//                     * Hacemos Rollback de la transaccion
//                     * Definimos la bandera de error, guardamos el error.
//                     */
//                    $message = "Error creando crédito:" . $guardarPago[1];
//                    throw new \Exception($message, 6);
//                }
            }
            $customer = Customer::find($creditNote->customer_id);
            $customer->positive_balance -= $creditNote->amount - $creditNote->amount_applied;
            $customer->update();
        }

        $details = CreditNoteDetail::whereCredit_note_id($creditNote->id)
            ->whereNotNull('item_id')
            ->whereDiscount('0')
            ->get();
//        dd($details);
        $name_document = 'Anulación '.$creditNote->serie->document->name.' ['.$creditNote->serie->name.'-'.$creditNote->correlative.']';

        foreach($details as $value){
            $this->updateItemQuantity($value->bodega_id, $value->item_id, $value->quantity * -1, $name_document);
        }
        $creditNote->status_id= 13;
        return $creditNote;
    }
}
