<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Supplier;
use App\Http\Requests\SupplierRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Image;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class SupplierController extends Controller
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
        $suppliers = Supplier::all();
        return view('supplier.index')->with('supplier', $suppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('supplier.create');
    }

    public function store_ajax(Request $request)
    {
        $suppliers = new Supplier;
        $suppliers->nit_supplier = $request->nit_supplier;
        $suppliers->company_name = $request->company_name;
        $suppliers->name = $request->name;
        $suppliers->email = $request->email;
        $suppliers->phone_number = $request->phone_number;
        $suppliers->address = $request->address;
        $suppliers->name_on_checks = $request->name_on_checks;
        $suppliers->max_credit_amount = $request->credit;
        $suppliers->days_credit = $request->days_credit;
        $suppliers->name_bank = $request->bank;
        $suppliers->account_number = $request->account_number;
        $suppliers->save();
        return $suppliers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(SupplierRequest $request)
    {
        $nit = 'C/F';
        $nit = strtoupper(Input::get('nit_supplier'));
        $nitValidation = 'required|unique:suppliers';
        $nameValidation = 'required';
        if ($nit == 'C/F') {
            $nitValidation = 'required';
            $nameValidation = 'required|unique:suppliers';
        }
        $validator = Validator::make($request->all(), [
            'nit_supplier' => $nitValidation,
            'company_name' => $nameValidation,
            'email' => 'email|unique:suppliers',
            'address' => 'required'
        ]);

        if ($validator->fails()) {
            $message = '';
            foreach ($validator->errors()->all() as $error) {
                $message .= $error . ' | ';
            }
            Session::flash('message', $message);
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

        $suppliers = new Supplier;
        $suppliers->nit_supplier = Input::get('nit_supplier');
        $suppliers->company_name = Input::get('company_name');
        $suppliers->name = Input::get('name');
        $suppliers->email = Input::get('email');
        $suppliers->phone_number = Input::get('phone_number');
        $suppliers->address = Input::get('address');
        $suppliers->days_credit = Input::get('days_credit');
        $suppliers->max_credit_amount = Input::get('credit');
        $suppliers->name_on_checks = Input::get('name_on_checks');
        $suppliers->name_bank = Input::get('bank');
        $suppliers->account_number = Input::get('account_number');
        $suppliers->save();
        // process avatar
        $image = $request->file('avatar');
        if (!empty($image)) {
            $avatarName = 'sup' . $suppliers->id . '.' .
                $request->file('avatar')->getClientOriginalExtension();

            $request->file('avatar')->move(
                base_path() . '/public/images/suppliers/', $avatarName
            );
            $img = Image::make(base_path() . '/public/images/suppliers/' . $avatarName);
            $img->save();
            $supplierAvatar = Supplier::find($suppliers->id);
            $supplierAvatar->avatar = $avatarName;
            $supplierAvatar->save();
        }

        // Session::flash('message', 'You have successfully added supplier');
        Session::flash('message', 'Proveedor insertado correctamente');
        Session::flash('alert-type', 'success');

        return Redirect::to('suppliers');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data = Supplier::find($id);

        return view('supplier.show')
            ->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $suppliers = Supplier::find($id);
        return view('supplier.edit')
            ->with('supplier', $suppliers);
    }

    public function getSupplier($id)
    {
        $supplier = Supplier::find($id);
        return $supplier;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(SupplierRequest $request, $id)
    {
        $suppliers = Supplier::find($id);
        $suppliers->nit_supplier = Input::get('nit_supplier');
        $suppliers->company_name = Input::get('company_name');
        $suppliers->name = Input::get('name');
        $suppliers->email = Input::get('email');
        $suppliers->phone_number = Input::get('phone_number');
        $suppliers->address = Input::get('address');
        // $suppliers->city = Input::get('city');
        // $suppliers->state = Input::get('state');
        // $suppliers->zip = Input::get('zip');
        // $suppliers->comments = Input::get('comments');
        // $suppliers->account = Input::get('account');
        $suppliers->max_credit_amount = Input::get('max_credit_amount');
        $suppliers->days_credit = Input::get('days_credit');
        $suppliers->name_bank = Input::get('name_bank');
        $suppliers->account_number = Input::get('account_number');
        $suppliers->name_on_checks = Input::get('name_on_checks');
        $suppliers->save();
        // process avatar
        $image = $request->file('avatar');
        if (!empty($image)) {
            $avatarName = 'sup' . $id . '.' .
                $request->file('avatar')->getClientOriginalExtension();

            $request->file('avatar')->move(
                base_path() . '/public/images/suppliers/', $avatarName
            );
            $img = Image::make(base_path() . '/public/images/suppliers/' . $avatarName);
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save();
            $supplierAvatar = Supplier::find($id);
            $supplierAvatar->avatar = $avatarName;
            $supplierAvatar->save();
        }

        // Session::flash('message', 'You have successfully updated supplier');
        Session::flash('message', 'Proveedor actualizado correctamente');
        Session::flash('alert-type', 'success');

        return Redirect::to('suppliers');
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
            $suppliers = Supplier::find($id);
            $suppliers->delete();
            // Session::flash('message', 'You have successfully deleted supplier');
            Session::flash('message', trans('supplier.deleted_ok'));

            return Redirect::to('suppliers');
        } catch (\Illuminate\Database\QueryException $e) {
            // Session::flash('message', 'Integrity constraint violation: You Cannot delete a parent row');
            Session::flash('message', trans('supplier.integrity_violation') . ' [' . $suppliers->company_name . ']');
            Session::flash('alert-type', 'error');
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('suppliers');
        }
    }

}
