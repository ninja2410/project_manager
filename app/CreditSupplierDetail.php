<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditSupplierDetail extends Model
{
    public function setPaidDateAttribute($date){
        $date_parts = explode('/', $date);
        $this->attributes['paid_date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }
    public function expense()
    {
        return $this->belongsTo('App\Payment','expense_id','id');
    }
    public function credit()
    {
        return $this->belongsTo('App\CreditSupplier','credit_supplier_id','id');
    }
    public function payment()
    {
        return $this->belongsTo('App\Payment','expense_id','id');
    }
}
