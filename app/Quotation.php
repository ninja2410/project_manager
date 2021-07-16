<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function price()
    {
        return $this->belongsTo('App\Price','price_id','id');
    }
    public function sale()
    {
        return $this->belongsTo('App\Sale','sale_id','id');
    }
}
