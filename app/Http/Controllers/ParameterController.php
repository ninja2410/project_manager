<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Parameter;
use App\Invoice_type;
use App\Item;
use App\log;
use App\User;
use App\Detail_invoice;
use \Auth, \Redirect, \Validator, \Input, \Session;
class ParameterController extends Controller
{
  public function __construct()
	{
		$this->middleware('auth');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Parameter::first();
        $items=Item::where('type', 1)->get();

        if (isset($data)) {
          return view('parameter.index', ['data'=>$data, 'items'=>$items]);
        }
        else{
          return view('parameter.create', ['items'=>$items]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $tmp=0;
      $tmp2=0;
      if ($request->amount_guarantor_required!="0.00") {
        $tmp=str_replace(",", "", $request->amount_guarantor_required);
      }
      if ($request->amount_authorize_required!="0.00") {
        $tmp2=str_replace(",", "", $request->amount_authorize_required);
      }
      $x=Parameter::first();
      if (isset($x)) {
        if ($x->name_company!=$request->name) {
          $log=new log();
          $log->action="Cambio";
          $log->change="Nombre de empresa:".$request->name;
          $log->user_id=Auth::user()->id;
          $log->save();
        }
        if ($x->phone!=$request->phone) {
          $log=new log();
          $log->action="Cambio";
          $log->change="Teléfono: ".$request->phone;
          $log->user_id=Auth::user()->id;
          $log->save();
        }
        if ($x->address!=$request->address) {
          $log=new log();
          $log->action="Cambio";
          $log->change="Dirección: ".$request->address;
          $log->user_id=Auth::user()->id;
          $log->save();
        }
        if ($x->email!=$request->email) {
          $log=new log();
          $log->action="Cambio";
          $log->change="Email: ".$request->email;
          $log->user_id=Auth::user()->id;
          $log->save();
        }

        $x->name_company=$request->name;
        $x->percent_max=0;
        $x->phone=$request->phone;
        $x->address=$request->address;
        $x->email=$request->email;
        $x->primary=$request->primary;
        $x->ceo = $request->ceo;
        $x->second = $request->second;
        $x->slogan = $request->slogan;
        $x->description=$request->description;
        $x->renovation_amount=0;
        $x->amount_guarantor_required=0;
        $x->percent_commission=0;
        $x->amount_authorize_required=0;
        $x->percent_evaluate=0;
        $x->navbar_color = $request->navbar_color;
        $x->leftmenu_color = $request->leftmenu_color;
        $x->select_color = $request->select_color;

        $x->website = $request->website;
        $x->footer_text = $request->footer_text;
        $x->facebook = $request->facebook;
        $x->instagram = $request->instagram;
        $x->twitter = $request->twitter;
        $x->whatsapp = $request->whatsapp;

        if (isset($request->fel)){
            $x->fel = true;
        }
        else{
            $x->fel = false;
        }
        $x->fel_username = $request->fel_username;
        $x->fel_cert = $request->fel_cert;
        $x->fel_firm = $request->fel_firm;
          $x->nit = $request->nit;

        Session::flash('message', 'Se han actualizado los datos.');
        $x->update();
      }
      else{
        $new=new Parameter();
        $new->name_company=$request->name;
        $new->percent_max=0;
        $new->phone=$request->phone;
        $new->address=$request->address;
        $new->email=$request->email;
        $new->primary = $request->primary;
        $new->ceo = $request->ceo;
        $new->second = $request->second;
        $new->slogan = $request->slogan;
        $new->description = $request->description;
        $new->amount_authorize_required=0;
        $new->percent_commission=0;
        $new->renovation_amount=0;
        $new->amount_guarantor_required=0;
        $new->percent_evaluate=0;
        $new->navbar_color = $request->navbar_color;
        $new->leftmenu_color = $request->leftmenu_color;
        $new->select_color = $request->select_color;
        //
        $new->website = $request->website;
        $new->footer_text = $request->footer_text;
        $new->facebook = $request->facebook;
        $new->instagram = $request->instagram;
        $new->twitter = $request->twitter;
        $new->whatsapp = $request->whatsapp;


          if (isset($request->fel)){
              $new->fel = true;
          }
          else{
              $new->fel = false;
          }
          $new->fel_username = $request->fel_username;
          $new->fel_cert = $request->fel_cert;
          $new->fel_firm = $request->fel_firm;
          $new->nit = $request->nit;

        Session::flash('message', 'Se han almacenado los datos.');
        $log=new log();
        $log->action="Configuración inicla de parámetros.";
        $log->user_id=Auth::user()->id;
        $log->save();
        $new->save();
      }
      \Illuminate\Support\Facades\Session::put('empresa', $request->name);
      $data=Parameter::first();
      $insert=1;
      $store=1;
      return redirect('parameters');
    }
    public function log(){
      $logs=log::orderby('id', 'desc')->get();
      $users=User::all();
      return view('parameter.logs')
      ->with('users', $users)
      ->with('logs', $logs);
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
        //
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
        //
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
