<?php

namespace App\Http\Controllers;

use App\BudgetConfig;
use App\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use App\LineTemplate;
use App\DetailLineTemplate;
use App\Categorie;
use Illuminate\Support\Facades\DB;
use \Auth, \Redirect, \Validator, \Input, \Session;

class LineTemplateController extends Controller
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
        $templates = LineTemplate::where('line_templates.status', 1)
            ->get();

        return view('line_template.index')
            ->with('templates', $templates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $change_prices = false;
        foreach (Auth::user()->roles as $rol) {
            foreach ($rol->permissions as $perm) {
                if ($perm->ruta == 'line-template/item/config_budget_price') {
                    $change_prices = true;
                    break;
                }
            }
        }
        $categories = Categorie::where('warranty', 0)
            ->where('type', 0)
            ->lists('name', 'id');
        $items = Item::where('status', 1)
            ->where('type_id', 1)
            ->get();
        $services = Item::where('status', 1)
            ->where('type_id', 2)
            ->get();
        $config = BudgetConfig::whereType(1)
            ->orderby('order')
            ->get();
        return view('line_template.create')
            ->with('change_prices', $change_prices)
            ->with('categories', $categories)
            ->with('services', $services)
            ->with('config', $config)
            ->with('items', $items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $json = json_decode($request->itemDetail);
            $newLine = new LineTemplate();
            $newLine->name = $request->name;
            $newLine->status = 1;
            $newLine->price = str_replace(',', '', $request->price);
            $newLine->categorie_id = $request->categorie_id;
            $newLine->description = $request->description;
            $newLine->items_quantity = $request->items_quantity;
            $newLine->size = $request->size;
            $newLine->save();
            foreach ($json as $key => $value) {
                $detail = new DetailLineTemplate();
                $detail->quantity = $value->quantity;
                $detail->item_id = $value->item_id;
                $detail->lineTemplate_id = $newLine->id;
                $detail->save();
            }
            DB::commit();
            Session::flash('message', 'Renglon ingresado correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', 'Error al crear el nuevo renglon: ', $e->getMessage());
            Session::flash('alert-class', 'alert-error');
        }
        return Redirect::to('line-template');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = LineTemplate::find($id);
        $details = DetailLineTemplate::where('lineTemplate_id', $id)
            ->get();
        $config = BudgetConfig::whereType(1)
            ->orderby('order')
            ->get();
        return view('line_template.show')
            ->with('details', $details)
            ->with('config', $config)
            ->with('template', $template);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $change_prices = false;
        foreach (Auth::user()->roles as $rol) {
            foreach ($rol->permissions as $perm) {
                if ($perm->ruta == 'line-template/item/config_budget_price') {
                    $change_prices = true;
                    break;
                }
            }
        }
        $template = LineTemplate::find($id);
        $idDetails = DetailLineTemplate::where('lineTemplate_id', $id)
            ->lists('item_id');
        $details = DetailLineTemplate::where('lineTemplate_id', $id)
            ->get();
        $items = Item::where('status', 1)
            ->where('type_id', 1)
//            ->whereNotIn('id', $idDetails)
            ->get();
        $services = Item::where('status', 1)
            ->where('type_id', 2)
//            ->whereNotIn('id', $idDetails)
            ->get();
        $categories = Categorie::where('warranty', 0)
            ->where('type', 0)
            ->lists('name', 'id');

        $count_items = 0;
        $count_services = 0;

        foreach ($details as $item) {
            if ($item->item->type_id == 2) {
                $count_services++;
            } else {
                $count_items++;
            }
        }
        $config = BudgetConfig::whereType(1)
            ->orderby('order')
            ->get();
        return view('line_template.edit')
            ->with('change_prices', $change_prices)
            ->with('categories', $categories)
            ->with('details', $details)
            ->with('items', $items)
            ->with('config', $config)
            ->with('services', $services)
            ->with('count_items', $count_items)
            ->with('count_services', $count_services)
            ->with('template', $template);
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
        DB::beginTransaction();
        try {
            $template = LineTemplate::find($id);
            $template->name = $request->name;
            $template->price = str_replace(',', '', $request->price);
            $template->description = $request->description;
            $template->categorie_id = $request->categorie_id;
            $template->items_quantity = $request->items_quantity;
            $template->size = $request->size;
            $template->update();
            /*ELIMINAR TODOS LOS DETALLES EXISTENTES*/
            $oldDetails = DetailLineTemplate::where('lineTemplate_id', $id)->get();
            foreach ($oldDetails as $key => $value) {
                $value->delete();
            }
            foreach (JSON_DECODE($request->itemDetail) as $key => $value) {
                $detail = new DetailLineTemplate();
                $detail->quantity = $value->quantity;
                $detail->item_id = $value->item_id;
                $detail->lineTemplate_id = $template->id;
                $detail->save();
            }
            Session::flash('message', 'Renglon editado correctamente.');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', 'Error al editar el nuevo renglon: ', $e->getMessage());
            Session::flash('alert-class', 'alert-error');
        }
        return Redirect::to('line-template');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = LineTemplate::find($id);
        $template->status = 0;
        $template->update();
        Session::flash('message', 'Renglon eliminado correctamente.');
        return Redirect::to('line-template');
    }
}
