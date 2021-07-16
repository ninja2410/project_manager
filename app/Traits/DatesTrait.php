<?php

namespace App\Traits;

trait DatesTrait {

    public function fixFecha($fecha)
    {
        $fecha1=$fecha;

        $fechaActual=date("Y-m-d");
        if($fecha1==null){
            $fecha1=$fechaActual.' 00:00:00';
        }else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
        }
        return $fecha1;
    }
    public function fixFechaFin($fecha)
    {
        $fecha1=$fecha;

        $fechaActual=date("Y-m-d");
        if($fecha1==null){
            $fecha1=$fechaActual.' 23:59:59';
        }else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 23:59:59';
        }
        return $fecha1;
    }

}