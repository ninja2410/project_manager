<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    public function pagos()
    {
        return $this->belongsToMany('App\Pago','module_prices','price_id','pago_id');
    }

    public function items()
    {
        return $this->belongsToMany('App\Item','item_prices','price_id','item_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query, $status)
    {
        return $query->where('active', $status);
    }

    
}
