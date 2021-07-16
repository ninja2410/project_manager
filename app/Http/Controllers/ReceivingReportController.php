<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Receiving;
use App\ReceivingOutlay;
use \Auth, \Redirect;
use Illuminate\Http\Request;
use App\Pago;
use Validator, \Input, \Session;
use DB;

class ReceivingReportController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
        $this->middleware('parameter');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
			// $receivingsReport = Receiving::all()->where();
			// $fecha1=Input::get('date1');
			// $fecha2=Input::get('date2');
		$fechaActual=date("Y-m-d");

		$fecha1=Input::get('date1');
		$fecha2=Input::get('date2');
		$document = Input::get('document');
		$document = $document ==null ? 'Todo':$document;

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

			//documentos
		$dataDocuments=DB::table('series')
			->leftJoin('documents','series.id_document','=','documents.id')
			->where('documents.sign','=','+')
			->where('documents.ajuste_inventario','=','0')
			->select('series.id as id_serie','documents.id  as id_document','documents.sign','series.name as serie','documents.name as document')
			->get();

		$receivingsReport_q=DB::table('receivings');
		$receivingsReport_q->leftJoin('series','receivings.id_serie','=','series.id');
		$receivingsReport_q->leftJoin('documents','series.id_document' ,'=', 'documents.id');
		$receivingsReport_q->leftJoin('users','receivings.user_id' ,'=', 'users.id');
		$receivingsReport_q->leftJoin('suppliers','receivings.supplier_id' ,'=', 'suppliers.id');
		if($document!='Todo'){
			$receivingsReport_q->where('receivings.id_serie',$document);
		}
		$receivingsReport_q->where('documents.sign','=','+');
		$receivingsReport_q->where('cancel_bill','=',0);
		$receivingsReport_q->whereBetween('receivings.created_at',[$fecha1,$fecha2]);
		$receivingsReport_q->whereNull('storage_destination');
		$receivingsReport_q->select(['receivings.id','receivings.expenses','receivings.reference','receivings.supplier_id','receivings.user_id','receivings.comments','receivings.created_at','receivings.total_cost','receivings.id_serie','receivings.id_pago','receivings.correlative','users.name as nameUser','suppliers.company_name']);
		$receivingsReport=$receivingsReport_q->get();
		//total
		$totalSales_q=DB::table('receiving_items')->leftJoin('receivings','receiving_items.receiving_id','=','receivings.id');
		$totalSales_q->leftJoin('series','receivings.id_serie','=','series.id');
		$totalSales_q->leftJoin('documents','series.id_document','=','documents.id');
		$totalSales_q->whereBetween('receivings.created_at',[$fecha1,$fecha2]);
		$totalSales_q->where('receivings.cancel_bill','=',0)->where('documents.sign','=','+');
		if($document!='Todo'){
			$totalSales_q->where('receivings.id_serie',$document);
		}
		$totalSales_q->select(DB::raw('sum(receiving_items.total_cost) as totalCompras'));
		$totalSales=$totalSales_q->get();
		return view('report.receiving')->with('receivingReport', $receivingsReport)->with('fecha1', $fecha1)->with('fecha2', $fecha2)->with('dataDocuments',$dataDocuments)->with('document',$document)->with('totalSales',$totalSales);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function reporte_compra()
	{
		// $dataPagos=Pago::all();
		$fecha1=Input::get('date1');
		$fecha2=Input::get('date2');
		if($fecha1 =="" && $fecha2==""){
			$fecha1= $fecha2= date("d/m/Y");
		}
		$nuevaFecha1 = explode('/', $fecha1);
		$nuevaFecha2 = explode('/', $fecha2);
		$diaFecha1=$nuevaFecha1[0];
		$mesFecha1=$nuevaFecha1[1];
		$anioFecha1=$nuevaFecha1[2];
		$diaFecha2=$nuevaFecha2[0];
		$mesFecha2=$nuevaFecha2[1];
		$anioFecha2=$nuevaFecha2[2];
		$rFecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
		$rFecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';

		$valores=Receiving::join('series','series.id','=','.id_serie')
		->join('documents','documents.id','=','series.id_document')
		->join('pagos','receivings.id_pago','=','pagos.id')
		->where('documents.sign','=','+')
		->whereNull('storage_destination')
		// ->where('receivings.id_pago','=',$id_pago)
		->where('cancel_bill','=',0)
		->whereBetween('receivings.created_at',[$rFecha1,$rFecha2])
		->select('receivings.id','receivings.total_cost','documents.name as document','receivings.correlative','series.name as serie','receivings.created_at','pagos.name')
		->orderBy('pagos.name','receivings.created_at')
		->get();

		// dd($valores);

			return view('report.report_receivings_today')
			->with('dataPagos', $valores)
			->with('fecha1', $rFecha1)
			->with('fecha2', $rFecha2);

	}
	public function report_cancel_bill()
	{
		$fecha1=Input::get('date1');
		$fecha2=Input::get('date2');
		$fechaActual=date("Y-m-d");
		if($fecha1 =="" && $fecha2==""){
			$fecha11=$fechaActual.' 00:00:00';
			$fecha22=$fechaActual.' 23:59:59';
			// echo "Fecha 1 = ".$fecha11.'<br>';
			// echo "Fecha 1 = ".$fecha22.'<br>';
			$data_receivings=Receiving::where('cancel_bill','=',1)->whereBetween('created_at',[$fecha11,$fecha22])
			->get();
			return view('report.report_cancel_bill_receivings')
			->with('data_receivings',$data_receivings)
			->with('fecha1', $fecha11)
			->with('fecha2', $fecha22);
		}else{
			$nuevaFecha1 = explode('/', $fecha1);
			$nuevaFecha2 = explode('/', $fecha2);
			$diaFecha1=$nuevaFecha1[0];
			$mesFecha1=$nuevaFecha1[1];
			$anioFecha1=$nuevaFecha1[2];
			$diaFecha2=$nuevaFecha2[0];
			$mesFecha2=$nuevaFecha2[1];
			$anioFecha2=$nuevaFecha2[2];
			$rFecha1=$anioFecha1.'-'.$mesFecha1.'-'.$diaFecha1.' 00:00:00';
			$rFecha2=$anioFecha2.'-'.$mesFecha2.'-'.$diaFecha2.' 23:59:59';
			$data_receivings=Receiving::where('cancel_bill','=',1)->whereBetween('created_at',[$rFecha1,$rFecha2])
			->get();
			return view('report.report_cancel_bill_receivings')
			->with('data_receivings',$data_receivings)
			->with('fecha1', $rFecha1)
			->with('fecha2', $rFecha2);
		}
	}
	public function get_details($id){
		$detailsItems=DB::table('receiving_items')->leftJoin('items','receiving_items.item_id','=','items.id')->where('receiving_items.receiving_id',$id)
		->select('receiving_items.id','items.id as item_id','items.item_name','receiving_items.quantity','receiving_items.cost_price','receiving_items.total_cost')->get();
		// return json_encode($detailsItems);
		return $detailsItems;
	}

	public function get_outlays($id){
			$outlays = ReceivingOutlay::select('description', 'amount')
			->where('document_id', $id)->get();
			return $outlays;
	}
}
