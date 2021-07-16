@extends('layouts/default')

@section('title','Editar Etapa')
@section('page_parent', trans('menu.projects'))
@section('header_styles')
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
            <i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            Editar etapa
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
            
          {!! Form::model($stage, array('url' => 'project/stages/'. $stage->id, 'id'=>'frmAdd', 'method' => 'PUT', 'files'=>true)) !!}
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblName', 'Ingrese nombre de projecto', array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">T</span>
                  {!! Form::text('name', $stage->name, array('class' => 'form-control')) !!}
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                {!! Form::label('lblOrder', 'Orden', array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-sort-by-order"></li></span>
                  {!! Form::number('order', $stage->order, array('class' => 'form-control')) !!}
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label for="type_id" class="control-label">{{trans('project.type_id')}}</label>
                <div class="input-group select2-bootstrap-prepend">
                  <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                  <select class="form-control" name="type_id" id="type_id">
                    <option value="">Seleccione tipo de proyecto ---></option>
                    @foreach($types as $value)
                      <option value="{!! $value->id !!}" @if ($stage->type_id == $value->id)
                        selected
                      @endif>{{ $value->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblColor', 'Color', array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">C</span>
                  {!! Form::color('color', $stage->color, array('class' => 'form-control')) !!}
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="galery">{{trans('menu.galery')}}</label>
                <div class="form-group">
                  {!! Form::checkbox('galery', $stage->galery,Input::old('galery'),  array('class' => 'form-control')) !!}
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            @include('partials.buttons',['cancel_url'=>"/project/stages"])
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
<script language="javascript" type="text/javascript"
        src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
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
              message: 'Debe ingresar nombre.'
            }
          }
        },
        order: {
          validators: {
            notEmpty: {
              message: 'Debe ingresar una descripción del proyecto.'
            }
          }
        },
        type_id: {
          validators: {
            notEmpty: {
              message: 'Seleccione tipo de proyecto a aplicar esta etapa.'
            }
          }
        },
        color: {
          validators: {
            notEmpty: {
              message: 'Debe ingresar una descripción del proyecto.'
            }
          }
        }
      }
    });
  });
</script>
@endsection
