@extends('layouts/default')
@section('title',trans('sale.sales_register'))
@section('page_parent',"Ventas")
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css"> -->

<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content') {!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!} {!! Html::script('js/sale.js',
array('type' => 'text/javascript')) !!}
<section class="content">
  <div class="row">
    <div class="col-md-12 ">
      <div class="panel panel-default">
        <div class="panel-body">
          <input type="hidden" name="path" id="path" value="{{ url('/') }}"> @if (Session::has('message'))
          <div class="alert alert-success">{{ Session::get('message') }}</div>
          @endif {!! Html::ul($errors->all()) !!}
          <div class="row" ng-controller="SearchItemCtrl" ng-init="nuevo('{{$id_almacen}}')">
            <input type="hidden" name="" value="{{$role_user[0]->id}}" id="role_user">
            <div class="col-md-3">
              <label>{{trans('sale.search_item')}} <input id="id_input_search"  ng-model="searchKeyword" class="form-control"></label>
              <table class="table table-hover">
                <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">
                  <input type="hidden" id="existencias2_@{{item.id}}" value="@{{item.quantity}}">
                  <input type="hidden" id="nombre_@{{item.id}}" value="@{{item.item_name}}">
                  <input type="hidden" id="precio_@{{item.id}}" value="@{{item.selling_price}}">
                  <input type="hidden" id="precioBajo_@{{item.id}}" value="@{{item.low_price}}">
                  <input type="hidden" id="type_@{{item.id}}" value="@{{item.type}}">
                  <input type="hidden" id="stock_action@{{item.id}}" value="@{{item.stock_action}}">
                  <td><span style="font-size: 12px;" id="@{{item.upc_ean_isbn}}">@{{item.item_name}} <b ng-if="item.type==2"> [kit]</b> <b ng-if="item.type==1 && item.stock_action=='+'" id="ex2_@{{item.id}}">[ @{{item.quantity}} ]</b></span>                    </td>
                  <td>
                    <button class="btn btn-success btn-xs" ng-if="item.quantity>0 || item.type==2 || item.stock_action=='='" type="button" id="nuevo_@{{item.id}}" name="barra_@{{item.upc_ean_isbn}}"
                      value="codig_barras_@{{item.upc_ean_isbn}}" onclick="add(this)">
                      <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                    </button>
                  </td>
                </tr>
              </table>
              <table style="display:none;">
                <tr ng-repeat="item in items">
                  <input type="hidden" id="existencias_@{{item.id}}" value="@{{item.quantity}}">
                  <td style="display:none;"><span style="font-size: 12px;" id="@{{item.upc_ean_isbn}}">@{{item.item_name}}  <b id="ex_@{{item.id}}">[ @{{item.quantity}} ]</b></span>                    </td></td>
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
                      <select class="form-control" name="id_bodegas" id="id_bodega" onchange="cambioBodega()">
                  <!-- <select class=" form-control " name="id_bodegas " id="id_bodega "  onchange="cambioBodega() " > -->
                   @if($id_almacen=="")
                   <option value="">No hay bodegas asignadas</option>
                   @else
                   @foreach($almacen as $value)
                   <option value="{!! $value->id !!}"  {{ ($id_almacen == $value->id) ?  'selected="selected"' : '' }}>{{ $value->name }}
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
                      <!-- <input type="text" class="form-control" id="employee" value="{{ Auth::user()->name }}" readonly/> -->
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
                      <option value="{!!$value->id!!}"{{ ($idFactura==$value->id)? 'selected="selected"' :''}} >{!!$value->nombre!!} - {!!$value->name!!}</option>
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
                <div class="form-group">
                  <div class="col-md-12">
                    <table id="target" class="table table-bordered">
                      <thead style="background: #9CBBD9">
                        <tr>
                          <th>ID</th>
                          <th width="30%">Producto</th>
                          <th>Precio</th>
                          <th>P. Venta</th>
                          <th>Cantidad</th>
                          <th>Total</th>
                          <th>&nbsp;</th>
                          {{--
                          <th>&nbsp;</th> --}}
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
                          <input type="text" class="form-control" id="total_pago" name="total_cost" style="text-align:right;" value="0" readonly/>
                        </div>
                      </div>
                    </div>
                    {{-- {!! Form::hidden('total_cost','@{{sum(saletemp)}}', Input::old('total_cost'), array('class' => 'form-control')) !!}
                    <div class="form-group">
                      <label for="amount_due" class="col-sm-4 control-label">{{trans('sale.amount_due')}}</label>
                      <div class="col-sm-8">
                        <p class="form-control-static"><label for="">0</label></p>
                      </div>
                    </div> --}}
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

  <!--  Ventana modal de listado de Customers-->
  <div class="modal fade" id="modal-1" role="dialog" aria-labelledby="modalLabelsuccess">
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
        </div>
        <div class="modal-footer">
          <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!--  Ventana modal de listado de Customers-->
  <!-- Modal new customer -->
  <div class="modal fade" id="modal-2" name="modal-2" role="dialog" aria-labelledby="modalLabelsuccess">
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
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery-1.12.4.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>


<script type="text/javascript" src="{{asset('/js/add.js') }}"></script>
<!-- Toast -->
<script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
<!-- <script type="text/javascript" src="{{asset('assets/js/sales/validateCustomer.js') }}"></script>

<script type="text/javascript" src="{{asset('assets/js/sales/create.js') }}"></script> -->
<script type="text/javascript">
  document.getElementById('total_pago').value=0;
  document.getElementById('name_customer').value="";
  //cambio de vendedor
  $('#employee').change(function(){
    var valueEmployee=$(this).val();
    if(valueEmployee!=0){
      $('#user_relation').val(valueEmployee);
    }else {
      $('#user_relation').val(0);
    }
  });
  document.getElementById('employee').addEventListener('change',function(){

  });
  //cambio de vendedor
function validarPrecioMinimo(button)
{


  var id_recibido=button.id;
  var precio_nuevo=document.getElementById(button.id);
  var id_array=id_recibido.split('_');
  var id=id_array[1];
  var precio_minimo=document.getElementById('costominimo_'+id);
  if(parseFloat(precio_nuevo.value)< parseFloat(precio_minimo.value))
  {
    alert("No se puede vender");

  }
  else
  {
    alert("Si se puede vender");
  }
}
  var elemento_de_venta=document.getElementById('elemento_de_venta');

  var cmbPago=document.getElementById('id_pago');
  // console.log(cmbPago);
  //idVenta.disabled=true;
  var result=document.getElementById('id_bodega');
  // if(result.value==0){
  //   document.getElementById('id_bodega').style.background = "red";
  //   document.getElementById('id_bodega').style.color = "white";
  // }
  // var idSerie=document.getElementById('id_serie');
  // idSerie.options[3].style.display="none";

  var id_correlativo=document.getElementById('id_correlativo');
  //id_correlativo.value=0;

  $('#id_serie').change(function(){
    var id_serie=$(this).val();
    if(id_serie!=0){
      $.ajax({
        method:'get',
            url: 'getCorrelativeSale/'+id_serie,
            success:function(data){
              $('#id_correlativo').val(data);
            },
            error:function(error){
              console.log(error);
            }
      });
    }else {
      $('#id_correlativo').val(0);
    }
  });

  var obtenerValorcmbPago=function(e){
    // var index=cmbPago.selectedIndex;
    // var texto=cmbPago[index].text;
    // var textoMayuscula=texto.toUpperCase();
    // if(textoMayuscula==="CRÉDITO"){
    //   idSerie.options[1].style.display="none";
    //   idSerie.options[2].style.display="none";
    //   idSerie.options[3].style.display="block";
    //   idSerie.value=0;
    //   id_correlativo.value=0;
    // }else{
    //   idSerie.options[1].style.display="block";
    //   idSerie.options[2].style.display="block";
    //   idSerie.options[3].style.display="none";
    //   id_correlativo.value=0;
    //   idSerie.value=0;
    // }
  }
  cmbPago.addEventListener('change',obtenerValorcmbPago);
  var invisible=document.getElementById('totalVenta');
  function cambioBodega(){


      document.getElementById('id_form_bodega').submit();
    }
    function valida(e){
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8){
        return true;
    }
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

const id_input_search = document.getElementById('id_input_search');
id_input_search.addEventListener('keydown', function onEvent(event) {
    if (event.key === "Enter") {
        //6952367900568
        //var codig_barras=document.getElementById('codig_barras_'+id_input_search.value);
        var codigo_barras=document.getElementsByName('barra_'+id_input_search.value);
        if(codigo_barras[0])
        {
          var id_button=codigo_barras[0].id;
          var button=document.getElementById(id_button);
          button.click();

          id_input_search.value="";
          id_input_search.focus();
        }
        else
        {
          alert("No existe el producto con ese código");
          id_input_search.value="";
          id_input_search.focus();

        }
        //array_codigo=codig_barras.split("_");
        //barras_codigo=array_codigo[2];
       /*/if (document.getElementById(id_input_search.value)) {

         var agregar_producto = document.getElementById('nuevo_'+id_input_search.value);
          agregar_producto.click();
        }else{
        }*/
    }
});
function add_customers (value_receiving){
  var customer_id=document.getElementById('customer_id');
  var name_customer=document.getElementById('name_customer');
  var name=value_receiving.id.split("_");
  var name_id=name[1].split("/");
  // alert(name_id[0]+' '+name_id[1]);
  customer_id.value=name_id[1];
  name_customer.value=name_id[0];

}
//boton de verificación de vender
var idVenta=document.getElementById('idVenta');
idVenta.addEventListener('click',function(){
  var select_serie = document.getElementById("id_serie");
  var name_customer=document.getElementById('name_customer');
  var customer_id=document.getElementById('customer_id');
  var item_quantity=document.getElementById('item_quantity');

  // var user_relation=document.getElementById('user_relation');
  var user_relation=document.getElementById('employee');

      selected = select_serie.value;
      if(selected==0 || name_customer.value=="" || customer_id.value==0)
      {
        if(name_customer.value=="" || customer_id.value==0)
        {
          var add_customer_btn=document.getElementById('add_customer_btn');
          add_customer_btn.click();
        }
        else
        {

          // select_serie.style="background-color:#e4a0a0"
          select_serie.focus();
        }
      }
      else if(parseInt(user_relation.value)==0){
        // alert('Debe de seleccionar un vendedor');
        user_relation.focus();
      }
      else if(parseInt(item_quantity.value)==0)
      {
        // alert("Debe de selecionar algún producto");
        document.getElementById('id_input_search').focus();
        //$("#modal-no-existe-producto").hide();
      }
      else
      {
        $.ajax({
          type:"post",
          url:'{{url('existCorrelative')}}',
          data:{
            _token: '{{csrf_token()}}',
            'id_serie':$('#id_serie').val(),
            'correlative':$('#id_correlativo').val()
          },
          success:function(data){
            if(data==0){
              document.getElementById('idVenta').style.display = 'none';
              document.getElementById('id_save_sales').submit();
            }else {
              alert('Ya se uso el correlativo');
            }
          },
          error:function(error){
            console.log(error);
          }
        });
      }
});

  $(document).ready(function() {
  $('#table_customers').DataTable({
    "bLengthChange": false,
       // "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        language: {
        search: "_INPUT_",
        searchPlaceholder: "Buscar...",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "oPaginate": {
                      "sFirst":    "Primero",
                      "sLast":     "Último",
                      "sNext":     "Siguiente",
                      "sPrevious": "Anterior"
                    },
      },
      "columnDefs": [
            {
                "visible": false,
                "searchable": false
            }
        ]
  });

  btnSaveCustomer=document.getElementById('btnSaveCustomer');
  btnSaveCustomer.addEventListener('click',function()
  {
    nit_customer2=document.getElementById('nit_customer2');
    name_customer2=document.getElementById('name_customer2');
    var address_customer2=document.getElementById('address_customer2');

      if(nit_customer2.value=="")
      {
        nit_customer2.focus();
      }
      else if(name_customer2.value=="")
      {
        name_customer2.focus();
      }
      else if(address_customer2.value=="")
      {
        address_customer2.focus();
      }
      else
      {
        // alert("Si se puede guardar el campo");
        // console.log("Llama Ajax");
         $.ajax({
          type:"post",
          url:'{{url('/customers/addCustomerAjaxPos')}}',
          data:{
            _token: '{{csrf_token()}}',
            'nit':$('#nit_customer2').val(),
            'name':$('#name_customer2').val(),
            'dpi':$('#dpi').val(),
            'email':$('#email').val(),
            'phone':$('#phone').val(),
            'address':address_customer2.value,
          },
          success:function(data){
            if((data.errors))
            {
              console.log("existe un error revisar");
            }
            else{
              // console.log(data);
              if(data=="Ya existe un cliente con ese nombre")
              {
                  alert("No se puede agregar ya existe un cliente con ese nombre");
                  address_customer2.value="Ciudad";
              }
              else
              {
                var id=data.id;
                var name=data.name;
                document.getElementById('customer_id').value=id;
                document.getElementById('name_customer').value=name;
                $('#table_customers').dataTable().fnDestroy();
                $('#table_customers').append("<tr role='row' class><td class>" + data.id + "</td><td>" + data.nit_customer+ "</td><td>" + data.name + "</td><td></td><td></td><td><button  type='button' name='button' class='btn btn-primary btn-xs' id='name_"+data.name+"/"+data.id+"' onclick='add_customers(this);' data-dismiss='modal'><span class='glyphicon glyphicon-check' aria-hidden='true'></span></button>"+"</td></tr>");
                $('#table_customers').dataTable();
                // console.log("Ahora debe ocultar el modal");
                $("#modal-2").hide();
                $(".modal-backdrop").remove();
              }
              // console.log("Aqui ya lo debio ocultar, se intenta nuevamente  ");
              // $('#modal-2').hide();
            }
          }
        });
      }

  });
} );

</script>


@stop
