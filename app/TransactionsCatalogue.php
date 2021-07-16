<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionsCatalogue extends Model
{
    use SoftDeletes;
    protected $table = 'bank_transactions_catalogue';

    public function payments()
    {
        return $this->hasMany('App\Payment', 'category_id', 'id');
    }

    public function revenues()
    {
        return $this->hasMany('App\Revenue', 'category_id', 'id');
    }

    /**
     * Scope to only include categories of a given sign.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $sign
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSign($query, $sign)
    {
        return $query->where('transaction_sign', $sign);
    }


    protected $dates = ['deleted_at'];
}
