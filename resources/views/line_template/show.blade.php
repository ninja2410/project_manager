@extends('layouts/default')

@section('title',trans('line-template.show'))
@section('page_parent', trans('line-template.line-templates'))
@section('header_styles')
  <link href="{{asset("assets/fontawesome/css/all.css")}}" rel="stylesheet">
@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('line-template.show')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          @if (Session::has('message'))
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
          @endif
          <div class="row">
            <div class="row">
              {!! Html::ul($errors->all()) !!}
              {!! Form::model($template, array('url' => 'line-template/'.$template->id, 'id'=>'newRoute')) !!}
              <div class="col-lg-6">
                {!! Form::label('lblName', trans('line-template.name').' ') !!}
                <div class="form-group input-group">
                  <span class="input-group-addon"><i class="fa fa-fw fa-font"></i></span>
                  {!! Form::text('name', Input::old('name'), array('class' => 'form-control', 'readonly' =>'true')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                {!! Form::label('lblPrice', trans('line-template.price').' ') !!}
                <div class="form-group input-group">
                  <span class="input-group-addon"><i class="fa fa-fw  fa-money"></i></span>
                  {!! Form::text('price', number_format($template->price, 2), array('class' => 'form-control money', 'readonly' =>'true')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                {!! Form::label('lblQuantity', trans('line-template.items_quantity').' ') !!}
                <div class="form-group input-group">
                  <span class="input-group-addon"><i class="fa fa-fw  fa-bars"></i></span>
                  {!! Form::text('items_quantity', number_format($template->items_quantity, 2), array('class' => 'form-control money', 'readonly' =>'true')) !!}
                </div>
              </div>
              <div class="col-lg-6">
                {!! Form::label('lblDescription', trans('line-template.description').' ') !!}
                <div class="form-group input-group">
                  <span class="input-group-addon"><i class="fa fa-fw fa-align-justify"></i></span>
                  {!! Form::text('description', Input::old('description'), array('class' => 'form-control', 'readonly' =>'true')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('size', trans('line-template.category')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-fw fa-tag"></i></span>
                    {!! Form::text('categorie_id', $template->category->name, array('class' => 'form-control', 'readonly' =>'true')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('size', trans('line-template.size')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-balance-scale-right"></i></span>
                    {!! Form::text('size', $template->size, array('class' => 'form-control', 'readonly' =>'true')) !!}
                  </div>
                </div>
              </div>
              <input type="hidden" name="itemDetail" id="itemDetail" value="">
              {!! Form::close() !!}
            </div>
          </div>
          <hr>
          <div class="col-lg-12">
            <h3>Elementos de renglón</h3>
            <table class="table table-striped table-bordered table-advance table-hover">
              <thead>
                <tr>
                  <th>
                    <i class="livicon" data-name="briefcase" data-size="16" data-c="#666666" data-hc="#666666" data-loop="true"></i>
                    {{trans('line-template.description')}}
                  </th>
                  <th>
                    <i class="fa fa-balance-scale-right"></i> {{trans('line-template.size')}}
                  </th>
                  <th>
                    <i class="fa fa-money-bill-wave"></i> {{trans('line-template.price_u')}}
                  </th>
                  <th>
                    <i class="fa fa-box"></i> {{trans('line-template.quantity')}}
                  </th>
                  <th>
                    <i class="fa fa-money-bill-wave"></i> {{trans('line-template.sub_total')}}
                  </th>
                </tr>
              </thead>
              <tbody>

              @foreach($config as $conf)
                <tr style="background-color: {{$conf->color}};">
                  <td colspan="5"><strong><center><i class="{{$conf->icon}}"></i> {{$conf->custom_text}}</center></strong></td>
                </tr>
                <?php
                  $counter = 0;
                  $_subtotal = 0;
                  ?>
                @foreach ($details as $element)
                  <?php
                  if ($conf->name=='Material y equipo'){
                    $_type = 1;
                  }
                  else{
                    $_type = 2;
                  }
                  ?>
                  @if($element->item->type_id == $_type)
                    <tr>
                      <td class="hidden-xs">{{$element->item->item_name}}</td>
                      <td>{{$element->item->size}}</td>
                      <td>@money($element->item->budget_cost)</td>
                      <td>
                        <span class="label label-sm label-success label-mini">{{$element->quantity}}</span>
                      </td>
                      <td>@money(($element->item->budget_cost * $element->quantity))</td>
                    </tr>
                    <?php
                      $counter += $element->quantity;
                      $_subtotal += ($element->quantity * $element->item->budget_cost);
                    ?>
                  @endif

                @endforeach
                <tr>
                  <td></td>
                  <td></td>
                  <td style="text-align: right;"><strong>{{trans('line-template.total_items')}}</strong></td>
                  <td style="border-top: 2px solid;"><span class="label label-sm label-info label-mini">{{$counter}}</span></td>
                  <td style="border-top: 2px solid;"><span class="label label-sm label-info label-mini">@money($_subtotal)</span></td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <div class="row" style="text-align:center">
            <a class="btn btn-info" href="{{url('line-template/'.$template->id.'/edit')}}">
              {{trans('button.edit')}}
            </a>
            <a class="btn btn-danger" href="{{url('line-template')}}">
              {{trans('button.cancel')}}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script src="{{asset('assets/js/vuejs/vue.min.js')}} "></script>
<script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
@endsection
