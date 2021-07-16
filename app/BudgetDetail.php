<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetDetail extends Model
{
    public function items(){
        return $this->hasMany(BudgetItem::class);
    }

    public function line_template(){
        return $this->belongsTo(LineTemplate::class);
    }

    public function header(){
        return $this->belongsTo(BudgetHeader::class, 'header_id');
    }
}
