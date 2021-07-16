<?php

use App\ReceivingItem;
use App\Receiving;

class ReportReceivingsDetailed {

    public static function receiving_detailed($receiving_id)
    {
        $receivingitems = ReceivingItem::where('receiving_id', $receiving_id)->get();
        return $receivingitems;
    }
    public static function tipo_de_pago_reporte_compras($id_pago,$fecha1,$fecha2){
      $valores=Receiving::join('series','series.id','=','receivings.id_serie')
      ->join('documents','documents.id','=','series.id_document')
      ->where('documents.sign','=','+')
      ->whereNull('storage_destination')
      ->where('receivings.id_pago','=',$id_pago)
      ->where('cancel_bill','=',0)
      ->whereBetween('receivings.created_at',[$fecha1,$fecha2])
      ->select('receivings.id','receivings.total_cost','documents.name as document','receivings.correlative','series.name as serie','receivings.created_at')
      ->get();
      return $valores;
    }
}
