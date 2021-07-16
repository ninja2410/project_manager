<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    // use SoftDeletes;

    public function accountsin()
    {
        return $this->belongsToMany('App\BankAccountType','bank_accounts_pagos','pago_id','bank_account_type_id')->withPivot('ingreso')->wherePivot('ingreso',1);
    }

    public function accountsout()
    {
        return $this->belongsToMany('App\BankAccountType','bank_accounts_pagos','pago_id','bank_account_type_id')->withPivot('ingreso')->wherePivot('ingreso',0);
    }


    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBankin($query)
    {
        return $query->where('banco_in', 1)->orderBy('orden_banco_in');
    }

    public function scopeBankout($query)
    {
        return $query->where('banco_out', 1)->orderBy('orden_banco_out');
    }

    public function scopeSale($query)
    {
        return $query->where('venta', 1)->orderBy('orden_venta');
    }

    public function scopeReceiving($query)
    {
        return $query->where('compra', 1)->orderBy('orden_compra');
    }

    public function scopeBankAccountType($query, $type)
    {
        return $query->where('type', $type);
    }


}
