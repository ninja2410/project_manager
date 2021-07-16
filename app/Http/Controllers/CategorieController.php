<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categorie;
use App\Http\Requests;
use App\Http\Requests\CategoriesRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use App\Http\Controllers\Controller;

class CategorieController extends Controller
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
        $categorie_product = Categorie::all();
        return view('categorie_product.index')->with('categorie_product', $categorie_product);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categorie_product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriesRequest $request)
    {
        $categorie_product = new Categorie;
        $categorie_product->name = input::get('name');
        $categorie_product->description = input::get('description');
        if (input::get('warranty') == "on") {
            $categorie_product->warranty = 1;
        }
        $categorie_product->type = 0;
        $categorie_product->save();
        Session::flash('message', 'Categoria insertada correctamente');
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
        $categorie_product = Categorie::find($id);
        return view('categorie_product.edit')
            ->with('categorie_product', $categorie_product);
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
            $validator = Validator::make($request->all(), [
                'name' =>
                    'required|unique:categories,name,' . $id
            ]);
            if ($validator->fails()) {
                $message = '';
                foreach ($validator->errors()->all() as $error){
                    $message .= $error.' | ';
                }
                throw new \Exception($message, 6);
            }
            $categorie_product = Categorie::find($id);
            $categorie_product->name = input::get('name');
            $categorie_product->description = input::get('description');
            if (input::get('warranty') == "on") {
                $categorie_product->warranty = 1;
            } else {
                $categorie_product->warranty = 0;
            }
            $categorie_product->update();
            Session::flash('message', 'Actualizacion correcta');
        }
        catch (\Exception $ex){
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
        try {
            $categorie_product = Categorie::find($id);
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
