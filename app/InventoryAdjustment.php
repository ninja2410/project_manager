<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryAdjustment extends Model
{
    public function serie(){
        return $this->belongsTo(Serie::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function almacen(){
        return $this->belongsTo(Almacen::class);
    }
}
