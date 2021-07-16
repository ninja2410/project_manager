<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\UserRole;
use \Auth, \Redirect, \Validator, \Input, \Session, \Hash;
class UserRoleController extends Controller
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
      $employees = User::all();
      return view('rolesUsers.index')->with('employee', $employees);
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
      $dataRoles= Role::all();
      $dataUser=User::find($id);
      return view('rolesUsers.add_user_role')
      ->with('dataRoles', $dataRoles)
      ->with('dataUser', $dataUser);
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
      $idRol=Input::get('id_rol');
    
      
     
      if($idRol==0){
        Session::flash('message','Seleccione un rol');
       return Redirect::to('user_role/'.$id.'/edit');
      }else{
        $user = User::find($id);
      $user->roles()->sync([$idRol]);
       
        Session::flash('message', 'Agregados correctamente');
        return Redirect::to('user_role');
      }
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
