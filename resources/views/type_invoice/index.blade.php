@extends('layouts/default')
@section('title','Parámetros de Facturación')
@section('page_parent',trans('credit.title'))

@section('header_styles')
  <!--  Tablas -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tabs.css') }}">
  <!--  Tablas -->
  <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
  <!-- Validaciones -->
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> 
  <!-- Toast -->
  <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content')
  <section class="content">
  	<div class="row">
  		<div class="col-md-12">
  			<div class="panel panel-default">
          <div class="row">
            <center>
              <b> <h3>TIPOS DE FACTURACIÓN</h3> </b>
            </center>
            <div class="container">
            @if(isset($det1))
              <div class="col-lg-12">
                  <h3>Facturación Resumida</h3>
                  <br>
                  <p>El detalle de factura de crédito únicamente contendrá el rubro:</p>
                  <br>
                  <div class="col-lg-4">
                  </div>
                  <div class="col-lg-4">
                    <input type="text" readonly class="form-control" name="" value="{{$res->item_name}}">
                  </div>
              </div>
              <br>
              <hr>
              <br>
              <hr>
              <div class="row">
                <hr>
                <div class="col-lg-12">
                  <h3>Facturación Detallada</h3>
                  <div class="col-lg-11">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <th>ID</th>
                        <th>SERVICIO</th>
                        <th>PORCENTAJE</th>
                      </thead>
                      <tbody>
                        <tr>
                          <td>{{$det1->id}}</td>
                          <td>{{$det1->item_name}}</td>
                          <td>% Máximo</td>
                        </tr>
                        <tr>
                          <td>{{$det2->id}}</td>
                          <td>{{$det2->item_name}}</td>
                          <td>Resto</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            @else
              <h4 style="text-align:center;">No se ha registrado ningún tipo de facturación, <br> registre uno para poder generar facturas de créditos.</h4>

              @endif
            <div class="row" style="text-align:center;">
              @if(isset($param))
                <a class="btn btn-info" href="{{url('invoice_type/edit')}}">
                    Cambiar
                </a>
              @else
              <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">No se puede configurar tipo de facturación</h4>
                <p>Debe registrar parametros generales para poder editar esta opción. </p>
                <hr>
                <center><a href="{{url('parameters')}}"> <button type="button" name="button" class="btn btn-info" >Parametros Generales</button> </a></center>
              </div>
              @endif
              </div>
            </div>
            <br>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('footer_scripts')
  <script src="{{asset('assets/js/vuejs/vue.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/numeral.min.js')}} "></script>
  <!--  Axios -->
  <script src="{{asset('assets/js/vuejs/axios.min.js')}} "></script>
  <!--  Axios -->
  <!--  Tablas -->
  <script type="text/javascript " src="{{ asset('assets/js/datatables/jquery.dataTables.min.js')}} "></script>
  <script type="text/javascript " src="{{ asset('assets/js/datatables/dataTables.bootstrap.min.js')}} "></script>
  <!--  Tablas -->
  <!--  Calendario -->
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} " type="text/javascript "></script>
  <!--  Calendario -->
  <!-- Valiadaciones -->
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  <script type="text/javascript " src="{{ asset('assets/js/pagare/validaciones.js') }} "></script>
  <!-- Toast -->
  <script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
  <script type="text/javascript " src="{{ asset('assets/js/pagare/app.js') }} "></script>

@stop
