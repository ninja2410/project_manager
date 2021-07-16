<?php

namespace App\Http\Controllers;

use App\Price;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;
use DB;
use App\UnitMeasure;

class UnitMeasureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = UnitMeasure::all();
        return view('unit_measure.index',  compact(['units']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('unit_measure.create');
    }

    public function verify_abbreviation(){
      $tmp = Input::get('unit_id');
      if (isset($tmp)){
          $revs = UnitMeasure::whereAbbreviation(Input::get('abbreviation'))
            ->where('id', '!=', $tmp)
            ->whereStatus_id(1)
            ->count();
      }
      else{
        $revs = UnitMeasure::whereAbbreviation(Input::get('abbreviation'))
          ->whereStatus_id(1)
          ->count();
      }

      if ($revs > 0){
          $isAvailable= false;
      }
      else{
          $isAvailable= true;
      }
      return json_encode(array('valid' => $isAvailable));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unit = new UnitMeasure();
        $unit->name = $request->name;
        $unit->abbreviation = $request->abbreviation;
        $unit->status_id = 1;
        $unit->created_by = Auth::user()->id;
        $unit->save();
        Session::flash('message','Unidad de medida creada correctamente');
        return Redirect::to('unit_measure');
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
        $unit = UnitMeasure::find($id);
        return view('unit_measure.edit', compact(['unit']));
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
        $unit = UnitMeasure::find($id);
        $unit->name = $request->name;
        $unit->abbreviation = $request->abbreviation;
        $unit->updated_by = Auth::user()->id;
        $unit->update();
        Session::flash('message','Unidad de medida modificada correctamente');
        return Redirect::to('unit_measure');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = UnitMeasure::find($id);
        $unit->status_id = 2;
        $unit->updated_by = Auth::user()->id;
        $unit->update();
        Session::flash('message','Unidad de medida eliminada correctamente');
        return Redirect::to('unit_measure');
    }

    public function getName(Request $request){
        $unit = UnitMeasure::find($request->unit_id);
        $price = Price::find($request->price_id);
        return json_encode(array("unit"=>$unit->name, "price"=>$price->name));
    }
}
