<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Atribute;
use App\Stage;
use App\ValueStages;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;

class AtributeController extends Controller
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
        $atributes=Atribute::join('stages', 'stages.id', '=', 'atributes.stage_id')
        ->select('atributes.*', 'stages.name as Etapa')
        ->where('atributes.status', 1)
        ->get();
        return view('project.atributes.index')
        ->with('atributes', $atributes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stages=Stage::where('status', 1)->get();
        return view('project.atributes.create')
        ->with('stages', $stages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $atribute=new Atribute();
        $atribute->name=$request->name;
        $atribute->size=$request->size;
        $atribute->type=$request->type;
        $atribute->stage_id=$request->stage_id;
        $atribute->status=1;
        $atribute->save();
        Session::flash('message','Atributo creado correctamente.');
        return Redirect::to('project/atributes');
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
        $atribute=Atribute::find($id);
        $stages=Stage::where('status', 1)->get();
        return view('project.atributes.edit')
        ->with('stages', $stages)
        ->with('atribute', $atribute);
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
        $atribute=Atribute::find($id);
        $atribute->name=$request->name;
        $atribute->size=$request->size;
        $atribute->type=$request->type;
        $atribute->stage_id=$request->stage_id;
        $atribute->update();
        Session::flash('message','Atributo editado correctamente.');
        return Redirect::to('project/atributes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $atribute=Atribute::find($id);
        $atribute->status=0;
        $atribute->update();
        Session::flash('message','Atributo eliminado correctamente.');
        return Redirect::to('project/atributes');
    }

    public function saveValueStage(Request $request){
      $valAtribute=ValueStages::where('project_id', $request->project_id)
      ->where('atribute_id', $request->atribute_id)
      ->where('status', 1)
      ->orderby('created_at', 'desc')
      ->first();
      if (isset($valAtribute)) {
        $valAtribute->value=$request->value;
        $valAtribute->update();
      }
      else{
        $valAtribute=new ValueStages();
        $valAtribute->value=$request->value;
        $valAtribute->date=date('Y-m-d');
        $valAtribute->status=1;
        $valAtribute->project_id=$request->project_id;
        $valAtribute->atribute_id=$request->atribute_id;
        $valAtribute->save();
      }
    }
}
