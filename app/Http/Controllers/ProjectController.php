<?php

namespace App\Http\Controllers;

use App\AlmacenUser;
use App\ExpenseCategory;
use App\log;
use App\Route;
use App\Serie;
use App\Traits\ProjectTrait;
use App\TypeProject;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Account;
use App\Atribute;
use App\Almacen;
use App\Stage;
use App\Revenue;
use App\ValueStages;
use App\Project;
use App\Payment;
use App\Images;
use App\Supplier;
use App\Retention;
use App\RegRetention;
use App\TransactionsCatalogue;
use App\Traits\TransactionsTrait;
use App\Pago;
use Illuminate\Support\Facades\DB;
use \Auth, \Redirect, \Validator, \Input, \Session;
use App\StateCellar;

class ProjectController extends Controller
{
    use TransactionsTrait;
    use ProjectTrait;

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
        $fecha1 = Input::get('date1');
        $fecha2 = Input::get('date2');
        $status = Input::get('status');
        $type = Input::get('type');
        $types = TypeProject::where('status_id', 1)->get();
        $all_status = StateCellar::where('type', 'General')
            ->get();
        if ($status == null) {
            $status = (array)1;
        } else {
            if ($status == 0){
                $status = StateCellar::where('type', 'General')
                    ->lists('id');
            }
            else{
                $status = (array)$status;
            }
        }
        if ($type == null) {
            $type = TypeProject::where('status_id', 1)
                ->lists('id');
        } else {
            $type = (array)$type;
        }

        $fechaActual = date("Y-m-d");
        if ($fecha1 == null) {
            $fecha1 = date('Y-m-d',strtotime($fechaActual." -3 month"));
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
        $projects = Project::join('customers', 'customers.id', '=', 'projects.customer_id')
            ->select('projects.*', 'customers.name as cliente')
            ->whereBetween('date', [$fecha1, $fecha2])
            ->whereIn('status', $status)
            ->whereIn('type_id', $type)
            ->orderby('date', 'desc')
            ->get();
        return view('project.index')
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('status', $status)
            ->with('type', $type)
            ->with('types', $types)
            ->with('all_status', $all_status)
            ->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $customer=Customer::all();
        $customer = Customer::select(DB::Raw('concat(nit_customer," | ",name) as name'), 'id')->get();
        $types = TypeProject::where('status_id', 1)->get();
        return view('project.create')
            ->with('types', $types)
            ->with('customer', $customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::begintransaction();
        try {
            /*CONFIGURAR FECHA*/
            $datesArray = explode("/", $request->date);
            $newDay = $datesArray[0];
            $newMounth = $datesArray[1];
            $newYear = $datesArray[2];
            $date = $newYear . '-' . $newMounth . '-' . $newDay;
            /*-----------------------*/

            $project = new Project();
            $project->name = $request->name;
            $project->status = 1;
            $project->date = $date;
            $project->description = $request->description;
            $project->code = $request->code;
            $project->customer_id = $request->customer_id;
            $project->price = str_replace(',', '', $request->price);
            $project->type_id = $request->type_id;

            if (isset($request->create_account)){
                /*-------CREAR CUENTA INTERNA--------*/
                $account = new Account;
                $account->account_name = "Cuenta Interna: " . $request->name;
                $account->bank_name = "Cuenta interna";
                $account->account_type_id = 2;
                $account->opening_balance = 0;
                $account->pago_id = 1;
                $account->account_responsible = Auth::user()->id;
                $account->status = 1;
                $account->user_id = Auth::user()->id;
                $account->categorie_id = 15;
                $account->save();
                $project->account_id = $account->id;
                $project->create_account = true;
                /*------------------------------------*/
            }
            if (isset($request->create_cellar)){
                /*REGITRO DE BODEGA*/
                $almacen = new Almacen;
                $almacen->name = "Bodega proyecto:" . $request->name;
                $almacen->id_state = 1;
                $almacen->comentario = $request->description;
                $almacen->categorie_id = 16;
                $almacen->adress = 'CIUDAD';
                $almacen->save();
                /**
                 * ASIGNAR BODEGA A USUARIO LOGUEADO
                 */
                $au = new AlmacenUser();
                $au->id_bodega = $almacen->id;
                $au->id_usuario = Auth::user()->id;
                $au->estado_user = 0;
                $au->save();
                $project->cellar_id = $almacen->id;
                $project->create_cellar = true;
                /*------------------------------------*/
            }
            /*------REGISTRO DE PROYECTO----------*/

            $project->save();
            /*------------------------------------*/
            DB::commit();
            Session::flash('message', 'Proyecto creado correctamente.');
            Session::flash('alert-type', 'success');
            return Redirect::to('project/stages_project/'.$project->id);
        } catch (\Exception $e) {
            DB::rollback();
            $error = $e->getMessage();
            Session::flash('message', "No se pudo crear el proyecto: " . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile());
            Session::flash('alert-class', 'alert-error');
            Session::flash('alert-type', 'error');
            return Redirect::to('project/projects');
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
        $project = Project::find($id);
        $customer = Customer::select(DB::Raw('concat(nit_customer," | ",name) as name'), 'id')->get();
        $date = date('d/m/Y', strtotime($project->date));
        $types = TypeProject::where('status_id', 1)->get();
        return view('project.edit')
            ->with('project', $project)
            ->with('types', $types)
            ->with('date', $date)
            ->with('customer', $customer);
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
        DB::begintransaction();
        $project = Project::find($id);
        try {
            $datesArray = explode("/", $request->date);
            $newDay = $datesArray[0];
            $newMounth = $datesArray[1];
            $newYear = $datesArray[2];
            $date = $newYear . '-' . $newMounth . '-' . $newDay;
            if ($project->name != $request->name) {
                // CAMBIAR NOMBRE A CUENTA Y BODEGA
                $account = Account::find($project->account_id);
                $account->account_name = $request->name;
                $account->update();
                $bodega = Almacen::find($project->cellar_id);
                $bodega->name = $request->name;
                $bodega->update();
            }
            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->oldValue = $project->name;
            $log->newValue = $request->name;
            $log->user_id = Auth::user()->id;
            $log->action = "Actualización de nombre proyecto:.";
            if ($log->oldValue != $log->newValue){
                $log->save();
            }
            #endregion
            $project->name = $request->name;
            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->oldValue = $project->date;
            $log->user_id = Auth::user()->id;
            $log->newValue = $date;
            $log->action = "Actualización de fecha proyecto:.";
            if ($log->oldValue != $log->newValue){
                $log->save();
            }
            #endregion
            $project->date = $date;
            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->oldValue = $project->code;
            $log->user_id = Auth::user()->id;
            $log->newValue = $request->code;
            $log->action = "Actualización de codigo proyecto:.";
            if ($log->oldValue != $log->newValue){
                $log->save();
            }
            #endregionv
            $project->code = $request->code;
            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->user_id = Auth::user()->id;
            $log->oldValue = $project->description;
            $log->newValue = $request->description;
            $log->action = "Actualización de descripción de  proyecto:.";
            if ($log->oldValue != $log->newValue){
                $log->save();
            }
            #endregionv
            $project->description = $request->description;
            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->user_id = Auth::user()->id;
            $log->oldValue = $project->customer_id;
            $log->newValue = $request->customer_id;
            $log->action = "Actualización de cliente de  proyecto:.";
            if ($log->oldValue != $log->newValue){
                $log->save();
            }
            #endregionv
            $project->customer_id = $request->customer_id;
            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->user_id = Auth::user()->id;
            $log->oldValue = $project->type_id;
            $log->newValue = $request->type_id;
            $log->action = "Actualización de tipo de  proyecto:.";
            if ($log->oldValue != $log->newValue){
                $log->save();
            }
            #endregionv
            $project->type_id = $request->type_id;
            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->user_id = Auth::user()->id;
            $log->oldValue = $project->price;
            $log->newValue = str_replace(",", "", $request->price);
            $log->action = "Actualización de precio de  proyecto:.";
            if ($log->oldValue != $log->newValue){
                $log->save();
            }
            #endregionv

            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->user_id = Auth::user()->id;
            $log->oldValue = $project->create_account;
            $log->newValue = (isset($request->create_account)) ? true : false;
            $log->action = "Habilitar/deshabilitar cuenta de proyecto.";
            if ($log->oldValue != $log->newValue){
                $log->save();
                $project->create_account = (isset($request->create_account)) ? true : false;
            }
            #endregion

            #region LOG
            $log = new log();
            $log->project_id = $id;
            $log->user_id = Auth::user()->id;
            $log->oldValue = $project->create_cellar;
            $log->newValue = (isset($request->create_cellar)) ? true : false;
            $log->action = "Habilitar/deshabilitar bodega de proyecto.";
            if ($log->oldValue != $log->newValue){
                $log->save();
                $project->create_cellar = (isset($request->create_cellar)) ? true : false;
            }
            #endregion

            $project->price = str_replace(",", "", $request->price);
            $project->update();
            $this->updateProject($project);
            DB::commit();
            Session::flash('message', 'Proyecto actualizado correctamente.');
            Session::flash('alert-type', 'success');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', "No se pudo actualizar el proyecto: " . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile());
            Session::flash('alert-class', 'alert-error');
            Session::flash('alert-type', 'error');
        }
        return Redirect::to('project/projects');
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
            $project = Project::find($id);
            $project->status = 2;
            $project->update();
            $account = Account::find($project->account_id);
            $account->status = 2;
            $account->update();
            $almacen = Almacen::find($project->cellar_id);
            $almacen->id_state = 2;
            $almacen->update();
            Session::flash('alert-type', 'success');
            Session::flash('message', 'Proyecto eliminado correctamente.');
        } catch (\Exception $e) {
            Session::flash('message', "No se pudo eliminar el proyecto: " . $e->getMessage() . " Linea:" . $e->getLine() . " Archivo:" . $e->getFile());
            Session::flash('alert-class', 'alert-error');
            Session::flash('alert-type', 'error');
        }
        return Redirect::to('project/projects');
    }

    public function stages($id)
    {
        $project = Project::find($id);
        $atributes = Atribute::where('status', 1)->get();
        $images = Images::where('project_id', $id)->get();
        $values = ValueStages::where('project_id', $id)
            ->where('status', 1)
            ->get();
        $stages = Stage::where('stages.status', 1)
            ->leftJoin('stage_project_registers as r', function ($join) use($id){
                $join->on('r.stage_id', '=','stages.id');
                $join->where('r.project_id', '=',$id);
            })
            ->orderby('order', 'asc')
            ->where('type_id', $project->type_id)
            ->select('stages.*', DB::raw('coalesce(r.status, 0) AS complete'))
            ->get();

        /**
         * OBTENER PERMISOS EN TRANSACCIONES BANCARIAS
         */
        $acc_revenues = Auth::user()->verifyPermission('Ver ingresos de proyectos [M. Proyectos]');
        $acc_expenses = Auth::user()->verifyPermission('Ver egresos de proyectos [M. Proyectos]');
        $acc_retentions = Auth::user()->verifyPermission('Retenciones');
        $acc_balance = Auth::user()->verifyPermission('Ver saldo de proyecto [M. Proyectos]');
        $acc_price = Auth::user()->verifyPermission('Ver monto acordado de proyecto [M. Proyectos]');
        return view('project.stage_project', compact(['acc_price','acc_revenues', 'acc_retentions', 'acc_expenses', 'acc_balance']))
            ->with('atributes', $atributes)
            ->with('project', $project)
            ->with('values', $values)
            ->with('images', $images)
            ->with('stages', $stages);
    }

    public function repeat($id){
        $project = Project::find($id);
        $project->status = 1;
        $project->update();
        /**
         * ACTIVAR CUENTA BANCARIA Y BODEGA
         */
        $bodega = Almacen::find($project->cellar_id);
        $bodega->id_state = 1;
        $bodega->update();

        $account = Account::find($project->account_id);
        $account->status = 1;
        $account->update();

        Session::flash('message', 'Proyecto reactivado correctamente.');
        Session::flash('alert-type', 'success');
        return Redirect::to('project/projects');
    }

    public function getRevenues($id)
    {
        $fecha1 = Input::get('date1');
        $fecha2 = Input::get('date2');
        $status = Input::get('status') == null ? 'Todo' : Input::get('status');
        $fechaActual = date("Y-m-d");
        if ($fecha1 == null) {
            $fecha1 = $fechaActual;
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
        $forma_pago = Input::get('forma_pago');
        $forma_pago = $forma_pago == null ? 'Todo' : $forma_pago;
        $dataPagos = Pago::sale()->get();
        $account = Account::find($id);
        $revenue = Revenue::where('account_id', $id)
            ->whereBetween('paid_at', [$fecha1, $fecha2]);
            $revenue->where('status', '!=', 2);
        if ($forma_pago != 'Todo') {
            $revenue->where('bank_tx_revenues.payment_method', $forma_pago);
        }
        if ($status != 'Todo') {
            $revenue->where('bank_tx_revenues.status', $status);
        }
        $revenues = $revenue->get();
        $status = Input::get('status') == null ? 'Todo' : Input::get('status');
        $dataStatus = StateCellar::banking()->get();
        $project = Project::where('account_id', $id)->first();
        $anul = Auth::user()->verifyPermission('Anular ingresos de cuenta [M. bancos]');
        return view('project.transactions.revenue_index')
            ->with('account', $account)
            ->with('project', $project)
            ->with('status', $status)
            ->with('dataStatus', $dataStatus)
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('dataPagos', $dataPagos)
            ->with('forma_pago', $forma_pago)
            ->with('anul', $anul)
            ->with('revenues', $revenues);
    }

    public function getExpenses($id)
    {

        $fecha1 = Input::get('date1');
        $fecha2 = Input::get('date2');
        $forma_pago = Input::get('forma_pago');
        $stage = Input::get('stage') == null ? 'Todo' : Input::get('stage');

        $fechaActual = date("Y-m-d");
        if ($fecha1 == null) {
            $fecha1 = $fechaActual . ' 00:00:00';
        } else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1 = $nuevaFecha1[0];
            $mesFecha1 = $nuevaFecha1[1];
            $anioFecha1 = $nuevaFecha1[2];
            $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1 . ' 00:00:00';
        }

        if ($fecha2 == null) {
            $fecha2 = $fechaActual . ' 23:59:59';
        } else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2 = $nuevaFecha2[0];
            $mesFecha2 = $nuevaFecha2[1];
            $anioFecha2 = $nuevaFecha2[2];
            $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2 . ' 23:59:59';
        }

        $forma_pago = $forma_pago == null ? 'Todo' : $forma_pago;

        $account = Account::find($id);
        $expense = Payment::leftjoin('stages', 'stages.id', '=', 'bank_tx_payments.stage_id')
            ->where('account_id', $id)
            ->where('bank_tx_payments.status', '!=', 2)
            ->select('bank_tx_payments.*', 'stages.name as etapa');
        if ($forma_pago != 'Todo') {
            $expense->where('bank_tx_payments.payment_method', $forma_pago);
        }
        if ($stage != 'Todo') {
            $expense->where('bank_tx_payments.stage_id', $stage);
        }
        $expenses = $expense->get();

        $project = Project::where('account_id', $id)->first();

        $dataPagos = Pago::sale()->get();
        $dataStatus = StateCellar::banking()->get();
        $stages = Stage::where('status', 1)->get();
        $anul = Auth::user()->verifyPermission('Anular egresos de cuenta [M. bancos]');
        return view('project.transactions.index_expenses')
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('dataPagos', $dataPagos)
            ->with('dataStatus', $dataStatus)
            ->with('account', $account)
            ->with('project', $project)
            ->with('forma_pago', $forma_pago)
            ->with('stage', $stage)
            ->with('stages', $stages)
            ->with('anul', $anul)
            ->with('expenses', $expenses);
    }


    public function createRevenues($id)
    {
        $accounts = Account::where('id', $id)->get();
        $project = Project::where('account_id', $id)->first();
        $retentions = Retention::where('status', 1)->get();
        $categories = TransactionsCatalogue::sign('+')->get();
        $payments = Pago::where('banco_in', 1)->get();
        $customer = Customer::where('id', $project->customer_id)->get();
        $series = Serie::where('id_document', 11)
            ->get();
        $last = Revenue::where('serie_id', $series[0]->id)
            ->select('receipt_number')
            ->orderby('receipt_number', 'desc')
            ->first();
        if (isset($last->receipt_number)){
            $receipt_number = $last->receipt_number+1;
        }
        else{
            $receipt_number = 1;
        }
        return view('project.transactions.create_revenue')
            ->with('payments', $payments)
            ->with('categories', $categories)
            ->with('project', $project)
            ->with('customer', $customer)
            ->with('series', $series)
            ->with('retentions', $retentions)
            ->with('accounts', $accounts)
            ->with('receipt_number', $receipt_number);
    }

    public function createExpenses($id)
    {
        $accounts = Account::find($id);
        $project = Project::where('account_id', $id)->first();
        $stages = Stage::where('status', 1)
            ->where('type_id', $project->type_id)
            ->get();
        $payments = Pago::where('banco_in', 1)->get();
        $suppliers = Supplier::all();
        $categories = ExpenseCategory::all();
        $users=User::all();
        $routes = Route::where('status_id', 1)->get();
        return view('project.transactions.create_expenses')
            ->with('payments', $payments)
            ->with('categories', $categories)
            ->with('suppliers', $suppliers)
            ->with('project', $project)
            ->with('users', $users)
            ->with('routes', $routes)
            ->with('stages', $stages)
            ->with('accounts', $accounts);
    }

    public function storeExpense(Request $request)
    {
        DB::beginTransaction();
        try {
            $gastos = [];
            $gastos['account_id'] = $request->account_id;
            $gastos['paid_at'] = $request->paid_at;
            $gastos['amount'] = $request->amount;
            $gastos['description'] = $request->description;
            $gastos['category_id'] = $request->category_id;
            $gastos['reference'] = $request->reference;
            $gastos['supplier_id'] = $request->supplier_id;
            $gastos['user_id'] = $request->user_id;
            $gastos['status'] = $request->status;
            $gastos['payment_method'] = $request->payment_method;
            $gastos['customer_id'] = $request->customer_id;
            if ($request->stage_id != "") {
                $gastos['stage_id'] = $request->stage_id;
            }
            $nuevo = new Request($gastos);
            $guardarg = $this->saveExpense($nuevo);
            if ($guardarg[0] < 0) {
                throw new \Exception($guardarg[1], 6);
            }
            DB::commit();
            Session::flash('alert-type', 'success');
            Session::flash('message', trans('expenses.save_ok'));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            Session::flash('alert-type', 'error');
        }
        return Redirect::to('project/expenses/' . $request->account_id);
    }

    public function storeRevenue(Request $request)
    {
        DB::beginTransaction();
        try {
            $jRetentions = json_decode($request->jRetentions);
            $ingresos = [];
            $ingresos['account_id'] = $request->account_id;
            $ingresos['paid_at'] = $request->paid_at;
            $ingresos['amount'] = $request->amount;
            $ingresos['description'] = $request->description;
            $ingresos['category_id'] = $request->category_id;
            $ingresos['reference'] = $request->reference;
            $ingresos['user_id'] = $request->user_id;
            $ingresos['receipt_number'] = $request->receipt_number;
            $ingresos['serie_id'] = $request->serie_id;
            $ingresos['status'] = $request->status;
            $ingresos['payment_method'] = $request->payment_method;
            $ingresos['customer_id'] = $request->customer_id;
            $ingresos['same_bank'] = $request->same_bank;
            $nuevo = new Request($ingresos);
            $guardar = $this->saveRevenue($nuevo);

            if ($guardar[0] < 0) {
                throw new \Exception($guardar[1], 6);
            }

            /*REALIZAR RETENCIONES*/
            foreach ($jRetentions as $key => $value) {
                $retention = Retention::find($value->retention_id);
                $gastos = [];
                $gastos['account_id'] = $request->account_id;
                $gastos['paid_at'] = $request->paid_at;
                $gastos['amount'] = $value->value;
                $gastos['description'] = $retention->description;
                $gastos['category_id'] = 5;
                $gastos['reference'] = $retention->name;
                $gastos['user_id'] = Auth::user()->id;
                $gastos['status'] = 1;
                $gastos['category_id'] = 1;
                $gastos['payment_method'] = 1;
                $nuevog = new Request($gastos);
                $guardarg = $this->saveExpense($nuevog);
                if ($guardarg[0] < 0) {
                    throw new \Exception($guardarg[1], 6);
                }
                $ingresosR = [];
                $ingresosR['account_id'] = $retention->account_id;
                $ingresosR['paid_at'] = $request->paid_at;
                $ingresosR['amount'] = $value->value;
                $ingresosR['description'] = $retention->description;
                $ingresosR['category_id'] = $request->category_id;
                $ingresosR['reference'] = 'Retención al ingreso de fecha ' . $request->paid_at;
                $ingresosR['user_id'] = $request->user_id;
                $ingresosR['status'] = $request->status;
                $ingresosR['payment_method'] = $request->payment_method;
                $ingresosR['customer_id'] = $request->customer_id;
                $ingresosR['same_bank'] = $request->same_bank;
                $nuevoR = new Request($ingresosR);
                $guardarR = $this->saveRevenue($nuevoR);
                if ($guardarR[0] < 0) {
                    throw new \Exception($guardarR[1], 6);
                }
                /*REGISTRO DE RETENCIONES*/
                $reg = new RegRetention();
                $reg->project_id = $request->project_id;
                $reg->calculated_value = $value->reference;
                $reg->real_value = $value->value;
                $reg->revenue_origin_id = $guardar[0];
                $reg->expense_id = $guardarg[0];
                $reg->date = $request->paid_at;
                $reg->retention_id = $value->retention_id;
                $reg->revenue_id = $guardarR[0];
                $reg->save();
            }
            Session::flash('alert-type', 'success');
            Session::flash('message', trans('revenues.save_ok'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-type', 'error');
        }
        return Redirect::to('banks/revenues/print_voucher/' . $guardar[0].'/true');
    }

    /**
     * @param $project_id :
     */
    public function getRetentions($project_id){
        #region FILTROS
            $fecha1 = Input::get('date1');
            $fecha2 = Input::get('date2');
            $retention = Input::get('retention');

            $fechaActual = date("Y-m-d");
            if ($fecha1 == null) {
                $fecha1 = $fechaActual . ' 00:00:00';
            } else {
                $nuevaFecha1 = explode('/', $fecha1);
                $diaFecha1 = $nuevaFecha1[0];
                $mesFecha1 = $nuevaFecha1[1];
                $anioFecha1 = $nuevaFecha1[2];
                $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1 . ' 00:00:00';
            }

            if ($fecha2 == null) {
                $fecha2 = $fechaActual . ' 23:59:59';
            } else {

                $nuevaFecha2 = explode('/', $fecha2);
                $diaFecha2 = $nuevaFecha2[0];
                $mesFecha2 = $nuevaFecha2[1];
                $anioFecha2 = $nuevaFecha2[2];
                $fecha2 = $anioFecha2 . '-' . $mesFecha2 . '-' . $diaFecha2 . ' 23:59:59';
            }

        $retention = $retention == null ? 'Todo' : $retention;
        #endregion
        $retention_type = Retention::where('status', 1)->get();
        $project = Project::find($project_id);
        $retentions_q = RegRetention::where('project_id', $project_id)
            ->whereBetween('date', [$fecha1, $fecha2]);

        if ($retention != 'Todo'){
            $retentions_q -> where('retention_id', $retention);
        }

        $retentions = $retentions_q->get();

        return view('project.transactions.index_retentions')
            ->with('project', $project)
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('retention_type', $retention_type)
            ->with('retention', $retention)
            ->with('retentions', $retentions);
    }

    public function logs($project_id){
        $logs = log::where('project_id', $project_id)->get();
        return view('project.logs')
            ->with('project_id', $project_id)
            ->with('logs', $logs);
    }
}
