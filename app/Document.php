<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function serie()
    {
        return $this->hasMany('App\Serie');
    }

    public function scopeType($query,$type_id)
    {
        return $query->where('id', '=',$type_id);
    }
}
