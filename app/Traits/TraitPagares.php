<?php

namespace App\Traits;


use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\PagareDetail;
use App\Pagare;

trait TraitPagares{
  public function updateStatus($id, $fecha){
    $moraCalculada = 0;
    $detonaMora = false;
    $fPago = date_create($fecha);
    $credito = Pagare::find($id);
    $pagos = PagareDetail::where('pagare_id', $id)
      ->where('surcharge', 0)
      ->where('status', 1)
      ->where('total_payment', 0)
      ->where('delay', 0)
      ->where('date_payment', '<', $fecha)
      ->get();
      //RECORRER PAGOS PENDIENTES
    foreach ($pagos as $key => $pago) {
        //Fecha estimada de pago
      $tmp = str_replace('/', '-', $pago->date_payment);
      $dateP = date_create($tmp);
        //CALCULAR DIFERENCIA ENTRE FECHAS
      $dif = date_diff($dateP, $fPago);
        //EVALUAR SI SE PASÓ DE LOS DÍAS PERMITIDOS
      if ($dif->days > $credito->days_mora && $dif->invert == 0) {
          //CALCULAR LA MORA A PAGO
        $detonaMora = true;
        $pago->delay = 1;
        $moraCalculada += $this->aplicarMora(
          $pago,
          ($credito->mora / 100),
          1
        );
      }
      $pago->date_mora = date('Y-m-d');
      $pago->update();
    }
  }
  public function aplicarMora($pago, $pctMora, $tipo)
  {
    $montoCuota = Pagare::where('id', $pago->pagare_id)
      ->select('amount')
      ->get();

    if ($tipo == 1) {
      $mora = $montoCuota[0]->amount * $pctMora;
    } else {
      $mora = $montoCuota[0]->amount * ($pctMora / 2);
    }
    $pago->surcharge = $mora;
    $pago->date_mora = date('Y-m-d');
    $pago->update();
    return $mora;
  }
}
