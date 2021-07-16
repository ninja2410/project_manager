<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTransferPayment extends Model
{
    public function transaction(){
        return $this->belongsTo('App\Payment', 'transaction_id');
    }

    public function account(){
        return $this->belongsTo('App\Account', 'account_id');
    }
}
