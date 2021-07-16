@extends('layouts/default')

@section('title',trans('parameter.title_sys'))
@section('page_parent',trans('parameter.title'))

@section('header_styles')
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('parameter.title_sys')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">

          <div class="row" style="text-align:right;">
            <a href="{{url("logs")}}">
              <button type="button" class="btn btn-success">
                Historial de cambios
              </span>
            </button>
          </a>
        </div>
        <br>
        <form class="" action="parameters/storage" method="post" id="parameters">
          <input type="hidden" name="_token" value="{{ csrf_token()}}">
          <input type="hidden" name="resume" value="" id="resume">
          <input type="hidden" name="detail" id="detail" value="">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.name_company'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-list"></li> </span>
                  <input type="text" name="name" class="form-control" value="{{$data->name_company}}">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.phone'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-phone"></li> </span>
                  <input type="text" name="phone" maxlength="8" class="form-control" value="{{$data->phone}}">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.email'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon"> <b>@</b> </span>
                  <input type="email" name="email" class="form-control" value="{{$data->email}}">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.address'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-road"></li> </span>
                  <input type="text" name="address" class="form-control" value="{{$data->address}}">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <div class="form-group">
                  {!! Form::label('lblSlogan', trans('parameter.slogan'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <b>S</b> </span>
                    <input type="text" name="slogan" class="form-control" value="{{$data->slogan}}">
                  </div>
                </div>
                {!! Form::label('lblname', trans('parameter.ceo')) !!}
                <div class="input-group">
                  <span class="input-group-addon"> <b>T</b> </span>
                  <input type="text" name="ceo" class="form-control" value="{{$data->ceo}}">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblSecondColor', trans('parameter.description'), array('class'=>'control-label')) !!}
                <div class="input-group" >
                  <span class="input-group-addon">T</span>
                  <textarea name="description" id="description" cols="30" rows="5" class="form-control" >{{$data->description}}</textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <center><h4>{{trans('parameter.fel_settings')}}</h4></center>
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('lblFel', trans('parameter.fel')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <b>F</b> </span>
                    <input type="checkbox" name="fel" {{$data->fel ? 'checked' : ''}} class="form-control" value="">
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('lblFel', trans('parameter.nit')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <b>N</b> </span>
                    <input type="text" name="nit" class="form-control" value="{{$data->nit}}">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblFel', trans('parameter.fel_username')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <li class="glyphicon glyphicon-user"></li> </span>
                    <input type="text" name="fel_username" class="form-control" value="{{$data->fel_username}}">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblfel_cert', trans('parameter.fel_cert')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <li class="glyphicon glyphicon-send"></li> </span>
                    <input type="text" name="fel_cert" class="form-control" value="{{$data->fel_cert}}">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblfirm', trans('parameter.fel_firm')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <li class="glyphicon glyphicon-send"></li> </span>
                    <input type="text" name="fel_firm" class="form-control" value="{{$data->fel_firm}}">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <center><h4>Personalización de pie de Ticket</h4></center>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.footer_text')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-italic"></li> </span>
                  <input type="footer_text" name="footer_text" class="form-control" value="{{$data->footer_text}}">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.website')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-link"></li> </span>
                  <input type="text" name="website" class="form-control" value="{{$data->website}}">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.facebook')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-thumbs-up"></li> </span>
                  <input type="facebook" name="facebook" class="form-control" value="{{$data->facebook}}">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.instagram')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-camera"></li> </span>
                  <input type="text" name="instagram" class="form-control" value="{{$data->instagram}}">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.twitter')) !!}
                <div class="input-group">
                  <span class="input-group-addon"> <li class="glyphicon glyphicon-star-empty"></li> </span>
                  <input type="twitter" name="twitter" class="form-control" value="{{$data->twitter}}">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblname', trans('parameter.whatsapp')) !!}
                <div class="input-group">
                  <span class="input-group-addon"><li class="glyphicon glyphicon-share"></li> </span>
                  <input type="text" name="whatsapp" class="form-control" value="{{$data->whatsapp}}">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <center><h4>Personalización de pantalla</h4></center>
            <div class="col-lg-4">
              <div class="form-group">
                {!! Form::label('lblPrimaryColor', trans('parameter.navbar_color'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">C</span>
                  <input type="color" name="navbar_color" class="form-control" value="{{$data->navbar_color}}">
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                {!! Form::label('lblPrimaryColor', trans('parameter.leftmenu_color'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">C</span>
                  <input type="color" name="leftmenu_color" class="form-control" value="{{$data->leftmenu_color}}">
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                {!! Form::label('lblPrimaryColor', trans('parameter.select_color'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">C</span>
                  <input type="color" name="select_color" class="form-control" value="{{$data->select_color}}">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <center><h4>Colores de factura</h4></center>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblPrimaryColor', trans('parameter.primary'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">C</span>
                  <input type="color" name="primary" class="form-control" value="{{$data->primary}}">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblPrimaryColor', trans('parameter.second'), array('class'=>'control-label')) !!}
                <div class="input-group">
                  <span class="input-group-addon">C</span>
                  <input type="color" name="second" class="form-control" value="{{$data->second}}">
                </div>
              </div>
            </div>
          </div>

          {{-- <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblgarant', trans('parameter.guarantor')) !!}
                <div class="input-group">
                  <span class="input-group-addon">Q</span>
                  <input type="text" name="amount_guarantor_required"  class="form-control money" value="{{$data->amount_guarantor_required}}">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                {!! Form::label('lblauthorize', trans('parameter.authorize')) !!}
                <div class="input-group">
                  <span class="input-group-addon">Q</span>
                  <input type="text" name="amount_authorize_required"  class="form-control money2" value="{{$data->amount_authorize_required}}">
                </div>
              </div>
            </div>
          </div> --}}
          <hr>
          <div class="col-lg-12">
            {{-- @include('partials.buttons_back',['submit_id'=>"value=Guardar"]) --}}
            @include('partials.buttons',['cancel_url'=>"/"])
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</section>
@endsection
@section('footer_scripts')
{{-- <script src="{{asset('assets/js/vuejs/vue.min.js')}} "></script> --}}
{{-- <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script> --}}
{{-- <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script> --}}
{{-- <script src="{{asset('assets/js/vuejs/numeral.min.js')}} "></script> --}}

<!-- Valiadaciones -->
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<!-- Toast -->
<script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
<script type="text/javascript">
  $(document).ready(function(){
    @if (Session::has('message'))
    toastr.success('{{ Session::get('message') }}', 'Correcto!');
    @endif
    $('#parameters').bootstrapValidator({
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
              message:'Debe ingresar un nombre para la empresa. '
            }
          }
        },
        evaluate:{
          validators:{
            notEmpty:{
              message:'Debe ingresar un porcentaje de evaluación de clientes.'
            },
            regexp:{
              regexp: /^[0-9]\d*(\.\d+)?$/,
              message: 'Ingrese un porcentaje válido'
            }
          }
        },
        renovation_amount:{
          validators:{
            notEmpty:{
              message:'Debe ingresar un porcentaje de pago mínimo para renovar créditos.'
            },
            regexp:{
              regexp: /^[0-9]\d*(\.\d+)?$/,
              message: 'Ingrese un porcentaje válido'
            }
          }
        },
        amount_guarantor_required:{
          validators:{
            notEmpty:{
              message:'Debe ingresar monto mínimo para solicitar fiador.'
            }
          }
        },
        address:{
          validators:{
            notEmpty:{
              message:'Debe ingresar dirección del negocio.'
            }
          }
        },
        primary:{
          validators:{
            notEmpty:{
              message:'Debe ingresar color.'
            }
          }
        },
        second:{
          validators:{
            notEmpty:{
              message:'Debe ingresar color.'
            }
          }
        },
        percent_commission:{
          validators:{
            notEmpty:{
              message:'Debe ingresar un porcentaje de comisiones a cobradores.'
            },
            regexp:{
              regexp: /^[0-9]\d*(\.\d+)?$/,
              message: 'Ingrese un porcentaje válido'
            }
          }
        },
        amount_authorize_required:{
          validators:{
            notEmpty:{
              message:'Debe ingresar monto mínimo para solicitar autorización administrativa.'
            }
          }
        },
        phone:{
          validators:{
            notEmpty:{
              message:'Debe ingresar teléfono del negocio.'
            },
            regexp:{
              regexp: /^[0-9]*$/,
              message: 'Ingrese un teléfono válido'
            },
            stringLength:{
              min:8,
              max:8,
              message:'El teléfono debe tener 8 dígitos.'
            }
          }
        },
        email:{
          validators:{
            notEmpty:{
              message:'Debe ingresar correo electrónico del negocio.'
            }
          }
        }
      }
    });
  });
</script>
@stop
