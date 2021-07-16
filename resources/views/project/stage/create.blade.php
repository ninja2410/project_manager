@extends('layouts/default')

@section('title','Nueva Etapa')
@section('page_parent',trans('menu.projects'))
@section('header_styles')
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
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
                            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            Nueva etapa
                        </h3>
                        <span class="pull-right clickable">
                <i class="glyphicon glyphicon-chevron-up"></i>
              </span>
                    </div>
                    <div class="panel-body">
                        
                        {!! Form::open(array('url' => 'project/stages','id'=>'frmAdd')) !!}
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('lblName', 'Ingrese nombre de la Etapa', array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <span class="input-group-addon">T</span>
                                        {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {!! Form::label('lblNumber', 'Orden', array('class'=>'control-label')) !!}
                                    <div class="input-group">
                                        <span class="input-group-addon"><li
                                                    class="glyphicon glyphicon-sort-by-order"></li></span>
                                        {!! Form::number('order', Input::old('order'), array('class' => 'form-control')) !!}
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
                                                <option value="{!! $value->id !!}">{{ $value->name }}
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
                                        {!! Form::color('color', Input::old('color'), array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="galery">{{trans('menu.galery')}}</label>
                                    <div class="form-group">
                                        {!! Form::checkbox('galery', 1,Input::old('galery'),  array('class' => 'form-control')) !!}
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
        $(document).ready(function () {
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
                                message: 'Debe ingresar el orden de la etapa.'
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
                                message: 'Debe seleccionar el color que identificará la etapa.'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
