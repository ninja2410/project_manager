@extends('app')
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css">

@stop
@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/sale.js', array('type' => 'text/javascript')) !!}
<div class="container">
  <div class="row">
    <div class="col-md-12 ">
      <div class="panel panel-default">
        <div class="panel-heading">
          <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> 
          {{trans('sale.sales_register')}}
        </div>
        <div class="panel-body">
          <input type="hidden" name="path" id="path" value="{{ url('/') }}">
          @if (Session::has('message'))
          <div class="alert alert-success">{{ Session::get('message') }}</div>
          @endif
          {!! Html::ul($errors->all()) !!}
          <div class="row" ng-controller="SearchItemCtrl" ng-init="nuevo('{{$id_almacen}}')">
           <input type="hidden" name="" value="{{$role_user[0]->id}}" id="role_user">
           <div class="col-md-3">
            <label>{{trans('sale.search_item')}} <input id="id_input_search"  ng-model="searchKeyword" class="form-control"></label>
            <table class="table table-hover">
              <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">
                <input type="hidden" id="existencias_@{{item.id}}" value="@{{item.quantity}}">
                <input type="hidden" id="nombre_@{{item.id}}" value="@{{item.item_name}}">
                <input type="hidden" id="precio_@{{item.id}}" value="@{{item.selling_price}}">
                <input type="hidden" id="precioBajo_@{{item.id}}" value="@{{item.low_price}}">

                <td><span id="@{{item.upc_ean_isbn}}" >@{{item.item_name}} <b>[EX=  @{{item.quantity}} ]</b></span> </td>
                <td>
                 <button class="btn btn-success btn-xs" ng-if="item.quantity>0" type="button" id="nuevo_@{{item.id}}" name="barra_@{{item.upc_ean_isbn}}" value="codig_barras_@{{item.upc_ean_isbn}}"  onclick="add(this)">
                   <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                 </button>
               </td>
             </tr>
           </table>
         </div>
         <div class="col-md-9">
          <div class="row">
            <div class="col-lg-5">
              <div class="form-group">
                <label for="customer_id" class="col-sm-3 control-label">Bodega</label>
                <div class="col-sm-9">
                  {!! Form::open(array('url' => 'sales','method' => 'get', 'class' => 'form-horizontal','id'=>'id_form_bodega')) !!}
                  <select class="form-control" name="id_bodegas" id="id_bodega"   >
                  <!-- <select class="form-control" name="id_bodegas" id="id_bodega"  onchange="cambioBodega()" > -->
                   @if($id_almacen==0)
                   <option value="0">No hay bodegas asignadas</option>
                   @else
                   @foreach($almacen as $value)
                   <option value="{!! $value->id !!}"  {{ ($id_almacen == $value->id) ?  'selected="selected"' : '' }}>{{ $value->name }}
                   </option>
                   @endforeach
                   @endif
                 </select>
                 {!! Form::close() !!}
               </div>
             </div>
           </div>
           <div class="col-lg-7">
            <div class="form-group">
              <label for="employee" class="col-sm-3 control-label">Vendedor</label>
              <div class="col-sm-9">
                <!-- <input type="text" class="form-control" id="employee" value="{{ Auth::user()->name }}" readonly/> -->
                <select name="employee" id="" class="form-control">
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
                <input type="text" name="name_customer" style="font-size: 11px" value="" id="name_customer" class="form-control" disabled>
              </div>
              <div class="col-ms-2">
                <a href="#" style="font-size: 12px" id="add_customer_btn" class="btn btn-raised btn-success btn-xs" data-toggle="modal" >Agregar</a>
              </div>
            </div>
          </div>  
        </div>
        <div class="row">
          <div class="form-group">
            <div class="col-lg-5">
              <label for="invoice" class="col-sm-3 control-label">{{trans('Serie')}}</label>
              <div class="col-sm-9" > 
                <?php if(isset($_GET['idFac'])){
                  $idFactura=$_GET['idFac'];}else{
                    $idFactura=0;
                  }?>
                  <div class="form-group">
                    <select class="form-control" name="serie_id" id="id_serie" >
                      <option value="0">Seleccione una serie</option>
                      @foreach($serieFactura as $value)
                      <option value="{!!$value->id!!}"{{ ($idFactura==$value->id)? 'selected="selected"' :''}} >{!!$value->nombre!!} - {!!$value->name!!}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-lg-7">
                <div class="form-group">
                  <label for="invoice" class="col-sm-3 control-label">{{trans('Correlativo')}}</label>
                  <div class="col-sm-4" >
                    <?php if(isset($_GET['correlativo'])){$valorCorrelative=$_GET['correlativo']; }
                    else{$valorCorrelative=0;}?>
                    <input type="text" name="correlativo_num" value="<?php echo  $valorCorrelative;?>" id="id_correlativo" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group">
              <div class="col-md-12">
                <table id="target" class="table table-bordered" >
                  <thead style="background: #9CBBD9">
                    <tr>
                      <th>ID</th>
                      <th width="30%">Producto</th>
                      <th>Precio</th>
                      <th>P. Venta</th>
                      <th>Cantidad</th>
                      <th>Total</th>
                      <th>&nbsp;</th>
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
            <div class="form-group">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="total" class="col-sm-4 control-label">{{trans('sale.add_payment')}}</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <div class="input-group-addon">Q</div>
                      <input type="text" class="form-control" id="add_payment" ng-model="add_payment" readonly/>
                    </div>
                  </div>
                </div>
                <div>&nbsp;</div>
                <div class="form-group">
                  <label for="employee" class="col-sm-4 control-label">{{trans('sale.comments')}}</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="comments" id="comments" />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="supplier_id" class="col-sm-4 control-label">{{trans('sale.grand_total')}}</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <div class="input-group-addon">Q</div>
                      <input type="text" class="form-control" id="total_pago" name="total_cost"  style="text-align:right;" value="0" readonly/>
                    </div>
                  </div>
                </div>
                {{--  {!! Form::hidden('total_cost','@{{sum(saletemp)}}', Input::old('total_cost'), array('class' => 'form-control')) !!}
                <div class="form-group">
                  <label for="amount_due" class="col-sm-4 control-label">{{trans('sale.amount_due')}}</label>
                  <div class="col-sm-8">
                    <p class="form-control-static"><label for="">0</label></p>
                  </div>
                </div>  --}}
              </div>
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
                    <a href="" class="btn btn-success btn-block" id="idVenta">
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
</div>
</div>
<!--  Ventana modal de listado de Customers-->
<div class="modal fade" id="modal-1" role="dialog" aria-labelledby="modalLabelsuccess">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" id="modalLabelsuccess">Listado de clientes</h4>

      </div>
      <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
        <div class="pull-right">
          <a href="#" style="font-size: 12px" id="add_customer_btn_2" class="btn btn-raised btn-success btn-xs" data-toggle="modal" data-target="#modal-2" data-dismiss="modal">Nuevo cliente</a>
        </div>
        <table class="table table-bordered table-striped"  id="table_customers">
          <thead>
            <td>No.</td>
            <td>Nit</td>
            <th>Nombre</th>
            <!-- <th>E-mail</th> -->
            <!-- <th>Teléfono</th> -->
            <th>Agregar</th>
          </thead>
          <tbody>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn  btn-danger" data-dismiss="modal">Cerrar!</button>
        </div>
      </div>
    </div>
  </div>
  <!--  Ventana modal de listado de Customers-->
  <!-- Modal new customer -->
  <div class="modal fade" id="modal-2" role="dialog" aria-labelledby="modalLabelsuccess">
    <div class="modal-dialog modal-lg" role="document">
     <form  method="post" id="idFormNewCustomer">
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
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="modal-footer">
              <button class="btn  btn-danger" data-dismiss="modal">Cerrar!</button>
              <button type="submit" class="btn  btn-primary" id="btnSaveCustomer">Guardar</button>
            </div>
          </div>
        </div>
      </div>
        </form>
    </div>
  </div>
  <!--  Fin del modal new customer-->

  <!-- Modal exists customer -->
  <div class="modal fade" id="myModal" role="dialog">
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
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar!</button>
        </div>
      </div>

    </div>
  </div>
  <!-- Modal exists customer -->
  <!-- Modal product empty -->
  <div class="modal fade" id="modal-no-existe-producto" role="dialog" aria-labelledby="modalLabelsuccess">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title" id="modalLabelsuccess">Error!!!!</h4>

        </div>
        <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
          <div class="pull-right">
            <a href="#" style="font-size: 12px" id="add_customer_btn_22" class="btn btn-raised btn-success btn-xs" data-toggle="modal" data-target="#modal-2" data-dismiss="modal">Nuevo cliente</a>
          </div>
          Seleccione algun producto
        </div>
        <div class="modal-footer">
          <button class="btn  btn-danger" data-dismiss="modal">Cerrar!</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal product empty -->
@endsection
@section('footer_scripts')
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>

<script type="text/javascript" src="{{asset('js/add.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/js/sales/validateCustomer.js') }}"></script>

<script type="text/javascript" src="{{asset('assets/js/sales/create.js') }}"></script>
@stop
