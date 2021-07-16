<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTransfer extends Model
{
    public function Created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function Updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
    public function serie(){
        return $this->belongsTo('App\Serie');
    }
    public function Almacen_origin(){
        return $this->belongsTo('App\Almacen', 'almacen_origin');
    }
    public function Almacen_destination(){
        return $this->belongsTo('App\Almacen', 'almacen_destination');
    }
    public function status(){
        return $this->belongsTo('App\StateCellar', 'status_id');
    }
    public function account_credit(){
        return $this->belongsTo('App\Account', 'account_credit_id');
    }
    public function price(){
        return $this->belongsTo('App\Price');
    }

    public function getDateAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
    public function getDateReceivedAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}