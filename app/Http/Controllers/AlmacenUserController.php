<?php

namespace App\Http\Controllers;

use App\Almacen;
use App\AlmacenUser;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Input;
use \Redirect;
use \Session;

class AlmacenUserController extends Controller
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
        // $bodegas=Almacen::join('state_cellars','state_cellars.id','=','almacens.id_state')
        // ->where('state_cellars.name', '=','Activo')
        // ->lists('almacens.name','almacens.id');
        // $users=User::join('state_cellars','users.user_state','=','state_cellars.id')
        // ->where('state_cellars.name','=','Activo')->select('users.id','users.name')->get();
        // return view('almacenUser.create_user_bodega')
        // ->with('users',$users)
        // ->with('bodegas',$bodegas);
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

    //asigamos los usuarios a la bodega
    public function edit($id)
    {
        $empleados       = User::all();
        $idBodega        = $id;
        $usersAndBodegas = DB::select('CALL sp_list_bodega_users(' . $id . ')');
        $dateBodega      = Almacen::find($id);
        return view('almacenUser.editar_user_bodega')
            ->with('bodega_user', $usersAndBodegas)
            ->with('dataBodega', $dateBodega);
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

        $idBodega          = Input::get('id_bodega');
        $idUsuario         = Input::get('usuarios');
        $borrarUsersBodega = AlmacenUser::where('id_bodega', '=', $idBodega)->get();
        // return $borrarUsersBodega;
        for ($i = 0; $i < count($borrarUsersBodega); $i++) {
            // echo "Valores ".$borrarUsersBodega[$i]->id."<br/>";
            $borrar = AlmacenUser::find($borrarUsersBodega[$i]->id);
            $borrar->delete();
        }
        $valorNuevo = count($idUsuario);
        for ($i = 0; $i < $valorNuevo; $i++) {
            $verificarExistencia = AlmacenUser::where('id_bodega', '=', $idBodega)
                ->where('id_usuario', '=', $idUsuario[$i])->value('id');
            if (!$verificarExistencia) {
                $userBodega              = new AlmacenUser;
                $userBodega->id_bodega   = $idBodega;
                $userBodega->id_usuario  = $idUsuario[$i];
                $userBodega->estado_user = 1;
                $userBodega->save();
            }
        }
        Session::flash('message', 'Agregados correctamente');
        return Redirect::to('almacen');
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
