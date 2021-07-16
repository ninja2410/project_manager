<?php

namespace App\Http\Controllers;

use App\Revenue;
use Illuminate\Http\Request;
use App\Serie;
use App\Document;
use App\Sale;
use App\StateCellar;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriesRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;


class SerieController extends Controller
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
        $series = Serie::where('status', '=', 1)->get();
        return view('serie.index')
            ->with('series', $series);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $document=Document::lists('name','id');
        $document = Document::where('status', '=', 1)->get();
        $state_cellar = StateCellar::lists('name', 'id');
        return view('serie.create')
            ->with('document', $document)
            ->with('state_cellar', $state_cellar);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->credit)) {
            $flagCredit = 1;
        } else {
            $flagCredit = 0;
        }
        $newSerie = new Serie;
        $serieDocument = Input::get('name_sign');
        $idDocumento = Input::get('document_id');
        $verificacion = Serie::where('name', '=', $serieDocument)
            ->where('id_document', '=', $idDocumento)->value('id');
        if ($verificacion != 0) {
            Session::flash('message', 'Documento y serie existentes');
            Session::flash('alert-class', 'alert-error');
            return Redirect::back()->withInput();
        } else {
            $newSerie->name = $serieDocument;
            $newSerie->id_document = $idDocumento;
            $newSerie->id_state = Input::get('id_state');
            if ($request->radio == 1) {
                $newSerie->proforma = 1;
            }
            $newSerie->credit = 0;
            $newSerie->save();
            Session::flash('message', 'Insertado correctamente');
            return Redirect::to('series');
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
        $series = Serie::find($id);
        $state_cellar = StateCellar::lists('name', 'id');
        $document = Document::all();
        return view('serie.edit')
            ->with('series', $series)
            ->with('state_cellar', $state_cellar)
            ->with('document', $document);
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
        $updSerie = Serie::find($id);
        $updSerie->name = Input::get('name_sign');
        $updSerie->id_document = Input::get('document_id');
        $updSerie->id_state = Input::get('id_state');
        $updSerie->proforma = $request->radio;
        $updSerie->update();
        Session::flash('message', 'Actualizado correctamente');
        return Redirect::to('series');
    }

    public function verify(Request $request)
    {
        $doc = Document::find($request->document);
        if ($doc->sign == '-') {
            $rsp = 1;
        } else {
            $rsp = 0;
        }
        return $rsp;
    }

    public function verify_number(Request $request)
    {
        $doc = Document::find($request->document);
        if ($doc->sign == '-') {
            //BUSCAR ALGUN MOVIMIENTO CON ESTA SERIE
            $transactions = Sale::where('id_serie', $request->serie_id)->count();
            if ($transactions == 0) {
                $rsp = 1;
            } else {
                $rsp = 0;
            }
        } else {
            $rsp = 0;
        }
        return $rsp;
    }

    /**
         * VERIFICAR VALIDEZ DE SERIE Y NÚMERO DE RECIBO DE CAJA
     * @param $serie
     * @param $no
     */
    public function verify_serie_number($serie, $no){
        $data = Revenue::where('serie_id', $serie)
            ->where('receipt_number', $no)
            ->count();
        $last = Revenue::where('serie_id', $serie)
            ->orderby('receipt_number', 'desc')
            ->first();
        if (isset($last)){
            $recomend = $last->receipt_number +1;
        }
        else{
            $recomend = 1;
        }
        $array = Array("counter" =>$data,
            "recomend"=>$recomend);
        return json_encode($array);
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
            $newSerie = Serie::find($id);
            $newSerie->status = 0;
            $newSerie->update();
            // redirect
            Session::flash('message', 'Eliminado correctamente');
            return Redirect::to('series');
        } catch (\Illuminate\Database\QueryException $e) {
            Session::flash('message', 'Serie utilizada en al menos un documento: No se puede eliminar');
            Session::flash('alert-class', 'alert-error');
            return Redirect::to('series');
        }
    }
}
