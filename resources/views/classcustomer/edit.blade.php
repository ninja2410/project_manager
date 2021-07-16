@extends('layouts/default')

@section('title','Editar clase de cliente')
@section('page_parent',"Editar Clases")
@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> 
{{-- CHECKBOX STYLE --}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/checklist/chklist.css') }}">
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <!-- <div class="panel-heading">Listado de series y documentos</div> -->
        <div class="panel-body">
          @if (Session::has('message'))
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
          @endif
          <div class="col-lg-12">
            {!! Html::ul($errors->all()) !!}
            {!! Form::model($class, array('url' => 'class/update/'.$class->id, 'id'=>'newClassCustomer')) !!}
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
      						{!! Form::label('lblName', trans('Nombre de la clase de cliente').' ') !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-font"></li> </span>
                    {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                  </div>

      					</div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
      						{!! Form::label('lblarrears', 'Número de cuotas atrasadas permitidas ') !!}
                  <div class="input-group">
                    <span class="input-group-addon">#</span>
                    {!! Form::text('arrears', Input::old('arrears'), array('class' => 'form-control')) !!}
                  </div>

      					</div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
      						{!! Form::label('lblPctRen', 'Porcentaje requerido para renovación de crédito') !!}
                  <div class="input-group">
                    <span class="input-group-addon">%</span>
                    {!! Form::text('pctRen', Input::old('ptcRen'), array('class' => 'form-control')) !!}
                  </div>
      					</div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
      						{!! Form::label('lblPtcAmountRen', 'Porcentaje de monto disponible para renovación de crédito') !!}
                  <div class="input-group">
                    <span class="input-group-addon">%</span>
                    {!! Form::text('pctAmountRen', Input::old('ptrAmountRen'), array('class' => 'form-control', 'id'=>'renovation')) !!}
                  </div>
      					</div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-5">
                <div class="form-group">
      						{!! Form::label('lblColor', 'Color de clase') !!}
      						{!! Form::color('color', Input::old('color'), array('class' => 'form-control', 'id'=>'selColor')) !!}
      					</div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
      						{!! Form::label('lblStatus', trans('Seleccione estado').' *') !!}
      						{!! Form::select('status_id', $status, Input::old('status_id'), array('class' => 'form-control', 'id'=>'status')) !!}
      					</div>
              </div>
              <div class="col-lg-1">
              </div>
              <div class="col-lg-3">
                <div class="form-group">
      						{!! Form::label('lblnotPay', 'Perdonar mora') !!} <br>
                  <label class="custom-control custom-checkbox">
                  <input id="chk" type="checkbox" name="noPaySurcharge"
                  @if ($class->noPaySurcharge)
                    checked
                  @endif
                   class="custom-control-input">
                  <span class="custom-control-indicator"></span>
                  </label>
      					</div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
      						{!! Form::label('lbldescription', 'Ingrese descripción de la clase.') !!}
      						{!! Form::textarea('description', Input::old('description'), array('class' => 'form-control')) !!}
      					</div>
              </div>
            </div>
            <div class="row">
  						@include('partials.buttons',['cancel_url'=>"/class"])
  					</div>
  					{!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
  	<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
    <script src="{{ asset('assets/js/classCustomer/classCustomer.js') }} " type="text/javascript "></script>
    <script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
@endsection
