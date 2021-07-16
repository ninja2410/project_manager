<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retention extends Model
{
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function estado(){
        return $this->belongsTo(StateCellar::class, 'status');
    }

}
