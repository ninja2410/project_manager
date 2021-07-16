<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditPayment extends Model
{

    public function credit()
    {
        return $this->belongsTo('App\Credit','credit_id','id');
    }

    public function revenue()
    {
        return $this->belongsTo('App\Revenue','revenue_id','id');
    }

    public function setPaidDateAttribute($date){
        $date_parts = explode('/', $date);
        $this->attributes['paid_date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }
}
