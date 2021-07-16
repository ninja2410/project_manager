<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use App\Role;
use App\Permission;


class RoleController extends Controller
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
        $roles = Role::all();

        return view('roles.index')
            ->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all()->pluck('descripcion', 'id');
        return view('roles.create')
            ->with('permissions', $permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Declaracon de Reglas del request
        $rules = array(
            'role' => 'required|unique:roles'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $error){
                $message .= $error.' | ';
            }
            Session::flash('message', trans('accounts.save_ok'));

            Session::flash('message', $message);
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

        $newRol = new Role;
        $newRol->role = $request->get('role');
        $newRol->admin = $request->get('is_admin');

        $newRol->save();

        $newRol->permissions()->attach($request->input('permissions', []), ['created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')]);


        Session::flash('message', 'Insertado correctamente');
        return Redirect::to('roles');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::find($id);

        $permissions = Permission::all()->pluck('descripcion', 'id');

        return view('roles.edit')
            ->with('roles', $roles)
            ->with('permissions', $permissions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'role' => 'required|unique:roles,role,' . $id . '',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $error){
                $message .= $error.' | ';
            }
            Session::flash('message', trans('accounts.save_ok'));

            Session::flash('message', $message);
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

        $UpdateRole = Role::find($id);
        $UpdateRole->admin = Input::get('is_admin');
        $UpdateRole->role = Input::get('role');
        $UpdateRole->save();
        $UpdateRole->permissions()->detach();
        $UpdateRole->permissions()->attach($request->input('permissions', []), ['updated_at' => date('Y-m-d h:i:s')]);


        Session::flash('message', 'Actualizado correctamente');
        return Redirect::to('roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $roles = Role::find($id);
            $roles->delete();
            // redirect
            // Session::flash('message', 'You have successfully deleted customer');
            Session::flash('message', trans('Eliminado exitosamente'));
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('roles');
        } catch (\Illuminate\Database\QueryException $e) {
            // dd($e);
            // Session::flash('message', 'Integrity constraint violation: You Cannot delete a parent row');
            Session::flash('message', trans('No es posible eliminar un rol asociado a algun usuario ó con permisos asignados: ') . ' [' . $roles->role . ']');

            Session::flash('alert-class', 'alert-error');
            return Redirect::to('roles');
        }
    }
}
