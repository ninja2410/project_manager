<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MoneyType;
use \DB,\Response,\Auth,\Input;
use App\DeskClosing;
use App\DeskClosingDetail;
use App\Sale;
use App\Revenue;
use App\Payment;
use Carbon\Carbon;
use App\Account;
use App\Pago;
use App\Serie;
use App\GeneralParameter;
use App\Transfer;
use App\Traits\TransactionsTrait;
class DeskClosingController extends Controller
{
    use TransactionsTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('parameter');
    }
    public function index($id)
    {
        $fecha1=Input::get('date1');
		$fecha2=Input::get('date2');

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
        $desk=DeskClosing::join('bank_accounts','bank_accounts.id','=','desk_closings.account_id')
        ->select('desk_closings.id','bank_accounts.account_name','desk_closings.startDate','desk_closings.finalDate',
        'desk_closings.cash_amount','desk_closings.deposit_amount','desk_closings.check_amount',
        'desk_closings.transfer_amount','desk_closings.card_amount','desk_closings.total')
        ->where('bank_accounts.id',$id)
        ->whereBetween('desk_closings.finalDate',[$fecha1,$fecha2])
        ->get();
        return view('desk_closing.index')
            ->with('idCaja',$id)
            ->with('desk',$desk)
            ->with('fecha1',$fecha1)
		    ->with('fecha2',$fecha2);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('desk_closing.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = array();
        // VERIFICAR SI EXISTE EL CORRELATIVO
        if(count(DeskClosing::where('serie_id',$request->serie_id)->where('correlative',$request->correlative)->get())>0){
            array_push($error,'El correlativo: '.$request->correlative. ' ya esta utilizado en un documento de la serie seleccionada.');
        }
        DB::beginTransaction();
        try{
            if(!empty($error)){
                $returnData=array(
                    'status' => 'error',
                    'message' => $error
                );
                DB::rollback();
                return Response::json($returnData, 500);
            }
            
            $Desk=new DeskClosing;
            $Desk->account_id=$request->account_id;
            $Desk->created_by=Auth::user()->id;
            $Desk->updated_by=Auth::user()->id;
            $Desk->correlative=$request->correlative;
            $Desk->serie_id=$request->serie_id;
            $Desk->startDate=$request->dates['finalDate'];;
            $Desk->finalDate=$request->startDate;
            $Desk->cash_amount=$request->cash_amount;
            $Desk->check_amount=$request->check_amount;
            $Desk->deposit_amount=$request->deposit_amount;
            $Desk->transfer_amount=$request->transfer_amount;
            $Desk->card_amount=$request->card_amount;
            $Desk->total=$request->total;
            $Desk->status_id=$request->status_id;
            $Desk->initial_balance=$request->dates['final_balance'];
            $Desk->final_balance=$request->total;
            $Desk->save();
            if($request->flag==true)
                foreach ($request->billetes as $billete) {
                    $item=new DeskClosingDetail;
                    $item->desk_closing_id=$Desk->id;
                    $item->payment_type_id=1;
                    $item->money_quanity=$billete['quantity'];
                    $item->money_type_quantity_id=$billete['id'];
                    $item->amount=$billete['value']*$billete['quantity'];
                    $item->save();
                }

            foreach ($request->revenue as $item) {
                if($item['selected'])
                {
                    $revenue=new DeskClosingDetail;
                    $revenue->desk_closing_id=$Desk->id;
                    $revenue->payment_type_id=$item['pm'];
                    $revenue->amount=$item['amount'];
                    if($item['tipo']=='Ingreso')
                        $revenue->revenue_id=$item['id'];
                    else    
                        $revenue->payment_id=$item['id'];
                    $revenue->save();
                }
                else
                    continue;
            }
            // INSERTAR TRANSACCIONES
            $fechaX= explode(" ",$request->dateX);
            foreach ($request->transfers as $item) {
            
                $new_expense = [];
                $new_expense['account_id'] = $item['account_id'];
                $new_expense['paid_at'] = $fechaX[0];
                $new_expense['amount'] = $item['amount'];
                $new_expense['description'] = $item['description'];
                if(!empty($item['category_id']))
                    $new_expense['category_id'] = $item['category_id'];
                else
                    $new_expense['category_id']=1;
                $new_expense['reference'] = $item['reference'];
                $new_expense['user_id'] = Auth::user()->id;
                $new_expense['status'] = 1;
                $new_expense['payment_method'] = $item['pm'];
                $new_expense['same_bank'] = 0;
                $new_expense['cash_register_id']=$Desk->id;
                $request_expense = new Request($new_expense);
                

                $new_revenue = [];
                $new_revenue['account_id'] = $item['bancos']['id'];
                $new_revenue['paid_at'] = $fechaX[0];
                $new_revenue['amount'] = $item['amount'];
                $new_revenue['description'] = $item['description'];
                if(!empty($item['bancos']['category_id']))
                    $new_expense['category_id'] = $item['bancos']['category_id'];
                else
                    $new_expense['category_id']=1;    
                $new_revenue['reference'] = $item['reference'];
                $new_revenue['user_id'] = Auth::user()->id;
                $new_revenue['status'] = 1;
                $new_revenue['payment_method'] = $item['pm'];
                $new_revenue['same_bank'] = 0;

                $request_revenue = new Request($new_revenue);
                
                $transfer = $this->saveTransfer($request_revenue, $request_expense);
            }
            // FIN DE TRANSACCIONES
            
            
            DB::commit();
            $returnData = array(
                'status' => 'success',
                'message' => array('Nuevo cierre agregado correctamente',$Desk->id)
            );
            return Response::json($returnData, 200);
        }catch(\Exception $e){
            DB::rollback();
          $returnData = array(
            'status' => 'error',
            'message' => array($e->getMessage())
          );
          return Response::json($returnData, 500);
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
        
        $desk=DeskClosing::join('bank_accounts','bank_accounts.id','=','desk_closings.account_id')
        ->join('series','series.id','=','desk_closings.serie_id')
        ->join('documents','documents.id','=','series.id_document')
        ->where('desk_closings.id',$id)->first();
        
        $acc_p = Account::leftJoin('bank_tx_payments as p', 'bank_accounts.id', '=', 'p.account_id')
            ->join('pagos', 'p.payment_method', '=', 'pagos.id')
            ->join('users', 'users.id', '=', 'p.user_id')
            ->join('state_cellars as st', 'st.id', '=', 'p.status')
            ->whereRaw("(p.created_at > ? AND p.created_at <= ?)", [$desk->startDate, $desk->finalDate])
            ->where('p.user_id',$desk->created_by)
            ->where('bank_accounts.id',$desk->account_id)
            ->select('st.name as status',DB::raw('"" as card_name'),DB::raw('COALESCE(p.card_number,"") as card_number'),DB::raw('"" as bank_name'),'p.id','p.paid_at', 'p.amount', 'p.payment_method as pm', 'bank_accounts.account_name', 'p.description', 'p.reference', 'users.name as user', 'pagos.name as payment_method','p.created_at', DB::raw("'Gasto' as tipo"),DB::raw("0.0 as value"),DB::raw("0 as selected"));
                                  
        $accounts = Account::leftJoin('bank_tx_revenues as p', 'bank_accounts.id', '=', 'p.account_id')
            ->join('pagos', 'p.payment_method', '=', 'pagos.id')
            ->join('users', 'users.id', '=', 'p.user_id')
            ->join('state_cellars as st', 'st.id', '=', 'p.status')
            ->whereRaw("(p.created_at > ? AND p.created_at <= ?)", [$desk->startDate, $desk->finalDate])
            ->where('p.user_id',$desk->created_by)
            ->select('st.name as status',DB::raw('COALESCE(p.card_name,"") as card_name'),DB::raw('COALESCE(p.card_number,"") as card_number'),DB::raw('COALESCE(p.bank_name,"") as bank_name'),'p.id', 'p.paid_at', DB::raw('ROUND(p.amount,2) as amount'), 'p.payment_method as pm', 'bank_accounts.account_name', 'p.description', 'p.reference', 'users.name as user', 'pagos.name as payment_method','p.created_at' , DB::raw("'Ingreso' as tipo"),DB::raw("0.0 as value"),DB::raw("0 as selected"))
            ->union($acc_p)
            ->orderby('created_at','asc')
            ->orderby('id','desc')
            ->get();
        
        $efectivo=DeskClosingDetail::join('money_types','money_types.id','=','desk_closing_details.money_type_quantity_id')
            ->where('desk_closing_id',$id)
            ->where('payment_type_id',1)
            ->where('desk_closing_details.money_quanity','>','0')
            ->select('money_types.name','desk_closing_details.amount','desk_closing_details.money_quanity')
            ->get();
        $cheque=DeskClosingDetail::join('bank_tx_revenues','bank_tx_revenues.id','=','desk_closing_details.revenue_id')
            ->where('desk_closing_id',$id)
            ->where('payment_type_id',2)
            ->select('bank_tx_revenues.description','bank_tx_revenues.reference','bank_tx_revenues.bank_name','bank_tx_revenues.amount')
            ->get();
        $deposito=DeskClosingDetail::join('bank_tx_revenues','bank_tx_revenues.id','=','desk_closing_details.revenue_id')
            ->where('desk_closing_id',$id)
            ->where('payment_type_id',3)
            ->select('bank_tx_revenues.description','bank_tx_revenues.reference','bank_tx_revenues.bank_name','bank_tx_revenues.amount')
            ->get();
        $tarjeta=DeskClosingDetail::join('bank_tx_revenues','bank_tx_revenues.id','=','desk_closing_details.revenue_id')
            ->where('desk_closing_id',$id)
            ->where('payment_type_id',4)
            ->select('bank_tx_revenues.description','bank_tx_revenues.reference','bank_tx_revenues.bank_name','bank_tx_revenues.amount','bank_tx_revenues.card_name','bank_tx_revenues.card_number')
            ->get();
        $transferencia=DeskClosingDetail::join('bank_tx_revenues','bank_tx_revenues.id','=','desk_closing_details.revenue_id')
            ->where('desk_closing_id',$id)
            ->where('payment_type_id',5)
            ->select('bank_tx_revenues.description','bank_tx_revenues.reference','bank_tx_revenues.bank_name','bank_tx_revenues.amount')
            ->get();
        
        $movimiento=Transfer::join('bank_tx_payments','bank_tx_payments.id','=','bank_tx_transfers.payment_id')
        ->join('bank_tx_revenues','bank_tx_revenues.id','=','bank_tx_transfers.revenue_id')
        ->join('bank_accounts','bank_accounts.id','=','bank_tx_revenues.account_id')
        ->where('bank_tx_payments.cash_register_id',$id)
        ->select('bank_tx_payments.description','bank_tx_payments.amount','bank_tx_payments.reference','bank_accounts.account_name')
        ->get();
        
        $user=DeskClosing::join('users','users.id','=','desk_closings.created_by')->select('name')->first();
        return view('desk_closing.show')
            ->with('desk',$desk)
            ->with('efectivo',$efectivo)
            ->with('cheque',$cheque)
            ->with('deposito',$deposito)
            ->with('tarjeta',$tarjeta)
            ->with('transferencia',$transferencia)
            ->with('movimiento',$movimiento)
            ->with('usuario',$user)
            ->with('cuentas',$accounts);
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
    //FUNCIONES PARA API
    public function getCaja($id)
    {
        $desk=Account::find($id);
        $desk->updated_at=Carbon::now();
        $desk->created_at=$desk->updated_at;
        $parametro=GeneralParameter::where('type','Cierre de caja')->where('name','Tipos de pago no coinciden.')->select('id','active')->get();
        $data=compact(['desk','parametro']);
        return $data;
    }
    public function getDocuments()
    {
        return Serie::join('documents','documents.id','=','series.id_document')->where('documents.name','Cierre de caja')->where('series.id_state',1)->select(DB::raw('CONCAT(documents.name," ",series.name) as name'),'series.id')->get();
    }
    public function selectCorrelative($id){
        $correlativo=DeskClosing::where('serie_id',$id)->orderBy('correlative','desc')->select(DB::raw('COALESCE(correlative,0)+1 as correlative'))->first();
        if($correlativo)
          return $correlativo;
        else
          return Response::json(['correlative'=>'1']);
      
      }
    public function getBillete()
    {
        return MoneyType::select('id','name','value',DB::raw('0 as quantity'))->where('status_id','1')->orderBy('value','desc')->get();
    }
    public function getSales(Request $request)
    {
        $ingreso=0;
        $gasto=0;
        $saldo=0;
        $Desk=null;
        $lastClosing=DeskClosing::max('id');
        $pagos=Pago::where('name','!=','Crédito')->select('pagos.id','pagos.name',DB::raw('0.00 as value'),DB::raw('0.00 as diferencia'))->get();
    
        if(!empty($lastClosing))
            $Desk=DeskClosing::select('id','created_at as finalDate','final_balance')->find($lastClosing);
        else
            $Desk=Account::select('id','created_at as finalDate',DB::raw('0 as final_balance'))->find($request->caja);

        $usuario=Account::where('id',$request->caja)->select('account_responsible')->first();

        $acc_p = Account::leftJoin('bank_tx_payments as p', 'bank_accounts.id', '=', 'p.account_id')
            ->join('pagos', 'p.payment_method', '=', 'pagos.id')
            ->join('users', 'users.id', '=', 'p.user_id')
            ->join('state_cellars as st', 'st.id', '=', 'p.status')
            ->whereRaw("(p.created_at > ? AND p.created_at <= ?)", [$Desk->finalDate, $request->date])
            ->where('p.user_id',$usuario->account_responsible)
            ->where('bank_accounts.id',$request->caja)
            ->select('st.name as status',DB::raw('"" as card_name'),DB::raw('COALESCE(p.card_number,"") as card_number'),DB::raw('"" as bank_name'),'p.id','p.paid_at', 'p.amount', 'p.payment_method as pm', 'bank_accounts.account_name', 'p.description', 'p.reference', 'users.name as user', 'pagos.name as payment_method','p.created_at', DB::raw("'Gasto' as tipo"),DB::raw("0.0 as value"),DB::raw("0 as selected"));
                                  
            $acc_r = Account::leftJoin('bank_tx_revenues as p', 'bank_accounts.id', '=', 'p.account_id')
            ->join('pagos', 'p.payment_method', '=', 'pagos.id')
            ->join('users', 'users.id', '=', 'p.user_id')
            ->join('state_cellars as st', 'st.id', '=', 'p.status')
            ->whereRaw("(p.created_at > ? AND p.created_at <= ?)", [$Desk->finalDate, $request->date])
            ->where('p.user_id',$usuario->account_responsible)
            ->where('bank_accounts.id','<>',$request->caja)
            ->select('st.name as status',DB::raw('COALESCE(p.card_name,"") as card_name'),DB::raw('COALESCE(p.card_number,"") as card_number'),DB::raw('COALESCE(p.bank_name,"") as bank_name'),'p.id', 'p.paid_at', DB::raw('ROUND(p.amount,2) as amount'), 'p.payment_method as pm', 'bank_accounts.account_name', 'p.description', 'p.reference', 'users.name as user', 'pagos.name as payment_method','p.created_at' , DB::raw("'Ingreso' as tipo"),DB::raw("0.0 as value"),DB::raw("0 as selected"));

            $acc_r2 = Account::leftJoin('bank_tx_revenues as p', 'bank_accounts.id', '=', 'p.account_id')
            ->join('pagos', 'p.payment_method', '=', 'pagos.id')
            ->join('users', 'users.id', '=', 'p.user_id')
            ->join('state_cellars as st', 'st.id', '=', 'p.status')
            ->whereRaw("(p.created_at > ? AND p.created_at <= ?)", [$Desk->finalDate, $request->date])
            ->where('bank_accounts.id',$request->caja)
            ->select('st.name as status',DB::raw('COALESCE(p.card_name,"") as card_name'),DB::raw('COALESCE(p.card_number,"") as card_number'),DB::raw('COALESCE(p.bank_name,"") as bank_name'),'p.id', 'p.paid_at', DB::raw('ROUND(p.amount,2) as amount'), 'p.payment_method as pm', 'bank_accounts.account_name', 'p.description', 'p.reference', 'users.name as user', 'pagos.name as payment_method','p.created_at' , DB::raw("'Ingreso' as tipo"),DB::raw("0.0 as value"),DB::raw("0 as selected"));
            

            $accounts = $acc_r
                ->unionAll($acc_r2)->unionAll($acc_p)
                ->orderby('created_at','asc')
                ->orderby('id','desc')
                ->get();
                


        foreach($accounts as $item){
            if($pagos->contains('id',$item->pm))
            {
                $pago=$pagos->find($item->pm);
                if($item->tipo=='Ingreso'&&$item->status!='Inactivo')
                {
                    $pago->value+=$item->amount;
                    $ingreso+=$item->amount;
                }
                else if($item->tipo=='Gasto'&&$item->status!='Inactivo')
                {
                    $pago->value-=$item->amount;
                    $gasto+=$item->amount;
                }
            }
        }
        $saldo=$ingreso-$gasto;
        $data=compact(['accounts','pagos','gasto','ingreso','saldo','Desk']);
        return Response::json($data);
    }
    public function getAccounts(Request $request){
        return Response::json(Account::whereNotNull('bank_id')->get());
    }
}
