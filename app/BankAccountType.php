<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccountType extends Model
{
    

    public function payment_types()
    {
        return $this->belongsToMany('App\Pagos','bank_accounts_pagos','pago_id','bank_account_type_id');
    }

    public function scopeBankAccount($query)
    {
        return $query->whereNotIn('id',[1,7]);
    }

    public function scopeCashRegister($query)
    {
        return $query->where('id', '=',7);
    }

}
