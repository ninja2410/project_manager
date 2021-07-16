<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Almacen extends Model
{
    protected $casts = [
        'usuario_bodega' => 'array',
    ];


    public function users()
    {
        return $this->belongsToMany('App\User','almacen_users','id_bodega','id_usuario');
    }

    public function scopeAsigned($query){
        if (!Session::get('administrador')) {
            //BUSCAR EL ALMACEN ASIGNADO AL USUARIO
            $almacens = AlmacenUser::whereId_usuario(Auth::user()->id)->lists('id_bodega');
            return $query->whereIn('almacens.id', $almacens);
        } else {
            return $query;
        }
    }
}
