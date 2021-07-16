<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\MoneyType;
use App\StateCellar;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;

class TypeMoneyController extends Controller
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
        $moneys=MoneyType::all();
        return view('moneyType.index')
        ->with('moneys', $moneys);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status=StateCellar::lists('name', 'id');
        return view('moneyType.create')
        ->with('status', $status);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new=new MoneyType();
        $new->name=$request->name;
        $new->value=$request->value;
        $new->status_id=$request->status_id;
        $new->save();
        return Redirect::to('/typeMoney');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $moneys=MoneyType::where('status_id', 1)->get();
        return view('moneyType.index')
        ->with('moneys', $moneys);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $typeMoney=MoneyType::find($id);
        $status=StateCellar::lists('name', 'id');
        return view('moneyType.edit')
        ->with('status', $status)
        ->with('typeMoney', $typeMoney);
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
        $money=MoneyType::find($id);
        $money->name=$request->name;
        $money->value=$request->value;
        $money->status_id=$request->status_id;
        $money->update();
        return Redirect::to('/typeMoney');
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
        MoneyType::destroy($id);
        Session::flash('message', 'Moneda eliminada con éxito.');
      } catch (\Exception $e) {
        Session::flash('message', 'Moneda utilizada en al menos un balance: No se puede eliminar');
        Session::flash('alert-class', 'alert-error');
      }
      return Redirect::to('typeMoney');
    }
}
