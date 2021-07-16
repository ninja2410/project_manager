<?php

namespace App\Http\Controllers;

use App\StageProjectRegister;
use App\TypeProject;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Stage;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;

class StageController extends Controller
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
        $stages=Stage::all();
        return view('project.stage.index')
        ->with('stages', $stages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = TypeProject::where('status_id', 1)->get();
        return view('project.stage.create')
            ->with('types', $types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->galery)) {
          $gal=true;
        }
        else{
          $gal=false;
        }
        $stage=new Stage();
        $stage->name=$request->name;
        $stage->order=$request->order;
        $stage->color=$request->color;
        $stage->status=1;
        $stage->type_id = $request->type_id;
        $stage->galery=$gal;
        $stage->save();
        Session::flash('message','Proyecto creado correctamente.');
        return Redirect::to('project/stages');
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
        $stage=Stage::find($id);
        $types = TypeProject::where('status_id', 1)->get();
        return view('project.stage.edit')
            ->with('types', $types)
            ->with('stage', $stage);
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
        if (isset($request->galery)) {
          $gal=true;
        }
        else{
          $gal=false;
        }
        $stage=Stage::find($id);
        $stage->name=$request->name;
        $stage->order=$request->order;
        $stage->color=$request->color;
        $stage->galery=$gal;
        $stage->type_id = $request->type_id;
        $stage->status=1;
        $stage->update();
        Session::flash('message','Etapa actualizada correctamente.');
        return Redirect::to('project/stages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      try {
        Stage::destroy($id);
        Session::flash('message','Etapa eliminada correctamente.');
      } catch (\Exception $e) {
        Session::flash('message', "No se pudo eliminar la etapa: " . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile());
        Session::flash('alert-class', 'alert-error');
      }
      return Redirect::to('project/stages');
    }

    /**
     * ACTUALIZAR EL ETSADO DE LAS ETAPAS DE PROYECTOS
     * @param $request
     */
    public function update_status_stage(Request $request){
        try {
            $register = StageProjectRegister::where('project_id', $request->project_id)
                ->where('stage_id', $request->stage_id)
                ->first();
            if (isset($register->id)){
                $register->status = $request->status;
                $register->update_by = Auth::user()->id;
                $register->update();
            }
            else{
                $register = new StageProjectRegister();
                $register->project_id = $request->project_id;
                $register->stage_id = $request->stage_id;
                $register->status = $request->status;
                $register->update_by = Auth::user()->id;
                $register->save();
            }
            $resp = 1;
        }
        catch (\Exception $ex){
            $resp = $ex->getMessage();
        }
        return $resp;
    }
}
