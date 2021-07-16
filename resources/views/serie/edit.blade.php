@extends('layouts/default')

@section('title','Actualizar serie')
@section('page_parent',"Documentos de inventario")
@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/css/radios.css')}}" rel="stylesheet" type="text/css" />
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="file" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            Actualizar serie
                        </h3>
                        <span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
                    </div>
					<div class="panel-body">
						{!! Html::ul($errors->all()) !!}
						{!! Form::model($series, array('route' => array('series.update', $series->id), 'method' => 'PUT', 'files' => true, 'id'=>'newSerie')) !!}
                        <div class="form-group">
                            {!! Form::label('name', trans('Elija un documento').' *') !!}
                            <select name="document_id" id="document_id" class="form-control"
                                    onchange="verificarDocumento(this);">
                                <option value="">Seleccione un documento</option>
                                @foreach ($document as $doc)
                                    <option id="doc_{{$doc->id}}" value="{{$doc->id}}" sign="{{$doc->sign}}" {{ ($doc->id == $series->id_document) ?  'selected="selected"' : '' }}>{{$doc->name}}</option>
                                @endforeach
                            </select>
                        </div>

						<div class="form-group">
							{!! Form::label('phone', trans('Serie').' ') !!}
							<input type="text" name="name_sign" value="{{$series->name}}" class="form-control">
						</div>
						<div class="form-group">
							{!! Form::label('size', trans('Estado')) !!}
							{!! Form::select('id_state', $state_cellar, Input::old('state_cellar'), array('class' => 'form-control')) !!}
						</div>
                        <div class="form-group" id="divPrint" style="display: none;">
                            <div class="row">
                                <center> <h4>Seleccione el formato de impresión a utilizar.</h4> </center>
                                <div class="funkyradio">
                                    <div class="col-lg-6">
                                        <div class="funkyradio-primary">
                                            <input type="radio" {{!$series->proforma ? 'checked' : ''}} name="radio" value="0" id="radio1" />
                                            <label for="radio1">Formato impresión externo</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="funkyradio-primary">
                                            <input type="radio" {{$series->proforma ? 'checked' : ''}} name="radio" value="1" id="radio2"/>
                                            <label for="radio2">Formato proforma</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
{{--						<div class="row">--}}
{{--							<div class="form-group">--}}
{{--								<div class="form-group">--}}
{{--									<div class="col-lg-2" id="type">--}}
{{--										{!! Form::label('size', 'Credito') !!}--}}
{{--										{!! Form::checkbox('credit',1, Input::old('credit'), array('class' => 'form-control')) !!}--}}
{{--									</div>--}}
{{--								</div>--}}
{{--								<br>--}}
{{--							</div>--}}
{{--						</div>--}}
						<div class="row">
							@include('partials.buttons',['cancel_url'=>"/series"])
						</div>
						{!! Form::close() !!}
					</div>
                </div>
                <!-- <div class="panel-heading">Actualizar documento</div> -->

            </div>
        </div>
    </section>
@endsection
@section('footer_scripts')
    <script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#newSerie').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                message: 'Valor no valido',
                fields: {
                    name_sign: {
                        validators: {
                            notEmpty: {
                                message: 'Debe ingresar serie. '
                            }
                        }
                    },
                    document_id: {
                        validators: {
                            notEmpty: {
                                message: 'Debe seleccionar un documento. '
                            }
                        }
                    }
                }
            });
            verificarDocumento(document.getElementById('document_id'));
        });

        function verificarDocumento(control) {
            var input = $('#doc_'+control.value);
            var sign = input.attr('sign');
            if (sign=='-'){
                document.getElementById('divPrint').style.display='inline';
            }
            else{
                document.getElementById('divPrint').style.display='none';
            }
        }
    </script>
@endsection
