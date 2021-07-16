<?php

namespace App\Http\Controllers;

use App\Budget;
use App\BudgetConfig;
use App\BudgetDetail;
use App\BudgetHeader;
use App\BudgetItem;
use App\Classes\NumeroALetras;
use App\GeneralParameter;
use App\Item;
use App\LineTemplate;
use App\Parameter;
use App\Project;
use App\StateCellar;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use Input;
use Illuminate\Support\Facades\Redirect;
use \Session;

class BudgetController extends Controller
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
    public function index($project_id)
    {
        $project = Project::find($project_id);
        $fecha1 = Input::get('date1');
        $fecha2 = Input::get('date2');
        $status = Input::get('status');
        $all_status = StateCellar::where('type', 'general')
            ->limit(2)
            ->get();
        if ($status == null) {
            $status = StateCellar::where('type', 'general')
                ->lists('id');
        } else {
            $status = (array)$status;
        }
        $fechaActual = date("Y-m-d");
        if ($fecha1 == null) {
            $fecha1 = $project->date;
        } else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1 = $nuevaFecha1[0];
            $mesFecha1 = $nuevaFecha1[1];
            $anioFecha1 = $nuevaFecha1[2];
            $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1;
        }

        if ($fecha2 == null) {
            $fecha2 = $fechaActual;
        } else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2 = $nuevaFecha2[0];
            $mesFecha2 = $nuevaFecha2[1];
            $anioFecha2 = $nuevaFecha2[2];
            $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2;
        }

        $url = url('project/'.$project_id.'/budget');

        $budgets = Budget::whereProject_id($project_id)
            ->whereIn('status_id', $status)
            ->whereBetween('date', [$fecha1, $fecha2])
            ->orderBy('date', 'desc')
            ->get();
        return view('project.budget.index', compact('project', 'budgets', 'fecha1', 'fecha2', 'status', 'all_status', 'url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project = Project::find($project_id);
        $config = BudgetConfig::whereType(1)
            ->orderby('order')
            ->get();
        $line_templates = LineTemplate::where('status', 1)->get();
        $services = Item::where('type_id', 2)
            ->whereStatus(1)
            ->get();
        $products = Item::whereType(1)
            ->whereStatus(1)
            ->wildcard()
            ->get();
        $wildcards = Item::whereType(1)
            ->whereStatus(1)
            ->whereWildcard(1)
            ->get();

        return view('project.budget.create')
            ->with('services', $services)
            ->with('products', $products)
            ->with('wildcards', $wildcards)
            ->with('project', $project)
            ->with('config', $config)
            ->with('line_templates', $line_templates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($project_id, Request $request)
    {
        DB::beginTransaction();
        try {
            $headBudget = (json_decode($request->budgetHead));
            $data = json_decode($request->data);
            $oldBudget = Budget::orderby('id', 'desc')
                ->where('project_id', $project_id)
                ->first();
            if (isset($oldBudget->correlative)){
                $correlative = $oldBudget->correlative + 1;
            }
            else{
                $correlative = 1;
            }

            #region Creando encabezado
            $budget = new Budget();
            $budget->correlative = $correlative;
            $budget->amount = $headBudget->totalCost;
            $budget->date = $request->date;
            $budget->days = $request->days;
            $budget->comments = $request->comment;
            $budget->date_temp_saved = date('Y-m-d');
            $budget->project_id = $project_id;
            $budget->status_id = 1;
            $budget->created_by = Auth::user()->id;
            $budget->save();
            #endregion
            foreach($data as $header){
                $head = new BudgetHeader();
                $head->name = $header->name;
                $head->budget_id = $budget->id;
                $head->save();
                foreach($header->details as $detail){
                    $det = new BudgetDetail();
                    $det->quantity = $detail->quantity;
                    $det->unit_cost = $detail->unitCost;
                    $det->total_cost = $detail->totalCost;
                    $det->line_template_id = $detail->line_id;
                    $det->header_id = $head->id;
                    $det->save();
                    foreach($detail->items as $item){
                        $itm = new BudgetItem();
                        $itm->quantity = $item->quantity;
                        $itm->unit_cost = $item->cost;
                        $itm->total_cost = $item->cost * $item->quantity;
                        $itm->item_id = $item->item_id;
                        $itm->budget_detail_id = $det->id;
                        $itm->save();
                    }
                }
            }
            DB::commit();
            Session::flash('message', trans('budget.save_ok'));
            Session::flash('alert-type', trans('success'));
            $url = 'project/'.$project_id.'/budget/'.$budget->id.'/show';
            $flag=1;
            $message = trans('budget.save_ok');
        }
        catch(\Exception $e){
            DB::rollback();
            $custMessage = $e->getMessage();
            $message = "Error ultimo:".$e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
            $flag=2;
            $url = '#';
        }
        $resp = array('flag'=>$flag, 'mensaje'=>$message,'url'=>$url);
        return json_encode($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id,$id)
    {
        $budget = Budget::find($id);
        $config = BudgetConfig::whereType(1)
            ->orderby('order')
            ->get();
        $parameters = Parameter::first();
        $imprimir_propietario = GeneralParameter::where('name','Imprimir propietario y negocio en proforma.')
            ->first()->active;
        $letras = NumeroALetras::convertir($budget->amount, 'quetzales', 'centavos');
        $precio_letras = trans('budget._total').ucfirst(strtolower($letras));
        $summary_items = BudgetItem::leftjoin('budget_details', 'budget_details.id', '=', 'budget_items.budget_detail_id')
            ->leftjoin('budget_headers', 'budget_headers.id', '=', 'budget_details.header_id')
            ->leftjoin('budgets', 'budgets.id', '=', 'budget_headers.budget_id')
            ->where('budgets.id', $id)
            ->groupby('budget_items.item_id')
            ->select('budget_items.*', DB::raw('sum(budget_items.quantity) as quantity_total'))
            ->get();
        return view('project.budget.show', compact('budget', 'config', 'summary_items',
            'parameters', 'imprimir_propietario', 'precio_letras'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id, $id)
    {
        $project = Project::find($project_id);
        $budget = Budget::find($id);
        $config = BudgetConfig::whereType(1)
            ->orderby('order')
            ->get();
        $line_templates = LineTemplate::where('status', 1)->get();
        $services = Item::where('type_id', 2)
            ->whereStatus(1)
            ->get();
        $products = Item::whereType(1)
            ->whereStatus(1)
            ->get();
        $wildcards = Item::whereType(1)
            ->whereStatus(1)
            ->whereWildcard(1)
            ->get();

        return view('project.budget.edit')
            ->with('services', $services)
            ->with('products', $products)
            ->with('wildcards', $wildcards)
            ->with('project', $project)
            ->with('config', $config)
            ->with('budget', $budget)
            ->with('line_templates', $line_templates);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($project_id, Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $headBudget = (json_decode($request->budgetHead));
            $data = json_decode($request->data);
            #region Creando encabezado
            $budget = Budget::find($id);
            $budget->amount = $headBudget->totalCost;
            $budget->date = $request->date;
            $budget->comments = $request->comment;
            $budget->date_temp_saved = date('Y-m-d');
            $budget->project_id = $project_id;
            $budget->status_id = 1;
            $budget->days = $request->days;
            $budget->updated_by = Auth::user()->id;
            $budget->update();
            #endregion

            #region limpiar detalles
            foreach($budget->details as $header){
                $header->delete();
            }

            #endregion
            foreach($data as $header){
                $head = new BudgetHeader();
                $head->name = $header->name;
                $head->budget_id = $budget->id;
                $head->save();
                foreach($header->details as $detail){
                    $det = new BudgetDetail();
                    $det->quantity = $detail->quantity;
                    $det->unit_cost = $detail->unitCost;
                    $det->total_cost = $detail->totalCost;
                    $det->line_template_id = $detail->line_id;
                    $det->header_id = $head->id;
                    $det->save();
                    foreach($detail->items as $item){
                        $itm = new BudgetItem();
                        $itm->quantity = $item->quantity;
                        $itm->unit_cost = $item->cost;
                        $itm->total_cost = $item->cost * $item->quantity;
                        $itm->item_id = $item->item_id;
                        $itm->budget_detail_id = $det->id;
                        $itm->save();
                    }
                }
            }
            DB::commit();
            Session::flash('message', trans('budget.save_ok'));
            Session::flash('alert-type', trans('success'));
            $url = 'project/'.$project_id.'/budget/'.$budget->id.'/show';
            $flag=1;
            $message = trans('budget.save_ok');
        }
        catch(\Exception $e){
            DB::rollback();
            $custMessage = $e->getMessage();
            $message = "Error ultimo:".$e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
            $flag=2;
            $url = '#';
        }
        $resp = array('flag'=>$flag, 'mensaje'=>$message,'url'=>$url);
        return json_encode($resp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        $budget = Budget::find($id);
        $budget->status_id = 2;
        $budget->update();
        Session::flash('message', trans('budget.save_ok'));
        Session::flash('alert-type', trans('success'));
        return Redirect::to('project/'.$project_id.'/budget');
    }

    public function updateBudgetCost(Request $request){
        $nv = $request->value;
        if ($request->change_all=="true"){
            $item = Item::find($request->id);
            $item->budget_cost = str_replace(",", "", $nv);
            $item->updated_budget_cost_at = date('Y-m-d');
            $item->save();
        }
        return $nv;
    }

    /**
     * Show the clone budget display
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clone($id){
        $budget = Budget::find($id);
        $config = BudgetConfig::whereType(1)
            ->orderby('order')
            ->get();
        $line_templates = LineTemplate::where('status', 1)->get();
        $services = Item::where('type_id', 2)
            ->whereStatus(1)
            ->get();
        $products = Item::whereType(1)
            ->whereStatus(1)
            ->get();
        $wildcards = Item::whereType(1)
            ->whereStatus(1)
            ->whereWildcard(1)
            ->get();

        $projects = Project::whereStatus(1)->get();
        return view('project.budget.clone')
            ->with('services', $services)
            ->with('products', $products)
            ->with('wildcards', $wildcards)
            ->with('budget', $budget)
            ->with('config', $config)
            ->with('projects', $projects)
            ->with('line_templates', $line_templates);
    }

    public function storeClon(Request $request){
        DB::beginTransaction();
        try {
            $headBudget = (json_decode($request->budgetHead));
            $data = json_decode($request->data);
            $oldBudget = Budget::orderby('id', 'desc')
                ->where('project_id', $request->project_id)
                ->first();
            if (isset($oldBudget->correlative)){
                $correlative = $oldBudget->correlative + 1;
            }
            else{
                $correlative = 1;
            }

            #region Creando encabezado
            $budget = new Budget();
            $budget->correlative = $correlative;
            $budget->amount = $headBudget->totalCost;
            $budget->date = $request->date;
            $budget->days = $request->days;
            $budget->comments = $request->comment;
            $budget->date_temp_saved = date('Y-m-d');
            $budget->project_id = $request->project_id;
            $budget->status_id = 1;
            $budget->created_by = Auth::user()->id;
            $budget->save();
            #endregion
            foreach($data as $header){
                $head = new BudgetHeader();
                $head->name = $header->name;
                $head->budget_id = $budget->id;
                $head->save();
                foreach($header->details as $detail){
                    $det = new BudgetDetail();
                    $det->quantity = $detail->quantity;
                    $det->unit_cost = $detail->unitCost;
                    $det->total_cost = $detail->totalCost;
                    $det->line_template_id = $detail->line_id;
                    $det->header_id = $head->id;
                    $det->save();
                    foreach($detail->items as $item){
                        $itm = new BudgetItem();
                        $itm->quantity = $item->quantity;
                        $itm->unit_cost = $item->cost;
                        $itm->total_cost = $item->cost * $item->quantity;
                        $itm->item_id = $item->item_id;
                        $itm->budget_detail_id = $det->id;
                        $itm->save();
                    }
                }
            }
            DB::commit();
            Session::flash('message', trans('budget.save_ok'));
            Session::flash('alert-type', trans('success'));
            $url = 'project/'.$request->project_id.'/budget/'.$budget->id.'/show';
            $flag=1;
            $message = trans('budget.save_ok');
        }
        catch(\Exception $e){
            DB::rollback();
            $custMessage = $e->getMessage();
            $message = "Error ultimo:".$e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile();
            $flag=2;
            $url = '#';
        }
        $resp = array('flag'=>$flag, 'mensaje'=>$message,'url'=>$url);
        return json_encode($resp);
    }
}
