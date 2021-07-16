@extends('layouts/default')

@section('title',trans('general.new_currency_denomination_full'))
@section('page_parent',trans('general.currency_denomination'))
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('general.new_currency_denomination')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          
          <div class="col-lg-1">
          </div>
          <div class="col-lg-10">
            {!! Html::ul($errors->all()) !!}
            {!! Form::open(array('url' => 'typeMoney/store', 'id'=>'newMoney')) !!}
            <div class="form-group">
              {!! Form::label('lblName','Ingrese nombre de moneda ') !!}
              {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
            </div>
            <div class="form-group">
              {!! Form::label('lblUser', 'Ingrese valor') !!}
              {!! Form::text('value', Input::old('value'), array('class' => 'form-control', 'id'=>'value')) !!}
            </div>
            <div class="form-group">
              {!! Form::label('lblStatus', trans('Seleccione estado').' *') !!}
              {!! Form::select('status_id', $status, Input::old('status_id'), array('class' => 'form-control', 'id'=>'status')) !!}
            </div>
            <div class="row">
              @include('partials.buttons',['cancel_url'=>"/typeMoney"])
            </div>
            {!! Form::close() !!}
          </div>
          <div class="col-lg-1">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script type="text/javascript " src="{{ asset('assets/js/moneyType/validations.js')}} "></script>
@endsection
