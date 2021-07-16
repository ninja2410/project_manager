@extends('layouts/default')

@section('title',trans('parameter.title_sys'))
@section('page_parent',trans('credit.title'))

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
          @if (Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
          @endif
          <form class="" action="parameters/storage" method="post" id="parameters">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="resume" value="" id="resume">
            <input type="hidden" name="detail" id="detail" value="">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblname', trans('parameter.name_company'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-list"></li> </span>
                    <input type="text" name="name" class="form-control" value="">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblname', trans('parameter.phone'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-phone"></li> </span>
                    <input type="text" maxlength="8" name="phone" class="form-control" value="">
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
                    <input type="email" name="email" class="form-control" value="">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblname', trans('parameter.address'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-road"></li> </span>
                    <input type="text" name="address" class="form-control" value="">
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
                      <input type="text" name="slogan" class="form-control" value="">
                    </div>
                  </div>
                  {!! Form::label('lblname', trans('parameter.ceo'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <b>T</b> </span>
                    <input type="text" name="ceo" class="form-control" value="">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblSecondColor', trans('parameter.description'), array('class'=>'control-label'))!!}
                  <div class="input-group" >
                    <span class="input-group-addon">T</span>
                    <textarea maxlength="255" name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
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
                      <input type="checkbox" name="fel" class="form-control" value="">
                    </div>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    {!! Form::label('lblFel', trans('parameter.nit')) !!}
                    <div class="input-group">
                      <span class="input-group-addon"> <b>N</b> </span>
                      <input type="text" name="nit" class="form-control" value="">
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    {!! Form::label('lblFel', trans('parameter.fel_username')) !!}
                    <div class="input-group">
                      <span class="input-group-addon"> <li class="glyphicon glyphicon-user"></li> </span>
                      <input type="text" name="fel_username" class="form-control" value="">
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
                      <input type="text" name="fel_cert" class="form-control" value="">
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    {!! Form::label('lblfirm', trans('parameter.fel_firm')) !!}
                    <div class="input-group">
                      <span class="input-group-addon"> <li class="glyphicon glyphicon-send"></li> </span>
                      <input type="text" name="fel_firm" class="form-control" value="">
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
                    <span class="input-group-addon"> <b>@</b> </span>
                    <input type="footer_text" name="footer_text" class="form-control" value="">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblname', trans('parameter.website')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-road"></li> </span>
                    <input type="text" name="website" class="form-control" value="">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblname', trans('parameter.facebook')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <b>@</b> </span>
                    <input type="facebook" name="facebook" class="form-control" value="">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblname', trans('parameter.instagram')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-road"></li> </span>
                    <input type="text" name="instagram" class="form-control" value="">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblname', trans('parameter.twitter')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"> <b>@</b> </span>
                    <input type="twitter" name="twitter" class="form-control" value="">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblname', trans('parameter.whatsapp')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-road"></li> </span>
                    <input type="text" name="whatsapp" class="form-control" value="">
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
                    <input type="color" name="navbar_color" class="form-control" value="#515763">
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('lblPrimaryColor', trans('parameter.leftmenu_color'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon">C</span>
                    <input type="color" name="leftmenu_color" class="form-control" value="#515763">
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('lblPrimaryColor', trans('parameter.select_color'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon">C</span>
                    <input type="color" name="select_color" class="form-control" value="#414151">
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
                    <input type="color" name="primary" class="form-control">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblSecondColor', trans('parameter.second'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon">C</span>
                    <input type="color" name="second" class="form-control">
                  </div>
                </div>
              </div>
            </div>

            {{-- <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('lblpct', 'Porcentaje para evaluación de clientes') !!}
                  <div class="input-group">
                    <span class="input-group-addon">%</span>
                    <input type="text" name="evaluate" class="form-control" value="" placeholder="Porcentaje de crédito activo necesario para optar a una renovación.">
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('lblRenMin', 'Porcentaje de pago mínimo para renovar crédito') !!}
                  <div class="input-group">
                    <span class="input-group-addon">%</span>
                    <input type="text" name="renovation_amount" class="form-control" value="" placeholder="Porcentaje de monto pagado para clasificar al cliente.">
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('lblCommission', 'Porcentaje de comision a cobradores') !!}
                  <div class="input-group">
                    <span class="input-group-addon">%</span>
                    <input type="text" name="percent_commission" class="form-control" value="" placeholder="Porcentaje de comisiones pagadas a cobradores.">
                  </div>
                </div>
              </div>
            </div> --}}
            {{-- <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblguarantor', trans('parameter.guarantor')) !!}
                  <div class="input-group">
                    <span class="input-group-addon">Q</span>
                    <input type="text" name="amount_guarantor_required"  class="form-control money">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('lblauthorize', trans('parameter.authorize')) !!}
                  <div class="input-group">
                    <span class="input-group-addon">Q</span>
                    <input type="text" name="amount_authorize_required"  class="form-control money_auth">
                  </div>
                </div>
              </div>
            </div> --}}
            <br>
            <div class="col-lg-12">
              @include('partials.buttons',[ 'cancel_url'=>"/",'submit_id'=>"value=Guardar"])
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
{{-- <script src="{{asset('assets/js/vuejs/vue.min.js')}} "></script>
<script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
<script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
<script src="{{asset('assets/js/vuejs/numeral.min.js')}} "></script> --}}
<!-- Valiadaciones -->
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<!-- Toast -->
<script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
<script type="text/javascript">
  $(document).ready(function(){
    // var cleave = new Cleave('.money', {
    //   numeral: true,
    //   numeralThousandsGroupStyle: 'thousand'
    // });
    // var cleave = new Cleave('.money_auth', {
    //   numeral: true,
    //   numeralThousandsGroupStyle: 'thousand'
    // });
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
        primary:{
          validators:{
            notEmpty:{
              message:'Debe seleccionar un color. '
            }
          }
        },
        second:{
          validators:{
            notEmpty:{
              message:'Debe seleccionar un color. '
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
