<?php

namespace App\Http\Controllers;

use App\Almacen;
use App\AlmacenUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\AlmacensRequest;
use App\Project;
use App\StateCellar;
use App\User;
use Illuminate\Http\Request;
use \Input;
use \Redirect;
use \Session;
use \Auth, \Validator;
use \Response;
use DB;

class AlmacenController extends Controller
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

        $almacen = Almacen::join('state_cellars', 'almacens.id_state', '=', 'state_cellars.id')
            ->select('almacens.id', 'almacens.name as nombre', 'almacens.phone', 'almacens.adress', 'almacens.comentario', 'state_cellars.name as estado')->get();

        // $users = DB::table('users')->Join('almacen_users', 'users.id', '=', 'almacen_users.id_usuario')->select('users.name', 'almacen_users.id_bodega')->get();

        return view('almacen.index')
        ->with('almacen', $almacen);
        // ->with('users', $users);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all()->pluck('name', 'id');
        $state_cellar = StateCellar::lists('name', 'id');
        return view('almacen.create')
        ->with('state_cellar', $state_cellar)
        ->with('users',$users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator =Validator::make($request->all(), [
                'name' =>
                    'required|unique:almacens',
                'adress'=>
                    'required:almacens,adress'
            ]);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }

            $almacen             = new Almacen;
            $almacen->name       = Input::get('name');
            $almacen->phone      = Input::get('phone');
            $almacen->adress     = Input::get('adress');
            $almacen->id_state   = Input::get('id_state');
            $almacen->comentario = Input::get('comentario');
            $almacen->save();
            Session::flash('message', 'Bodega insertada correctamente');
            DB::commit();
        }
        catch(\Exception $ex){
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return redirect('almacen/create')
                ->withInput();
        }
        return Redirect::to('almacen');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //   return view('almacen.create_user_bodega');
    // }
    public function show()
    {
        $bodegas = Almacen::join('state_cellars', 'state_cellars.id', '=', 'almacens.id_state')
            ->where('state_cellars.name', '=', 'Activo')
            ->lists('almacens.name', 'almacens.id');
        $users = DB::table('users')->Join('almacen_users', 'users.id', '=', 'almacen_users.id_usuario')->select('users.name', 'almacen_users.id_bodega')->get();

        return view('almacen.create_user_bodega')
            ->with('users', $users)
            ->with('bodegas', $bodegas);
    }

    public function operar(Request $request)
    {

        $idBodega = Input::get('id_bodega');
        // $data = Input::get('usuarios');
        // $bodegaUpdate=Almacen::find($idBodega);
        // $obtenerColumna=$bodegaUpdate->usuario_bodega;
        // $obtenerColumna['id']=$data;
        // $bodegaUpdate->usuario_bodega=$obtenerColumna;
        // $bodegaUpdate->save();
        // $feat = Input::get('usuarios');
        $myCheckboxes = $request->input('usuarios');
        // dd($myCheckboxes);
        // $jsonCompleto=json_encode($myCheckboxes);
        $jsonCompleto = json_encode($myCheckboxes, JSON_FORCE_OBJECT);
        echo $jsonCompleto;

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $almacen      = Almacen::find($id);
        $state_cellar = StateCellar::lists('name', 'id');
        $users = User::all()->pluck('name', 'id');;
        return view('almacen.edit')
            ->with('almacen', $almacen)
            ->with('state_cellar', $state_cellar)
            ->with('users',$users);
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
        DB::beginTransaction();
        try {
            $validator =Validator::make($request->all(), [
                'name' =>
                    'required|unique:almacens,name,' . $id,
                'adress'=>
                    'required:almacens,adress,'.$id
            ]);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }

            $almacen             = Almacen::find($id);
            $almacen->name       = Input::get('name');
            $almacen->phone      = input::get('phone');
            $almacen->adress     = input::get('adress');
            $almacen->id_state   = input::get('id_state');
            $almacen->comentario = input::get('comentario');
            $almacen->save();

            $almacen->users()->sync($request->input('users', []));

            Session::flash('message', 'Almacen actualizado Correctamente');
            DB::commit();
        }
        catch(\Exception $ex){
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

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
        try
        {
            $almacen         = Almacen::find($id);
            $almacen->delete();
            // $almacen->id_state = 0;
            // $almacen->save();

            // redirect
            Session::flash('message', 'Eliminado correctamente');
            return Redirect::to('almacen');
        } catch (\Illuminate\Database\QueryException $e) {
            Session::flash('message', 'Bodega en uso: No se puede eliminar ['.$almacen->name.']');
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('almacen');
        }
    }

    public function getStorages(){
        //
        $dataUser  = Auth::user();
        $dataStorage=Almacen::leftJoin('almacen_users','almacens.id','=','almacen_users.id_bodega')
        ->where('almacen_users.id_usuario',$dataUser->id)
        ->select('almacens.id','almacens.name','almacens.adress')
        ->get();

        return Response::json($dataStorage);
    }

    public function get_details($id){
        // $AlmacenUser=AlmacenUser::join('users','almacen_users.id_usuario','=','users.id')
        //     ->where('id_bodega',$id)
        //     ->select('users.name')
        //     ->get();

            $AlmacenUser=DB::table('almacen_users')->join('users','almacen_users.id_usuario','=','users.id')
            ->where('id_bodega',$id)
            ->select('users.name')
            ->get();


        return $AlmacenUser;
    }

    public function getAccountAlmacen($almacen_id){
        #region Verificar si existe proyecto asociado
        $project = Project::where('cellar_id', $almacen_id)->first();
        if (isset($project)){
            return $project->account_id;
        }
        else{
            return -1;
        }
        #endregion
    }
}
