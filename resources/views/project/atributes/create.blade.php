@extends('layouts/default')

@section('title',trans('menu.atributes'))
@section('page_parent',trans('menu.projects'))
@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>
@endsection
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            Nuevo atributo de etapa
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          
          {!! Form::open(array('url' => 'project/atributes','id'=>'frmAdd')) !!}
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblName', 'Ingrese nombre del atributo', array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">T</span>
                  {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblSize', 'Tamaño' , array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-resize-horizontal"></li></span>
                  <select class="form-control" name="size">
                    <option value="">Seleccione una opción</option>
                    <option value="col-md-12">Grande</option>
                    <option value="col-md-6">Mediano</option>
                    <option value="col-md-3">Pequeño</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblTipo', 'Tipo', array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">C</span>
                  <select class="form-control" name="type">
                    <option value="">Seleccione una opción</option>
                    <option value="text">Texto</option>
                    <option value="number">Número</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="date">Fecha</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblStage', 'Etapa', array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-flag"></li></span>
                  <select class="form-control" name="stage_id">
                    <option value="">Seleccione una opción</option>
                    @foreach ($stages as $key => $stage)
                    <option value="{{$stage->id}}">{{$stage->order}}) {{$stage->name}} | {{$stage->tipo->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            @include('partials.buttons',['cancel_url'=>"/project/atributes"])
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script type="text/javascript " src="{{ asset('assets/js/route/validations.js')}} "></script>
<script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('select').select2({
      allowClear: true,
      theme: "bootstrap",
      placeholder: "Buscar"
    });
    $('#frmAdd').bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      message: 'Valor no valido',
      fields: {
        name: {
          validators: {
            notEmpty: {
              message: 'Debe ingresar nombre del campo.'
            }
          }
        },
        size: {
          validators: {
            notEmpty: {
              message: 'Debe ingresar tamaño del campo.'
            }
          }
        },
        type: {
          validators: {
            notEmpty: {
              message: 'Debe ingresar tipo de campo.'
            }
          }
        },
        stage_id: {
          validators: {
            notEmpty: {
              message: 'Seleccione la etapa a la cual pertenece el campo.'
            }
          }
        }
      }
    });
  });
</script>
@endsection
