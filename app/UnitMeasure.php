<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitMeasure extends Model
{
    public function status(){
      return $this->belongsto(StateCellar::class, 'status_id');
    }
}
