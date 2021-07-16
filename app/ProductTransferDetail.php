<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTransferDetail extends Model
{
    public function item(){
        return $this->belongsTo('App\Item');
    }
}
