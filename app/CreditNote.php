<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CreditNote extends Model
{
    public function serie()
    {
        return $this->belongsTo('App\Serie','serie_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id');
    }
    public function sale(){
        return $this->belongsTo('App\Sale', 'sale_id');
    }
    public function status(){
        return $this->belongsTo('App\StateCellar', 'status_id');
    }
    public function scopeAlmacen($query){
        if (!Session::get('administrador')) {
            //BUSCAR EL ALMACEN ASIGNADO AL USUARIO
            $almacens = AlmacenUser::whereId_usuario(Auth::user()->id)->lists('id_bodega');
            return $query->join('sales', 'sales.id', '=', 'credit_notes.sale_id')
                ->whereIn('sales.almacen_id', $almacens);
        } else {
            return $query;
        }
    }
}
