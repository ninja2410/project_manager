<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Transfer;
use App\Account;

use App\TransactionsCatalogue;
use App\Pago;

use \Redirect;
use \Session;

use App\Traits\TransactionsTrait;
use App\Payment;
use App\Revenue;

class TransfersController extends Controller
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
        $transfers = Transfer::with(['payment', 'payment.account', 'revenue', 'revenue.account'])->get();
        return view('banking.transfers.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::all();

        $pago = Pago::bankOut()->get();
        return view('banking.transfers.create', compact('accounts', 'pago'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $status = $request->status;

        $new_expense = [];
        $new_expense['account_id'] = $request->account_id_source;
        $new_expense['paid_at'] = $request->paid_at;
        $new_expense['amount'] = $request->amount;
        $new_expense['description'] = $request->description;
        $new_expense['category_id'] = $request->category_id;
        $new_expense['reference'] = $request->reference;
        $new_expense['user_id'] = $user_id;
        $new_expense['status'] = $status;
        // $new_expense['supplier_id'] = $request->supplier_id;
        $new_expense['payment_method'] = $request->payment_method;
        $request_expense = new Request($new_expense);

        $new_revenue = [];
        $new_revenue['account_id'] = $request->account_id_destination;
        $new_revenue['paid_at'] = $request->paid_at;;
        $new_revenue['amount'] = $request->amount;
        $new_revenue['description'] = $request->description;
        $new_revenue['category_id'] = $request->category_id;
        $new_revenue['reference'] = $request->reference;
        $new_revenue['same_bank'] = 1;
        $new_revenue['user_id'] = $user_id;
        $new_revenue['status'] = $status;
        // $new_revenue['customer_id'] = $request->customer_id;
        $new_revenue['payment_method'] = $request->payment_method;
        $request_revenue = new Request($new_revenue);

        // var_dump($request_expense->all());
        // echo '<br><br>';
        // var_dump($request_revenue->all());

        // dd($nuevo->all());


        $transfer = $this->saveTransfer($request_revenue, $request_expense);


        if ($transfer[0] < 0) {
            Session::flash('message', $transfer[1]);
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

        Session::flash('message', trans('transfers.save_ok'));

        return redirect('banks/transfers');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transfer = Transfer::find($id);
        $payment = Payment::find($transfer->payment_id);
        $revenue = Revenue::find($transfer->revenue_id);
        // ::where('id', $id)->with(['payment', 'payment.account', 'revenue', 'revenue.account'])->get();
        // echo ' Transfer ' . '<br>';
        // var_dump($transfer);
        // echo '<br>' . ' Payment ' . '<br>';
        // var_dump($payment);
        // echo '<br>' . ' Revenue ' . '<br>';
        // dd($revenue);
        return view('banking.transfers.show', compact('transfer', 'payment', 'revenue'));
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
        //
    }
}
