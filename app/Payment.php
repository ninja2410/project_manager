<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    protected $table = 'bank_tx_payments';
    //

    protected $dates = ['deleted_at', 'paid_at'];

    protected $fillable = ['company_id', 'account_id', 'paid_at', 'amount', 'currency', 'currency_rate', 'bill_id', 'supplier_id', 'description', 'category_id', 'payment_method', 'reference', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function stage()
    {
        return $this->belongsTo('App\Stage');
    }

    public function category()
    {
        return $this->belongsTo('App\ExpenseCategory', 'category_id', 'id');
    }

    public function bill()
    {
        return $this->belongsTo('App\Receiving', 'bill_id', 'id');
    }

    public function pago()
    {
        return $this->belongsTo('App\Pago', 'payment_method');
    }

    public function scopeExpenses($query)
    {
        return $query->whereNull('bill_id');
    }

//    public function setaPaidAtAttribute($date)
//    {
//        $date_parts = explode('/', $date);
//        $this->attributes['paid_at'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
//    }

    public function getPaidAtAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }
}
