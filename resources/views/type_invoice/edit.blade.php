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
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/checklist/chklist.css') }}">
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
        @if (count($items)>=2)
          <div class="panel panel-default">
            <div class="col-lg-12">
                <h3>TIPOS DE FACTURACIÓN</h3>
            </div>
            <div class="row">
              <div class="container">
                <div class="row">
                  <div class="col-lg-11">
        						<ul class="nav nav-tabs">
        						  <li class="active"><a data-toggle="tab" href="#home">Resumida</a></li>
        						  <li><a data-toggle="tab" href="#menu1">Detallada</a></li>
        						</ul>
        						<div class="tab-content">
        						  <div id="home" class="tab-pane fade in active">
        								<div id="demo">
                          <h4>Seleccione un servicio para mostrar en facturación resumida.</h4>
                          <div class="bhoechie-tab-content active">
                            <center>
                              <form action="{{url('invoice_type/store')}}" method="POST" id="frm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="detail" id="det">
                                <label> <h3>Rubro de Facturación Resumida</h3> </label>
                                <select  class="form-control" name="resume" id="sel_resume" @if (isset($old->id))
                                  value="{{$old->resume}}"
                                @endif>
                                  @foreach ($items as $key => $value)
                                    <option @if (isset($old->id))
                                      @if ($old->resume==$value->id)
                                        selected
                                      @endif
                                    @endif value="{{$value->id}}">
                                      {{$value->id}}) {{$value->item_name}}
                                    </option>
                                  @endforeach
                                </select>
                              </form>
                            </center>
                          </div>
        								</div>
        						  </div>
        						  <div id="menu1" class="tab-pane fade">
        								<div id="demo">
                          <h4>Seleccione 2 servicios para mostrar en facturación detallada.</h4>
                          <div style="max-height: 300px; overflow-y: auto; -ms-overflow-style: -ms-autohiding-scrollbar;">
                          <table class="table table-bordered table-striped">
                            <thead>
                            <th style="width:10%">No</th>
                            <th style="width:40%">Servicio</th>
                            <th style="width:20%">Asignar</th>
                            <th style="width:30%">Valores</th>
                            </thead>
                            <tbody>
                            @foreach($items as $key=>$value)
                              <tr id="tr{{$value->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{$value->item_name}}</td>
                                <td>
                                  <label class="custom-control custom-checkbox">
                                      <input id="{{$value->id}}" type="checkbox"  class="custom-control-input" onchange="agregar(this)">
                                      <span class="custom-control-indicator"></span>
                                  </label>
                                </td>
                                <td>
                                  <label id="sel{{$value->id}}"></label>
                                </td>
                              </tr>
                            @endforeach
                            </tbody>
                          </table>
                          </div>
        								</div>
        						  </div>
        						</div>
        					</div>
                </div>
                <br>
                <div class="row" style="text-align:center;">
                <button type="button" onclick="enviarForm()" class="btn btn-primary" id="btn_save" >
                      {{trans('button.save')}}
                  </button>
                  <a class="btn btn-danger" href="{{url('invoice_type')}}">
                      Cancelar
                  </a>
                </div>
              </div>
              <br>
            </div>
            @include('percent_max.modal_percent')
          </div>
        @else
          <div class="panel panel-default">
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">No se puede configurar tipo de facturación</h4>
              <p>No se puede configurar tipo de facturación, debe agregar por lo menos 2 servicios. Dirigase al apartado de productos para crearlos.</p>
              <hr>
              <center><a href="{{url('items')}}"> <button type="button" name="button" class="btn btn-info">Agregar servicios</button> </a></center>
            </div>
          </div>
        @endif

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
  <script type="text/javascript">
  var agregados=[];

  function enviarForm(){
    if(agregados.length!=2){
      toastr.error('Debe seleccionar dos rubros de facturación detallada.');
    }
    else{
      $('#frm').submit();
    }
  }
  function agregar(chk){
    var sel=document.getElementById('sel'+chk.id);
    if(chk.checked){
      if(agregados.length==2)
      {
        toastr.error('Solo puede seleccionar dos rubros');
        chk.checked=false;
      }
      else if(agregados.length==0){
        sel.innerText="Porcenaje Máximo";
        agregados.push(chk.id);
      }
      else{
        sel.innerText="Resto de valor";
        agregados.push(chk.id);
      }
    }
    else{
      var index=agregados.indexOf(chk.id);
      agregados.splice(index, 1);
      if(agregados.length==1){
        document.getElementById('sel'+agregados[0]).innerText="Porcentaje Máximo";
      }
      sel.innerText="";
    }
    $('#det').val(agregados);
  }
  $(document).ready(function(){
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
    });
  </script>
@stop
