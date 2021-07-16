<?php

namespace App\Http\Middleware;

use App\Parameter;
use App\Traits\NotificationTrait;
use App\User;
use Closure;
use Illuminate\Support\Facades\Redirect;
use \Session, \Auth;

class ParameterMiddleware
{
    use NotificationTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::find(Auth::user()->id);
        \Illuminate\Support\Facades\Session::put('notifications', array());
        $administrador = false;
        /*
         * VERIFICAR ROL DE USUARIO, BUSCAR ROL ADMINISTRADOR
         * */
        foreach($user->roles as $rol){
            if ($rol->admin==1){
                $administrador = true;
            }
        }
        \Illuminate\Support\Facades\Session::put('administrador', $administrador);
        #region ANTIGUA LÓGICA DE NOTIFICACIONES
        /*
         * SI ES ADMINISTRADOR BUSCAR NOTIFICACIONES PENDIENTES
         * */
        if ($administrador){
            $notifications = array_merge($this->creditReceivings(), $this->credit(), $this->verifyOverdueChecks());
        }
        else{
            $notifications = array();
        }
        $notifications = array_merge($notifications, $this->verifyInventoryClosing());
        \Illuminate\Support\Facades\Session::put('notifications', $notifications);
        #endregion

        \Illuminate\Support\Facades\Session::put('empresa', 'Cacao.gt');
        $param = Parameter::count();
        if ($param==0){
            // dd($notifications);
            Session::flash('message', 'Debe registrar los datos de la empresa para poder continuar');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('parameters');
        }
        else{
            $parameter = Parameter::first();
            \Illuminate\Support\Facades\Session::put('empresa', $parameter->name_company);
            \Illuminate\Support\Facades\Session::put('navbar_color', $parameter->navbar_color);
            \Illuminate\Support\Facades\Session::put('leftmenu_color', $parameter->leftmenu_color);
            \Illuminate\Support\Facades\Session::put('select_color', $parameter->select_color);
            return $next($request);
        }

    }
}
