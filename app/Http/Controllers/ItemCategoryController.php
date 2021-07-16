<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use App\ItemCategory;
use App\ItemType;
use \Auth;
use \Input;

// use Illuminate\Support\Facades\Validator
use \Validator;
use \Redirect;
use \Session;

class ItemCategoryController extends Controller
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
        $categorie_product = ItemCategory::all();

        return view('categorie_product.index')
            ->with('categorie_product', $categorie_product);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipos = ItemType::all()->pluck('name', 'id');

        return view('categorie_product.create')
            ->with('tipos', $tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $messages = [
                'required' => 'El nombre es requerido.',
                'unique' => 'El nombre ya esta en uso.'
            ];
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:item_categories,name,',
                'item_type_id' => 'required'
            ], $messages);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error) {
                    $message .= $error . ' | ';
                }
                throw new \Exception($message, 6);
            }
            $user = Auth::user();
            $categorie_product = new Itemcategory;
            $categorie_product->name = input::get('name');
            $categorie_product->description = input::get('description');
            $categorie_product->item_type_id = input::get('item_type_id');
            $categorie_product->status = 1;
            $categorie_product->created_by = $user->id;
            $categorie_product->updated_by = $user->id;
            $categorie_product->save();

            Session::flash('message', 'Categoria creada correctamente');
        } catch (\Exception $ex) {
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput()->withErrors($validator);
        }


        return Redirect::to('categorie_product');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categorie_product = ItemCategory::find($id);
        $tipos = ItemType::all()->pluck('name', 'id');
        return view('categorie_product.edit')
            ->with('categorie_product', $categorie_product)
            ->with('tipos', $tipos);
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
        try {
            $messages = [
                'required' => 'El nombre es requerido.',
                'unique' => 'El nombre ya esta en uso.'
            ];
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:item_categories,name,' . $id
            ], $messages);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }
            $user = Auth::user();
            $categorie_product = ItemCategory::find($id);
            $categorie_product->name = input::get('name');
            $categorie_product->description = input::get('description');
            $categorie_product->item_type_id = input::get('item_type_id');
            $categorie_product->status = 1;
            $categorie_product->updated_by = $user->id;
            $categorie_product->update();
            Session::flash('message', 'Actualizacion correcta');
        } catch (\Exception $ex) {
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }
        return Redirect::to('categorie_product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Item::where('id_categorie', $id)->count();
        // echo 'id '.$id.'<br>';
        // exit($existe);
        if ($existe > 0) {
            Session::flash('message', 'Categoria asignada a ' . $existe . ' productos. No se puede eliminar.');
            Session::flash('alert-class', 'alert-error');

            return Redirect::to('categorie_product');
        }
        try {
            $categorie_product = ItemCategory::find($id);
            $categorie_product->delete();

            Session::flash('message', 'Eliminado correctamente');
            return Redirect::to('categorie_product');

        } catch (\Illuminate\Database\QueryException $e) {

            Session::flash('message', 'Categoria asignada a al menos un Producto: No se puede eliminar');
            Session::flash('alert-class', 'alert-error');

            return Redirect::to('categorie_product');
        }
    }
}
    