@extends('layouts/default')
@section('title',trans('sale.sales_register'))
@section('page_parent',trans('sale.sales'))
@section('header_styles')

{{-- bootstrap validator --}}
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />
{{-- select 2 --}}
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
{{-- date time picker --}}
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
{{-- wizad --}}
<link href="{{ asset('assets/vendors/wizard/jquery-steps/css/jquery.steps.css') }}" rel="stylesheet" >
{{-- autocomplete --}}
<link href="{{ asset('assets/css/easy-autocomplete.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content')
<section class="content">
  <div class="row" style="padding-top:5px;">
    <div class="col-lg-12 ">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="shopping-cart-out" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('sale.sales_register')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div> {{-- panel-heading--}}
        <div class="panel-body">
          
          {{--  --}}
          <div id="wizard">
            <h4>Venta</h4>
            <section>
              {!! Form::open(array('url' => 'sales','method' => 'post','id'=>'id_save_sales','class'=>'form')) !!}
              <input type="hidden" name="token" value="{{csrf_token()}}" id="token_">
              <input type="hidden" name="path" id="path" value="{{ url('/') }}">

              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('date_tx', trans('Fecha')) !!}
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="livicon" data-name="calendar" data-size="16" data-c="#555555" data-hc="#555555" data-loop="true"></i>
                    </div>
                    {!! Form::text('date_tx', Input::old('date_tx'), array('id'=>'date_tx','class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('id_bodega', trans('sale.storage'), ['class' => 'control-label']) !!}
                  <div class="input-group select2-bootstrap-prepend">
                    <div class="input-group-addon"><i class="fa fa-archive"></i></div>
                    <select class="form-control" name="id_bodegas" id="id_bodega">
                      <option value="0">Seleccione bodega</option>
                      @foreach($almacen as $value)
                      <option value="{!! $value->id !!}" {{($value->id==$id_almacen ? 'selected' : '')}}>{{ $value->name }}
                      </option>
                      @endforeach
                    </select>
                    <input type="hidden" name="id_bodegas" value="{{$id_almacen}}">
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('payment_method', trans('sale.payment_method')) !!}
                  <div class="input-group select2-bootstrap-prepend">
                    <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                    <select class="form-control" title="trans('revenues.payment_method')" name="id_pago" id="id_pago" >
                      <option value="0">--Seleccione--</option>
                      @foreach($payments as $item)
                      <option value="{!! $item->id !!}" @if(old('payment_method') === $item->id) selected="selected" @endif>{{ $item->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group>
                    <label for="customer_id" class="control-label">{{trans('sale.customer')}}</label>
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-users"></i></div>
                      <select class="form-control" name="customer_id" id="customer_id">
                        <option value="0">Seleccione cliente</option>
                        @foreach($customer as $value)
                        <option value="{!! $value->id !!}">{{ $value->name }}
                        </option>
                        @endforeach
                      </select>
                      <div class="input-group-btn">
                        <a href="#" style="font-size: 14px" id="add_customer_btn" class="btn btn-default btn-icon" data-toggle="modal" data-target="#modal-2"><i class="fa fa-plus"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group>
                    <label for="serie_id" class="control-label">{{trans('sale.document')}}</label>
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-folder"></i></div>
                      <select class="form-control" name="serie_id" id="id_serie">
                        @foreach($serieFactura as $value)
                        <option value="{!!$value->id!!}" @if($idFac==$value->id) selected="selected" @endif >{!!$value->nombre!!} - {!!$value->name. ' '.$value->id !!}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-1">
                  <div class="form-group>
                    <label for="id_correlativo" class="control-label">{{trans('sale.number')}}</label>
                    {{-- <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="check" data-size="16" data-c="#555555" data-hc="#555555" data-loop="true"></i>
                      </div> --}}
                      {!! Form::text('correlativo_num', Input::old('id_correlativo'), array('id'=>'id_correlativo','class' => 'form-control')) !!}
                      {{-- </div> --}}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      {!! Form::label('user_relation', trans('sale.salesman'), ['class' => 'control-label']) !!}
                      <div class="input-group select2-bootstrap-prepend">
                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                        <select class="form-control" name="user_relation" id="user_relation">
                          @foreach($dataUsers as $id => $name)
                          <option value="{!! $id !!}" {{($id==$idUserActive ? 'selected' : '')}} >{{ $name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                {{--  --}}
                <br>
                <div class="col-lg-2">
                  <div class="form-group" style="text-align: right;">
                    <label class="control-label" style="padding-top: 0.5em;">{{trans('sale.search_item')}}</label>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="barcode" data-size="16" data-c="#555555" data-hc="#555555" data-loop="true"></i>
                      </div>
                      <input type="text" autocomplete="off" id="codigo" class="form-control" placeholder="Ingrese código">
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="search" data-size="16" data-c="#555555" data-hc="#555555" data-loop="true"></i>
                      </div>
                      {{-- <div class="easy-autocomplete" style="width: 305px !important"> --}}
                        <input type="text" id="autocom" class="easy-autocomplete" placeholder="Buscar por nombre" autocomplete="off">
                        {{-- </div> --}}
                        {{-- <input type="text" id="autocom" class="easy-autocomplete" placeholder="Autocomplete" autocomplete="off"> --}}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <a class="btn btn-small btn-info" href="#" id="add_item_btn" data-toggle="modal" data-target="#modal-products"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Mostrar listado completo</a>
                    </div>
                  </div>
                  {{--  --}}


                  {{-- <div class="col-lg-12">
                    <div id="txtHint" class="title-color" style="padding-top:50px; text-align:center;" ><b></b></div>
                  </div> --}}
                  {{--  --}}
                  <div class="row">
                    <div class="form-group">
                      <div class="col-md-12">
                        <table id="target" class="table table-bordered" >
                          <thead style="background: #9CBBD9">
                            <tr>
                              {{-- <th>No.</th> --}}
                              <th>Código</th>
                              <th style="width:30%">Producto</th>
                              <th>Precio</th>
                              <th>Cantidad</th>
                              <th>Total</th>
                              <th style="width:0%">&nbsp;</th>
                              <th style="width:0%">&nbsp;</th>
                              <th style="width:5%;text-align:left;">&nbsp;</th>
                              <th style="width:0%">&nbsp;</th>
                              {{--  <th>&nbsp;</th>  --}}
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>



                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="comments" class="col-sm-4 control-label">{{trans('sale.comments')}}</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" name="comments" id="comments" />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" style="text-align: right;font-size: 30px;">
                      <label>Total:</label>
                      <input type="hidden" name="item_quantity" value="0" id="item_quantity">
                      <input type="hidden" name="total_cost" style="border: none;text-align:center;background-color: white;"   id="total_general" value="0">
                      <input type="text" name="total_cost2" style="border: none;text-align:center;background-color: white;" disabled  id="total_general2" value="0">

                      <input type="hidden" name="number_correlative" value="0" id="number_correlative">
                      <input type="hidden" name="types_payments" value="0" id="types_payments">
                      <input type="hidden" name="patient" value="0" id="patient">
                      <input type="hidden" name="owner_name" value="" id="owner_name">
                      <input type="hidden" name="nit_owner" value="" id="nit_owner">
                      <input type="hidden" name="owner_address" value="" id="owner_address">
                      <input type="hidden" name="id_response" value="" id="id_response">
                      <input type="hidden" name="fecha_creacion" id="fecha_creacion">
                    </div>
                  </div>
                  {!! Form::close() !!}
                  <div class="row">
                    <div class="form-group">
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="col-sm-4" style="text-align:center;"></div>
                          <div class="col-sm-4" style="text-align:center;"></div>
                          <div class="col-sm-4" style="text-align:center;">
                            <div class="form-group">
                              <button  class="btn btn-primary btn-block" id="idVenta">
                                {{trans('sale.submit')}}
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>

                <h4>Pago</h4>
                <section>
                  <p>
                    Donec mi sapien, hendrerit nec egestas a, rutrum vitae dolor. Nullam venenatis diam ac ligula elementum pellentesque.
                    In lobortis sollicitudin felis non eleifend. Morbi tristique tellus est, sed tempor elit. Morbi varius, nulla quis condimentum
                    dictum, nisi elit condimentum magna, nec venenatis urna quam in nisi. Integer hendrerit sapien a diam adipiscing consectetur.
                    In euismod augue ullamcorper leo dignissim quis elementum arcu porta. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Vestibulum leo velit, blandit ac tempor nec, ultrices id diam. Donec metus lacus, rhoncus sagittis iaculis nec, malesuada a diam.
                    Donec non pulvinar urna. Aliquam id velit lacus.
                  </p>
                </section>
              </div>

              {{-- </div> --}}
            </div> {{-- panel-body--}}
          </div>{{-- panel-primary--}}
        </div>{{-- col-lg-12 --}}
      </div>{{-- row --}}

      <!-- Modal new customer -->
      <div class="modal fade in" id="modal-2" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg" role="document">
          <form method="post" id="idFormNewCustomer">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalLabelsuccess">Agregar nuevo cliente</h4>
              </div> {{-- modal-header --}}
              <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="nit_customer2">Nit cliente: *</label>
                    <input type="text" name="nit_customer2" value="C/F" class="form-control" required id="nit_customer2">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="name_customer2">Nombre: *</label>
                    <input type="text" name="name_customer2" value="" class="form-control" required id="name_customer2">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="address_customer2">Dirección: *</label>
                    <input type="text" name="address_customer2" value="Ciudad" class="form-control" required id="address_customer2">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="phone">Teléfono:</label>
                    <input type="text" name="phone" maxlength="8" class="form-control" id="phone">
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-5"></div>
                  <div class="col-lg-3">
                    <button type="button" class="btn  btn-primary" id="btnSaveCustomer">Guardar</button>
                    <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                  </div>
                  <div class="col-lg-4"></div>
                </div> {{-- row --}}
              </div> {{-- modal-body --}}

            </div> {{-- modal-content --}}
          </form>
        </div> {{-- modal-dialog  --}}

      </div> {{-- modal fade in  --}}
      {{-- </div> --}}
      <!--  Fin del modal new customer-->
      {{-- Inicio Modal productos --}}
      @include('partials.list-products')
      {{-- Fin Modal productos --}}

    </section>
    @endsection

    @section('footer_scripts')

    {{-- bootstrap validator --}}
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
    {{-- Select2 --}}
    <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>

    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    {{-- CUSTOM JS --}}
    <script type="text/javascript" src="{{ asset('assets/js/sales/customer_add.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/sales/validations_new.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/sales/new_sale.js')}} "></script>

    {{-- autocomplete --}}
    <script src="{{asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/sales/sales-table.js')}} "></script>
    {{-- Wizard --}}

    <script src="{{asset('assets/vendors/wizard/jquery-steps/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/vendors/wizard/jquery-steps/js/wizard.js')}}"></script>
    <script src="{{asset('assets/vendors/wizard/jquery-steps/js/jquery.steps.js')}}"></script>
    <script>


      /**
      * Funciones cuando la pagina este cargada - INICIO \/
      */
      $( document ).ready(function() {
        document.getElementById("idFormNewCustomer").onkeypress = function(e) {
          var key = e.charCode || e.keyCode || 0;
          if (key == 13) {
            e.preventDefault();
          }
        }

        document.getElementById("autocom").onkeypress = function(e) {
          var key = e.charCode || e.keyCode || 0;
          if (key == 13) {
            e.preventDefault();
          }
        };
        autocomplete();

        $("#codigo").keydown(function(event){
          if( event.which == 13)
          {
            event.preventDefault();
            var codigo=  $("#codigo").val().trim();
            if((typeof $("#id_bodega").val() === 'undefined') || ($("#id_bodega").val() === null))
            {
              toastr.error("Seleccione una bodega.");
            }
            else {
              var bodega=  $("#id_bodega").val().trim();
              if((codigo !== "") ) {
                buscar_codigo(codigo,bodega);
              }
            }
          };
        });
        $("#date_tx").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
        document.getElementById('date_tx').value=get_date_today();

        $('select').select2({
          allowClear: true,
          theme: "bootstrap",
          placeholder: "Buscar"
        });


        $('#id_bodega').trigger("change");
        $('#id_serie').trigger("change");
        $(function () {
          $("#wizard").steps({
            headerTag: "h4",
            bodyTag: "section",
            transitionEffect: "slideLeft"
          });
        });
      });
      /**
      * Funciones cuando la pagina este cargada - INICIO /\
      */

      $('#date_tx').blur(function(){
        if($('#date_tx').val()=="") {
          $('#date_tx').val(get_date_today());
        } });

        function get_date_today()
        {
          var f = new Date();
          var fechaCompleta=f.getDate()+"/"+(f.getMonth()+1)+"/"+f.getFullYear();
          return fechaCompleta;
        }

        function buscar_codigo(codigo,bodega)
        {
          $.get( "{{ url('api/sales/search_code?id=') }}"+codigo+"&bodega_id="+bodega, function( data ) {
            agregar(data);/*Llamamos la funcion para agregar a la tabla de productos*/
          });
        };

        function autocomplete()
        {
          var options = {
            url: "../api/sales/autocomplete",

            getValue: "item_name",

            template: {
              type: "custom",
              method: function(value, item) {
                return '<strong>'+item.upc_ean_isbn +  " | " + value + '</strong> | Q.' + item.selling_price;
              }
            },

            list: {
              maxNumberOfElements: 10,
              match: {
                enabled: true
              },
              sort: {
                enabled: true
              },
              onChooseEvent: function() { /*Cuando seleccionan elemento*/
                var value = $("#autocom").getSelectedItemData(); /*Obtenemos el objeto con la data que viene de ajax*/
                agregar(value); /*Llamamos la funcion para agregar a la tabla de productos*/
              }
            },

            theme: "bootstrap",

            ajaxSettings: {
              dataType: "json",
              method: "GET",
              data: {
              }
            },

            preparePostData: function(data) {
              data.id = $("#autocom").val();
              data.bodega_id=$("#id_bodega").val();
              return data;
            },

            requestDelay: 400
          };

          $("#autocom").easyAutocomplete(options);
        }

      </script>
      @stop
