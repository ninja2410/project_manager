@extends('layouts/default')
@section('title',trans('budget_config.edit'))
@section('page_parent',trans('menu.parameters'))

@section('header_styles')
    <!-- Validaciones -->
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Iconpicker -->
    <link href="{{ asset('assets/css/iconpicker/iconpicker.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/fontawesome/css/all.css")}}" rel="stylesheet">
@stop
@section('content')
    <section class="content">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                <span class="glyphicon glyphicon-inbox" aria-hidden="true">
                </span>
                    {{trans('budget_config.edit')}}
                </div>
                <div class="panel-body">
                    <div class="row">
                        <h3>{{trans('budget_config.line_template')}}</h3>
                        <h4>{{trans('budget_config.groups')}}</h4>
                        <div class="row">
                            {!! Form::open(['url' => url('budget_config'), 'method' => 'post', 'id'=> 'frmEdit']) !!}
                            <input type="hidden" id="data" name="data" value="{{json_encode($line_template_config)}}">
                            @foreach($line_template_config as $config)
                                <div class="col-lg-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading"
                                             style="background-color: {{$config->color}} !important;">
                                            <h3 class="panel-title">
                                                <i class="{{$config->icon}}"></i> {{$config->name}}
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="box-body">
                                                <p>{{$config->description}}</p>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="">{{trans('budget_config.custom_text')}}: </label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="livicon" data-name="notebook" data-size="16"
                                                                   data-c="#555555" data-hc="#555555"
                                                                   data-loop="true"></i>
                                                            </div>
                                                            <input type="text" config_id="{{$config->id}}" required
                                                                   minlength="3" name="custom_text"
                                                                   class="form-control" value="{{$config->custom_text}}"
                                                                   id="custom_text_{{$config->id}}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="">{{trans('budget_config.icon')}}: </label>
                                                        <div class="input-group">
                                                            <input data-placement="bottomRight"
                                                                   required
                                                                   name="icon"
                                                                   id="icon_{{$config->id}}"
                                                                   class="form-control icp icp-auto"
                                                                   value="{{$config->icon}}"
                                                                   type="text"/>
                                                            <span class="input-group-addon"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="">{{trans('budget_config.order')}}: </label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="livicon" data-name="list" data-size="16"
                                                                   data-c="#555555" data-hc="#555555"
                                                                   data-loop="true"></i>
                                                            </div>
                                                            <input type="number" config_id="{{$config->id}}" required
                                                                   min="0" name="order"
                                                                   class="form-control number"
                                                                   value="{{$config->order}}"
                                                                   id="order_{{$config->id}}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="">{{trans('budget_config.color')}}: </label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="livicon" data-name="brush" data-size="16"
                                                                   data-c="#555555" data-hc="#555555"
                                                                   data-loop="true"></i>
                                                            </div>
                                                            <input type="color" config_id="{{$config->id}}" required
                                                                   name="color"
                                                                   class="form-control" value="{{$config->color}}"
                                                                   id="color_{{$config->id}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {!! Form::close() !!}
                        </div>
                        @include('layouts.modal_confirm_generic', array('confirm'=>'Desea guardar los datos de configuracion?'))
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4" style="text-align: center;">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="btn_save">
                                        {{trans('button.save')}}
                                    </button>
                                    <a class="btn btn-danger" href="{{ url('budget_config') }}">
                                        {{trans('button.cancel')}}
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer_scripts')
    {{-- FORMATO DE MONEDAS --}}
    <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
    <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
    <!-- Valiadaciones -->
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
    <!-- Iconpicker -->
    <script src="{{ asset('assets/js/iconpicker/iconpicker.js') }} " type="text/javascript "></script>
    <script>
        $(document).ready(function () {
            $('#frmEdit').bootstrapValidator({

                feedbackIcons: {

                    valid: 'glyphicon glyphicon-ok',

                    invalid: 'glyphicon glyphicon-remove',

                    validating: 'glyphicon glyphicon-refresh'

                }
            });
            $('.icp-auto').iconpicker().on('iconpickerSelected', function(event){
                $('#frmEdit').data('bootstrapValidator')
                    .updateStatus($(this), 'NOT_VALIDATED')
                    .validateField($(this));
            });

            $('#btn_save').click(function () {
                valid();
            });
        });
        function valid(){
            var $validator = $('#frmEdit').data('bootstrapValidator').validate();
            if ($validator.isValid() ) {
                $('#confirmSave').modal('show');
            }
            else{
                toastr.error("Debe llenara los campos requeridos.")
            }
        }
        function sendForm(){
            let data = JSON.parse($('#data').val());
            let newData = [];
            data.forEach(function (element) {
                element.custom_text = $('#custom_text_'+element.id).val();
                element.icon = $('#icon_'+element.id).val();
                element.order = $('#order_'+element.id).val();
                element.color = $('#color_'+element.id).val();
                newData.push(element);
            });
            $('#data').val(JSON.stringify(newData));
            $('#confirmSave').modal('hide');
            document.getElementById('frmEdit').submit();
        }
    </script>
@stop