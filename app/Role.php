<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    // use SoftDeletes;
    public $table = 'roles';

    protected $dates = [
        'created_at',
        'updated_at',
        // 'deleted_at',
    ];

    public function permissions()
    {
        return $this->belongsToMany('App\Permission','role_permissions','id_rol','id_permission');
    }
}
