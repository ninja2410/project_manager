<?php

namespace App\Http\Controllers;

use App\StateCellar;
use http\Url;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Retention;
use App\Account;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Auth, \Redirect, \Validator, \Input, \Session;

class RetentionController extends Controller
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
        $status = Input::get('status');
        $all_status = StateCellar::where('type', 'General')
            ->limit(2)
            ->get();
        if ($status == null) {
            $status = (array)1;
        } else {
            if ($status == 0){
                $status = StateCellar::where('type', 'General')
                    ->lists('id');
            }
            else{
                $status = (array)$status;
            }
        }
        $url = url('banks/retention');
        $retentions=Retention::whereIn('status', $status)->get();
        return view('banking.retention.index')
            ->with('status', $status)
            ->with('url', $url)
            ->with('all_status', $all_status)
        ->with('retentions', $retentions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banking.retention.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::begintransaction();
        try {
          /*CREAR CUENTA*/
          $account = new Account;
          $account->account_name = "Cuenta Interna: ".$request->name;
          $account->bank_name = "Cuenta interna";
          $account->account_type_id = 1;
          $account->opening_balance = 0;
          $account->pago_id = 1;
          $account->account_responsible = Auth::user()->id;
          $account->status = 1;
          $account->user_id = Auth::user()->id;
          $account->categorie_id=17;
          $account->save();
          /*----------------------------*/
          $retention=new Retention();
          $retention->name=$request->name;
          $retention->percent=$request->percent;
          $retention->description=$request->description;
          $retention->status=1;
          $retention->account_id=$account->id;
          $retention->save();

          DB::commit();
          Session::flash('message','Retención creada correctamente.');
        } catch (\Exception $e) {
          DB::rollback();
          Session::flash('message', "No se pudo crear la retención: " . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile());
          Session::flash('alert-class', 'alert-error');
        }
        return Redirect::to('banks/retention');
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
        $retention=Retention::find($id);
        return view('banking.retention.edit')
        ->with('retention', $retention);
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
        DB::begintransaction();
        try {
          $retention=Retention::find($id);
          $retention->name=$request->name;
          $retention->percent=$request->percent;
          $retention->description=$request->description;
          $retention->update();
          /*ACTUALIZAR LA CUENTA*/
          $account=Account::find($retention->account_id);
          $account->account_name='Cuenta Interna:'.$request->name;
          $account->update();
          DB::commit();
          Session::flash('message','Retención actualizada correctamente.');
        } catch (\Exception $e) {
          DB::rollback();
          Session::flash('message', "No se pudo actualizar la retención: " . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile());
          Session::flash('alert-class', 'alert-error');
        }
        return Redirect::to('banks/retention');
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
        $retention=Retention::find($id);
        $account = Account::find($retention->account_id);
        $account->status = 2;
        $account->update();
        $retention->status = 2;
        $retention->update();
        Session::flash('message','Retención eliminada correctamente.');
      } catch (\Exception $e) {
        Session::flash('message', "No se pudo eliminar la retención: " . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile());
        Session::flash('alert-class', 'alert-error');
      }
      return Redirect::to('banks/retention');
    }
}
