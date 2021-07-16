<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Document;
use App\StateCellar;
use App\Http\Requests\DocumentRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use DB;

class DocumentController extends Controller
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
        $dataDocumento=Document::where('status','=',1)->get();
        // $series=Serie::where('status','=',1)->get();
        return view('document.index')
        ->with('dataDocumento',$dataDocumento);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $state_cellar=StateCellar::general()->lists('name','id');
      return view('document.create')
      ->with('state_cellar',$state_cellar);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentRequest $request)
    {
        $dataDocumento=new Document;
        $dataDocumento->name=Input::get('name');
        $dataDocumento->sign=Input::get('sign');
        $dataDocumento->id_state=Input::get('id_state');
        $dataDocumento->type_fel = Input::get('type_fel');
        $dataDocumento->save();
        Session::flash('message','Documento insertado correctamente');
        return Redirect::to('documents');
    }

    public function verifyTypeFel(){
        $tmp = Input::get('document_id');
        if (isset($tmp)){
            $revs = Document::where('type_fel', Input::get('type_fel'))
                ->whereId_state(1)
                ->where('id', '!=', Input::get('document_id'))
                ->count();
        }
        else{
            $revs = Document::where('type_fel', Input::get('type_fel'))
                ->whereId_state(1)
                ->count();
        }

        if ($revs > 0){
            $isAvailable= false;
        }
        else{
            $isAvailable= true;
        }
        return json_encode(array('valid' => $isAvailable));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $dataDocumento=Document::find($id);
      $state_cellar=StateCellar::general()->lists('name','id');
      return view('document.edit')
      ->with('state_cellar',$state_cellar)
      ->with('dataDocumento', $dataDocumento);
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
      $documentUpdate=Document::find($id);
      $name=Input::get('name_document');
      $sign=Input::get('sign_document');
      $exists=Document::where('name','=',$name)
        ->where('sign','=',$sign)
        ->whereNotIn('id',[$id])->get();



      if(count($exists)==0)
      {
        $documentUpdate->name=$name;
        $documentUpdate->sign=$sign;
        $documentUpdate->id_state=Input::get('id_state');
        $documentUpdate->type_fel = Input::get('type_fel');
        $documentUpdate->save();
        Session::flash('message','Documento actualizado correctamente');
        return Redirect::to('documents');
      }
      else
      {
        Session::flash('message','Documento ya existe');
        Session::flash('alert-class', 'alert-error');
          return Redirect::back()->withInput();
      }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      try
      {
        $document = Document::find($id);
            $document->status=0;
            $document->save();
            // redirect
            Session::flash('message', 'Eliminado correctamente');
            return Redirect::to('documents');
          }
        catch(\Illuminate\Database\QueryException $e)
      {
          Session::flash('message', 'Violación de restricciones de integridad: No se puede eliminar una fila principal');
          Session::flash('alert-class', 'alert-error');
            return Redirect::to('documents');
        }
    }
}
