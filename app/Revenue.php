<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revenue extends Model
{
    use SoftDeletes;
    protected $table = 'bank_tx_revenues';

    protected $dates = ['deleted_at', 'paid_at'];

    protected $fillable = ['company_id', 'account_id', 'paid_at', 'amount', 'currency', 'currency_rate', 'invoice_id', 'customer_id', 'description', 'category_id', 'payment_method', 'reference', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function serie()
    {
        return $this->belongsTo('App\Serie');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    // public function category()
    // {
    //     return $this->belongsTo('App\TransactionsCatalogue', 'category_id', 'id');
    // }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Sale', 'invoice_id', 'id');
    }

    public function pago()
    {
        return $this->belongsTo('App\Pago', 'payment_method');
    }

    public function retention()
    {
        return $this->hasOne('App\RegRetention', 'revenue_id');
    }

    public function setaPaidAtAttribute($date)
    {
        $date_parts = explode('/', $date);
        $this->attributes['paid_at'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }

    public function getPaidAtAttribute($date)
    {
        return date('d/m/Y', strtotime($date));
    }

    //
}
