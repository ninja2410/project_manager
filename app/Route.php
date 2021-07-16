<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Route extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User','route_users','route_id','user_id');
    }
    public function costumers()
    {
        return $this->belongsToMany('App\Customer','route_costumers','route_id','customer_id');
    }
    public function states()
    {
        return $this->belongsTo('App\StateCellar','status_id','id');
    }
    public function creador()
    {
        return $this->belongsTo('App\User','created_by','id');
    }
    public function actualizacion()
    {
        return $this->belongsTo('App\User','updated_by','id');
    }
    public function scopeActive($query)
    {
        return $query->where('status_id', '=',1);
    }

    public function scopeAsigned($query){
        if (!Session::get('administrador')) {
            //BUSCAR EL ALMACEN ASIGNADO AL USUARIO
            $routes = RouteUser::where('user_id',Auth::user()->id)->select('route_id')->get();
            return $query->whereIn('routes.id', $routes);
        } else {
            return $query;
        }
    }

}
