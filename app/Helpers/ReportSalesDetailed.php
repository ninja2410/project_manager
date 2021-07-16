<?php

use App\SaleItem;
use App\DetailCredit;
use App\AlmacenUser;
use App\RolePermission;
use App\Sale;
use Illuminate\Support\Facades\DB;
use App\Receiving;
use App\ReceivingItem;


class ReportSalesDetailed {

    public static function sale_detailed($sale_id)
    {
        $SaleItems = SaleItem::where('sale_id', $sale_id)->get();
        return $SaleItems;
    }
    public static function credit_detailed($id_factura)
    {
        $SaleItems = DetailCredit::where('id_factura', $id_factura)->get();
        return $SaleItems;
    }
    public static function detail_user_almacen($id_bodega){
      $AlmacenUser=AlmacenUser::where('id_bodega',$id_bodega)->get();
      return $AlmacenUser;
    }
    public static function detail_permisos_roles($id_rol){
      $rolPermiso=RolePermission::where('id_rol',$id_rol)->get();
      return $rolPermiso;
    }

    public static function tipo_de_pago_reporte_venta($id_pago,$fecha1,$fecha2){
      $valores=Sale::join('series','series.id','=','sales.id_serie')
      ->join('documents','documents.id','=','series.id_document')
      ->where('documents.sign','=','-')
      ->where('sales.id_pago','=',$id_pago)
      ->whereBetween('sales.created_at',[$fecha1,$fecha2])
      ->where('cancel_bill','=',0)
      ->select('sales.id','sales.total_cost','documents.name as document','sales.correlative','series.name as serie','sales.created_at')
      ->get();
      return $valores;
    }




}
