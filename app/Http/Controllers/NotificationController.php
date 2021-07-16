<?php

namespace App\Http\Controllers;

use App\GeneralParameter;
use App\Traits\NotificationTrait;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use \Auth, \Redirect, \Validator, \Input, \Session;
use App\Http\Controllers\Controller;
use Monolog\Handler\IFTTTHandler;

class NotificationController extends Controller
{
    use NotificationTrait;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('parameter');
    }
    /**
        FUNCION PARA OBTENER NOTIFICACIONES DE USUARIOS DEPENDIENDO DE SU ROL
     *
     * DISEÑADO PARA PETICIONES CON AJAX
     */
    public function getNotifications(){
        $user = User::find(Auth::user()->id);
        $administrador = false;
        /*
         * VERIFICAR ROL DE USUARIO, BUSCAR ROL ADMINISTRADOR
         * */
        foreach($user->roles as $rol){
            if ($rol->admin==1){
                $administrador = true;
            }
        }
        /*
         * SI ES ADMINISTRADOR BUSCAR NOTIFICACIONES PENDIENTES
         * */
        if ($administrador){
            $parameter = GeneralParameter::find(4);
            if ($parameter->text_value=='1'){
                $notifications = array_merge($this->creditReceivings(), $this->credit(), $this->verifyOverdueChecks());

            }
            else{
                $notifications = array();
            }
        }
        else{
            $notifications = array();
        }
        $notifications = array_merge($notifications, $this->verifyInventoryClosing());
        \Illuminate\Support\Facades\Session::put('notifications', $notifications);
        return $notifications;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
