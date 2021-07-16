<?php

namespace App\Http\Controllers;

use App\BudgetConfig;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use \Auth, \Validator, \Input, \Session, \Response;

class BudgetConfigController extends Controller
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
        $line_template_config = BudgetConfig::whereType(1)->get();
        return view('budget_config.index')
            ->with('line_template_config', $line_template_config);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $line_template_config = BudgetConfig::whereType(1)->get();
        return view('budget_config.edit')
            ->with('line_template_config', $line_template_config);
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
            $newData = json_decode($request->data);
            foreach ($newData as $data){
                $config = BudgetConfig::find($data->id);
                $config->color = $data->color;
                $config->icon = $data->icon;
                $config->custom_text = $data->custom_text;
                $config->order = $data->order;
                $config->updated_by = Auth::user()->id;
                $config->update();
            }
            Session::flash('message',trans('budget_config.save_ok'));
        }
        catch(\Exception $ex){
            Session::flash('message',trans('budget_config.save_error'). ' | '.$ex->getMessage());
            Session::flash('alert-class', 'alert-error');
        }
        return Redirect::to('budget_config');
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
