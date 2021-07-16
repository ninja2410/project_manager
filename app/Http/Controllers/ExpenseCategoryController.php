<?php

namespace App\Http\Controllers;

use App\ExpenseTax;
use App\Http\Requests;
use App\ExpenseCategory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use \Session, \Input,\Redirect;

class ExpenseCategoryController extends Controller
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
        $expense_catetories = ExpenseCategory::all();
        return view('expense_category.index', compact('expense_catetories'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('expense_category.create');
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:expense_categories'
            ]);

            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }

            $ExpenseCategory=new ExpenseCategory();
            $ExpenseCategory->name=Input::get('name');
            $ExpenseCategory->created_by=Auth::user()->id;
            $ExpenseCategory->updated_by=Auth::user()->id;
            $ExpenseCategory->save();

            Session::flash('message','Registro guardado correctamente');
            Session::flash('alert-class', 'success');
        }
        catch (\Exception $ex){
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

        return Redirect::to('banks/expense_categories');
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
        $expense_category=ExpenseCategory::find($id);
        return view('expense_category.edit')
        ->with('expense_category',$expense_category);
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
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:expense_categories,name,'.$id
            ]);

            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }

            $expense_category=ExpenseCategory::find($id);
            $expense_category->name=input::get('name');
            $expense_category->updated_by=Auth::user()->id;
            $expense_category->save();
            Session::flash('message','Actualizado correctamente');
            Session::flash('alert-type', trans('success'));
        }
        catch (\Exception $ex){
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }
        return Redirect::to('banks/expense_categories');
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
            $expense_category = ExpenseCategory::find($id);
            $expense_category->delete();
            // redirect
            // Session::flash('message', 'You have successfully deleted employee');
            Session::flash('message', 'Registro eliminado correctamente');
            Session::flash('alert-type', trans('success'));
        } catch (\Illuminate\Database\QueryException $e) {
            Session::flash('message', 'Categoria de gasto en al menos una gasto: No se puede eliminar');
            Session::flash('alert-class', 'error');

        }
        return Redirect::to('banks/expense_categories');
    }

    public function taxes_category($id){
        $category = ExpenseCategory::find($id);
        $taxes = ExpenseTax::where('expense_categorie_id', $id)->get();
        $resp  = array('categorie'=>json_encode($category), 'taxes'=>json_encode($taxes));
        return json_encode($resp);
    }
}
