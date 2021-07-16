<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function account()
    {
        return $this->belongsTo('App\Account');
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
    public function type()
    {
        return $this->belongsTo('App\TypeProject');
    }
}
