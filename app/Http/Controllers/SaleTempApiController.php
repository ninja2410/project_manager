<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SaleTemp;
use App\Item;
use App\BodegaProducto;
use \Auth, \Redirect, \Validator, \Input, \Session, \Response;
use Illuminate\Http\Request;

class SaleTempApiController extends Controller {

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
		return Response::json(SaleTemp::with('item')->get());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('sale.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$SaleTemps = new SaleTemp;
		$idRecibido=Input::get('item_id');
		$valorEncontrado=SaleTemp::where('item_id','=',$idRecibido)
			->value('id');

			if($valorEncontrado!=0){
				$UpdateTemps = SaleTemp::find($valorEncontrado);
						$UpdateTemps->quantity = $UpdateTemps->quantity+1;
						$AntesdeInsertar=$UpdateTemps->quantity;
						if($AntesdeInsertar<=$UpdateTemps->cellar_quantity){
							$valorVenta=Input::get('selling_price');
							$valorCompra=Input::get('cost_price');
							$UpdateTemps->total_cost=$UpdateTemps->total_cost+$valorCompra;
							$UpdateTemps->total_selling =$UpdateTemps->total_selling + $valorVenta;
							// $UpdateTemps->low_price=Input::get('low_price');
							$UpdateTemps->save();
							return $UpdateTemps;
						}
						// $UpdateTemps->total_cost = Input::get('total_cost');
			}else {
				$SaleTemps->item_id = $idRecibido;
				$SaleTemps->cost_price = Input::get('cost_price');
		    $SaleTemps->selling_price = Input::get('selling_price');
				$SaleTemps->quantity = 1;
				$SaleTemps->total_cost = Input::get('cost_price');
		    $SaleTemps->total_selling = Input::get('selling_price');
				$SaleTemps->id_bodega=Input::get('id_bodega');
				$SaleTemps->cellar_quantity=Input::get('cellar_quantity');
				$SaleTemps->low_price=Input::get('low_price');
				$SaleTemps->save();
				return $SaleTemps;
			}
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
		$bandera=Input::get('bandera');
		$SaleTemps = SaleTemp::find($id);
		if($bandera==0)
		{
				$Existencias=$SaleTemps->cellar_quantity;
				$ExistenciasNuevas=Input::get('quantity');
				if($ExistenciasNuevas<=$Existencias){
					$SaleTemps->quantity = $ExistenciasNuevas;
					$SaleTemps->total_cost = Input::get('total_cost');
					$SaleTemps->total_selling = Input::get('total_selling');

					$SaleTemps->save();
					return $SaleTemps;
				}
		}

		else
		{
			$SaleTemps->total_selling = Input::get('total_selling');
			$SaleTemps->low_price=Input::get('low_price');
			$SaleTemps->save();
			return $SaleTemps;
		}

		}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		SaleTemp::destroy($id);
	}

}
