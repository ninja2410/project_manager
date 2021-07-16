@extends('layouts/default')

@section('title','Añadir imágenes a galería')
@section('page_parent','Galería')
@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/dropzone.css')}}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">{{trans('customer.new_customer')}}</div> -->
				<div class="panel-body">
					<div class="row">
						<h3>Añadir imágenes de cliente</h3>
						<form class="dropzone" action="{{ route('images')}}" method="post" id="my-awesome-dropzone">
							<input type="hidden" name="_token" id="token" value="{{csrf_token()}}">
							<input type="hidden" name="customer" value="{{$customer}}">
						</form>
					</div>
					<br>
					<center>
						<a class="btn btn-info" href="{{ url('images/index/'.$customer) }}">
								Aceptar
						</a>
					</center>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection
@section('footer_scripts')
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} " type="text/javascript "></script>
<script type="text/javascript" src="{{ asset('assets/js/dropzone.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/images/images.js') }}"></script>
<script type="text/javascript">
$('#newCustomer').bootstrapValidator({
		feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
		},
		message: 'Valor no valido',
		fields:{
				nit_customer:{
						validators:{
								notEmpty:{
										message:'Debe ingresar NIT del cliente. '
								}
						}
				},
				dpi:{
					validators:{
						notEmpty:{
							message:'Debe ingresar DPI.'
						},
						stringLength:{
							min:13,
							max:13,
							message:'DPI inválido, debe tener 13 dígitos.'
						},
						regexp:{
							regexp:/^[0-9]+$/,
							message:'DPI inválido, solo debe ingresar digitos del 0 al 9'
						}
					}
				},
				name:{
					validators:{
						notEmpty:{
							message:'Debe ingresar nombre.'
						}
					}
				},
				address:{
					validators:{
						notEmpty:{
							message:'Debe ingresar dirección.'
						}
					}
				},
				phone_number:{
					validators:{
						notEmpty:{
							message:'Debe ingresar un numero de teléfono'
						},
						stringLength:{
							min:8,
							max:8,
							message:'El número de telefono debe tener 8 digitos. '
						},
						regexp:{
							regexp: /^[0-9]+$/,
							message: 'Ingrese un número válido.'
						}
					}
				},
				marital_status:{
					validators:{
						notEmpty:{
							message:'Debe seleccionar un estado civil.'
						}
					}
				},
				birthdate:{
					validators:{
						notEmpty:{
							message:'Debe ingresar una fecha de nacimiento.'
						}
					}
				},
				refer1Nombre:{
					validators:{
						notEmpty:{
							message:'Debe ingresar nombre de referencia'
						}
					}
				},
				refer1Telefono:{
					validators:{
						notEmpty:{
							message:'Debe ingresar un numero de teléfono'
						},
						stringLength:{
							min:8,
							max:8,
							message:'El número de telefono debe tener 8 digitos. '
						},
						regexp:{
							regexp: /^[0-9]+$/,
							message: 'Ingrese un número válido.'
						}
					}
				},
				refer2Nombre:{
					validators:{
						notEmpty:{
							message:'Debe ingresar nombre de referencia'
						}
					}
				},
				refer2Telefono:{
					validators:{
						notEmpty:{
							message:'Debe ingresar un numero de teléfono'
						},
						stringLength:{
							min:8,
							max:8,
							message:'El número de telefono debe tener 8 digitos. '
						},
						regexp:{
							regexp: /^[0-9]+$/,
							message: 'Ingrese un número válido.'
						}
					}
				},
				refer3Nombre:{
					validators:{
						notEmpty:{
							message:'Debe ingresar nombre de referencia'
						}
					}
				},
				refer3Telefono:{
					validators:{
						notEmpty:{
							message:'Debe ingresar un numero de teléfono'
						},
						stringLength:{
							min:8,
							max:8,
							message:'El número de telefono debe tener 8 digitos. '
						},
						regexp:{
							regexp: /^[0-9]+$/,
							message: 'Ingrese un número válido.'
						}
					}
				}
		}
});
$(document).ready(function(){
	$("#birthdate").datetimepicker({
    locale:'es',
    defaultDate:new Date(2000, 10 - 1, 25),
    minDate: new Date(1900, 10 - 1, 25),
    format:'DD/MM/YYYY',
  }).parent().css("position :relative ");
});
</script>
@endsection
