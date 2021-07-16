<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    public function unit(){
      return $this->belongsTo(UnitMeasure::class, 'unit_id');
    }
}
