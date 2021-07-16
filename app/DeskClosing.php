<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeskClosing extends Model
{
    public function details()
    {
        return $this->belongsTo('App\DeskClosingDetail','desk_closing_id','payment_type_id','money_quanity','revenue_id','payment_id','money_type_quantity_id','amount');
    }
}
