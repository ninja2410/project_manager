<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Pago;
use App\InvoiceCredit;
use App\Detail_invoice;
use Validator, \Input, \Session;
use DB;
use App\Http\Controllers\Controller;

class CreditReportController extends Controller
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
      $fecha1=Input::get('date1');
      $fecha2=Input::get('date2');
      $document = Input::get('document');
          $document = $document ==null ? 'Todo':$document;

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

          // $fecha2 =$fecha2== null ? $fechaActual.' 23:59:59' : $fecha2;
      // return $fecha1.'  '.$fecha2;
      // dd($fecha1.'  '.$fecha2);
      $dataDocuments=DB::table('series')
        ->leftJoin('documents','series.id_document','=','documents.id')
        ->where('series.credit','=',1)
        ->select('series.id as id_serie','documents.id  as id_document','documents.sign','series.name as serie','documents.name as document')
        ->get();
      $salesReport_q=DB::table('invoice_credits');
      $salesReport_q->leftJoin('series','invoice_credits.serie_id','=','series.id');
      $salesReport_q->leftJoin('documents','series.id_document','=','documents.id');
      $salesReport_q->leftJoin('users','users.id','=','invoice_credits.user_id');
      $salesReport_q->leftJoin('customers','invoice_credits.customer_id','=','customers.id');
      if($document!='Todo'){
        $salesReport_q->where('invoice_credits.serie_id',$document);
      }
      $salesReport_q->whereBetween('invoice_credits.date',[$fecha1,$fecha2]);
      $salesReport_q->select(['invoice_credits.id as id_sales','customers.name as customer_name','documents.name as document','series.name','invoice_credits.number','invoice_credits.date','users.name as user_name','invoice_credits.amount']);
      $salesReport_q->orderBy('invoice_credits.date','DESC');
      $salesReport_q->orderBy('series.name','ASC');
      $salesReport_q->orderBy('number','ASC');
      $salesReport=$salesReport_q->get();
      //dd($salesReport);
      $pagos=Pago::all();
      return view('report.report_credits')->with('saleReport', $salesReport)->with('pagoss',$pagos)->with('fecha1', $fecha1)
        ->with('fecha2', $fecha2)->with('dataDocuments',$dataDocuments)->with('document',$document);
    }

    public function get_details($id){
      $detailsItems=Detail_invoice::where('invoice_id', $id)->get();
      return $detailsItems;
  	}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show($id)
    {
        //
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
