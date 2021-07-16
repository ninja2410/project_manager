@extends('layouts/default')
@section('title',trans('sale.sales_register'))
@section('page_parent',"Ventas")
@section('header_styles')
{{-- CSS TO SELECT --}}
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<section class="content">
  <div class="row">
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
            <div class="row">
              <div class="col-lg-5">
                <div class="form-group">
                  <label for="customer_id" class="col-sm-3 control-label">Bodega</label>
                  <div class="col-sm-9">
                    {!! Form::open(array('url' => 'sales','method' => 'get', 'class' => 'form-horizontal','id'=>'id_form_bodega')) !!}
                    <select class="form-control" name="id_bodegas" id="id_bodega">
                      <!-- <select class=" form-control " name="id_bodegas " id="id_bodega "  onchange="cambioBodega() " > -->
                        @if($id_almacen=="")
                        <option value="">No hay bodegas asignadas</option>
                        @else
                        <option value="">Seleccione bodega</option>
                        @foreach($almacen as $value)
                        <option value="{!! $value->id !!}">{{ $value->name }}
                        </option>
                        @endforeach
                        @endif
                      </select> {!! Form::close() !!}
                    </div>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="form-group">
                    <label for="employee" class="col-sm-3 control-label">Vendedor</label>
                    <div class="col-sm-9">
                      <select name="employee" id="employee" class="form-control">
                        <option value="0">Seleccione vendedor</option>
                        @foreach($dataUsers as $key => $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                        @endForeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              {!! Form::open(array('url' => 'sales','method' => 'post','id'=>'id_save_sales')) !!}
              <input type="hidden" name="id_bodegas" value="{{$id_almacen}}">
              <input type="hidden" id="item_quantity" value="0" name="item_quantity">
              <div class="row">
                <div class="col-lg-5">
                  <div class="form-group">
                    <label for="customer_id" class="col-sm-3 control-label">Pago:</label>
                    <div class="col-sm-9">
                      <?php if(isset($_GET['tipoPago'])) ?>
                      <select class="form-control" name="id_pago" id="id_pago">
                        @foreach($pagoss as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="form-group">
                    <label for="customer_id" class="col-sm-3 control-label">{{trans('sale.customer')}}</label>
                    <div class="col-sm-7">
                      <input type="hidden" name="customer_id" value="0" id="customer_id">
                      <input type="hidden" name="user_relation" value="0" id="user_relation">
                      <input type="text" name="name_customer" style="font-size: 11px" value="" id="name_customer" class="form-control" disabled>
                    </div>
                    <div class="col-sm-2">
                      <a href="#" style="font-size: 12px" id="add_customer_btn" class="btn btn-raised btn-success btn-xs" data-toggle="modal" data-target="#modal-1">Agregar</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="form-group">
                  <div class="col-lg-5">
                    <label for="invoice" class="col-sm-3 control-label">{{trans('Serie')}}</label>
                    <div class="col-sm-9">
                      <?php if(isset($_GET['idFac'])){
                        $idFactura=$_GET['idFac'];}else{
                          $idFactura=0;
                        }?>
                        <div class="form-group">
                          <select class="form-control" name="serie_id" id="id_serie">
                            <option value="0">Seleccione una serie</option>
                            @foreach($serieFactura as $value)
                            <option value="{!!$value->id!!}" @if($idFactura==$value->id)) 'selected="selected"' @endif >{!!$value->nombre!!} - {!!$value->name!!}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-7">
                      <div class="form-group">
                        <label for="invoice" class="col-sm-3 control-label">{{trans('Correlativo')}}</label>
                        <div class="col-sm-4">
                          <?php if(isset($_GET['correlativo'])){$valorCorrelative=$_GET['correlativo']; }
                          else{$valorCorrelative=0;}?>
                          <input type="text" name="correlativo_num" value="<?php echo  $valorCorrelative;?>" id="id_correlativo" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
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
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="employee" class="col-sm-4 control-label">{{trans('sale.comments')}}</label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" name="comments" id="comments" />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" style="text-align: right;font-size: 30px;">
                      <label style="color:blue;">Total factura:</label>
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
                            <a href="#" class="btn btn-success btn-block" id="idVenta">
                              {{trans('sale.submit')}}
                            </a>
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

        <!--  Ventana modal de listado de Customers-->
        <div class="modal fade in" id="modal-1" tabindex="-1" role="dialog" aria-hidden="false">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalLabelsuccess">Listado de clientes</h4>
              </div>
              <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
                <div class="pull-left">
                  <a href="#" id="add_customer_btn_2" class="btn btn-raised btn-success btn-xs" data-toggle="modal" data-target="#modal-2"
                  data-dismiss="modal">Nuevo cliente</a>
                </div>
                <br>
                <table class="table table-bordered table-striped" id="table_customers">
                  <thead>
                    <td>No.</td>
                    <td>Nit</td>
                    <th>Nombre</th>
                    <th>E-mail</th>
                    <th>Teléfono</th>
                    <th>Agregar</th>
                  </thead>
                  <tbody>
                    @foreach($customer as $i=> $value)
                    <tr>
                      <td>{{$i+1}}</td>
                      <td>{{$value->nit_customer}}</td>
                      <td>{{$value->name}}</td>
                      <td>{{$value->email}}</td>
                      <td>{{$value->phone_number}}</td>
                      <td>
                        <!-- <button  type="button" name="button" class="btn btn-primary btn-xs" id="name_{{$value->nit_customer.'/'.$value->id}}" onclick="add_supplier(this);" > -->
                          <button type="button" name="button" class="btn btn-primary btn-xs" id="name_{{$value->name.'/'.$value->id}}" onclick="add_customers(this);"
                            data-dismiss="modal">
                            <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                          </button>
                        </td>
                      </tr>
                      @endforeach

                    </tbody>
                  </table>

                  <div class="modal-footer">
                    <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--  Ventana modal de listado de Customers-->
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
                        <input type="text" name="nit_customer2" value="c/f" class="form-control" required id="nit_customer2">
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
                        <label for="address_customer">DPI:</label>
                        <input type="text" name="dpi2" maxlength="13" class="form-control" id="dpi">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="address_customer">Email:</label>
                        <input type="text" name="email2" class="form-control" id="email">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="address_customer">Teléfono:</label>
                        <input type="text" name="phone" maxlength="8" class="form-control" id="phone">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="modal-footer">
                        <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn  btn-primary" id="btnSaveCustomer">Guardar</button>
                      </div>
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
        {{-- SCRIPT TO SELECT 2 --}}
        <script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
        
        <script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery-1.12.4.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery.dataTables.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/assets/vendors/print/js/dataTables.bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
        <script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
        {{-- CUSTOM JS --}}
        <script type="text/javascript " src="{{ asset('assets/js/sales/customer.js')}} "></script>
        <script type="text/javascript " src="{{ asset('assets/js/sales/validations.js')}} "></script>
        <script type="text/javascript " src="{{ asset('assets/js/sales/app.js')}} "></script>

        <script>
          $( document ).ready(function() {
            $('#id_bodega').trigger("change");

            function logKey(event) {
              if( event.which == 13)
              {
                var myTable = document.getElementById('records_table');
                var rows= myTable.rows.length;
                if(rows==2){
                  var row =myTable.rows[1];
                  boton=row.cells[5].firstChild;
                  console.log(' event repeat '+event.repeat);
                  add(boton);
                  event.stopPropagation();
                }
              }
            }


            $('#records_table').on( 'draw.dt', function () {
              document.addEventListener('keydown', logKey);
            } );
            
            $('#ruta').select2({
              allowClear: true
            });
          });
        </script>
        @stop
