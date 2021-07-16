<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Permission;
use \Auth, \Redirect, \Validator, \Input, \Session;

class PermissionController extends Controller
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
      $listPermision=Permission::all();
      return view('permissions.index')
      ->with('listPermission', $listPermision);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newPermission=New Permission;
        $newPermission->descripcion=Input::get('descripcion');
        $newPermission->ruta=Input::get('url');
        $newPermission->save();
        Session::flash('message','Insertado correctamente');
        return Redirect::to('permissions');
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
      $returnPermiso=Permission::find($id);
      return view('permissions.edit')
      ->with('listaPermiso', $returnPermiso);
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
      $UpdatePermiso=Permission::find($id);
      $UpdatePermiso->descripcion=Input::get('descripcion');
      $UpdatePermiso->ruta=Input::get('url');
      $UpdatePermiso->save();
      Session::flash('message','Actualizado correctamente');
      return Redirect::to('permissions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
          $permisos = Permission::find($id);
              $permisos->delete();
              // redirect
              // Session::flash('message', 'You have successfully deleted customer');
              Session::flash('message', trans('Eliminado exitosamente'));

              return Redirect::to('permissions');
        }
          catch(\Illuminate\Database\QueryException $e)
        {
            // Session::flash('message', 'Integrity constraint violation: You Cannot delete a parent row');
            Session::flash('message', trans('No es posible eliminar un permiso asociado a algun usuario.').' ['.$permisos->descripcion.']');

            Session::flash('alert-class', 'alert-error');
              return Redirect::to('permissions');
          }
    }
}
