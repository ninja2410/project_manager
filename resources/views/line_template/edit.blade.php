@extends('layouts/default')

@section('title',trans('line-template.edit'))
@section('page_parent', trans('line-template.line-templates'))
@section('header_styles')
  <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>
  <link href="{{asset("assets/fontawesome/css/all.css")}}" rel="stylesheet">
  {{--  XEDITABLE  --}}
  <link href="{{ asset('assets/x-editable/bootstrap-editable.css') }}" rel="stylesheet"/>
  {{--    CUSTOM CACAO--}}
  <link href="{{ asset('assets/css/budget/custom.css') }}" rel="stylesheet"/>
@endsection
@section('content')
  <input type="hidden" id="token" value="{{ csrf_token() }}">
  <input type="hidden" id="change_prices" value="{{ $change_prices }}">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
              {{trans('line-template.edit')}}
            </h3>
            <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
          </div>
          <div class="panel-body">

            <div class="row">
              <div class="row">
                {!! Form::model($template, array('url' => 'line-template/'.$template->id, 'id'=>'frmNew', 'method'=>'PUT')) !!}
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      {!! Form::label('lblName', trans('line-template.name'), array("class"=>"control-label")) !!}
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw fa-font"></i></span>
                        {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      {!! Form::label('lblPrice', trans('line-template.price').' ') !!}
                      <div class=" input-group">
                        <span class="input-group-addon"><i class="fa fa-fw  fa-money"></i></span>
                        {!! Form::text('price', Input::old('price'), array('class' => 'form-control', 'id'=>'total_cost_line', 'readonly')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      {!! Form::label('lblItems', trans('line-template.items_quantity'), array('class'=>'control-label')) !!}
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw  fa-bars"></i></span>
                        {!! Form::text('items_quantity', Input::old('items_quantity'), array('class' => 'form-control', 'id'=>'total_qty_line', 'readonly'=>'readonly')) !!}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      {!! Form::label('lblDescription', trans('line-template.description').' ') !!}
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw fa-align-justify"></i></span>
                        {!! Form::text('description', Input::old('description'), array('class' => 'form-control')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      {!! Form::label('size', trans('line-template.category')) !!}
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw fa-tag"></i></span>
                        {!! Form::select('categorie_id', $categories, Input::old('categorie_id'), array('class' => 'form-control js-example-basic-single')) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      {!! Form::label('size', trans('line-template.size')) !!}
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw fa-tag"></i></span>
                        {!! Form::text('size', Input::old('size'), array('class' => 'form-control')) !!}
                      </div>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="itemDetail" id="itemDetail" value="">
                {!! Form::close() !!}
              </div>
            </div>
            <hr>
            <div class="col-lg-12">
              <h3>Elementos de nuevo renglón</h3>
              <div class="col-lg-4">
                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                  <li class="active">
                    <a href="#home" id="tabProducts" data-toggle="tab">{{trans('line-template.products')}}</a>
                  </li>
                  <li>
                    <a href="#profile" id="tabServices" data-toggle="tab">{{trans('line-template.services')}}</a>
                  </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                  <div class="tab-pane fade active in" id="home">
                    <input type="text" class="form-control" placeholder="Buscar"
                           onkeyup="filterTable(this, 'lProducts')"/>
                    <hr>
                    <div style="overflow-y: scroll; height:200px;">
                      <table class="table table-advance table-hover" id="lProducts">
                        <tbody>
                        @include('project.budget.components.list_services', ['_list'=>$items])
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="profile">
                    <input type="text" class="form-control" placeholder="Buscar"
                           onkeyup="filterTable(this, 'lServices')"/>
                    <hr>
                    <div style="overflow-y: scroll; height:200px;">
                      <table class="table table-advance table-hover" id="lServices">
                        <tbody>
                        @include('project.budget.components.list_services', ['_list'=>$services])
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <h3 class="panel-title">
                      <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff"
                         data-hc="white"></i> Artículos seleccionados
                    </h3>
                  </div>
                  <div class="panel-body" id="selected_items_accordion">
                    <div class="panel-group" id="accordion-cat-1">
                      @foreach($config as $key => $conf)
                        <div class="panel panel-default panel-faq">
                          <div class="panel-heading" style="background-color: {{$conf->color}};">
                            <a id="{{$conf->name=='Material y equipo'? "acItems": "acServices"}}"
                               style="color: white;" data-toggle="collapse" data-parent="#accordion-cat-1"
                               href="#faq-cat-1-sub-{{$key}}">
                              <h4 data-toggle="tooltip" title="{{$conf->description}}" class="panel-title">
                                <i class="{{$conf->icon}}"></i> {{$conf->custom_text}}
                                <span class="pull-right"></span>
                              </h4>
                            </a>
                          </div>
                          <div id="faq-cat-1-sub-{{$key}}" class="panel-collapse collapse">
                            <div class="panel-body">
                              <table class="table table-striped table-bordered table-advance table-hover {{($conf->name=='Material y equipo')? "allowProducts":"allowServices"}}">
                                <thead>
                                <tr>
                                  <th style="width: 40%">
                                    <i class="livicon"
                                       data-name="briefcase"
                                       data-size="16"
                                       data-c="#666666"
                                       data-hc="#666666"
                                       data-loop="true"></i>
                                    {{trans('line-template.description')}}
                                  </th>
                                  <th style="width: 15%">
                                    <i class="fa fa-balance-scale-right"></i> {{trans('line-template.size')}}
                                  </th>
                                  <th style="width: 15%">
                                    <i class="fa fa-money-bill-wave"></i> {{trans('line-template.price_u')}}
                                  </th>
                                  <th style="width: 15%">
                                    <i class="fa fa-box"></i> {{trans('line-template.quantity')}}
                                  </th>
                                  <th style="width: 15%">
                                    <i class="fa fa-money-bill-wave"></i> {{trans('line-template.sub_total')}}
                                  </th>
                                </tr>
                                </thead>
                                <tbody style="height: 40px;">
                                <tr class="comodin"></tr>
                                <?php
                                $_tmptotal = 0;
                                $_tmpqty = 0;
                                ?>
                                @foreach ($template->details as $element)
                                    <?php
                                    if ($conf->name == 'Material y equipo') {
                                        $_type = 1;
                                    } else {
                                        $_type = 2;
                                    }
                                    ?>
                                    @if($element->item->type_id ==$_type)
                                      <tr class="_item_row" item_id="{{$element->item->id}}">
                                        <td class="hidden-xs itm_name">{{$element->item->item_name}}</td>
                                        <td class="hidden-xs itm_size">{{$element->item->size}}</td>
                                        <td>
                                          @if($element->item->verifyExpire())
                                            <span class="label label-sm label-info label-mini">
                                        @else
                                                <span class="label label-sm label-danger label-mini">
                                        @endif
                                            Q
                                            <a href="#"
                                               data-pk="{{$element->item->id}}"
                                               class="unit_cost item_{{$element->item->id}}"
                                               item_id="{{$element->item->id}}">{{number_format($element->item->budget_cost, 2)}}</a>
                                        </span>
                                        </td>
                                        <td class="row_qty">
                                          <input type="hidden" class="refer_quantity_item" value="{{$element->quantity}}">
                                          <span class="label label-sm label-success label-mini">
                                            <a href="#" class="quantity_item" item_id="{{$element->item->id}}">{{$element->quantity}}</a>
                                        </span>
                                        </td>
                                        <td>
                                          <span class="label label-sm label-default label-mini sut_total">@money($element->item->budget_cost * $element->quantity)</span>
                                        </td>
                                        <td>
                                          <button type="button" class="btn btn-sm btn-danger" onclick="deleteElement('_item_row', this)">X</button>
                                        </td>
                                      </tr>
                                      <?php
                                      $_tmptotal += ($element->quantity * $element->item->budget_cost);
                                      $_tmpqty += ($element->quantity);
                                      ?>
                                    @endif
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr style="border-top: 2px solid;">
                                  <td colspan="3"
                                      style="text-align: right;">{{trans('line-template.total_items').' '.strtolower($conf->custom_text)}}</td>
                                  <td>
                                      <span class="label label-sm label-default label-mini subtotal_quantity_table"
                                            type_item="{{$conf->id}}">{{number_format($_tmpqty, 2)}}</span>
                                  </td>
                                  <td>
                                      <span class="label label-sm label-default label-mini subtotal_table"
                                            type_item="{{$conf->id}}">@money($_tmptotal)</span>
                                  </td>
                                </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" style="text-align:center">
              <div class="form-group">
                <div class="col-lg-4">

                </div>
                <div class="col-lg-8" style="text-align:left">
                  <button type="button" onclick="" id="btn_Guar" class="btn btn-primary" data-toggle="modal"
                          data-target="#modalConf">
                    {{trans('button.save')}}
                  </button>
                  <a class="btn btn-danger" href="{{url('line-template')}}">
                    {{trans('button.cancel')}}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{--Begin modal--}}
    <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modalConf" role="dialog"
         aria-labelledby="modalLabelfade"
         aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h4 class="modal-title">Confirmación Guardar</h4>
          </div>
          <div class="modal-body">
            <div class="text-center">
              ¿Esta seguro que desea guardar el nuevo renglón?
            </div>
          </div>
          <div class="modal-footer" style="text-align:center;">
            <button class="btn  btn-info" onclick="send()">Aceptar</button>
            <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    {{--End modal--}}
  </section>
@endsection
@section('footer_scripts')
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  <script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script language="javascript" type="text/javascript"
          src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
  {{--  XEDITABLE  --}}
  <script src="{{ asset('assets/x-editable/bootstrap-editable.min.js') }}" type="text/javascript"></script>
  {{--  CUSTOM CACAO  --}}
  <script type="text/javascript" src="{{ asset('assets/js/line_template/functions.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/line_template/main.js') }}"></script>
  <script>
    $(document).ready(function () {
      setxEditableInputs();
    });
  </script>
@endsection
