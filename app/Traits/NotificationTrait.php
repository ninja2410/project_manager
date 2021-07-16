<?php

namespace App\Traits;

use App\Account;
use App\Credit;
use App\CreditSupplier;
use App\GeneralParameter;
use App\InventoryClosing;
use App\Notification;
use App\Parameter;
use App\Payment;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\HttpFoundation\Session\Session;


trait NotificationTrait
{

    public function verifyInventoryClosing(){
        $parameter = GeneralParameter::find(11);
        $laps_days = GeneralParameter::find(12);
        $days = (int)$parameter->text_value;
        $notifications = Array();
        $current = InventoryClosing::orderby('id', 'desc')
            ->first();
        if (isset($current)){
            $dateStart = new \DateTime($current->date);
        }
        else{
            $dateStart = new \DateTime($lDate = GeneralParameter::where('name', 'Mes inicial cierres de inventario.')
                ->first()->text_value);
        }
        $today = new \DateTime();
        if (\Illuminate\Support\Facades\Session::get('administrador', false)){
            $url = url("inventory_closing/create");
        }
        else {
            $url = "#";
        }
        $br = "&nbsp;";
        $diferencia = date_diff($dateStart, $today);
        if ($diferencia->m>=$days){
            if ((int)date('d') > (int)$laps_days->text_value){
                \Illuminate\Support\Facades\Session::put('inventory_close', "true");
                $message = "Debe registrar cierre de inventario para registrar movimientos de producto";
            }
            else{
                $days_ = (int)$laps_days->text_value - (int)date('d');
                \Illuminate\Support\Facades\Session::put('inventory_close', "false");
                $message = "Le quedan $days_ días de margen para cerrar el inventario.";

            }

            $notification = Array("message" => $message, "cliente" => null,
                "proveedor" => null, "url" => $url, "module" => "Inventory");
            array_push($notifications, $notification);
        }
        return $notifications;
    }
    public function creditReceivings()
    {
        /*
         * VERIFICAR EL NUMERO DE DIAS
         * */
        $days = (int)GeneralParameter::find(5)->text_value;
        $credits = CreditSupplier::where('status_id', 7)
            ->orderby('supplier_id')
            ->get();
        $hoy = new \DateTime(date('Y-m-d'));
        //$hoy = new \DateTime("2019-12-25");
        $notifications = Array();
        $supplier_id = 0;
        foreach ($credits as $key=> $credit) {
            if ($key==0){
                $supplier_id = $credit->supplier_id;
                $vencidos = 0;
                $proximos = 0;
                $supplier_name = $credit->supplier->company_name;
            }
            if ($credit->supplier_id != $supplier_id){
                /*GENERAR NUEVA NOTIFICACIÓN*/
                if ($vencidos>0){
                    $notification = Array("message" => "$vencidos Cuenta(s) por pagar vencida", "cliente" => null,
                        "proveedor" => $supplier_name, "url" => url("credit_suppliers/" . $supplier_id . "/edit"), "module" => "CXP");
                    array_push($notifications, $notification);
                }
                if ($proximos>0){
                    $notification = Array("message" => "$proximos Cuenta(s) por pagar proximas a vencer", "cliente" => null,
                        "proveedor" => $supplier_name, "url" => url("credit_suppliers/" . $supplier_id . "/edit"), "module" => "CXP");
                    array_push($notifications, $notification);
                }
                $vencidos = 0;
                $proximos = 0;
                $supplier_id = $credit->supplier_id;
                $supplier_name = $credit->supplier->company_name;
            }

            $nuevaFecha1 = explode('/', $credit->date_payments);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1;
            $credito = new \DateTime($fecha1);
            $diferencia = $hoy->diff($credito);
            if ($diferencia->invert){
                $vencidos++;
            }
            else{
                if ($diferencia->days <= $days){
                    $proximos++;
                }
            }
            if (($key+1) == count($credits)){
                /*GENERAR NUEVA NOTIFICACIÓN*/
                if ($vencidos>0){
                    $notification = Array("message" => "$vencidos Cuenta(s) por pagar vencida", "cliente" => null,
                        "proveedor" => $supplier_name, "url" => url("credit_suppliers/" . $supplier_id . "/edit"), "module" => "CXP");
                    array_push($notifications, $notification);
                }
                if ($proximos>0){
                    $notification = Array("message" => "$proximos Cuenta(s) por pagar proximas a vencer", "cliente" => null,
                        "proveedor" => $supplier_name, "url" => url("credit_suppliers/" . $supplier_id . "/edit"), "module" => "CXP");
                    array_push($notifications, $notification);
                }
            }
        }
        return $notifications;
    }

    public function credit()
    {
        /*
         * VERIFICAR EL NUMERO DE DIAS
         * */
        $days = (int)GeneralParameter::find(5)->text_value;
        $credits = Credit::where('status_id', 7)
            ->orderby('id_cliente')
            ->get();
        $hoy = new \DateTime(date('Y-m-d'));
        //$hoy = new \DateTime("2019-12-25");
        $notifications = Array();
        $customer_id = 0;
        foreach ($credits as $key=> $credit) {
            if ($key==0){
                $customer_id = $credit->id_cliente;
                $vencidos = 0;
                $proximos = 0;
                $customer_name = $credit->customer->name;
            }
            if ($credit->id_customer != $customer_id){
                /*GENERAR NUEVA NOTIFICACIÓN*/
                if ($vencidos>0){
                    $notification = Array("message" => "$vencidos Cuenta(s) por cobrar vencida", "cliente" => $customer_name,
                        "proveedor" => null, "url" => url("credit/" . $customer_id . "/edit"), "module" => "CXC");
                    array_push($notifications, $notification);
                }
                if ($proximos>0){
                    $notification = Array("message" => "$proximos Cuenta(s) por cobrar proximas a vencer", "cliente" => $customer_name,
                        "proveedor" => null, "url" => url("credit/" . $customer_id . "/edit"), "module" => "CXC");
                    array_push($notifications, $notification);
                }
                $vencidos = 0;
                $proximos = 0;
                $customer_id = $credit->id_cliente;
                $customer_name = $credit->customer->name;
            }

            $nuevaFecha1 = explode('/', $credit->date_payments);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1;
            $credito = new \DateTime($fecha1);
            $diferencia = $hoy->diff($credito);
            if ($diferencia->invert){
                $vencidos++;
            }
            else{
                if ($diferencia->days <= $days){
                    $proximos++;
                }
            }
            if (($key+1) == count($credits)){
                /*GENERAR NUEVA NOTIFICACIÓN*/
                if ($vencidos>0){
                    $notification = Array("message" => "$vencidos Cuenta(s) por cobrar vencida", "cliente" => $customer_name,
                        "proveedor" => null, "url" => url("credit/" . $customer_id . "/edit"), "module" => "CXC");
                    array_push($notifications, $notification);
                }
                if ($proximos>0){
                    $notification = Array("message" => "$proximos Cuenta(s) por cobrar proximas a vencer", "cliente" => $customer_name,
                        "proveedor" => null, "url" => url("credit/" . $customer_id . "/edit"), "module" => "CXC");
                    array_push($notifications, $notification);
                }
            }
        }
        return $notifications;

    }

    /**
     * Método que lee la tabla de notificaciones para mostratlas
     * @return array
     */
    public function verifyOverdueChecks(){
        $notifications = Array();
        $get = Notification::where('status', 1)->get();
        foreach($get as $value){
            $not = Array("message" => $value->message, "cliente" => null,
                "proveedor" => null, "url" => $value->url, "module" => $value->module);
            array_push($notifications, $not);
        }
        return $notifications;
    }

    public function verifyOverdueChecksAccount($account_id){
        $refDate = date('Y-m-d', strtotime(date('Y-m-d')." - 6 month"));
        $cheques = Payment::where('payment_method', 2)
            ->whereDate('paid_at', '<', $refDate)
            ->where('account_id', $account_id)
            ->where('status', '!=', 2)
            ->orderby('account_id', 'desc')
            ->count();
        return $cheques;
    }

    /**
     * Verifica los cheques emitodos con mas de 6 meses de antiguedad, este método se utiliza en el
     * schedue que funciona como un cron ejecutable todos los dias a las 3 am
     * @return array
     */
    public function verifyOverdueChecksCommand(){
        $notifications = Array();
        $contador = 1;
        $refDate = date('Y-m-d', strtotime(date('Y-m-d')." - 6 month"));
        $cheques = Payment::where('payment_method', 2)
            ->whereDate('paid_at', '<', $refDate)
            ->where('status', '!=', 2)
            ->orderby('account_id', 'desc')
            ->get();
        foreach($cheques as $key => $cheque){
            if (isset($cheques[$key+1]->account_id)){
                if ($cheque->account_id != $cheques[$key+1]->account_id){
                    $account = Account::find($cheque->account_id);
                    $notification = Array("message" => "La cuenta $account->account_name tiene $contador cheque(s) vencidos", "cliente" => $account->id,
                        "proveedor" => null, "url" => ("/banks/accounts/statement/" . $account->id), "module" => "BANKING");
                    array_push($notifications, $notification);
                    $contador = 1;
                }
                else{
                    $contador++;
                }
            }
            else{
                $account = Account::find($cheque->account_id);
                $notification = Array("message" => "La cuenta $account->account_name tiene $contador cheque(s) vencidos", "cliente" => $account->id,
                    "proveedor" => null, "url" => ("/banks/accounts/statement/" . $account->id), "module" => "BANKING");
                array_push($notifications, $notification);
            }
        }
        return $notifications;
    }
}

;
