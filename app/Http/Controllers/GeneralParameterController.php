<?php

namespace App\Http\Controllers;

use App\User;
use Validator;

use App\Http\Requests;
use App\GeneralParameter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class GeneralParameterController extends Controller
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
        $parameters = GeneralParameter::all();
        return view('general_parameters.index')->with('parameters', $parameters);
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {        
        $users=User::where('show_in_tx',0)->lists('name','id');
        return view('general_parameters.create')->with('users',$users);
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // dd($request->all());
        //validate
        $rules = array(
            'type'        => 'required',
            'name'        => 'required',
            'description' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $error){
                $message .= $error.' | ';
            }
            Session::flash('message', $message);
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('general-parameteres/create')
            ->withInput();
        } else {
            $logged_user  = Auth::user();
            $user= Input::get('assigned_user_id')==0?null:Input::get('assigned_user_id');
            
            $parameters = new GeneralParameter();
            $parameters->type = Input::get('type');
            $parameters->name = Input::get('name');
            $parameters->description=Input::get('description');
            $parameters->text_value = Input::get('text_value');
            $parameters->min_amount = Input::get('min_amount');
            $parameters->max_amount = Input::get('max_amount');
            $parameters->default_amount = Input::get('default_amount');
            $parameters->assigned_user_id = $user;
            $parameters->created_by = $logged_user->id;
            $parameters->updated_by = $logged_user->id;
            $parameters->active = 1;
            $parameters->save();
            Session::flash('message', 'Pago insertado correctamente');
            Session::flash('alert-type', trans('success'));
            return Redirect::to('general-parameters');
        }
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
        $parameters = GeneralParameter::find($id);
        $users=User::where('show_in_tx',0)->lists('name','id');
        return view('general_parameters.edit')
        ->with('parameters', $parameters)
        ->with('users', $users);
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
        $rules = array(
            'type'        => 'required',
            'name'        => 'required',
            'description' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $error){
                $message .= $error.' | ';
            }
            Session::flash('message', $message);
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()
            ->withInput();
        } 
        $logged_user  = Auth::user();
        $user= Input::get('assigned_user_id')==0?null:Input::get('assigned_user_id');
        
        $parameters = GeneralParameter::find($id);
        $parameters->type = Input::get('type');
        $parameters->name = Input::get('name');
        $parameters->description=Input::get('description');
        $parameters->text_value = Input::get('text_value');
        $parameters->min_amount = Input::get('min_amount');
        $parameters->max_amount = Input::get('max_amount');
        $parameters->default_amount = Input::get('default_amount');
        $parameters->assigned_user_id = $user;            
        $parameters->updated_by = $logged_user->id;
        $parameters->active = Input::get('active');;
        $parameters->save();
    
        Session::flash('message', 'Actualizado correctamente');
        Session::flash('alert-type', trans('success'));
        return Redirect::to('general-parameters');
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
            $pagos = Pago::find($id);
            $pagos->delete();
            // redirect
            // Session::flash('message', 'You have successfully deleted employee');
            Session::flash('message', 'Registro eliminado correctamente');
            Session::flash('alert-type', trans('success'));
        } catch (\Illuminate\Database\QueryException $e) {
            Session::flash('message', 'Tipo de pago en al menos una transacción: No se puede eliminar');
            Session::flash('alert-class', 'error');
            
        }
        return Redirect::to('pago');
    }
}
