<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    protected $table = 'bank_accounts';

    protected $fillable = ['company_id', 'account_name', 'account_number', 'account_type', 'currency', 'opening_balance', 'bank_id', 'bank_name', 'bank_phone', 'bank_address', 'pct_interes'];

    public function revenue()
    {
        return $this->hasMany('App\Revenue');
    }

    public function revenueAccount()
    {
        return $this->hasMany('App\Revenue', 'revenue.account_id', 'id');
    }

    public function payment()
    {
        return $this->hasMany('App\Payment');
    }

    public function paymentAccount()
    {
        return $this->hasMany('App\Payment', 'payment.account_id', 'id');
    }

    public function responsible()
    {
        return $this->belongsTo('App\User', 'account_responsible', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\BankAccountType', 'account_type_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function payment_restricted()
    {
        return $this->belongsTo('App\Pago', 'pago_id', 'id');
    }

    public function scopeHaveBalance($query)
    {
        return $query->where('pct_interes', '>', 0);
    }

    public function scopeIsType($query,$type_id)
    {
        return $query->where('account_type_id', '=',$type_id);
    }

    public function scopeNotType($query,$type_id)
    {
        return $query->where('account_type_id', '<>',$type_id);
    }
    
    public function payment_types()
    {
        return $this->belongsToMany('App\Pago','bank_accounts_pagos','bank_account_type_id','pago_id');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Almacen','almacen_id','id');        
    }



    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($account) {
            $relationMethods = ['revenue', 'payment'];

            foreach ($relationMethods as $relationMethod) {
                if ($account->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }

    /**
     * Convert opening balance to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setOpeningBalanceAttribute($value)
    {
        $value = str_replace(",", "", $value);
        $this->attributes['opening_balance'] = (double)$value;
    }

    public function setBalanceAttribute($value)
    {
        $value = str_replace(",", "", $value);    
        $this->pct_interes = $this->pct_interes + (double)$value;
    }

    public function getBalanceAttribute()
    {        
        return $this->pct_interes;
    }

    protected $dates = ['deleted_at'];

}

