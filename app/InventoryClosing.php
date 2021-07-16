<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryClosing extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function almacen(){
        return $this->belongsTo('App\Almacen');
    }
    public function status(){
        return $this->belongsTo('App\StateCellar');
    }
    public function getInventoryClosingDateAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}
