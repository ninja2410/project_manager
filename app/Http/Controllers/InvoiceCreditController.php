<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\Pagare;
use App\PagareDetail;
use App\InvoiceCredit;
use App\Customer;
use App\Warranty;
use App\Invoice_type;
use App\Parameter;
use App\Max_pct;
use App\Detail_invoice;
use App\Serie;
use App\InvoicePaymentRelation;
use App\Item;
use \Redirect;
use App\Document;
use App\User;
use App\Classes\NumeroALetras;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InvoiceCreditController extends Controller
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
    public function index($id)
    {
      //ESTILO DE FACTURACIÓN
      $type=Invoice_type::orderby('date', 'desc')->first();
      $percent=Max_pct::where('date', date('Y-m-d'))->get();
      //CALCULAR CORRELATIVO
      $correlativo=0;
      $total_payments=0;
      $correlativo=$ultimo=InvoiceCredit::max('id');
      $correlativo++;
      //HEADER DE CREDITO
      $credit=Pagare::find($id);
      $payments=PagareDetail::where('pagare_id', $id)->where('status', 0)->where('invoiced', 0)->get();
      $countPayments=PagareDetail::where('pagare_id', $id)->where('status', 0)->where('invoiced', 0)->count();
      $customer=Customer::find($credit->customer_id);
      $company=Parameter::first();
      $warranties=Warranty::where('pagare_id', $id)->get();


      if ($countPayments==0) {
        return Redirect::to('pagares');
      }
      //CALCULAR MONTO
      $total_amount=0;
      foreach ($payments as $payment) {
        $total_amount+=$payment->total_payment;
      }
      //CREAR FACTURA
      $invoice=new InvoiceCredit();
      $invoice->date=date('Y-m-d');
      $invoice->customer_id=$customer->id;
      $invoice->serie_id=6;
      $invoice->number=$correlativo;
      $invoice->percent_admin=$company->percent_max;
      $invoice->amount=$total_amount;
      $invoice->max_id=$percent[0]->id;
      $invoice->date_create=$invoice->date;
      $invoice->type_id=$type->id;
      $invoice->save();
      foreach ($payments as $payment) {
        $payment->invoiced=1;
        $payment->invoice_id=$invoice->id;
        $payment->update();
      }
      $serie=Serie::find($invoice->serie_id);
      return view('pdf.creditInvoice', ['credit'=> $credit, 'payments'=>$payments, 'customer'=>$customer, 'company'=>$company, 'warranties'=>$warranties, 'invoice'=>$invoice, 'serie'=>$serie]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
      $invoice_type=Invoice_type::where('status', 1)->orderby('id', 'dsc')->first();
      $series=Serie::where('credit', 1)->where('status', 1)->get();
      $documentos=Document::where('status', 1)->where('sign', '=')->where('name', 'like', '%credito%')->get();
      $credit=Pagare::find($id);
      $customer=Customer::find($credit->customer_id);
      $pendingPayments=PagareDetail::where('pagare_id', $id)->where('status', 0)->orwhere('pending_payment','>',0)->where('invoiced', 0)->count();
      $payments=PagareDetail::where('pagare_id', $id)->get();
      $pays=PagareDetail::where('pagare_id', $id)
      ->where('invoiced', 0)->get();
      $gn=PagareDetail::where('pagare_id', $id)->get();
      $total_amount=0;
      $total_payment=0;
      $total_surcharge=0;
      $total_surchargeRecived=0;
      $total_payments=0;
      $total_pendingpayment=0;
      $total_invoices=0;
      $total_surcharge_=0;
      $cor=InvoiceCredit::where('serie_id', $series[0]->id)->orderby('id', 'desc')->first();
      $total_inv=DB::table('invoice_payment_relations')
      ->join('pagare_details', 'pagare_details.id', '=', 'invoice_payment_relations.payment_id')
      ->join('pagares', 'pagares.id', '=', 'pagare_details.pagare_id')
      ->join('invoice_credits', 'invoice_credits.id', '=', 'invoice_payment_relations.invoice_id')
      ->where('pagares.id', $credit->id)->groupby('invoice_credits.id')->get();
      foreach ($gn as $key => $value) {
        if ($value->pending_surcharge==0) {
          $total_surcharge_+=$value->surcharge_recived;
        }
      }
      foreach ($total_inv as $key => $value) {
        $total_invoices+=$value->amount;
      }
      if (isset($cor)) {
        $correlativo=$cor->number+1;
      }else{
        $correlativo=1;
      }
      //CALCULAR RESUMEN DE CREDITO
      foreach ($pays as $key => $value) {
        $total_amount+=($value->amount+$value->interes);
        $total_payment+=($value->total_payment-$value->invoice_id);
        $total_surcharge+=$value->surcharge;
        $total_surchargeRecived+=$value->pending_surcharge;
        if ($value->status==1) {
          $total_payments++;
          if ($value->total_recived==0) {
            $total_pendingpayment+=$value->amount+$value->interes;
          }
          else{
            $total_pendingpayment+=$value->pending_payment;
          }
        }
      }
      $total_invoices-=$total_surcharge_;
      $previus=Max_pct::orderby('date', 'desc')->first();
      $pct=Max_pct::whereDate('date','=', date('Y-m-d'))->get();
      if (isset($pct[0])) {
        $percent=Max_pct::whereDate('date', '=',date('Y-m-d'))
        ->orderby('id', 'desc')
        ->get();
      }
      else{
        $percent=0;
      }
      $total_to_invoice=((($total_payment*$credit->ptc_interes/100)));
      //dd($total_payment);
    //$total_to_invoice=round($total_to_invoice,2);
      // $total_to_invoice=($total_to_invoice/((100+$credit->ptc_interes)/100));
      //dd($total_to_invoice);
      if (isset($invoice_type->id)) {
        $resume=Item::find($invoice_type->resume);
        $det1=Item::find($invoice_type->item_id1);
        $det2=Item::find($invoice_type->item_id2);
        return view('pagares.create_invoice', ['credit'=>$credit, 'customer'=>$customer,
                    'total_invoices'=>$total_invoices, 'pendingPayments'=>$pendingPayments, 'payments'=>$payments,
                  'total_amount'=>$total_amount, 'total_payment'=>$total_payment,
                'total_surcharge'=>$total_surcharge, 'total_surchargeRecived'=>$total_surchargeRecived,
              'total_payments'=>$total_payments, 'total_pendingpayment'=>$total_pendingpayment,
            'percent'=>$percent, 'series'=>$series, 'resume'=>$resume, 'det1'=>$det1,
          'det2'=>$det2, 'previus'=>$previus, 'correlativo'=>$correlativo, 'invoice_type'=>$invoice_type,
        'total_to_invoice'=>$total_to_invoice]);
      }
      else{
        return view('pagares.create_invoice', ['credit'=>$credit, 'customer'=>$customer,
                    'total_invoices'=>$total_invoices,'pendingPayments'=>$pendingPayments, 'payments'=>$payments,
                  'total_amount'=>$total_amount, 'total_payment'=>$total_payment,
                'total_surcharge'=>$total_surcharge, 'total_surchargeRecived'=>$total_surchargeRecived,
              'total_payments'=>$total_payments, 'total_pendingpayment'=>$total_pendingpayment,
            'percent'=>$percent, 'series'=>$series,
          'previus'=>$previus, 'correlativo'=>$correlativo, 'invoice_type'=>$invoice_type,
        'total_to_invoice'=>$total_to_invoice]);
      }
    }


    public function correlativo($serie_id){
      $old=InvoiceCredit::where('serie_id', $serie_id)->orderby('number', 'desc')->first();
      if (isset($old->id)) {
        $resp= $old->number+1;
      }
      else{
        $resp= 1;
      }
      return response()->json($resp);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reprint($id)
    {
      $rel=InvoicePaymentRelation::where('payment_id', $id)->first();
      $pago=PagareDetail::find($id);
      $invoice=InvoiceCredit::where('id', $rel->invoice_id)->first();
      $details=Detail_invoice::where('invoice_id', $invoice->id)->get();
      $customer=Customer::find($invoice->customer_id);
      $credit=Pagare::find($pago->pagare_id);
      $company=Parameter::first();
      $serie=Serie::find($invoice->serie_id);
      $warranties=Warranty::where('pagare_id', $credit->id)->get();
      $letras = NumeroALetras::convertir($invoice->amount, 'quetzales', 'centavos');
      $precio_letras = ucfirst(strtolower($letras));
      $dataUsers=User::find($invoice->user_id);
        return view('pdf.creditInvoice', ['credit'=> $credit,
        'customer'=>$customer,'dataUsers'=>$dataUsers,
        'company'=>$company, 'warranties'=>$warranties, 'invoice'=>$invoice, 'serie'=>$serie,
        'details'=>$details, 'precio_letras'=>$precio_letras]);
    }

    public function reprintInv($id)
    {
      $rel=InvoicePaymentRelation::where('invoice_id', $id)->first();
      $pago=PagareDetail::find($id);
      $invoice=InvoiceCredit::where('id', $rel->invoice_id)->first();
      $details=Detail_invoice::where('invoice_id', $invoice->id)->get();
      $customer=Customer::find($invoice->customer_id);
      $credit=Pagare::find($pago->pagare_id);
      $company=Parameter::first();
      $serie=Serie::find($invoice->serie_id);
      $warranties=Warranty::where('pagare_id', $credit->id)->get();
      $letras = NumeroALetras::convertir($invoice->amount, 'quetzales', 'centavos');
      $precio_letras = ucfirst(strtolower($letras));
      $dataUsers=User::find($invoice->user_id);
        return view('pdf.creditInvoice', ['credit'=> $credit,
        'customer'=>$customer,'dataUsers'=>$dataUsers,
        'company'=>$company, 'warranties'=>$warranties, 'invoice'=>$invoice, 'serie'=>$serie,
        'details'=>$details, 'precio_letras'=>$precio_letras]);
    }

    public function reprintInvoice($id)
    {
      $rel=InvoicePaymentRelation::where('invoice_id', $id)->first();
      $pago=PagareDetail::find($rel->payment_id);
      $invoice=InvoiceCredit::find($id);
      $details=Detail_invoice::where('invoice_id', $invoice->id)->get();
      $customer=Customer::find($invoice->customer_id);
      $credit=Pagare::find($pago->pagare_id);
      $company=Parameter::first();
      $serie=Serie::find($invoice->serie_id);
      $warranties=Warranty::where('pagare_id', $credit->id)->get();
      $letras = NumeroALetras::convertir($invoice->amount, 'quetzales', 'centavos');
      $precio_letras = ucfirst(strtolower($letras));
      $dataUsers=User::find($invoice->user_id);
        return view('pdf.creditInvoice', ['credit'=> $credit,
        'customer'=>$customer,'dataUsers'=>$dataUsers,
        'company'=>$company, 'warranties'=>$warranties, 'invoice'=>$invoice, 'serie'=>$serie,
        'details'=>$details, 'precio_letras'=>$precio_letras]);
    }

    public function verify(Request $request){
      $bandera=InvoiceCredit::where('number', $request->number)->where('serie_id', $request->serie)->count();
      if ($bandera!=0) {
        $resp='El correlativo '. $request->number .' ya existe para esta serie.';
      }
      else{
        $resp="";
      }
      return response()->json($resp);
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

    public function save(Request $request){
      $monto= str_replace(",", "", $request->total_invoice);
      $newInvoice=new InvoiceCredit();
      $dt_=$request->fecha;
      $arr_=explode("/", $dt_);
      $nw_=$arr_[2].'-'. $arr_[1].'-'. $arr_[0];
      $newInvoice->date=$nw_;
      $newInvoice->date_create=date('Y-m-d');
      $newInvoice->customer_id=$request->customer_id;
      $newInvoice->amount=$monto;
      $newInvoice->user_id=auth()->user()->id;
      $newInvoice->serie_id=$request->serie;
      $newInvoice->percent_admin=$request->percent;
      $newInvoice->number=$request->numero;
      $newInvoice->type_id=$request->type_id;
      $newInvoice->max_id=$request->max_pct;
      $newInvoice->save();
      if ($request->type_invoice==2) {
        //RESUMIDO
        $res=new Detail_invoice();
        $res->amount=$monto;
        $res->user_description=$request->resumeUser;
        $res->date=date('Y-m-d');
        $res->status=1;
        $res->invoice_id=$newInvoice->id;
        $res->item_id=$request->resume_id;
        $res->save();
      }
      else{
        $res1=new Detail_invoice();
        $res1->amount=($monto*$request->percent/100);
        $res1->user_description=$request->det1User;
        $res1->date=date('Y-m-d');
        $res1->status=1;
        $res1->invoice_id=$newInvoice->id;
        $res1->item_id=$request->det1_id;
        $res1->save();
        $residuo=$monto-$res1->interes-$res1->amount;
        $res2=new Detail_invoice();
        $res2->amount=$residuo;
        $res2->user_description=$request->det2User;
        $res2->date=date('Y-m-d');
        $res2->status=1;
        $res2->invoice_id=$newInvoice->id;
        $res2->item_id=$request->det2_id;
        $res2->save();
      }

      if ($request->amount_invoice_type==1) {
        //SOLO PAGADO
        $pagos=PagareDetail::where('pagare_id', $request->credit)->where('total_payment','>', 0)->where('invoiced', 0)->get();
        foreach ($pagos as $key => $p) {
          $p->pending_surcharge=0;
          $p->invoiced=1;
          $p->invoice_id=0;
          $rel=new InvoicePaymentRelation();
          $rel->payment_id=$p->id;
          $rel->invoice_id=$newInvoice->id;
          $rel->save();
          $p->update();
        }
      }
      else{
        $pagos=PagareDetail::where('pagare_id', $request->credit)->where('invoiced', 0)->get();
        foreach ($pagos as $key => $p) {
          if ($p->invoiced==0||$p->invoice_id>0) {
            $p->pending_surcharge=0;
            $rel=new InvoicePaymentRelation();
            $rel->payment_id=$p->id;
            $rel->invoice_id=$newInvoice->id;
            $rel->save();
            $p->invoiced=1;
            $p->update();
          }

        }
      }
      //GUARDAR FACTURACIÓN EN DETALLE DE PAGOS
      $details=Detail_invoice::where('invoice_id', $newInvoice->id)->get();
      $customer=Customer::find($newInvoice->customer_id);
      $credit=Pagare::find($request->credit);
      $company=Parameter::first();
      $dataUsers=User::find($newInvoice->user_id);
      $serie=Serie::find($newInvoice->serie_id);
      $warranties=Warranty::where('pagare_id', $credit->id)->get();
      $letras = NumeroALetras::convertir($newInvoice->amount, 'quetzales', 'centavos');
      $precio_letras = ucfirst(strtolower($letras));
        return view('pdf.creditInvoice', ['credit'=> $credit,
        'customer'=>$customer,'dataUsers'=>$dataUsers,
        'company'=>$company, 'warranties'=>$warranties, 'invoice'=>$newInvoice, 'serie'=>$serie,
        'details'=>$details, 'precio_letras'=>$precio_letras]);
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
