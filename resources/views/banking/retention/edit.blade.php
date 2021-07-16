@extends('layouts/default')

@section('title','Editar Retención')
@section('page_parent',trans('project.retention'))
@section('header_styles')
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <!-- <div class="panel-heading">Listado de series y documentos</div> -->
          <div class="panel-heading clearfix">
              <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                  Editar Retención
              </h4>
          </div>
        <div class="panel-body">
          @if (Session::has('message'))
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
          @endif
          <div class="col-lg-1">
          </div>
          <div class="col-lg-10">
            {!! Html::ul($errors->all())!!}
            {!! Form::model($retention, array('url' => 'banks/retention/'.$retention->id, 'id'=>'newRoute', 'method' => 'PUT')) !!}
            <div class="col-lg-6">
              <div class="form-group">
    						{!! Form::label('lblName', trans('Ingrese nombre de retención').' ') !!}
    						{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
    					</div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
    						{!! Form::label('lblPercent', trans('Ingrese porcentaje de retención').' ') !!}
    						{!! Form::text('percent', Input::old('percent'), array('class' => 'form-control percent')) !!}
    					</div>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
    						{!! Form::label('lblDescription', trans('Ingrese descripción de retención').' ') !!}
    						{!! Form::text('description', Input::old('description'), array('class' => 'form-control')) !!}
    					</div>
            </div>
            <div class="row">
  						@include('partials.buttons',['cancel_url'=>"banks/retention"])
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
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script type="text/javascript">
    $(document).ready(function(){
      var cleave = new Cleave('.percent', {
          numeral: true,
          numeralThousandsGroupStyle: 'thousand'
      });
      $('#newRoute').bootstrapValidator({
          feedbackIcons: {
              valid: 'glyphicon glyphicon-ok',
              invalid: 'glyphicon glyphicon-remove',
              validating: 'glyphicon glyphicon-refresh'
          },
          message: 'Valor no valido',
          fields:{
              name:{
                  validators:{
                      notEmpty:{
                          message:'Debe ingresar nombre de retención.'
                      }
                  }
              },
              percent:{
                  validators:{
                      notEmpty:{
                          message:'Debe ingresar porcentaje de retención.'
                      }
                  }
              },
              description:{
                  validators:{
                      notEmpty:{
                          message:'Debe ingresar descripción de retención.'
                      }
                  }
              }
          }
      });
    });

  </script>
@endsection
