<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetHeader extends Model
{
    public function getDetails(){
        return $this->hasMany(BudgetDetail::class, 'header_id');
    }
}
