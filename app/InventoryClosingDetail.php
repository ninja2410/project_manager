<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryClosingDetail extends Model
{
    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
