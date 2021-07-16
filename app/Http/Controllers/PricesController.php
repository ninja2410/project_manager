<?php

namespace App\Http\Controllers;

use App\Categorie;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use App\ItemCategory;
use \Input;
use \Redirect;
use \Session;
use \Auth, \Validator;
use \Response;

// duse DB;
use Illuminate\Support\Facades\DB;
use App\Price;
use App\Pago;

class PricesController extends Controller
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
        $prices = Price::orderBy('order', 'asc')
            ->whereActive(1)
            ->get();
        return view('prices.index')
            ->with('prices', $prices);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pagos = Pago::all()->pluck('name', 'id');
        $items = Item::select(DB::Raw('concat(upc_ean_isbn," - ",item_name) as name'), 'id')
            ->wildcard()
            ->lists('name', 'id');

        // dd($items);
        return view('prices.create')
            ->with('pagos', $pagos)
            ->with('items', $items);
        // ->with('category',$category);
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
            'name' => 'required|unique:prices,name',
            'order' => 'required|unique:prices,order'
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

        $dataUser = Auth::user();
        $price = new Price;
        $price->name = Input::get('name');
        $price->pct = Input::get('pct');
        $price->pct_min = Input::get('pct_min');
        $price->amount = Input::get('amount');
        $price->amount_min = Input::get('amount_min');
        $price->cant_min = Input::get('cant_min');
        $price->cant_max = Input::get('cant_max');
        $price->date_min = Input::get('date_min');
        $price->date_max = Input::get('date_max');
        $price->order = Input::get('order');
        $price->main = Input::get('main');
        $price->active = Input::get('active');
        $price->created_by = $dataUser->id;
        $price->updated_by = $dataUser->id;
        $price->save();

        $price->pagos()->sync($request->input('pagos', []));
        $price->items()->sync($request->input('items', []));


        Session::flash('message', 'Precio insertado correctamente');
        return Redirect::to('prices');
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
        $prices = Price::find($id);

        $pagos = Pago::all()->pluck('name', 'id');
        $items = Item::select(DB::Raw('concat(upc_ean_isbn," - ",item_name) as name'), 'id')
            ->wildcard()
            ->lists('name', 'id');

        return view('prices.edit')
            ->with('prices', $prices)
            ->with('pagos', $pagos)
            ->with('items', $items);
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
            'name' => 'required|unique:prices,name,' . $id,
            'order' => 'required|unique:prices,order,' . $id
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

        $dataUser = Auth::user();
        $price = Price::find($id);
        $price->name = Input::get('name');
        $price->pct = Input::get('pct');
        $price->pct_min = Input::get('pct_min');
        $price->amount = Input::get('amount');
        $price->amount_min = Input::get('amount_min');
        $price->cant_min = Input::get('cant_min');
        $price->cant_max = Input::get('cant_max');
        $price->date_min = Input::get('date_min');
        $price->date_max = Input::get('date_max');
        $price->order = Input::get('order');
        $price->main = Input::get('main');
        $price->active = Input::get('active');
        $price->updated_by = $dataUser->id;
        $price->save();

        $price->pagos()->sync($request->input('pagos', []));
        $price->items()->sync($request->input('items', []));


        Session::flash('message', 'Precio actualizado correctamente');
        return Redirect::to('prices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $price = Price::find($id);
        $price->active = 0;
        $price->update();
        Session::flash('message', 'Precio eliminado correctamente');
        return Redirect::to('prices');
    }
}
