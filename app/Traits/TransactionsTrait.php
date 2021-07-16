<?php

namespace App\Traits;

use App\BankReconciliation;
use App\CreditNote;
use App\CreditSupplier;
use App\CreditSupplierDetail;
use App\Notification;
use App\RegRetention;
use App\Supplier;
use DB;

use App\Sale;
use App\Expense;

use \Auth;
use \Validator;
use App\Credit;
use App\Account;

use App\Payment;
use App\Receiving;
use App\Revenue;
use App\Customer;

use App\Transfer;
use App\DetailCredit;
use App\CreditPayment;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\Request as AppRequest;

trait TransactionsTrait
{
    use NotificationTrait;
    public function saveRevenue(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!isset($request->currency)) {
                $request->currency = 'Q';
                $request->request->add(['currency' => 'Q']);
            }
            if (!isset($request->currency_rate)) {
                $request->currency_rate = 1;
                $request->request->add(['currency_rate' => 1]);
            }
            if (!isset($request->status)) {
                // $request->status = 1;
                $request->request->add(['status' => 1]);
            }

            $account_id = $request->account_id;
            $account_name = Account::find($account_id);
            $dt_ = $request->paid_at;
            $arr_ = explode("/", $dt_);
            $nw_ = $arr_[2] . '-' . $arr_[1] . '-' . $arr_[0];
            $conciliation = BankReconciliation::where('account_id', $account_id)
                ->where('month', $arr_[1])
                ->where('year', $arr_[2])
                ->where('closed', 1)
                ->count();
            if ($conciliation > 0) {
                throw new \Exception(' No se puede registrar operaciones con fecha de: ' . trans('months.' . (int)$arr_[1]) . ' en la cuenta ' . $account_name->account_name . ' porque el mes esta cerrado.', 6);
            }
            $amount = $request->amount;

            $request->payment_method = ($request->payment_method === "" ? 1 : $request->payment_method);
            $request->status = ($request->status === "" ? 5 : $request->status);
            /**Si no viene status, NO conciliado */

            $request->invoice_id = ($request->invoice_id === "" ? null : $request->invoice_id);
            $request->serie_id = ($request->serie_id === "" ? null : $request->serie_id);
            $request->customer_id = ($request->customer_id === "" ? null : $request->customer_id);

            $request->bank_name = ($request->bank_name === "" ? null : $request->bank_name);
            $request->same_bank = ($request->same_bank === "" ? 0 : $request->same_bank);

            $request->card_name = ($request->card_name === "" ? null : $request->card_name);
            $request->card_number = ($request->card_number === "" ? null : $request->card_number);

            $request->amount_applied = (($request->amount_applied === "" || $request->amount_applied === null) ? $amount : $request->amount_applied);
            $request->receipt_number = ($request->receipt_number === null ? "" : $request->receipt_number);

            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'account_id' => 'required',
                'paid_at' => 'required',
                'amount' => 'required|min:1',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return ['-1', $validator];
            }

            $revenue = new Revenue();
            $revenue->account_id = $account_id;
            $revenue->paid_at = $nw_;
            $revenue->amount = $amount;
            $revenue->receipt_number = $request->receipt_number;
            $revenue->currency = $request->currency;
            $revenue->currency_rate = $request->currency_rate;
            $revenue->invoice_id = $request->invoice_id;
            $revenue->customer_id = $request->customer_id;
            $revenue->description = $request->description;
            // $revenue->category_id = $request->category_id;
            $revenue->payment_method = $request->payment_method;
            $revenue->reference = $request->reference;
            $revenue->status = $request->status;
            $revenue->user_id = $request->user_id;

            $revenue->bank_name = $request->bank_name;
            $revenue->same_bank = $request->same_bank;

            $revenue->card_name = $request->card_name;
            $revenue->card_number = $request->card_number;
            $revenue->amount_applied = $request->amount_applied;

            $revenue->serie_id = $request->serie_id;
            if (isset($request->deposit) && $request->deposit > 0){
                $revenue->deposit = $request->deposit;
            }
            $revenue->save();

            $account = Account::find($account_id);
            $account->balance = $amount;
            $account->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "Error ingreso:" . $e->getMessage();
            return ['-1', $message];
        }

        return [$revenue->id, 'sucess'];
    }

    public function saveExpenseGeneral(Request $request)
    {
        $dt_ = $request->paid_at;
        $arr_ = explode("/", $dt_);
        $nw_ = $arr_[2] . '-' . $arr_[1] . '-' . $arr_[0];
        DB::beginTransaction();
        try {
            $expense = new Expense();
            $expense->category_id = $request->category_id;
            $expense->account_id = $request->account_id;
            $expense->supplier_id = ($request->supplier_id === ""? null : $request->supplier_id);
            $expense->description = $request->description;
            $expense->payment_type_id = $request->payment_method;
            $expense->expense_date = $nw_;
            $expense->state_id = 1;
            $expense->document_type_id = $request->document_type_id;
            $expense->reference = $request->reference;
            $expense->assigned_user_id = $request->assigned_user_id;//**
            $expense->route_id = $request->route_id;//**
            $expense->amount = $request->amount;
            $expense->cant = $request->cant;//**
            $expense->unit_price = $request->unit_price;//**
            $expense->payment_status = $request->payment_status;//**
            $expense->created_by = Auth::user()->id;
            $expense->credit_note_id = ($request->credit_note_id == ""? null : $request->credit_note_id);
            $expense->bank_expense_id = $request->bank_expense_id;
            $expense->save();

            #region Actualizar estado de nota de crédito
            if (isset($request->credit_note_id) && $request->credit_note_id!=""){
                $cn = CreditNote::find($request->credit_note_id);
                $cn->amount_applied += $request->amount;
                if ($cn->amount == $cn->amount_applied){
                    $cn->status_id = 11;
                }
                $cn->update();

                /**
                 * ACTUALIZAR SALDO A FAVOR DE CLIENTE
                 */
                $cn->customer->positive_balance -= $request->amount;
                $cn->customer->update();
            }
            #endregion

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = "Error en gasto:" . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
            return ['-1', $message];
        }
        return [$expense->id, 'sucess'];
    }

    public function saveExpense(Request $request)
    {
        DB::beginTransaction();
        try {
            $dt_ = $request->paid_at;
            $arr_ = explode("/", $dt_);
            $nw_ = $arr_[2] . '-' . $arr_[1] . '-' . $arr_[0];
            $account_id = $request->account_id;
            $account_name = Account::find($account_id);
            $conciliation = BankReconciliation::where('account_id', $account_id)
                ->where('month', $arr_[1])
                ->where('year', $arr_[2])
                ->where('closed', 1)
                ->count();
            if ($conciliation > 0) {
                throw new \Exception(' No se puede registrar operaciones con fecha de: ' . trans('months.' . (int)$arr_[1]) . ' en la cuenta ' . $account_name->account_name . ' porque el mes esta cerrado.', 6);
            }

            // dd($request->all());
            if (!isset($request->currency)) {
                $request->currency = 'Q';
                $request->request->add(['currency' => 'Q']);

            }
            if (!isset($request->currency_rate)) {
                $request->currency_rate = 1;
                $request->request->add(['currency_rate' => 1]);
            }

            if (!isset($request->status)) {
                // $request->status = 1;
                $request->request->add(['status' => 5]);
            }
            // dd($request);

            $request->payment_method = ($request->payment_method === "" ? 1 : $request->payment_method);
            $request->status = ($request->status === "" ? 5 : $request->status);
            /**Si no viene status, NO conciliado */

            $request->bill_id = ($request->bill_id === "" ? null : $request->bill_id);
            $request->supplier_id = ($request->supplier_id === "" ? null : $request->supplier_id);
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'account_id' => 'required',
                'paid_at' => 'required',
                'amount' => 'required|min:1',
                // 'category_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return ['-1', $validator];
            }

            $account_id = $request->account_id;
            $amount = $request->amount;
            $expense = new Payment();
            $expense->account_id = $account_id;
            $expense->paid_at = $nw_;
            $expense->amount = $amount;
            $expense->currency = $request->currency;
            $expense->currency_rate = $request->currency_rate;
            $expense->bill_id = $request->bill_id;
            $expense->supplier_id = $request->supplier_id;
            $expense->description = $request->description;
            $expense->recipient = $request->recipient;
            $expense->payment_method = $request->payment_method;
            $expense->reference = $request->reference;
            $expense->category_id = $request->category_id;
            $expense->status = $request->status;
            $expense->user_id = $request->user_id;
            if (isset($request->stage_id)) {
                $expense->stage_id = $request->stage_id;
            }
            if (isset($request->cash_register_id)) {
                $expense->cash_register_id = $request->cash_register_id;
            }
            // dd($expense);
            $expense->save();
            $account = Account::find($account_id);
            $account->balance = -$amount;

            $account->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "Error egreso:" . $e->getMessage();
            return ['-1', $message];
        }
        return [$expense->id, 'sucess'];
    }

    public function saveTransfer(Request $revenue, Request $expense)
    {
        $expense = $this->saveExpense($expense);
        $revenue = $this->saveRevenue($revenue);

        if ($expense[0] < 0) {
            return ['-1', $expense[1]];
        }
        if ($revenue[0] < 0) {
            return ['-1', $revenue[1]];
        }
        // echo ' expense <br>';
        // var_dump($expense[0]);
        // echo "<br><br>";
        // echo ' revenue <br>';
        // var_dump($revenue[0]);
        // exti();
        $new_transfer = new Transfer();

        $new_transfer->payment_id = $expense[0];
        $new_transfer->revenue_id = $revenue[0];
        $new_transfer->user_id = auth()->user()->id;;
        $new_transfer->status = 1;
        $new_transfer->save();
        return [$new_transfer->id, 'success'];


    }

    public function saveCredit(Request $creditRequest)
    {
        DB::beginTransaction();
        try {
            // return $request->all();
            $creditData = new Credit;
            $totalCuotas = $creditRequest->total_pagos;
            $totalEnganche = $creditRequest->total_eganche;
            $totalInteres = $creditRequest->total_interes;
            $totalMonto = $creditRequest->montoCredito;


            // $fecha = "2017-08-15";
            $fecha = $creditRequest->date_payments;
            $idcliente = $creditRequest->id_cliente;
            $idFactura = $creditRequest->id_factura;

            $creditData->id_cliente = $idcliente;
            $creditData->id_factura = $idFactura;
            $creditData->number_payments = $totalCuotas;
            $creditData->enganche = $totalEnganche;
            $creditData->credit_total = $totalMonto;
            $creditData->total_interes = $totalInteres;
            $creditData->date_payments = $fecha;
            $creditData->status_id = 7;
            /**No Pagado */

            $creditData->save();

            //buscamos cliente y  actualizamos saldo
            $findCustomer = Customer::find($idcliente);
            $findCustomer->balance = $findCustomer->balance + $creditRequest->montoCredito;
            $findCustomer->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "Error credito:" . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
            return ['-1', $message];
        }

        return [$creditData->id, 'success'];
    }

    public function saveCreditSupplier(Request $creditRequest)
    {
        DB::beginTransaction();
        try {
            // return $request->all();
            $creditData = new CreditSupplier();
            $totalCuotas = $creditRequest->total_pagos;
            $totalEnganche = $creditRequest->total_eganche;
            $totalInteres = $creditRequest->total_interes;
            $totalMonto = $creditRequest->montoCredito;


            // $fecha = "2017-08-15";
            $fecha = $creditRequest->date_payments;
            $idSupplier = $creditRequest->id_supplier;
            $idFactura = $creditRequest->id_factura;

            $creditData->supplier_id = $idSupplier;
            $creditData->receiving_id = $idFactura;
            $creditData->number_payments = $totalCuotas;
            $creditData->enganche = $totalEnganche;
            $creditData->credit_total = $totalMonto;
            $creditData->total_interes = $totalInteres;
            $creditData->date_payments = $fecha;
            $creditData->status_id = 7;
            /**No Pagado */

            $creditData->save();

            //buscamos cliente y  actualizamos saldo
            $findSupplier = Supplier::find($idSupplier);
            $findSupplier->balance = $findSupplier->balance + $creditRequest->montoCredito;
            $findSupplier->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "Error credito de proveedor:" . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
            return ['-1', $message];
        }

        return [$creditData->id, 'success'];
    }

    public function saveCreditPayment(Request $creditRequest)
    {
        DB::beginTransaction();
        try {
            $monto_abono = round($creditRequest->amount, 2);
            $findCredit = Credit::find($creditRequest->credit_id);
            $find_invoice = Sale::find($findCredit->id_factura);

            /**
             * Si el monto a pagar es mayor al monto del crédito o al monto de la factura
             * No permitir.
             */
            $diferencia_credito = round($findCredit->credit_total - $findCredit->paid_amount, 2);
            $diferencia_factura = round($find_invoice->total_cost - $find_invoice->total_paid, 2);
            // if($monto_abono>$diferencia_credito) || $monto_abono > $diferencia_factura)
            if ($monto_abono > $diferencia_credito) {
                DB::rollBack();
                $message = "Abono no puede ser mayor al crédito total o al monto total de la factura." . $monto_abono . ' dif c ' . $diferencia_credito . ' dif f ' . $diferencia_factura;
                return ['-1', $message];

            } else if ($diferencia_credito == $monto_abono) {
                $findCredit->status_id = 6;
                $find_invoice->payment_status = 1;
                // $find_invoice->total_paid = $find_invoice->total_cost;
            }

            /**
             * Si el monto a pagar es igual a lo que falta por pagar
             * marcar el crédito como pagado.
             */
            // if(($findCredit->credit_total-$findCredit->paid_amount)===$monto_abono)
            // if(($monto_abono+$findCredit->paid_amount)===$findCredit->credit_total)

            $findCredit->paid_amount = $findCredit->paid_amount + $monto_abono;
            $findCredit->save();

            $find_invoice->total_paid = $find_invoice->total_paid + $monto_abono;
            $find_invoice->save();

            /**
             * Si el monto pagado es igual al monto total de la factura
             * Marcar la factura como pagada.
             */
            // if($creditRequest->amount===$find_invoice->total_cost)
            // {


            // }

            // return $request->all();
            $creditData = new CreditPayment;

            // $fecha = "2017-08-15";
            $creditData->paid_date = $creditRequest->paid_date;
            $creditData->credit_id = $creditRequest->credit_id;
            $creditData->revenue_id = $creditRequest->payment_id;
            $creditData->comment = $creditRequest->comment;
            $creditData->created_by = $creditRequest->created_by;
            $creditData->amount = $monto_abono;
            $creditData->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "Error pago credito:" . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
            return ['-1', $message];
        }

        return [$creditData->id, 'success'];
    }

    public function saveCreditPaymentSupplier(Request $creditRequest)
    {
        DB::beginTransaction();
        try {
            $monto_abono = round($creditRequest->amount, 2);
            $findCredit = CreditSupplier::find($creditRequest->credit_id);
            $find_invoice = Receiving::find($findCredit->receiving_id);
            /**
             * Si el monto a pagar es mayor al monto del crédito o al monto de la factura
             * No permitir.
             */
            $diferencia_credito = round($findCredit->credit_total - $findCredit->paid_amount, 2);
            $diferencia_factura = round($find_invoice->total_cost - $find_invoice->total_paid, 2);
            // if($monto_abono>$diferencia_credito) || $monto_abono > $diferencia_factura)
            if ($monto_abono > $diferencia_credito) {
                DB::rollBack();
                $message = "Abono no puede ser mayor al crédito total o al monto total de la factura." . $monto_abono . ' dif c ' . $diferencia_credito . ' dif f ' . $diferencia_factura;
                return ['-1', $message];

            } else if ($diferencia_credito == $monto_abono) {
                $findCredit->status_id = 6;
                $find_invoice->payment_status = 1;
                // $find_invoice->total_paid = $find_invoice->total_cost;
            }

            /**
             * Si el monto a pagar es igual a lo que falta por pagar
             * marcar el crédito como pagado.
             */
            // if(($findCredit->credit_total-$findCredit->paid_amount)===$monto_abono)
            // if(($monto_abono+$findCredit->paid_amount)===$findCredit->credit_total)

            $findCredit->paid_amount = $findCredit->paid_amount + $monto_abono;
            $findCredit->save();

            $find_invoice->total_paid = $find_invoice->total_paid + $monto_abono;
            $find_invoice->save();

            /**
             * Si el monto pagado es igual al monto total de la factura
             * Marcar la factura como pagada.
             */
            // if($creditRequest->amount===$find_invoice->total_cost)
            // {


            // }

            // return $request->all();
            $creditData = new CreditSupplierDetail();

            // $fecha = "2017-08-15";
            $creditData->paid_date = $creditRequest->paid_date;
            $creditData->credit_supplier_id = $creditRequest->credit_id;
            $creditData->expense_id = $creditRequest->payment_id;
            $creditData->comment = $creditRequest->comment;
            $creditData->receiving_id = $creditRequest->id_factura;
            $creditData->created_by = $creditRequest->created_by;
            $creditData->amount = $monto_abono;
            $creditData->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $message = "Error pago credito a proveedor:" . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
            return ['-1', $message];
        }

        return [$creditData->id, 'success'];
    }

    /**
     * Función que permite anular una transacción bancaria
     * @param $tx_id : ID de transacción a anular
     * @param $type : tipo de transacción a anular, puede ser 'revenue' o 'payment'
     * @param $is_anul_invoice : Parametro opcional para indicar que la anulaciónd e transacción viene de una factura ya anulada
     */
    public function cancelTransaction($tx_id, $type, $is_anul_invoice)
    {
        DB::beginTransaction();
        try {
            if ($type == 'revenue') {
                $transaction = Revenue::find($tx_id);
                $sign = -1;
            } else {
                $transaction = Payment::find($tx_id);
                $sign = 1;
            }
            $account = Account::find($transaction->account_id);

            $conciliation = BankReconciliation::where('account_id', $account->id)
                ->where('month', date('m'))
                ->where('year', date('Y'))
                ->where('closed', 1)
                ->count();
            if ($conciliation > 0) {
                throw new \Exception(' No se puede registrar operaciones con fecha de: ' . trans('months.' . (int)date('m')) . '/' . date('Y') . ' en la cuenta ' . $account->account_name . ' porque el mes esta cerrado.', 6);
            }

            if ($transaction->reconcilied) {
                throw new \Exception("No se pueden anular transacciones marcadas como conciliadas.", 6);
            }


            //Modificar saldo de la cuenta
            $account->balance = $sign * ($transaction->amount);
            $account->update();
            $transaction->status = 2;
            $transaction->update();
            //
            #region acciones adicionales a ingresos
            if ($type == 'revenue') {
                #region Si el pago tiene asociado una factura de venta dejarla al crédito
                if (isset($transaction->invoice_id)&&!$is_anul_invoice) {
                    $sale = Sale::find($transaction->invoice_id);
                    $sale->id_pago = 6;
                    $sale->total_paid = 0;
                    $sale->payment_status = 0;
                    $sale->update();
                    $customer = Customer::find($sale->customer_id);
                    $credito = [];
                    $credito['total_pagos'] = 1;
                    $credito['total_eganche'] = 0;
                    $credito['total_interes'] = 0;
                    $credito['montoCredito'] = $sale->total_cost;
                    $credito['date_payments'] = date('d/m/Y', strtotime(date('Y-d-m', strtotime($sale->sale_date)) . " + 15 days"));
                    $credito['id_cliente'] = $sale->customer_id;
                    $credito['id_factura'] = $sale->id;
                    $nuevo = new Request($credito);
                    $guardarPago = $this->saveCredit($nuevo);
                    if ($guardarPago[0] < 0) {
                        /**
                         * Si hubo errores al guardar FOrma de pago
                         * Hacemos Rollback de la transaccion
                         * Definimos la bandera de error, guardamos el error.
                         */
                        $message = "Error creando crédito:" . $guardarPago[1];
                        throw new \Exception($message, 6);
                    }
                }
                #endregion

                #region VERIFICAR SI ES UN PAGO DE CREDITO
                $payments = CreditPayment::whereRevenue_id($tx_id)->get();
                foreach ($payments as $payment) {
                    $payment->credit->paid_amount -= $payment->amount;
                    $payment->credit->status_id = 7;
                    $payment->credit->save();
                    $payment->credit->customer->balance += $payment->amount;
                    $payment->credit->customer->save();
                    $payment->delete();
                }
                #endregion

                #region VERIFICAR SI NO TIENE RETENCIONES ASOCIADAS
                $retentions = RegRetention::whereRevenue_origin_id($tx_id)->get();
                foreach($retentions as $retention){
                    $this->cancelTransaction($retention->revenue_id, 'revenue', false);
                    $this->cancelTransaction($retention->expense_id, 'expense', false);
                    $retention->delete();
                }
                #endregion
            }
            #endregion

            #region acciones adicionales a egresos
            if ($type=='payment'){
                #region Actaulizar listado de notificaciones
                $tmp = $this->verifyOverdueChecksAccount($account->id);
                if ($tmp == 0){
                    Notification::where('account_id', $account->id)->delete();
                }
                #endregion
                #region Verificar si no tiene compras asociadas
                if (isset($transaction->bill_id) && !$is_anul_invoice){
                    $receiving = Receiving::find($transaction->bill_id);
                    $receiving->id_pago = 6;
                    $receiving->total_paid = 0;
                    $receiving->payment_type = 0;
                    $receiving->update();
                    #region Si esta asociado a una compra actualizar la misma a crédito
                    $credito = [];
                    $credito['total_pagos']     = 1;
                    $credito['total_eganche']   = 0;
                    $credito['total_interes']   = 0;
                    $credito['montoCredito']    = $receiving->total_cost;
                    $credito['date_payments']   = date('d/m/Y', strtotime(date('Y-m-d', strtotime($receiving->date))." + 15 days"));
                    $credito['id_supplier']      = $receiving->supplier_id;
                    $credito['id_factura']      = $receiving->id;

                    $nuevo = new Request($credito);
                    $guardarPagoCredit = $this->saveCreditSupplier($nuevo);
                    if ($guardarPagoCredit[0] < 0) {
                        /**
                         * Si hubo errores al guardar FOrma de pago
                         * Hacemos Rollback de la transaccion
                         * Definimos la bandera de error, guardamos el error.
                         */
                        $message = "Error en creación de crédito:" . $guardarPagoCredit[1];
                        throw new \Exception($message, 6);
                    }
                    #endregion
                }
                #endregion
                #region Verificar si es pago de cuenta por pagar
                $payments = CreditSupplierDetail::whereExpense_id($tx_id)->get();
                foreach($payments as $payment){
                    $payment->credit->paid_amount -= $payment->amount;
                    $payment->credit->status_id = 7;
                    $payment->credit->save();
                    $payment->credit->supplier->balance += $payment->amount;
                    $payment->credit->supplier->save();
                    $payment->delete();
                }
                #endregion
            }
            #endregion

            DB::commit();
            return [1, trans('revenues.anul_ok')];
        } catch (\Exception $e) {
            DB::rollback();
            return [-1, $e->getMessage()];
        }
    }

}


