@extends('layouts/default')

@section('title',trans('parameter.log'))
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
            {{trans('parameter.log')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          <h3>Historial de cambios</h3>
          <hr>
          <hr>
          @if (isset($data))
          <p class="alert alert-info">Datos actualizados con éxito.</p>
          @endif
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <td>Fecha</td>
                <td>Acción</td>
                <td>Cambio</td>
                <td>Usuario</td>
              </tr>
            </thead>
            @foreach($logs as $value)
            <tr>
              <td>{{date('d/m/Y', strtotime($value->created_at))}}</td>
              <td>{{$value->action}}</td>
              <td>{{$value->change}}</td>
              <td>{{$users->find($value->user_id)->name}}</td>
            </tr>
            @endforeach
            <tbody>
            </tbody>
          </table>
          <div class="row">
            <center>
              <a href="{{url("parameters")}}">
                <button type="button" class="btn btn-danger">
                  Cancelar
                </span>
              </button>
            </a>
          </center>
        </div>
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
{{-- <script type="text/javascript " src="{{ asset('assets/js/pagare/app.js') }} "></script> --}}
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
              message:'Debe ingresar un porcentaje de pago mínimo para renovar créditos'
            },
            regexp:{
              regexp: /^[0-9]\d*(\.\d+)?$/,
              message: 'Ingrese un porcentaje válido'
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
        amount_guarantor_required:{
          validators:{
            notEmpty:{
              message:'Debe ingresar monto mínimo para solicitar fiador.'
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
