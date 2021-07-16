<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Payment;
use App\Supplier;
use App\Account;
use App\Pago;
use App\StateCellar;
use \Input;
use \Redirect;
use \Session;
use Validator;
use App\Traits\TransactionsTrait;

class PaymentsController extends Controller
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
            $fecha1=$fechaActual.' 00:00:00';
        }else {
            $nuevaFecha1 = explode('/', $fecha1);
            $diaFecha1=$nuevaFecha1[0];
            $mesFecha1=$nuevaFecha1[1];
            $anioFecha1=$nuevaFecha1[2];
            $fecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
        }

        if($fecha2==null){
            $fecha2=$fechaActual.' 23:59:59';
        }else {

            $nuevaFecha2 = explode('/', $fecha2);
            $diaFecha2=$nuevaFecha2[0];
            $mesFecha2=$nuevaFecha2[1];
            $anioFecha2=$nuevaFecha2[2];
            $fecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';
        }
        $forma_pago = Input::get('forma_pago');
        $forma_pago = $forma_pago ==null ? 'Todo':$forma_pago;
        $status = Input::get('status') ==null ? 'Todo':Input::get('status');
        $dataPagos = Pago::sale()->get();
        $dataStatus = StateCellar::banking()->get();

        $expense = Payment::with('account','pago');

        if($forma_pago!='Todo'){
            $expense->where('bank_tx_payments.payment_method',$forma_pago);
        }
        if($status!='Todo'){
            $expense->where('bank_tx_payments.status',$status);
        }
        $expense->whereBetween('bank_tx_payments.paid_at',[$fecha1,$fecha2]);
        $expenses= $expense->get();
        // dd($expenses);

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
        $payments = Pago::bankOut()->get();

        return view('banking.expenses.create', compact('accounts', 'payments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gastos = [];
        $gastos['account_id'] = $request->account_id;
        $gastos['paid_at'] = $request->paid_at;
        $gastos['amount'] = $request->amount;
        $gastos['description'] = $request->description;
        $gastos['category_id'] = $request->category_id;
        $gastos['reference'] = $request->reference;
        $gastos['user_id'] = $request->user_id;
        $gastos['status'] = $request->status;
        $gastos['supplier_id'] = $request->supplier_id;
        $gastos['payment_method'] = $request->payment_method;
        $gastos['recipient'] = $request->recipient;

        $nuevo = new Request($gastos);

        // dd($nuevo->all());
        $guardar = $this->saveExpense($nuevo);

        if ($guardar[0] < 0) {
            Session::flash('message', $guardar[1]);
            Session::flash('alert-class', 'alert-error');
            return redirect('banks/expenses/create')
                ->withInput();
        }

        Session::flash('message', trans('expenses.save_ok'));

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
        $expense = Payment::find($id);
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
        //
    }
}
