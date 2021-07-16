<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemType extends Model
{
    use SoftDeletes;
    protected $table = 'item_types';

    protected $dates = ['deleted_at'];

    public function scopeProducts($query)
    {
        return $query->where('id', 1)->orderBy('id');
    }

    public function scopeServices($query)
    {
        return $query->where('id', 2)->orderBy('id');
    }

    public function scopeFurniture($query)
    {
        return $query->where('id', 3)->orderBy('id');
    }

    public function scopeCategory($query,$id)
    {
        return $query->where('id', $id)->orderBy('id');
    }
}
