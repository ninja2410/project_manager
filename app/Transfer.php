<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use SoftDeletes;
    protected $table = 'bank_tx_transfers';

    protected $fillable = ['company_id', 'payment_id', 'revenue_id', 'user_id', 'status'];

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'payment_id', 'id');
    }

    public function paymentAccount()
    {
        return $this->belongsTo('App\Account', 'payment.account_id', 'id');
    }

    public function revenue()
    {
        return $this->belongsTo('App\Revenue');
    }

    public function revenueAccount()
    {
        return $this->belongsTo('App\Account', 'revenue.account_id', 'id');
    }

    protected $dates = ['deleted_at'];
}
