<?php

namespace App\Http\Controllers;

use App\CreditNote;
use App\Expense;
use App\ExpenseTax;
use App\Notification;
use App\Receiving;
use App\Route;
use App\Traits\NotificationTrait;
use App\User;
use Doctrine\DBAL\Driver\IBMDB2\DB2Driver;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Payment;
use App\Supplier;
use App\Account;
use App\ExpenseCategory;
use App\Pago;
use Image;
use App\StateCellar;
use App\TransactionsCatalogue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \Input;
use mysql_xdevapi\Exception;
use \Redirect;
use \Session;
use Validator;
use App\Traits\TransactionsTrait;

class ExpensesController extends Controller
{
    use TransactionsTrait;

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
        $fecha1=Input::get('date1');
        $fecha2=Input::get('date2');


        $fechaActual=date("Y-m-d");
        if($fecha1==null){
            $fecha1=$fechaActual;
        }else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1;
        }

        if($fecha2==null){
            $fecha2=$fechaActual;
        }else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2=$nuevaFecha2[0];
            $mesFecha2=$nuevaFecha2[1];
            $anioFecha2=$nuevaFecha2[2];
            $fecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2;
        }
        $forma_pago = Input::get('forma_pago');
        $forma_pago = $forma_pago ==null ? 'Todo':$forma_pago;
        $status = Input::get('status') ==null ? 'Todo':Input::get('status');
        $dataPagos = Pago::sale()->get();
        $dataStatus = StateCellar::general()->limit(2)->get();

        $expense = Expense::with('account','pago','category');

        if($forma_pago!='Todo'){
            $expense->where('payment_type_id',$forma_pago);
        }
        if($status!='Todo'){
            $expense->where('state_id',$status);
        }
        $expense->whereBetween('expense_date',[$fecha1,$fecha2]);
        $expenses= $expense->get();
        return view('banking.expenses.index', compact('expenses'))
        ->with('fecha1', $fecha1)
        ->with('fecha2', $fecha2)
        ->with('dataPagos',$dataPagos)
        ->with('dataStatus',$dataStatus)
        ->with('forma_pago',$forma_pago)
        ->with('status',$status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::all();
        $suppliers = Supplier::all();
        $categories = ExpenseCategory::where('type',1)->get();
        $users=User::all();
        $payments = Pago::bankOut()->get();
        $routes = Route::where('status_id', 1)->get();
        $credit_notes = CreditNote::whereStatus_id(12)->get();
        return view('banking.expenses.create', compact('accounts',
            'payments','categories','suppliers', 'users', 'routes', 'credit_notes'));
    }

    public function expense_account($id){
        $expense = Payment::find($id);
        return view('banking.expenses.bk_show', compact('expense'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            if ($request->total_cost!=""){
                //INFORMACIÓN DE IMPUESTO APLICADO
                $tax = ExpenseTax::find($request->taxe_category);
                //CREAR GASTO PARA REGISTRAR EL IMPUESTO
                $impuesto = [];
                $impuesto['account_id'] = $request->account_id;
                $impuesto['paid_at'] = $request->paid_at;
                $impuesto['amount'] = floatVal($request->total_cost);
                $impuesto['description'] = $tax->description;
                $impuesto['category_id'] = 1; //CATEGORIA DE IMPUESTOS INTERNOS
                $impuesto['reference'] = $request->reference;
                $impuesto['user_id'] = $request->user_id;
                $impuesto['status'] = $request->status;
                $impuesto['supplier_id'] = $request->supplier_id;
                $impuesto['payment_method'] = $request->payment_method;
                $impuesto['recipient'] = $request->recipient;
                //DATOS DE GASTOS GENERALES
                $impuesto['assigned_user_id'] = $request->user_assigned_id;
                $impuesto['route_id'] = $request->route_id;
                $impuesto['cant'] = $request->units;
                $impuesto['unit_price'] = $tax->value;
                $impuesto['payment_status'] = 1;
                //---------------------------
                $newImpuesto = new Request($impuesto);
                $guardarImpuesto = $this->saveExpense($newImpuesto);
                $guardarImpuesto2 = $this->saveExpenseGeneral($newImpuesto);
                if ($guardarImpuesto[0]<0){
                    throw new \Exception($guardarImpuesto[1], 6);
                }
                if ($guardarImpuesto2[0]<0){
                    throw new \Exception($guardarImpuesto2[1], 6);
                }
            }

            $gastos = [];
            $gastos['account_id'] = $request->account_id;
            $gastos['paid_at'] = $request->paid_at;
            $gastos['amount'] = ($request->amount-floatVal($request->total_cost));
            $gastos['description'] = $request->description;
            $gastos['category_id'] = $request->category_id;
            $gastos['reference'] = $request->reference;
            $gastos['user_id'] = $request->user_id;
            $gastos['status'] = $request->status;
            $gastos['supplier_id'] = $request->supplier_id;
            $gastos['payment_method'] = $request->payment_method;
            $gastos['category_id'] = $request->category_id;
            $gastos['recipient'] = $request->recipient;
            //DATOS DE GASTOS GENERALES
            $gastos['assigned_user_id'] = $request->user_assigned_id;
            $gastos['route_id'] = $request->route_id;
            $gastos['cant'] = 0;
            $gastos['unit_price'] = 0;
            $gastos['payment_status'] = 1;
            $gastos['credit_note_id'] = $request->credit_note_id;
            //---------------------------
            $nuevo = new Request($gastos);

            // dd($nuevo->all());
            $guardar = $this->saveExpense($nuevo);
            if ($guardar[0] < 0) {
                throw new \Exception($guardar[1], 6);
            }
            $gastos['bank_expense_id'] = $guardar[0];
            $nuevo = new Request($gastos);
            $guardar2 = $this->saveExpenseGeneral($nuevo);
            $image = $request->avatar;
            if (!empty($image)) {
                $avatarName = 'expense' . $guardar2[0] . '.' .
                    $request->file('avatar')->getClientOriginalExtension();

                $request->file('avatar')->move(
                    base_path() . '/public/images/expenses/',
                    $avatarName
                );
                $img = Image::make(base_path() . '/public/images/expenses/' . $avatarName);
                $img->save();
                $customerAvatar = Expense::find($guardar2[0]);
                $customerAvatar->photo = $avatarName;
                $customerAvatar->save();
            }

            if ($guardar2[0] < 0) {
                throw new \Exception($guardar2[1], 6);
            }
            DB::commit();
            Session::flash('message', trans('expenses.save_ok'));
        }
        catch (\Exception $e){
            DB::rollBack();
            Session::flash('message', trans('expenses.save_error').':'.$e->getMessage());
            Session::flash('alert-class', 'alert-error');
            return redirect('banks/expenses/create')
                ->withInput();
        }
        return redirect('banks/expenses');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Expense::find($id);
        return view('banking.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);
        DB::beginTransaction();
        try {
            $trans = $this->cancelTransaction($id, 'payment', false);
            /**
             * ANULAR GASTO GENERAL ASOCIADO
             */
            $expense = Expense::whereBank_expense_id($id)->first();
            if (isset($expense->id)){
                $expense->state_id = 2;
                $expense->update();

                $expense->creditNote->amount_applied -= $expense->amount;
                $expense->creditNote->status_id = 12;
                $expense->creditNote->update();

                $expense->creditNote->customer->positive_balance += $expense->amount;
                $expense->creditNote->customer->update();
            }


            Session::flash('message', $trans[1]);
            if ($trans[0]!=1){
                throw new \Exception($trans[1], 6);
            }
            Session::flash('message', trans('bank_expenses.deleted_ok'));
            DB::commit();
        }
        catch(\Exception $ex){
            DB::rollback();
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-type', 'error');
        }

        return Redirect::to('banks/accounts/statement/'.$payment->account_id);
    }
}
