<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\StateCellar;
use App\ClassTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassCustomerRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
class ClassCustomerController extends Controller
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
        $classes=ClassTable::all();
        return view('classcustomer.index')
        ->with('classes', $classes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status=StateCellar::lists('name', 'id');
        return view('classcustomer.create')
        ->with('status', $status);
    }

    public function verify($arrears){
      $n=ClassTable::where('status_id', 1)
      ->where('arrears', $arrears)
      ->count();
      if ($n==0) {
        return 1;
      }
      else{
        return 0;
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validacion
        $validator =Validator::make($request->all(), [
            'arrears' => 'required|unique:class_tables'
        ]);
        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $class=new ClassTable();
        $class->name=$request->name;
        $class->arrears=$request->arrears;
        $class->pctRen=$request->pctRen;
        $class->pctAmountRen=$request->pctAmountRen;
        $class->color=$request->color;
        $class->status_id=$request->status_id;
        $class->description=$request->description;
        $class->user_id=Auth::user()->id;
        if ($request->pctAmountRen==0) {
          $class->renovation=0;
        }
        else{
          $class->renovation=1;
        }
        if (isset($request->noPaySurcharge)) {
          $class->noPaySurcharge=1;
        }
        else{
          $class->noPaySurcharge=0;
        }
        $class->save();
        Session::flash('message','Clase creada correctamente');
        return redirect::to('class');
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
        $class= ClassTable::find($id);
        $status=StateCellar::lists('name', 'id');
        return view('classcustomer.edit')
        ->with('status', $status)
        ->with('class', $class);
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
      $class=ClassTable::find($id);
      $class->name=$request->name;
      $class->arrears=$request->arrears;
      $class->pctRen=$request->pctRen;
      $class->pctAmountRen=$request->pctAmountRen;
      $class->color=$request->color;
      $class->status_id=$request->status_id;
      $class->description=$request->description;
      if ($request->pctAmountRen==0) {
        $class->renovation=0;
      }
      else{
        $class->renovation=1;
      }
      if (isset($request->noPaySurcharge)) {
        $class->noPaySurcharge=1;
      }
      else{
        $class->noPaySurcharge=0;
      }
      $class->update();
      Session::flash('message','Clase modificada correctamente');
      return redirect::to('class');
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
          ClassTable::destroy($id);
          Session::flash('message', 'Clase eliminada con éxito.');
        } catch (\Exception $e) {
          Session::flash('message', 'Clase utilizada en al menos un cliente: No se puede eliminar');
          Session::flash('alert-class', 'alert-error');
        }
        return Redirect::to('class');
    }
}
