<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditSupplier extends Model
{
    public function invoice()
    {
        return $this->belongsTo('App\Receiving','receiving_id');
    }

    public function status(){
        return $this->belongsTo(StateCellar::class, 'status_id');
    }

    public function supplier(){
        return $this->belongsTo('App\Supplier','supplier_id','id');
    }

    public function details()
    {
        return $this->belongsToMany('App\CreditSupplierDetail','credit_supplier_details','credit_supplier_id','id');
    }

    public function setDatePaymentsAttribute($date){
        $date_parts = explode('/', $date);
        $this->attributes['date_payments'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }
    public function getDatePaymentsAttribute($date){
        return date('d/m/Y', strtotime($date));
    }
}
