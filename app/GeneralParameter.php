<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralParameter extends Model
{
    use SoftDeletes;

    public function assigned_user()
    {
        return $this->belongsTo('App\User', 'assigned_user_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    protected $dates = ['deleted_at'];
}
