<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettlementRouteDetail extends Model
{
    public function category(){
        return $this->belongsTo('App\ExpenseCategory', 'expense_category_id');
    }
    public function serie(){
        return $this->belongsTo('App\Serie');
    }
    public function settlement(){
        return $this->belongsTo('App\SettlementRoute');
    }
    public function pago(){
        return $this->belongsTo('App\Pago', 'payment_type_id');
    }
}
