<?php

namespace App\Http\Controllers;

use App\Classes\NumeroALetras;
use App\Credit;
use App\DetailCredit;
use App\Parameter;
use App\Project;
use App\Sale;
use App\Serie;
use Doctrine\DBAL\Schema\AbstractAsset;
use \URL;
use \Input;

use mysql_xdevapi\Exception;
use \Session;
use App\Pago;

use App\User;
use \Redirect;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Validator;
use App\Account;
use App\Revenue;
use App\Customer;
use App\StateCellar;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\TransactionsCatalogue;
use App\Traits\TransactionsTrait;
use DB;
use App\Http\Controllers\Controller;

class RevenuesController extends Controller
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
        $fecha1 = Input::get('date1');
        $fecha2 = Input::get('date2');
        $forma_pago = Input::get('forma_pago');
        $forma_pago = $forma_pago == null ? 'Todo' : $forma_pago;
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
        $dataPagos = Pago::sale()->get();
        $dataStatus = StateCellar::banking()->get();

        $revenue = Revenue::with('account', 'pago');

        if ($forma_pago != 'Todo') {
            $revenue->where('bank_tx_revenues.payment_method', $forma_pago);
        }
        if ($status != 'Todo') {
            $revenue->where('bank_tx_revenues.status', $status);
        }
        $revenue->whereBetween('bank_tx_revenues.paid_at', [$fecha1, $fecha2]);
        $revenues = $revenue->get();



        return view('banking.revenues.index', compact('revenues'))
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('dataPagos', $dataPagos)
            ->with('dataStatus', $dataStatus)
            ->with('forma_pago', $forma_pago)
            ->with('status', $status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Url::current()==url('banks/revenues/create')){
            $payments = Pago::bankIn()
                ->limit(2)
                ->get();
            $accounts = Account::isType(7)
                ->whereStatus(1)
                ->get();
            $view = 'create';
            $deposit = null;
        }
        else{
            $payments = Pago::bankIn()
                ->get();
            $accounts = Account::NotType(7)
                ->whereStatus(1)
                ->get();
            $view = 'create_deposit';
            $deposit = true;
        }
        $receipt_number = intval(Revenue::max('receipt_number')) + 1;

        $customer = Customer::select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'), 'id', DB::Raw('(max_credit_amount-balance) as max_credit_amount'), 'balance')->get();


        $series = Serie::where('id_document', 11)/* Recibos */
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
        $url = 'banks/revenues';
        return view('banking.revenues.'.$view, compact('accounts', 'payments', 'customer'))
            ->with('series', $series)
            ->with('url', $url)
            ->with('deposit', $deposit)
            ->with('receipt_number', $receipt_number);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $ingresos = new Request();
        $ingresos = [];
        $ingresos['account_id'] = $request->account_id;
        $ingresos['paid_at'] = $request->paid_at;
        $ingresos['amount'] = $request->amount;
        $ingresos['receipt_number'] = $request->receipt_number;
        $ingresos['description'] = $request->description;
        $ingresos['category_id'] = $request->category_id;
        $ingresos['reference'] = $request->reference;
        $ingresos['user_id'] = $request->user_id;
        $ingresos['status'] = $request->status;
        $ingresos['payment_method'] = $request->payment_method;
        $ingresos['customer_id'] = $request->customer_id;
        $ingresos['bank_name'] = $request->bank_name;
        $ingresos['same_bank'] = $request->same_bank;
        $ingresos['card_name'] = $request->card_name;
        $ingresos['card_number'] = $request->card_number;
        $ingresos['amount_applied'] = $request->amount;
        $ingresos['serie_id'] = $request->serie_id;
        $ingresos['deposit'] = $request->deposit;
        $nuevo = new Request($ingresos);

        // dd($nuevo->all());
        $guardar = $this->saveRevenue($nuevo);


        // dd($request->all());
        // $request->payment_method = ($request->payment_method === "" ? 1 : $request->payment_method);
        // $request->status = ($request->status === "" ? 1 : $request->status);

        // $request->invoice_id = ($request->invoice_id === "" ? null : $request->invoice_id);
        // $request->customer_id = ($request->customer_id === "" ? null : $request->customer_id);
        // // dd($request->all());
        // $validator = Validator::make($request->all(), [
        //     ' account_id ' => ' required ',
        //     ' paid_at ' => ' required ',
        //     ' amount ' => ' required | min : 1 ',
        //     ' category_id ' => ' required ',
        //     ' status ' => ' required ',
        // ]);

        // if ($validator->fails()) {
        //     return redirect(' banks / revenues / create ')
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        // $account_id = $request->account_id;
        // $amount = $request->amount;

        // $dt_ = $request->paid_at;
        // $arr_ = explode("/", $dt_);
        // $nw_ = $arr_[2] . ' - ' . $arr_[1] . ' - ' . $arr_[0];

        // $revenue = new Revenue();
        // $revenue->account_id = $account_id;
        // $revenue->paid_at = $nw_;
        // $revenue->amount = $amount;
        // $revenue->currency = $request->currency;
        // $revenue->currency_rate = $request->currency_rate;
        // $revenue->invoice_id = $request->invoice_id;
        // $revenue->customer_id = $request->customer_id;
        // $revenue->description = $request->description;
        // $revenue->category_id = $request->category_id;
        // $revenue->payment_method = $request->payment_method;
        // $revenue->reference = $request->reference;
        // $revenue->status = $request->status;
        // $revenue->user_id = $request->user_id;

        // $revenue->save();

        // $account = Account::find($account_id);
        // $account->balance = $amount;

        // $account->save();
        // echo ' 0 ' . $guardar[0] . ' 1 ' . $guardar[1];
        // echo '<br>';
        // dd($guardar);

        if ($guardar[0] < 0) {
            Session::flash('message', $guardar[1]);
            Session::flash('alert-class', 'alert-error');
            return redirect('banks/revenues/create')
                ->withInput();
        }

        Session::flash('message', trans('revenues.save_ok'));
        Session::flash('alert-type', trans('success'));

        return redirect('banks/revenues/print_voucher/' . $guardar[0].'/false');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $revenue = Revenue::with('account', 'pago')->find($id);

        return view('banking.revenues.show', compact('revenue'));
    }

    public function print($id, $_pj){
        $revenue = Revenue::find($id);
        $parameters = Parameter::first();
        $letras = NumeroALetras::convertir($revenue->amount, 'quetzales', 'centavos');
        $precio_letras = ucfirst(strtolower($letras));
        if ($_pj == 'true'){
            $bk_route =url('project/revenues/'.$revenue->account_id);
        }
        else{
            $bk_route =url('banks/accounts/statement/'.$revenue->account_id);
        }
        return view('banking.revenues.voucher', compact(['bk_route','revenue', 'parameters', 'precio_letras']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $revenue = Revenue::find($id);
        $trans = $this->cancelTransaction($id, 'revenue', false);
        if ($trans[0] == 1){
            Session::flash('message', $trans[1]);
        }
        else{
            Session::flash('message', $trans[1]);
            Session::flash('alert-type', 'error');
        }
        return Redirect::to('banks/accounts/statement/' . $revenue->account_id);
    }


    public function deposits(){

        $fecha1 = Input::get('date1');
        $fecha2 = Input::get('date2');
        $forma_pago = Input::get('forma_pago');
        $deposit = Input::get('deposit');
        $forma_pago = $forma_pago == null ? 'Todo' : $forma_pago;
        $deposit = $deposit == null ? 'Todo' : $deposit;
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
        $dataPagos = Pago::sale()->get();
        $dataStatus = StateCellar::banking()->get();

        $revenue = Revenue::with('account', 'pago')
            ->join('bank_accounts', 'bank_accounts.id', '=', 'bank_tx_revenues.account_id')
            ->where('account_type_id', '!=', 7);

        if ($forma_pago != 'Todo') {
            $revenue->where('bank_tx_revenues.payment_method', $forma_pago);
        }
        if ($status != 'Todo') {
            $revenue->where('bank_tx_revenues.status', $status);
        }
        if ($deposit != 'Todo') {
            if ($deposit=="1"){
                $revenue->whereNotNull('bank_tx_revenues.deposit');
            }
            else{
                $revenue->where('bank_tx_revenues.deposit', '=', null);
            }

        }
        $revenue->whereBetween('bank_tx_revenues.paid_at', [$fecha1, $fecha2]);
        $revenue->select('bank_tx_revenues.*');
        $revenues = $revenue->get();
//        dd($revenues);
        return view('banking.revenues.index', compact('revenues'))
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('dataPagos', $dataPagos)
            ->with('dataStatus', $dataStatus)
            ->with('forma_pago', $forma_pago)
            ->with('deposit', $deposit)
            ->with('status', $status);
    }

    public function addDeposit(Request $request){
        $url = '';
        try {
            $revenue = Revenue::find($request->revenue_id);
            $revenue->deposit = $request->deposit;
            $revenue->update();
            Session::flash('message', trans('revenues.save_deposit_ok'));
            if ($request->view =="index"){
                $url = 'banks/deposits';
            }
            if ($request->view=="show"){
                $url = "banks/revenues/".$revenue->id;
            }
        }
        catch (\Exception $e){
            Session::flash('message', trans('revenues.save_deposit_error'));
            Session::flash('alert-type', 'danger');
        }
        return Redirect::to($url);
    }

    public function verifyDeposit(){
        $revs = Revenue::where('deposit', Input::get('deposit'))
            ->where('status', '!=', 2)
            ->count();
        if ($revs > 0){
            $isAvailable= false;
        }
        else{
            $isAvailable= true;
        }
        return json_encode(array('valid' => $isAvailable));
    }
}
