<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Route;
use App\User;
use App\Customer;
use App\Account;
use App\target;
use App\Balance_detail;
use App\StateCellar;
use App\RouteUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Auth, \Redirect, \Validator, \Input, \Session, \Response;

class RouteController extends Controller
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
    // return Response::json(Route::with('users')->with('costumers')->with('states')->get());
    public function index()
    {
        return view('route.index');
    }

    public function index_ajax()
    {
        return Response::json(Route::with('states')->with('users')->with('costumers')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('route.create')
            ->with('routes', Route::all())
            ->with('users', User::leftJoin('route_users', 'users.id', '=', 'route_users.user_id')->whereNull('route_users.user_id')->select('users.id', 'users.name')->get())
            ->with('customers', Customer::leftJoin('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')->whereNull('route_costumers.customer_id')->where('customers.id', '!=', '1')->select('customers.id', 'customers.name')->get())
            ->with('states', StateCellar::where('id', '1')->orWhere('id', '2')->get()->pluck('name', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'unique:routes',
        ]);


        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $error){
                $message .= $error.' | ';
            }
            Session::flash('message', $message);
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }
        DB::beginTransaction();
        try {
            $route = new Route;
            $route->name = $request->name;
            $route->description = $request->description;
            $route->goal_amount = $request->amount;
            $route->created_by = Auth::id();
            $route->updated_by = Auth::id();
            $route->status_id = $request->states;
            $route->save();
            $manager = Input::get('users');
            $route->users()->sync($manager);
            $customer = Input::get('customers');
            if (!empty($customer))
                $route->costumers()->sync($customer);

            DB::commit();
            Session::flash('message', 'Insertado correctamente');
            return redirect::to('routes');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', $e->getMessage());
            return redirect::to('routes');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('route.show')
            ->with('route', Route::with('users')
                ->with('costumers')
                ->with('states')
                ->with('creador')
                ->with('actualizacion')
                ->get()->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('route.edit')
            ->with('route', Route::with('users')->with('costumers')->get()->find($id))
            ->with('users', User::leftJoin('route_users', 'users.id', '=', 'route_users.user_id')
                ->whereNull('route_users.user_id')
                ->orWhere('route_users.route_id', $id)
                ->select('users.id', 'users.name')->get())
            ->with('customers', Customer::leftJoin('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')
                ->where('customers.id', '!=', '1')
                ->whereNull('route_costumers.customer_id')
                ->orWhere('route_costumers.route_id', $id)
                ->select('customers.id', 'customers.name')->get())
            ->with('states', StateCellar::where('id', '1')->orWhere('id', '2')->get()->pluck('name', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'unique:routes,name,' . $id,
        ]);

        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $error){
                $message .= $error.' | ';
            }
            Session::flash('message', $message);
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }
        DB::beginTransaction();
        try {
            $route = Route::find($id);
            $route->name = $request->name;
            $route->description = $request->description;
            $route->goal_amount = $request->amount;
            $route->updated_by = Auth::id();
            $route->status_id = $request->states;
            $route->save();

            $manager = Input::get('users');
            $route->users()->detach();
            $route->users()->attach($manager);

            $customer = Input::get('customers');
            if (!empty($customer)) {
                $route->costumers()->detach();
                $route->costumers()->sync($customer);
            }
            DB::commit();
            Session::flash('message', 'Ruta actualizada con éxito.');
            return redirect::to('routes');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', $e->getMessage());
            return redirect::to('routes');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $route = Route::find($id);
            $route_users = RouteUser::where('route_id', $id)->get();
            foreach ($route_users as $item) {
                $item->delete();
            }
            $route->delete();
            DB::commit();
            Session::flash('message', trans('route.deleted'));
            return Redirect::to('routes');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            Session::flash('message', trans('item.integrity_violation') . ' [' . $route->name . ']');
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('routes');
        }
    }
}
