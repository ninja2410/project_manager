<?php

namespace App\Http\Controllers;

use App\Sale;
use App\User;
use App\Serie;
use App\Credit;
use App\Revenue;
use App\Customer;
use App\Parameter;
use App\RouteUser;
use App\CreditNote;
use App\StateCellar;
use App\Http\Requests;
use App\CreditNoteDetail;
use App\GeneralParameter;

use App\Traits\DatesTrait;
use Illuminate\Http\Request;
use App\Classes\NumeroALetras;
use App\Traits\CreditNoteTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;
use PhpParser\Node\Param;
use INFILE;

class CreditNoteController extends Controller
{
    use CreditNoteTrait;
    use DatesTrait;

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
        $fecha1 = $this->fixFecha(Input::get('date1'));
        $fecha2 = $this->fixFechaFin(Input::get('date2'));
        $status = Input::get('status');
        $all_status = StateCellar::where('type', 'credit_notes')
            ->get();
        if ($status == null) {
            $status = StateCellar::where('type', 'credit_notes')
                ->lists('id');
        } else {
            $status = (array)$status;
        }


        $administrador = Session::get('administrador');
        $ruta_requerida = GeneralParameter::active()->where('name', 'Campo ruta requerido.')->first();
        /** Si la ruta es requerida y no es administrador */
        if ((isset($ruta_requerida)) && ($administrador == false)) {
            $rutas = RouteUser::where('user_id', Auth::user()->id)->select('route_id')->get();
            if (count($rutas) == 0) {
                $rutas = [0, 0];
            }
            $credit_notes = CreditNote::join('customers', 'customers.id', '=', 'credit_notes.customer_id')
                ->join('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')
                ->whereIn('route_costumers.route_id', $rutas)
                ->whereIn('status_id', $status)
                ->almacen()
                ->select('credit_notes.*')
                ->whereBetween('date', [$fecha1, $fecha2])
                ->get();
        } else {
            $credit_notes = CreditNote::whereIn('status_id', $status)
                ->whereBetween('date', [$fecha1, $fecha2])
                ->almacen()
                ->get();
        }
        return view('credit_note.index')
            ->with('fecha1', $fecha1)
            ->with('fecha2', $fecha2)
            ->with('status', $status)
            ->with('all_status', $all_status)
            ->with('url', url('credit_note'))
            ->with('credit_notes', $credit_notes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($sale_id = 0)
    {
        $idUserActive = Auth::user()->id;
        $ValorSeries = Serie::join('documents', 'documents.id', '=', 'series.id_document')
            ->where('series.id_document', '=', 10)
            ->where('series.id_state', '=', 1)
            ->select('series.name', 'series.id', 'documents.name as nombre')
            ->orderBy('series.name', 'asc')->get();
        $correlativo = CreditNote::orderby('id', 'desc')->first();
        if (!isset($correlativo->correlative)) {
            $correlativo = 1;
        } else {
            $correlativo = $correlativo->correlative + 1;
        }
        $dataUsers = User::where('show_in_tx', 0)->get();
        if ((isset($ruta_requerida)) && ($administrador == false)) {
            $rutas = RouteUser::where('user_id', Auth::user()->id)->select('route_id')->get();
            if (count($rutas) == 0) {
                $rutas = [0, 0];
            }
            $customers = Customer::
            join('route_costumers', 'customers.id', '=', 'route_costumers.customer_id')
                ->whereIn('route_costumers.route_id', $rutas)
                ->select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'), 'customers.id', DB::Raw('(max_credit_amount-balance) as max_credit_amount'), 'balance')->get(); //all();
        } else {
            $customers = Customer::select(DB::Raw('concat(nit_customer," | ",name," | ",if((max_credit_amount-balance)>0,"C","0")) as name'), 'id', DB::Raw('(max_credit_amount-balance) as max_credit_amount'), 'balance')->get(); //all();
        }
        return view('credit_note.create')
            ->with('correlative', $correlativo)
            ->with('customer', $customers)
            ->with('sale_id', $sale_id)
            ->with('dataUsers', $dataUsers)
            ->with('idUserActive', $idUserActive)
            ->with('series', $ValorSeries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * TIPOS DE NOTAS DE CREDITO
         * 1) Descuento
         * 2) Devolución
         * 3) Anulación
         */
        DB::beginTransaction();
        try {
            $empresa = Parameter::first();
            $sale = Sale::find($request->sale_id);
            $customer = $sale->customer;
            $detalles_descuentos = json_decode($request->descuentos);
            $detalles_devoluciones = json_decode($request->devolucion);

            #region Creación de encabezado de nota de crédito
            $nuevaFecha1 = explode('/', $request->date_tx);
            $diaFecha1 = $nuevaFecha1[0];
            $mesFecha1 = $nuevaFecha1[1];
            $anioFecha1 = $nuevaFecha1[2];
            $fecha1 = $anioFecha1 . '-' . $mesFecha1 . '-' . $diaFecha1;
            $total_amount = str_replace("Q", "", $request->total_nota_credito);
            $total_amount = (float)str_replace(" ", "", $total_amount);
            $nc_header = new CreditNote();
            $nc_header->amount = $total_amount;
            $nc_header->correlative = $request->correlative;
            $nc_header->date = $fecha1;
            $nc_header->comment = $request->comment;
            $nc_header->reference = $request->credit_note_sale;
            $nc_header->sale_id = $sale->id;
            $nc_header->serie_id = $request->serie_id;
            $nc_header->created_by = Auth::user()->id;
            $nc_header->customer_id = $sale->customer_id;
            $nc_header->type = $request->type_nc;
            $nc_header = $this->applyNote($nc_header);
            $nc_header->save();
            #endregion

            #region CREAR ENCABEZADOS FEL
            $header = json_decode(json_encode(array(
                "date"=>date('Y-m-d\TH:i:sP'),
                "email"=>'',
                "nit"=>$empresa->nit,
                "nombre_comercial"=>$empresa->fel_username,
                "nombre_emisor"=>$empresa->fel_username,
                "address"=>$empresa->address,
                "postal_code"=>"01011",
                "municipality"=>"Guatemala",
                "departament"=>"Guatemala"
            )));
            $receiver = json_decode(json_encode(array(
                "email"=>'',
                "nit"=>strtoupper(str_replace(["/", "-", " "], "",$customer->nit_customer)),
                "name"=>$customer->name,
                "address"=>$customer->address,
                "postal_code"=>"01011",
                "municipality"=>"Guatemala",
                "departament"=>"Guatemala"
            )));
            #endregion

            #region Actualizar nc_amount en ventas para llevar control de montos asociados a notas de crédito
            $sale->nc_amount += $nc_header->amount;
            $sale->update();
            #endregion
            $details = [];
            if ($request->type_nc == 2) {
                $motivo = "Devolución";
                $details = $this->setDevolucion($nc_header->id, $detalles_devoluciones,
                    $nc_header->serie->document->name . " " . $nc_header->serie->name . "-$nc_header->correlative");
                #region ACTUALIZAR INGRESO SI NO FUE AL CRÉDITO
//                if ($sale->payment_status) {
//                    $revenues = Revenue::where('invoice_id', $sale->id)->get();
//                    $tmp_amnt = 0;
//                    $tmp__contador = 0;
//                    do {
//                        if ($revenues[$tmp__contador]->amount_applied >= $nc_header->amount) {
//                            $revenues[$tmp__contador]->amount_applied -= $nc_header->amount;
//                            $tmp_amnt = $nc_header->amount;
//                        } else {
//                            $tmp_amnt += $revenues[$tmp__contador]->amount;
//                            $revenues[$tmp__contador]->amount_applied = 0;
//                        }
//                        $revenues[$tmp__contador]->update();
//                        $tmp__contador++;
////                        dd($tmp_amnt."/".$nc_header->amount);
//                    } while ($tmp_amnt != $nc_header->amount);
//                }
                #endregion
            }
            if ($request->type_nc == 1 || $request->type_nc == 4) {
                $motivo = "Descuento";
                $details = $this->setDescuento($nc_header->id, $detalles_descuentos);
                #region ACTUALIZAR INGRESO SI NO FUE AL CRÉDITO
//                if ($sale->payment_status) {
//                    $revenues = Revenue::where('invoice_id', $sale->id)->get();
//                    $tmp_amnt = 0;
//                    $tmp__contador = 0;
//                    if ($request->type_nc == 1) {
//                        do {
//                            if ($revenues[$tmp__contador]->amount_applied >= $nc_header->amount) {
//                                $revenues[$tmp__contador]->amount_applied -= $nc_header->amount;
//                                $tmp_amnt = $nc_header->amount;
//                            } else {
//                                $tmp_amnt += $revenues[$tmp__contador]->amount;
//                                $revenues[$tmp__contador]->amount_applied = 0;
//                            }
//                            $revenues[$tmp__contador]->update();
//                            $tmp__contador++;
//                        } while ($tmp_amnt != $nc_header->amount);
//                    } else {
//                        $revenues[0]->amount_applied -= $nc_header->amount;
//                        $revenues[0]->update();
//                    }
//                }
                #endregion
            }
            if ($request->type_nc == 3) {
                $motivo = "Anulación";
                $details = $this->setAnulacion($nc_header->id, $sale->id);
                #region ACTUALIZAR INGRESO SI NO FUE AL CRÉDITO
//                if ($sale->payment_status) {
//                    $revenues = Revenue::where('invoice_id', $sale->id)->get();
//                    foreach ($revenues as $revenue) {
//                        $revenue->amount_applied = 0;
//                        $revenue->update();
//                    }
//                }
                #endregion
            }

            #region VERIFICAR SI FEL ESTA ACTIVO EN LA EMPRSA, EVIAR DATOS A INFILE DE SER TRUE
            $doc_serie = Serie::find($request->serie_id);
            $details = json_decode(json_encode($details));
            $nombreDeTransaccion = $doc_serie->document->name.' '.$doc_serie->name.'-'.$nc_header->correlative;
            if (isset($empresa->fel)&&$empresa->fel && $doc_serie->document->type_fel !='' && $sale->api_uuid!=''){
                $fel_response = INFILE::certifyNcre($header, $receiver, $details, $sale,$motivo,
                    $empresa->fel_username, $empresa->fel_cert, $empresa->fel_firm, $nombreDeTransaccion);
                $json_fel = (json_decode(json_encode($fel_response)));
                if ($json_fel->resultado == false){
                    throw new \Exception("Error certificando documento: ".json_encode($json_fel->descripcion_errores));
                }
                else{
                    $nc_header->api_info = $json_fel->informacion_adicional;
                    $nc_header->api_uuid = $json_fel->uuid;
                    $nc_header->api_serie = $json_fel->serie;
                    $nc_header->api_fecha = $json_fel->fecha;
                    $nc_header->xml_certificado = $json_fel->xml_certificado;
                    $nc_header->api_numero = $json_fel->numero;
                    $nc_header->update();
                }
            }
            #endregion

            Session::flash('message', trans('credit_notes.save_ok'));
            Session::flash('alert-type', trans('success'));
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
//            dd($ex->getMessage() . ': ' . $ex->getLine() . ' ' . $ex->getFile());
            Session::flash('message', trans('credit_notes.save_error') . $ex->getMessage());
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        }

        return Redirect::to('credit_note/' . $nc_header->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $credit_note = CreditNote::find($id);
        $details = CreditNoteDetail::where('credit_note_id', $id)->get();
        $parameters = Parameter::first();
        $letras = NumeroALetras::convertir($credit_note->amount, 'quetzales', 'centavos');
        $precio_letras = ucfirst(strtolower($letras));
        return view('documents.credit_note')
            ->with('details', $details)
            ->with('precio_letras', $precio_letras)
            ->with('parameters', $parameters)
            ->with('credit_note', $credit_note);
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
        DB::beginTransaction();
        try {
            $empresa = Parameter::first();
            $creditNote = CreditNote::find($id);
            $nombreDocument = $creditNote->serie->document->name.' '.$creditNote->serie->name.'-'.$creditNote->correlative;
            $creditNote = $this->applyAnul($creditNote);
            $creditNote->update();
            if ($creditNote->api_uuid!=''){
                $json_fel = INFILE::certifyAnul($creditNote->api_uuid, $creditNote->api_fecha, $empresa->nit, str_replace("/", "",$creditNote->customer->nit_customer), date('Y-m-d\TH:i:sP'),
                    'Anulación nota de crédito '.$nombreDocument, $empresa->fel_username,
                    $empresa->fel_cert, $empresa->fel_firm, $nombreDocument);
                $json_fel = (json_decode(json_encode($json_fel)));
                if ($json_fel->resultado == false){
                    throw new \Exception("Error certificando anulación: ".json_encode($json_fel->descripcion_errores));
                }
                else{
                    $creditNote->fecha_anulacion = $json_fel->fecha;
                    $creditNote->update();
                }
            }
            DB::commit();
            Session::flash('message', trans('credit_notes.anul_ok'));
            Session::flash('alert-type', trans('success'));
        }
        catch(\Exception $e){
            DB::rollback();
            Session::flash('message', trans('credit_notes.save_error') . $e->getMessage());
            Session::flash('alert-class', 'alert-error');
        }
        return Redirect::to('credit_note');
    }
}
