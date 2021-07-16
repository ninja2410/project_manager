<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\RouteUser;
use Illuminate\Support\Facades\URL;
use App\User;
use App\StateCellar;
use App\Role;
use Image;
use App\UserRole;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use \Auth, \Redirect, \Validator, \Input, \Session, \Hash;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('parameter');
	}

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		// $employees = User::all();
		$employees = User::where('users.show_in_tx', 0)->get();
		return view('employee.index')->with('employee', $employees);
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		$number= User::max('number')==null?0:User::max('number');
		$number++;
		$state_cellar = StateCellar::general()->lists('name', 'id');
		$dataRoles = Role::all();
		return view('employee.create')
		->with('state_cellar', $state_cellar)
		->with('number', $number)
		->with('dataRoles',$dataRoles);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store(EmployeeStoreRequest $request)
	{
		// dd($request->all());
		// store
		$users = new User;
		$users->name = Input::get('name');
		$users->email = Input::get('email');
		if ($request->password!=="") {
			$users->password = Hash::make(Input::get('password'));
		}
		$users->user_state = Input::get('id_state');
		$users->last_name = $request->last_name;
		$users->DPI = $request->dpi;

		$dateArray = explode("/", $request->birthdate);
		$nDay = $dateArray[0];
		$nMounth = $dateArray[1];
		$nYear = $dateArray[2];
		$users->birthdate = $nYear . '/' . $nMounth . '/' . $nDay;

		$users->phone = $request->phone;
		$dateArray = explode("/", $request->date_hire);
		$nDay = $dateArray[0];
		$nMounth = $dateArray[1];
		$nYear = $dateArray[2];
		$users->date_hire = $nYear . '/' . $nMounth . '/' . $nDay;

		if($request->date_dimissal!=="") {
			$dateArray = explode("/", $request->date_dimissal);
			$nDay = $dateArray[0];
			$nMounth = $dateArray[1];
			$nYear = $dateArray[2];
			$users->date_dimissal = $nYear . '/' . $nMounth . '/' . $nDay;
		} 

		$users->nationality = $request->nationality;
		$users->mobile = $request->mobile;
		$users->address = $request->address;
		$users->alternative_address = $request->alternative_address;
		if(!empty($request->igss))
			$users->no_IGSS = $request->igss;
		$users->number = $request->number;
		$users->emergency_name = $request->emergency_name;
		$users->emergency_phone = $request->emergency_phone;
		$users->shoe_size = $request->shoe_size;
		$users->trouser_size = $request->trouser_size;
		$users->shirt_size = $request->shirt_size;
		$users->comments = $request->comments;
		$users->sales_goal = $request->sales_goal;
		$users->collection_goal = $request->collection_goal;
		$users->expenses_max = $request->expenses_max;


		$users->save();

		// $users->roles()->sync($request->input('roles', []));
		$users->roles()->attach($request->input('roles', []),['created_at'=>date('Y-m-d h:i:s'),'updated_at'=>date('Y-m-d h:i:s')]);
		//GUARDAR AVATAR
		$image = $request->file('avatar');
		if (!empty($image)) {
			$avatarName = 'usr' . $users->id . '.' .
			$request->file('avatar')->getClientOriginalExtension();

			$request->file('avatar')->move(
				base_path() . '/public/images/users/',
				$avatarName
			);
			$img = Image::make(base_path() . '/public/images/users/' . $avatarName);
			$img->resize(100, null, function ($constraint) {
				$constraint->aspectRatio();
			});
			$img->save();
			$customerAvatar = User::find($users->id);
			$customerAvatar->avatar = $avatarName;
			$customerAvatar->save();
		} else {
			$avatarName = 'usrx.png';
			$customerAvatar = User::find($users->id);
			$customerAvatar->avatar = $avatarName;
			$customerAvatar->save();
		}
		// Session::flash('message', 'You have successfully added employee');
		Session::flash('message', 'Empleado agregado correctamente');
		// Session::flash('alert-class', 'success');

		return Redirect::to('employees');
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function show($id)
	{
		$employees = User::find($id);
		$employees->birthdate = date('d/m/Y', strtotime($employees->birthdate));
		$employees->date_hire = date('d/m/Y', strtotime($employees->date_hire));
		$employees->date_dimissal = date('d/m/Y', strtotime($employees->date_dimissal));
		$state_cellar = StateCellar::lists('name', 'id');
		$dataRoles = Role::all();

		return view('employee.profile')
		->with('data', $employees)
		->with('state_cellar', $state_cellar)
		->with('dataRoles', $dataRoles)
		->with('finish_route','/');
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function edit($id)
	{
		$employees = User::with('roles')->find($id);
		
		$employees->birthdate = date('d/m/Y', strtotime($employees->birthdate));
		$employees->date_hire = date('d/m/Y', strtotime($employees->date_hire));
		$employees->date_dimissal = date('d/m/Y', strtotime($employees->date_dimissal));
		// dd($employees);
		$state_cellar = StateCellar::general()->lists('name', 'id');
		$dataRoles = Role::all();

		return view('employee.edit')
		->with('employee', $employees)
		->with('state_cellar', $state_cellar)
		->with('dataRoles', $dataRoles)
		->with('finish_route','0');
	}

	public function editProfile($id)
	{
		$employees = User::find($id);
		$employees->birthdate = date('d/m/Y', strtotime($employees->birthdate));
		$employees->date_hire = date('d/m/Y', strtotime($employees->date_hire));
		$employees->date_dimissal = date('d/m/Y', strtotime($employees->date_dimissal));
		$state_cellar = StateCellar::lists('name', 'id');
		$dataRoles = Role::all();

		return view('employee.edit')
		->with('employee', $employees)
		->with('state_cellar', $state_cellar)
		->with('dataRoles', $dataRoles)
		->with('finish_route','/');
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update($id)
	{
		if (($id == 1) && (Auth::user()->id!=1)) {
			// Session::flash('message', 'You cannot edit admin on MasterPOS');
			Session::flash('message', 'No se puede actualizar el Administrador');

			Session::flash('alert-class', 'alert-error');
			return Redirect::to('employees');
		} else {



			$rules = array(
				'name' => 'required',
				'email' => 'required|email|unique:users,email,' . $id . '',
				// 'number' => 'required|number|unique:users,number,' . $id . ''
			);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()) {
				return Redirect::to('employees/' . $id . '/edit')
				->withErrors($validator);
			} else {
				$users = User::find($id);
				$users->name = Input::get('name');
				$users->email = Input::get('email');
				if (!empty(Input::get('password'))) {
					$users->password = Hash::make(Input::get('password'));
				}
				if (Input::get('password') != "") {
					$users->password = Hash::make(Input::get('password'));
				}
				$users->user_state = Input::get('id_state');
				$users->last_name = Input::get('last_name');
				$users->DPI = Input::get('dpi');

				if (Input::get('birthdate') != "") {
					$dateArray = explode("/", Input::get('birthdate'));
					// dd($dateArray);
					$nDay = $dateArray[0];
					$nMounth = $dateArray[1];
					$nYear = $dateArray[2];
					$users->birthdate = $nYear . '/' . $nMounth . '/' . $nDay;
				}

				$users->phone = Input::get('phone');
				if (Input::get('date_hire') != "") {
					$dateArray = explode("/", Input::get('date_hire'));
					$nDay = $dateArray[0];
					$nMounth = $dateArray[1];
					$nYear = $dateArray[2];
					$users->date_hire = $nYear . '/' . $nMounth . '/' . $nDay;
				}

				if (Input::get('date_dimissal') != "") {
					$dateArray = explode("/", Input::get('date_dimissal'));
					$nDay = $dateArray[0];
					$nMounth = $dateArray[1];
					$nYear = $dateArray[2];
					$users->date_dimissal = $nYear . '/' . $nMounth . '/' . $nDay;
				}

				$users->nationality = Input::get('nationality');
				$users->mobile = Input::get('mobile');
				$users->address = Input::get('address');
				$users->alternative_address = Input::get('alternative_address');
				$users->no_IGSS = Input::get('no_IGSS');
				$users->number = Input::get('number');
				$users->emergency_name = Input::get('emergency_name');
				$users->emergency_phone = Input::get('emergency_phone');
				$users->shoe_size = Input::get('shoe_size');
				$users->trouser_size = Input::get('trouser_size');
				$users->shirt_size = Input::get('shirt_size');
				$users->comments = Input::get('comments');
				$users->sales_goal = Input::get('sales_goal');
				$users->collection_goal = Input::get('collection_goal');
				$users->expenses_max =Input::get('expenses_max');
				$users->save();
				$ruta = Input::get('finish_route');
				if($ruta=='0'){					
					$users->roles()->detach();
					$users->roles()->attach(Input::get('roles', []),['updated_at'=>date('Y-m-d h:i:s')]);
				}

				// process avatar
				$image = Input::file('avatar');
				if (!empty($image)) {
					$avatarName = 'usr' . $id . '.' .
					Input::file('avatar')->getClientOriginalExtension();

					Input::file('avatar')->move(
						base_path() . '/public/images/users/',
						$avatarName
					);
					$img = Image::make(base_path() . '/public/images/users/' . $avatarName);
					$img->resize(100, null, function ($constraint) {
						$constraint->aspectRatio();
					});
					$img->save();
					$customerAvatar = User::find($id);
					$customerAvatar->avatar = $avatarName;
					$customerAvatar->save();
				}
				Session::flash('message', 'Empleado actualizado correctamente');
				
				if($ruta!='0')
				{
					return Redirect::to($ruta);
				}

				return Redirect::to('employees');
				// return URL::previous();
			}
		}
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy($id)
	{
		if ($id == 1) {
			// Session::flash('message', 'You cannot delete admin on MasterPOS');
			Session::flash('message', 'No se puede eliminar al Administrador');

			Session::flash('alert-class', 'alert-error');
			return Redirect::to('employees');
		} else {
			try {
				$users = User::find($id);
				$users->delete();
				// redirect
				// Session::flash('message', 'You have successfully deleted employee');
				Session::flash('message', 'Empleado eliminado correctamente');

				return Redirect::to('employees');
			} catch (\Illuminate\Database\QueryException $e) {
				// Session::flash('message', 'Integrity constraint violation: You Cannot delete a parent row');				
				Session::flash('message', 'Violación de integridad, no se puede eliminar: ['.$users->name.' '.$users->last_name.']');

				Session::flash('alert-class', 'alert-error');
				return Redirect::to('employees');
			}
		}
	}
	public function getRouteUser($user_id){	
	    $route = RouteUser::where('user_id', $user_id)
            ->orderby('id', 'desc')
            ->first();
	    if (isset($route->route_id)){
            return $route->route_id;
        }
	    else{
	        return -1;
        }

    }
}
