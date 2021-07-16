<?php

namespace App\Traits;

use DB;
use \Auth;

use Validator;
use App\Almacen;
use App\Http\Requests;
use Illuminate\Http\Request;

trait AlmacenTrait {


	 /**
     * Devuelve las bodegas a las que el usuario tiene acceso
     * 
     * @param $userid : usuario del que deseamos saber las bodegas asigandas.    
     */
	public function getAlmacenByUser($userid)
	{

		return  Almacen::join('almacen_users', 'almacens.id', '=', 'almacen_users.id_bodega')
        ->where('almacen_users.id_usuario', '=', $userid)
        ->where('id_state', '=', '1')
        ->orderBy('almacens.created_at','ASC')
        ->select('almacens.name', 'almacens.id')->get();
	}
    
};
