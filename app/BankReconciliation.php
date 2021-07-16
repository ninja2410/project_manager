<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankReconciliation extends Model
{
    public function account()
    {
        return $this->belongsTo('App\Account', 'account_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
