<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;
use App\Holiday;
class CalendarController extends Controller
{
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

    public function index()
    {
        $holidays_date=Holiday::all();
        foreach ($holidays_date as $day) {
          if ($day->reply==1) {
            //EXTENDER POR 20 AÑOS
            for ($i=1; $i <= 20; $i++) {
              $tmp=new Holiday();
              $fecha=date('Y-m-d', strtotime($day->holidays_date));
              $nueva=strtotime('+'.$i.' year', strtotime($fecha));
              $tada=date('Y-m-d', $nueva);
              $tmp->holidays_date=$tada;
              $tmp->name_day=$day->name_day;
              $tmp->reply=$day->reply;
              $holidays_date->push($tmp);

              $tmp2=new Holiday();
              $fecha=date('Y-m-d', strtotime($day->holidays_date));
              $nueva=strtotime('-'.$i.' year', strtotime($fecha));
              $tada=date('Y-m-d', $nueva);
              $tmp2->holidays_date=$tada;
              $tmp2->name_day=$day->name_day;
              $tmp2->reply=$day->reply;
              $holidays_date->push($tmp2);
            }
          }
        }
        return view('calendar.index',['holidays_date'=>$holidays_date]);
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
        $reply=Input::get('reply');
        $type_action=Input::get('type_action');
        $dateReceiving=Input::get('dateReceiving');
        $dateArray=explode("-", $dateReceiving);
        $nDay=$dateArray[2];
        $nMounth=$dateArray[1];
        $nYear=$dateArray[0];
        if($type_action=='insert'){
          $new_holiday=new Holiday();
          $new_holiday->reply=$reply;
          $new_holiday->holidays_date=$dateReceiving;
          $new_holiday->name_day='No permite pago';
          $new_holiday->save();
        }else {
          if ($reply==1) {
            $days=Holiday::all();
            foreach ($days as $key => $value) {
              if ($value->reply==1) {
                for ($i=-20; $i < 20; $i++) {
                  $fechadb=date('Y-m-d', strtotime($value->holidays_date));
                  $date=strtotime('+'.$i.' year', strtotime($dateReceiving));
                  $dt=date('Y-m-d', $date);
                  if ($fechadb==$dt) {
                    $value->delete();
                  }
                }
              }
            }
          }
          else{
            $delete=Holiday::where('holidays_date',$dateReceiving)->value('id');
            $deleteElement=Holiday::find($delete);
            $deleteElement->delete();
          }

          //$delete=Holiday::where('holidays_date', 'like', '%'.$d)->value('id');
          //$delete=Holiday::where('holidays_date', 'like', '%'.$d)->value('id');
        }
        return $dataReceiving;
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
