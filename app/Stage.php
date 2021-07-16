<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    function tipo(){
        return $this->belongsTo('App\TypeProject', 'type_id');
    }

    function register(){
        return $this->hasOne('App\StageProjectRegister', 'stage_id');
    }
}
