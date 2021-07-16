<?php

namespace App\Http\Controllers;

use App\BodegaProducto;
use App\Http\Controllers\Controller;
use App\Receiving;
use App\SaleTemp;
use Illuminate\Http\Request;
use \Redirect;
use \Auth;
use \Input;
use App\Almacen;
use App\Customer;
use App\Document;
use App\Item;
use App\ItemKitItem;
use App\Pago;
use App\Sale;
use App\SaleItem;
use App\Serie;


class BodegaProductoController extends Controller
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
        echo 'hola que hace';
        // $bodegas=BodegaProducto::all();
        // dd($bodegas);

        $item_id         = 1;
        $id_bodega       = 1;
        $valorEncontrado = BodegaProducto::where('id_product', '=', $item_id)
            ->where('id_bodega', '=', $id_bodega)
            ->value('id');
        $existencias = BodegaProducto::find($valorEncontrado);

        return $existencias->quantity;
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
        $ingreso = new Receiving;

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

    public function borrarVentaTemporal($idDocumento, $id, $idCliente, $numCorrelative,$tipoPago)
    //public function borrarVentaTemporal($id)
    {

      echo "idDocumento:".$idDocumento." <br/>";
      echo "id_bodega: ".$id."<br/>";
      echo "idCliente: ".$idCliente."<br/>";
      echo "numCorrelative: ".$numCorrelative." <br/>";
      echo "tipoPago: .$tipoPago.<br/>";

        SaleTemp::truncate();

        //return redirect()->action('SaleController@nuevoControlador', ['id' => $id]);
        return Redirect::to('sales?id=' . $id );
    }
}
