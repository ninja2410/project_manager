<?php

namespace App\Traits;

use App\Sale;
use DB;
use \Auth, \Redirect, \Validator, \Input, \Session;

trait SaleTrait
{
    public function getSaleDocument($id)
    {
        $document = Sale::join('series as s','sales.id_serie','=','s.id')
            ->join('documents as d','s.id_document','=','d.id')
            ->select(DB::Raw("concat(d.name,' ',s.name,'-',sales.correlative) AS Documento"))
            ->where('sales.id',$id)
            ->first();
            // dd($document);
        return $document->Documento;
    }
}
