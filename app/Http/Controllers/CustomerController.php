<?php namespace App\Http\Controllers;

use Image;
use Datetime;
use App\Route;
use App\Images;
use App\Pagare;
use Datatables;
use App\Customer;
use App\Reference;
use App\RouteUser;
use App\ClassTable;
use App\CustomerClass;
use App\Http\Requests;
use App\RouteCostumer;
use App\GeneralParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use \Auth, \Redirect, \Validator, \Input, \Session, \Response;

class CustomerController extends Controller
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
        return view('customer.index');
    }

    public function list_customers()
    {
        $administrador = Session::get('administrador');
        $ruta_requerida = GeneralParameter::active()->where('name', 'Campo ruta requerido.')->first();
        /** Si la ruta es requerida y no es administrador */
        if ((isset($ruta_requerida)) && ($administrador == false)) {
            $rutas = RouteUser::where('user_id', Auth::user()->id)->select('route_id')->get();
            if (count($rutas) == 0) {
                $rutas = [0, 0];
            };

            $customers = Customer::join('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')
                ->whereIn('route_costumers.route_id', $rutas)
                ->select('customers.*')
                ->groupBy('customers.id')
                ->get();
        } else {
            $customers = Customer::select('customers.*')
                ->groupBy('customers.id')
                ->get();
        }
//        dd(($customers[0]));
        $data = array('data' => $customers);
        return json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {        
        // $cust_code = intval(Customer::max(DB::Raw('coalesce(customer_code,0)'))==""?0: Customer::max(DB::Raw('coalesce(customer_code,0)')));
        
        return view('customer.create')
            ->with('requerido', count(GeneralParameter::where('name', 'Campo ruta requerido.')->where('active', '1')->get()) != 0 ? true : false)
            ->with('rutas', Route::where('status_id', '1')->get())
            ->with('codigo', intval(Customer::max(DB::Raw('cast(coalesce(customer_code,0) as unsigned)'))) + 1);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $nit = 'C/F';
            $nit = trim(strtoupper(Input::get('nit_customer')));
            $dpi = Input::get('dpi');
            $customer_code = trim(strtoupper(Input::get('customer_code')));

            $nitValidation = 'required|unique:customers';
            $nameValidation = 'required';
            if ($nit == 'C/F') {
                $nitValidation = 'required';
                $nameValidation = 'required|unique:customers';
            }
            if (trim($dpi) != "") {
                $dpi_validation = 'required|unique:customers,dpi';
            } else {
                $dpi_validation = '';
            }
            if ($customer_code != "") {
                $code_validation = 'required|unique:customers,customer_code';
            } else {
                $code_validation = '';
            }
            //Validacion
            $validator = Validator::make($request->all(), [
                'nit_customer' => $nitValidation,
                'name' => $nameValidation,
                'email' => 'email|unique:customers',
                'dpi' => $dpi_validation,
                'customer_code' => $code_validation
            ]);


            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }


            // store
            $customers = new Customer;
            //	            $customers->nit_customer=Input::get('nit_customer');
            $customers->nit_customer = $nit;
            $customers->dpi = $dpi;
            $customers->name = strtoupper(Input::get('name'));
            $customers->email = Input::get('email');
            $customers->phone_number = Input::get('phone_number');
            $customers->address = Input::get('address');

            $customers->comment = Input::get('comment');
            $customers->max_credit_amount = Input::get('max_credit_amount');
            $customers->days_credit = Input::get('days_credit');
            $customers->customer_code = $customer_code;


            $customers->birthdate = Input::get('birthdate');
            $customers->marital_status = Input::get('marital_status');
            $customers->state = Input::get('state');
            $customers->city = Input::get('city');

            $customers->balance = 0.00;
            $customers->created_by = $user->id;
            $customers->updated_by = $user->id;

            $customers->save();
            // process avatar
            $image = $request->file('avatar');
            if (!empty($image)) {
                $avatarName = 'cus' . $customers->id . '.' .
                    $request->file('avatar')->getClientOriginalExtension();

                $request->file('avatar')->move(
                    base_path() . '/public/images/customers/',
                    $avatarName
                );
                $img = Image::make(base_path() . '/public/images/customers/' . $avatarName);
                $img->save();
                $customerAvatar = Customer::find($customers->id);
                $customerAvatar->avatar = $avatarName;
                $customerAvatar->save();
            }
            // agregar a la ruta
            if (!empty($request->ruta)) {
                $customers->routes()->attach($request->ruta);
            }
            Session::flash('message', 'Cliente agregado de manera correcta');
            Session::flash('alert-class', 'success');
            DB::commit();
        }
        catch (\Exception $ex){
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }
        return Redirect::to('customers');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $customers = Customer::with('routes')->get()->find($id);
        return view('customer.edit')
            ->with('requerido', count(GeneralParameter::where('name', 'Campo ruta requerido.')->where('active', '1')->get()) != 0 ? true : false)
            ->with('rutas', Route::where('status_id', '1')->get())
            ->with('customer', $customers);
    }

    public function show_references($id)
    {
        $references = Reference::where('customer_id', $id)->get();
        return view('customer.list_references')
            ->with('references', $references);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $id = $request->customer_id;
            $nit = 'C/F';
            $nit = trim(strtoupper(Input::get('nit_customer')));
            $nitValidation = 'required|unique:customers,nit_customer,' . $id;
            $nameValidation = 'required';
            $dpi = Input::get('dpi');
            $customer_code = trim(strtoupper(Input::get('customer_code')));
            if ($nit == 'C/F') {
                $nitValidation = 'required';
                $nameValidation = 'required|unique:customers,name,' . $id;
            }
            if (trim($dpi) != "") {
                $dpi_validation = 'required|unique:customers,dpi,' . $id;
            } else {
                $dpi_validation = '';
            }
            if ($customer_code != "") {
                $code_validation = 'required|unique:customers,customer_code,' . $id;
            } else {
                $code_validation = '';
            }
            // dd($code_validation);
            //Validacion
            $validator = Validator::make($request->all(), [
                'nit_customer' => $nitValidation,
                'name' => $nameValidation,
                'email' => 'email|unique:customers,email,' . $id,
                'dpi' => $dpi_validation,
                'customer_code' => $code_validation
            ]);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }
            $customers = Customer::find($id);
            $customers->nit_customer = Input::get('nit_customer');
            $customers->nit_customer = $nit;
            $customers->dpi = $dpi;
            $customers->name = strtoupper(Input::get('name'));
            $customers->email = Input::get('email');
            $customers->phone_number = Input::get('phone_number');
            $customers->address = Input::get('address');
            $customers->customer_code = $customer_code;

            $customers->comment = Input::get('comment');
            $customers->max_credit_amount = Input::get('max_credit_amount');
            $customers->days_credit = Input::get('days_credit');
            if (Input::get('birthdate') != '') {
                $customers->birthdate = Input::get('birthdate');
            }
            $customers->marital_status = Input::get('marital_status');
            $customers->state = Input::get('state');
            $customers->city = Input::get('city');
            $customers->updated_by = $user->id;

            $customers->update();
            // process avatar
            $image = $request->file('avatar');
            if (!empty($image)) {
                $avatarName = 'cus' . $id . '.' .
                    $request->file('avatar')->getClientOriginalExtension();

                $request->file('avatar')->move(
                    base_path() . '/public/images/customers/',
                    $avatarName
                );
                $img = Image::make(base_path() . '/public/images/customers/' . $avatarName);
                $img->save();
                $customerAvatar = Customer::find($id);
                $customerAvatar->avatar = $avatarName;
                $customerAvatar->save();
            }
            if (!empty($request->ruta)) {
                $route_customer = RouteCostumer::where('customer_id', $id)->get();
                foreach ($route_customer as $item) {
                    $item->delete();
                }
                $customers->routes()->attach($request->ruta);
            }
            // redirect
            // Session::flash('message', 'You have successfully updated customer');
            Session::flash('message', 'Cliente actualizado de manera correcta');
            Session::flash('alert-class', 'success');
            DB::commit();
        }
        catch (\Exception $ex){
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

        return Redirect::to('customers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $customers = Customer::find($id);
            $route_customer = RouteCostumer::where('customer_id', $id)->get();
            foreach ($route_customer as $item) {
                $item->delete();
            }
            $customers->delete();
            Session::flash('message', trans('customer.deleted_ok'));
            return Redirect::to('customers');
        } catch (\Illuminate\Database\QueryException $e) {
            Session::flash('message', trans('customer.integrity_violation') . ' [' . $customers->name . ']');
            Session::flash('alert-class', 'alert-error');
            Session::flash('alert-type', 'error');


            return Redirect::to('customers');
        }
    }

    public function getCustomer()
    {
        $administrador = Session::get('administrador');
        $ruta_requerida = GeneralParameter::active()->where('name', 'Campo ruta requerido.')->first();
        /** Si la ruta es requerida y no es administrador */
        if ((isset($ruta_requerida)) && ($administrador == false)) {
            $rutas = RouteUser::where('user_id', Auth::user()->id)->select('route_id')->get();
            if (count($rutas) == 0) {
                $rutas = [0, 0];
            };
            $data_customer = Customer::join('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')
                ->whereIn('route_costumers.route_id', $rutas)
                ->select('customers.id', 'customers.name', 'customers.nit_customer', 'customers.phone_number')->get();
        } else {
            $data_customer = Customer::select('customers.id', 'customers.name', 'customers.nit_customer', 'customers.phone_number')->get();
        }

        // return $data_customer;+
        return response()->json($data_customer);

        // return Datatables::of($data_customer)
        // ->make(true);

    }

    public function prueba()
    {
        return view('pruebaModal');
    }

    public function addCustomerAjaxPos(Request $request)
    {
        $user = Auth::user();
        $existe = Customer::where('name', $request->name)->where('nit_customer', $request->nit)->count();
        if ($existe == 0) {
            $email = (isset($request->email) && $request->email != "") ? $request->email : '';
            $newCustomer = new Customer();
            $newCustomer->name = $request->name;
            $newCustomer->nit_customer = $request->nit;
            $newCustomer->dpi = $request->dpi;
            $newCustomer->email = $email;
            $newCustomer->phone_number = $request->phone;
            $newCustomer->address = $request->address;
            $newCustomer->created_by = $user->id;
            $newCustomer->updated_by = $user->id;
            $newCustomer->save();
            if ($request->ruta != "No hay data") {
                $ruta = new RouteCostumer;
                $ruta->customer_id = $newCustomer->id;
                $ruta->route_id = $request->ruta;
                $ruta->save();
            }
        } else {
            $data_return = "Ya existe un cliente con ese nombre";
            return response()->json($data_return);
        }
        return response()->json($newCustomer);
    }

    public function getProfile($id)
    {
        $data = Customer::with('routes')->get()->find($id);

        return view('customer.customer_profile')
            ->with('data', $data);
    }
}
