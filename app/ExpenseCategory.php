<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }
}
