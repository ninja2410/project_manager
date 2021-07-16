<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    public function invoice()
    {
        return $this->belongsTo('App\Sale','id_factura');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer','id_cliente','id');
    }

    public function payments()
    {
        return $this->belongsToMany('App\CrediPayment','credit_payments','id_credit','id_payment');
    }


    public function setDatePaymentsAttribute($date){
        $date_parts = explode('/', $date);
        $this->attributes['date_payments'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }

    public function getDatePaymentsAttribute($date){
        return date('d/m/Y', strtotime($date));
    }
}
