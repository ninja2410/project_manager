<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\UserTrait;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    use UserTrait;
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            $permisos = array();
            \Illuminate\Support\Facades\Session::put('permisions', $permisos);
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }
        $permisos = $this->getUserPermissions(Auth::user()->id);
        \Illuminate\Support\Facades\Session::put('permisions', $permisos);

        return $next($request);
    }
}
