@extends('layouts/default')
@section('title',trans('sale.sales_register'))
@section('page_parent',"Ventas")
@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/easy-autocomplete.min.css') }}" rel="stylesheet" type="text/css" />
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
        </div>
        <div class="panel-body">
          <input type="hidden" name="token" value="{{csrf_token()}}" id="token_">
          <input type="hidden" name="path" id="path" value="{{ url('/') }}">
          
          <div class="col-md-12">
            {!! Form::open(array('url' => 'sales','method' => 'post','id'=>'id_save_sales')) !!}
            <div class="row">
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
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('user_relation', trans('sale.salesman'), ['class' => 'control-label']) !!}
                  <div class="input-group select2-bootstrap-prepend">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                    {{-- {!! Form::select('user_relation', $dataUsers, null, array('class' => 'form-control','id'=>'user_relation')) !!} --}}
                    <select class="form-control" name="user_relation" id="user_relation">
                      {{-- <option value="0">Seleccione vendedor</option> --}}
                      @foreach($dataUsers as $id => $name)
                      <option value="{!! $id !!}" {{($id==$idUserActive ? 'selected' : '')}} >{{ $name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="id_bodegas" value="{{$id_almacen}}">

            <div class="row">
              {{-- cliente \/--}}
              <div class="col-lg-4">
                <div class="form-group>
                  {!! Form::label('customer_id', trans('sale.customer'), ['class' => 'control-label']) !!}
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
              {{-- cliente /\--}}
              {{-- serie \/--}}
              <div class="col-lg-4">
                <div class="form-group>
                  {!! Form::label('serie_id', trans('sale.document'), ['class' => 'control-label']) !!}
                  <div class="input-group select2-bootstrap-prepend">
                    <div class="input-group-addon"><i class="fa fa-folder"></i></div>
                    <select class="form-control" name="serie_id" id="id_serie">
                      {{-- <option value="0">Seleccione una serie</option> --}}
                      @foreach($serieFactura as $value)
                      <option value="{!!$value->id!!}" @if($idFac==$value->id) 'selected="selected"' @endif >{!!$value->nombre!!} - {!!$value->name. ' '.$value->id !!}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              {{-- serie /\--}}
              {{-- Número  \/--}}
              <div class="col-lg-4">
                {!! Form::label('id_correlativo', trans('sale.number')) !!}
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="livicon" data-name="sort-numeric-asc" data-size="16" data-c="#555555" data-hc="#555555" data-loop="true"></i>
                  </div>
                  {!! Form::text('correlativo_num', Input::old('id_correlativo'), array('id'=>'id_correlativo','class' => 'form-control')) !!}
                </div>
              </div>
              {{-- Número /\--}}


            </div>
            {{--  --}}
            <div class="row">
              <div class="form-group">
                <div class="col-md-12">
                  <table id="target" class="table table-bordered" >
                    <thead style="background: #9CBBD9">
                      <tr>
                        {{-- <th>No.</th> --}}
                        <th>Código</th>
                        <th width="30%">Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th width="0%">&nbsp;</th>
                        <th width="0%">&nbsp;</th>
                        <th width="5%" style="text-align:left;">&nbsp;</th>
                        <th width="0%">&nbsp;</th>
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
              <div class="col-lg-2">
                  <div class="form-group">
                    <input type="text" autocomplete="off" id="codigo" class="form-control" placeholder="Ingrese código">
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <input type="text" autocomplete="off" id="search" class="form-control" placeholder="Ingrese producto">
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="form-group">
                    <input type="text" id="autocom" class="easy-autocomplete" placeholder="Autocomplete" autocomplete="off">
                  </div>
                </div>
              </div>
              <!-- search box container ends  -->
              <div id="txtHint" class="title-color" style="padding-top:50px; text-align:center;" ><b></b></div>
              {{-- <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">

                    <table class="table table-striped" id="records_table">
                      <thead>
                        <th style="font-size:10px; text-align:left;">Código</th>
                        <th width="30%" style="font-size:11px; text-align:left;">Producto</th>
                        <th style="font-size:12px; text-align:center;">Exist</th>
                        <th style="font-size:12px; text-align:left;">Agregar</th>
                        <th style="display:none;">&nbsp;</th>
                        <th style="display:none;">&nbsp;</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>

                    <table id="table_reference" style="display:none;">
                      <thead>
                        <th>id</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                    <div class="col-md-12 center text-center">
                      <span class="left" id="total_reg"></span>
                      <ul class="pagination pager" id="myPager"></ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-1">
                </div>
                <div class="col-lg-7">
                  <div class="form-group">
                    <table id="target" class="table table-striped">
                      <thead>
                        <tr style="background-color:rgb(160, 189, 200);">
                          <th width="50%">Producto</th>
                          <th width="15%">Cantidad</th>
                          <th width="15%">Precio</th>
                          <th width="15%">Subtotal</th>
                          <th width="0%">&nbsp;</th>
                          <th width="0%">&nbsp;</th>
                          <th width="5%" style="text-align:left;">&nbsp;</th>
                          <th width="0%">&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div> --}}
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
                  <label>Total factura:</label>
                  <input type="hidden" name="item_quantity" value="0" id="item_quantity">
                  <input type="hidden" name="total_cost" style="border: none;text-align:center;background-color: white;"   size="10"  id="total_general" value="0">
                  <input type="text" name="total_cost2" style="border: none;text-align:center;background-color: white;" disabled  size="10"  id="total_general2" value="0">

                  <input type="hidden" name="number_correlative" value="0" id="number_correlative">
                  <input type="hidden" name="types_payments" value="0" id="types_payments">
                  <input type="hidden" name="patient" value="0" id="patient">
                  <input type="hidden" name="owner_name" value="" id="owner_name">
                  <input type="hidden" name="nit_owner" value="" id="nit_owner">
                  <input type="hidden" name="owner_address" value="" id="owner_address">
                  <input type="hidden" name="" value="" id="id_response">
                  <input type="hidden" name="fecha_creacion" id="fecha_creacion">
                </div>
              </div>
              {!! Form::close() !!}
              <div class="row">
                <div class="form-group">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="col-sm-4" style="aling:center;"></div>
                      <div class="col-sm-4" style="aling:center;"></div>
                      <div class="col-sm-4" style="aling:center;">
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary btn-block" id="idVenta">
                            {{trans('sale.submit')}}
                          </button>
                          {{-- <a href="#" class="btn btn-primary btn-block" id="idVenta"> </a>--}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal new customer -->
    <div class="modal fade in" id="modal-2" tabindex="-1" role="dialog" aria-hidden="false">
      <div class="modal-dialog modal-lg" role="document">
        <form method="post" id="idFormNewCustomer">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h4 class="modal-title" id="modalLabelsuccess">Agregar nuevo cliente</h4>
            </div>
            <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="nit_customer">Nit cliente: *</label>
                  <input type="text" name="nit_customer2" value="C/F" class="form-control" required id="nit_customer2">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="name_customer">Nombre: *</label>
                  <input type="text" name="name_customer2" value="" class="form-control" required id="name_customer2">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="address_customer">Dirección: *</label>
                  <input type="text" name="address_customer2" value="Ciudad" class="form-control" required id="address_customer2">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="address_customer">Teléfono:</label>
                  <input type="text" name="phone" maxlength="8" class="form-control" id="phone">
                </div>
              </div>
              {{-- <div class="col-lg-6">
                <div class="form-group">
                  <label for="address_customer">DPI:</label>
                  <input type="text" name="dpi2" maxlength="13" class="form-control" id="dpi">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="address_customer">Email:</label>
                  <input type="text" name="email2" class="form-control" id="email">
                </div>
              </div> --}}
            </div>
            <div class="row">
              <div class="col-lg-5"></div>
              <div class="col-lg-3">
                <button type="button" class="btn  btn-primary" id="btnSaveCustomer">Guardar</button>
                <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
              </div>
              <div class="col-lg-4"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!--  Fin del modal new customer-->

  <!-- Modal exists customer -->
  <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title ">Error!!!!!!</h4>
        </div>
        <div class="modal-body">
          <p>Ya existe un empleado con ese nombre.</p>
        </div>
        <div class="modal-footer bg-danger">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>

    </div>
  </div>
  <!-- Modal exists customer -->
  <!-- Modal product empty -->
  <div class="modal fade in" id="modal-no-existe-producto" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title" id="modalLabelsuccess">Error!!!!</h4>

        </div>
        <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
          <div class="pull-right">
            <a href="#" style="font-size: 12px" id="add_customer_btn_2" class="btn btn-raised btn-success btn-xs" data-toggle="modal"
            data-target="#modal-2" data-dismiss="modal">Nuevo cliente</a>
          </div>
          Seleccione algun producto
        </div>
        <div class="modal-footer">
          <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal product empty -->
</section>
@endsection

@section('footer_scripts')
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery-1.12.4.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
<script type="text/javascript " src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>

<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
{{-- CUSTOM JS --}}
<script type="text/javascript " src="{{ asset('assets/js/sales/customer_add.js')}} "></script>
<script type="text/javascript " src="{{ asset('assets/js/sales/validations_new.js')}} "></script>
<script type="text/javascript " src="{{ asset('assets/js/sales/new_sale.js')}} "></script>

<script src="{{asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
<script>

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
    function consultar(texto,bodega)
    {
      $.get( "{{ url('api/sales/search?id=') }}"+texto+"&bodega_id="+bodega, function( data ) {
        $( "#txtHint" ).html( data );
      });
    };

    function buscar_codigo(codigo,bodega)
    {
      $.get( "{{ url('api/sales/search_code?id=') }}"+codigo+"&bodega_id="+bodega, function( data ) {
        $( "#txtHint" ).html( data );
      });
    };
    function autocomplete()
    {
      var options = {
        url: "api/sales/autocomplete",

        getValue: "item_name",

        template: {
          type: "descrition",
          fields: {
            description: "upc_ean_isbn"
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
          onChooseEvent: function() {
            var value = $("#autocom").getSelectedItemData();
            agregar(value);
            // console.log(value);
            // $("#data-holder").val(value).trigger("change");
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
          data.id = $("#autocom").val()   ;
          return data;
        },

        requestDelay: 400
      };

      $("#autocom").easyAutocomplete(options);
    }
    $( document ).ready(function() {
      autocomplete();
      $("#date_tx").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
      var fecha= new Date();
      document.getElementById('date_tx').value=get_date_today();

      $('select').select2({
        allowClear: true,
        theme: "bootstrap",
        placeholder: "Buscar"
      });
      document.getElementById("idFormNewCustomer").onkeypress = function(e) {
        var key = e.charCode || e.keyCode || 0;
        if (key == 13) {
          // alert("I told you not to, why did you do it?");
          e.preventDefault();
        }
      }

      $("#search").keyup(function(){
        var str=  $("#search").val().trim();
        var largo = $("#search").val().length;
        var bdg=  $("#id_bodega").val().trim();
        console.log('bodega '+bdg+' texto '+largo);
        if((str == "") || (bdg == 0)) {
          $( "#txtHint" ).html("<b>Ingrese un texto o seleccione una bodega</b>");
        }else {
          consultar(str,bdg);
        }
      });

      $("#codigo").keydown(function(event){
        if( event.which == 13)
        {
          var codigo=  $("#codigo").val().trim();
          var bodega=  $("#id_bodega").val().trim();
          if((codigo == "") || (bodega == 0)) {
            $( "#txtHint" ).html("<b>Ingrese un el código y seleccione una bodega</b>");
          }else {
            buscar_codigo(codigo,bodega);
          }
        };
      });


      $('#id_bodega').trigger("change");
      $('#id_serie').trigger("change");




      function logKey(event) {
        if( event.which == 13)
        {
          var myTable = document.getElementById('records_table');
          var rows= myTable.rows.length;
          if(rows==2){
            var row =myTable.rows[1];
            boton=row.cells[5].firstChild;
            // console.log(' event repeat '+event.repeat);
            add(boton);
            event.stopPropagation();
          }
        }
      }


      $('#codigo').on( 'draw.dt', function () {
        document.addEventListener('keydown', logKey);
      } );

    });
  </script>
  @stop
