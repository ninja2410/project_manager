<?php

namespace App\Traits;

use App\Account;
use App\Almacen;
use App\UserRole;
use \Auth, \Redirect, \Validator, \Input, \Session;

trait UserTrait {
    public function validPermission($user, $permision){
        foreach($user->roles as $rol){
            foreach($rol->permissions as $perm){
                if ($perm->descripcion == $permision && $perm->estado == 1){
                    return true;
                }
            }
        }
        return false;
    }

    public function getUserPermissions($id)
    {
        $user_permisions = UserRole::join('roles as r','user_roles.role_id','=','r.id')
            ->join('role_permissions as pr','r.id','=','pr.id_rol')
            ->join('permissions as p','pr.id_permission','=','p.id')
            ->select(
                'p.id as IdPermiso',
                'user_roles.user_id',
                'r.id',
                'p.ruta',
                'p.descripcion',
                'p.ruta_padre'
            )
            ->where('user_roles.user_id',$id)
            ->get();
            
            return $user_permisions;
    }

        
}