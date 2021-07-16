<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ReceivingTemp;
use App\Item, App\ItemKitItem;
use DB, \Auth, \Redirect, \Validator, \Input, \Session, \Response;
use Illuminate\Http\Request;

class ReceivingTempApiController extends Controller {

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
		return Response::json(ReceivingTemp::with('item')->get());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('receiving.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// cek item_id sudah ada atau belum, jika sudah ada update quantity,
		// jika belum ada tambahkan data (info@mytuta.com)
	//	$result_item_id = ReceivingTemp::where('item_id', '=', Input::get('item_id'))->first();
		//	if ($result_item_id === null) {

				$this->newItem();


			//			}
			//		else
			//	{
						/* $ReceivingTemps = ReceivingTemp::find($result_item_id->item_id);
						$ReceivingTemps->quantity = 5;
						$ReceivingTemps->total_cost = 54;
						$ReceivingTemps->save();
						return $ReceivingTemps; */
			//		echo "warik";
			//	$this->updateItem();
			//	}
	}
public function updateItem()
{
	$ReceivingTemps = ReceivingTemp::find(3);
	$ReceivingTemps->quantity = 5;
	$ReceivingTemps->total_cost = 54;
	$ReceivingTemps->save();
	return $ReceivingTemps;
}
///aca es donde hace la insercion del nuevo valor
public function newItem()
{

	$type = Input::get('type')!=""?Input::get('type'):1;
		$ReceivingTemps = new ReceivingTemp;
		$idRecibido=Input::get('item_id');
		$valorEncontrado=ReceivingTemp::where('item_id','=',$idRecibido)->value('id');
		
		if($valorEncontrado!=0){
			$UpdateReceiving=ReceivingTemp::find($valorEncontrado);
			$UpdateReceiving->quantity=$UpdateReceiving->quantity+1;
			$valorCompra=Input::get('cost_price');
			$UpdateReceiving->total_cost=$UpdateReceiving->total_cost+$valorCompra;
			$UpdateReceiving->save();
			return $UpdateReceiving;
		}
		else{
			$ReceivingTemps->item_id = $idRecibido;
			$ReceivingTemps->cost_price = Input::get('cost_price');
			$ReceivingTemps->total_cost = Input::get('total_cost');
			$ReceivingTemps->quantity = 1;
			$ReceivingTemps->last_cost=Input::get('last_cost');
			$ReceivingTemps->save();
			return $ReceivingTemps;
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
		$cantidad=Input::get('quantity');
		$costo=Input::get('cost_price');
		if (Input::get('pr_price')=="") {
			$costo_prorrateo=Input::get('pr_price');
		}
		else {
			$costo_prorrateo=$costo;
		}
		$ReceivingTemps = ReceivingTemp::find($id);

        $ReceivingTemps->quantity = $cantidad;
				$ReceivingTemps->cost_price=$costo;
				$ReceivingTemps->pr_price=$costo_prorrateo;
        $ReceivingTemps->total_cost = $cantidad*$costo;
				// Input::get('total_cost');
        $ReceivingTemps->save();
        return $ReceivingTemps;
	}

	public function addPror($id, $price){
		$tmp=ReceivingTemp::find($id);
		$tmp->pr_price=$price;
		$tmp->update();
		return $tmp;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		ReceivingTemp::destroy($id);
	}

}
