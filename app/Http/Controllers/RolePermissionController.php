<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\RolePermission;
use App\Role;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
  public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('parameter');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // $listPermissions=RolePermission::all();
      // return view('permissions.index')
      // ->with('listadePermisos', $listPermissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $idRol=$id;
      $permisosRoles = DB::select('CALL sp_list_permisos_roles('.$idRol.')');
      $dataRol=Role::find($idRol);
      return view('roles.add_permision_roles')
      ->with('dataRol', $dataRol)
      ->with('dataPermisos', $permisosRoles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $idRol=Input::get('id_Rol');
      $idPermisos = Input::get('permisos');
      $borrarPermisosRoles=RolePermission::where('id_rol','=',$idRol)->get();
      // return $borrarUsersBodega;
      for ($i=0; $i <count($borrarPermisosRoles) ; $i++) {
        // echo "Valores ".$borrarUsersBodega[$i]->id."<br/>";
        $borrar=RolePermission::find($borrarPermisosRoles[$i]->id);
        $borrar->delete();
      }
      $valorNuevo=count($idPermisos);
      for ($i=0; $i <$valorNuevo ; $i++) {
      $verificarExistencia=RolePermission::where('id_rol','=',$idRol)
      ->where('id_permission','=',$idPermisos[$i])->value('id');
      if(!$verificarExistencia){
        $permisoBodega=new RolePermission;
        $permisoBodega->id_rol=$idRol;
        $permisoBodega->id_permission=$idPermisos[$i];
        $permisoBodega->estado_permiso=1;
        $permisoBodega->save();
        }
      }
      Session::flash('message', 'Agregados correctamente');
      return Redirect::to('roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
