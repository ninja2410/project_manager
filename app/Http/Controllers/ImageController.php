<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Images;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Image;
use Response;
class ImageController extends Controller
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
      //
    }

    public function indexCustomer($id){
      $images=Images::where('customer_id', $id)->get();
      return view('customer.gallery')
      ->with('customer', $id)
      ->with('images', $images);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status=StateCellar::all();
        return view('images.create')
        ->with('status', $status);
    }
    public function createCustomer($customer){
      return view('customer.createGallery')
      ->with('customer', $customer);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {
        $image=new Images();
        $image->project_id=$request->project_id;
        $image->stage_id=$request->stage_id;
        $image->save();
        $file = $request->file('file');
    	$image->original_name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $directory = base_path() .'/public/images/project';
        $filename = "File_".$request->customer."_".$image->id."."."{$extension}";
        $image->path=$filename;
        $image->update();
        $upload_success = Input::file('file')->move($directory, $filename);
      } catch (\Exception $e) {
        $image->delete();
        return Response::json('error: '.$e->getMessage(), 400);
      }
      if( $upload_success ) {
      	return Response::json('success', 200);
      } else {
      	return Response::json('error', 400);
      }
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
        try {
            $info=Images::find($id);
            if(\File::exists(public_path('images/project/'.$info->path))){
                \File::delete(public_path('images/project/'.$info->path));
            }else{
                Session::flash('message', 'Archivo no encontrado, el registro se ha eliminado');
                Session::flash('alert-class', 'alert-warning');
            }
            Images::destroy($id);
            Session::flash('message', 'Se ha eliminado el archivo correctamente.');
        }
        catch(\Exception $ex){
            Session::flash('message', $ex->getMessage());
            Session::flash('alert-class', 'alert-warning');
        }
        return Redirect::back();
    }
}
