<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCategory extends Model
{
    use SoftDeletes;
    protected $table = 'item_categories';

    protected $dates = ['deleted_at'];

    public function scopeProducts($query)
    {
        return $query->where('item_type_id', 1)->orderBy('id');
    }

    public function scopeServices($query)
    {
        return $query->where('item_type_id', 2)->orderBy('id');
    }

    public function scopeFurniture($query)
    {
        return $query->where('item_type_id', 3)->orderBy('id');
    }

    public function scopeCategory($query,$id)
    {
        return $query->where('item_type_id', $id)->orderBy('id');
    }

    public function tipo()
    {
        return $this->belongsTo('App\ItemType', 'item_type_id', 'id');
    }
}
