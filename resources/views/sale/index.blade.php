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
@stop
@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/sale.js', array('type' => 'text/javascript')) !!}
<div class="container-fluid">
   <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{trans('sale.sales_register')}}</div>
                <div class="panel-body">
                <input type="hidden" name="path" id="path" value="{{ url('/') }}">
                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
                {!! Html::ul($errors->all()) !!}


                  <div class="row" ng-controller="SearchItemCtrl" ng-init="nuevo('{{$id_almacen}}')">
                    <input type="hidden" name="" value="{{$role_user[0]->role}}" id="role_user">

                    <div class="col-md-3">
                        <label>{{trans('sale.search_item')}} <input id="id_input_search"  ng-model="searchKeyword" class="form-control"></label>
                        <table class="table table-hover">
                        <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">
                            <td><span id="@{{item.upc_ean_isbn}}" >@{{item.item_name}}</span> </td>
                             <td>
                             <button class="btn btn-success btn-xs" ng-if="item.quantity>0" type="button" id="nuevo_@{{item.upc_ean_isbn}}" ng-click="addSaleTemp(item, newsaletemp)">
                             <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                             </button>
                             </td>
                        </tr>
                        </table>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                                <div class="col-md-5">
                                  <!-- Serie de la factura normal -->
                                  <!-- <input type="text" name="" value="0" id="items_quantity"> -->
                                  {!! Form::open(array('url' => 'sales','method' => 'get', 'class' => 'form-horizontal','id'=>'id_form_bodega')) !!}
                                  <div class="form-group" >
                                      <label for="customer_id" class="col-sm-3 control-label">Bodega</label>
                                      <div class="col-sm-9">
                                          <select class="form-control" name="id_bodegas" id="id_bodega"  onchange="cambioBodega()" >
                                            @if($id_almacen==0)
                                            <option value="0">No hay bodegas asignadas</option>
                                             @else
                                              @foreach($almacen as $value)
                                              <option value="{!! $value->id !!}"  {{ ($id_almacen == $value->id) ?  'selected="selected"' : '' }}>{{ $value->name }}
                                              </option>
                                              @endforeach
                                            @endif
                                          </select>
                                      </div>
                                  </div>
                                  {!! Form::close() !!}

                                  {!! Form::open(array('url' => 'sales','method' => 'post', 'class' => 'form-horizontal','id'=>'id_save_sales')) !!}
                                  <input type="hidden" name="id_bodegas" value="{{$id_almacen}}">
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
                                    <div class="form-group">
                                        <label for="employee" class="col-sm-3 control-label">{{trans('sale.employee')}}</label>
                                        <div class="col-sm-9">
                                        <input type="text" class="form-control" id="employee" value="{{ Auth::user()->name }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group"  id="mensaje">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="customer_id" class="col-sm-3 control-label">{{trans('sale.customer')}}</label>
                                        <div class="col-sm-6">
                                          <input type="hidden" name="customer_id" value="0" id="customer_id">
                                          <input type="text" name="name_customer" value="" id="name_customer" class="form-control" disabled>
                                        </div>
                                        <div class="col-ms-2">
                                          <a href="#" style="font-size: 12px" id="add_customer_btn" class="btn btn-raised btn-success btn-xs" data-toggle="modal" data-target="#modal-1">Agregar</a>
                                        </div>
                                    </div>


                                    <div class="form-group">
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
                        <table class="table table-bordered">
                            <tr>
                              <th>{{trans('sale.item_id')}}</th>
                              <th>{{trans('sale.item_name')}}</th>
                              <th>{{trans('sale.price')}}</th>
                              <th>{{trans('sale.discount')}}</th>
                              <th>{{trans('sale.quantity')}}</th>
                              <th>{{trans('sale.total')}}</th>
                              <th>&nbsp;</th>
                            </tr>
                            <tr ng-repeat="newsaletemp in saletemp" id="ventaNuevoElemento" class="ventaNuevoElemento">
                            <td>@{{newsaletemp.item_id}}</td>
                            <td>@{{newsaletemp.item.item_name}}</td>
                            <td>@{{newsaletemp.item.selling_price | currency:"Q"}}</td>
                            <td>
                              <input type="hidden" name="" value="@{{newsaletemp.item.low_price}}" id="costominimo_@{{newsaletemp.item_id}}">
                              <input type="hidden" name="" value="@{{newsaletemp.item.selling_price}}" id="precionormal_@{{newsaletemp.item_id}}">
                              <input type="text" style="text-align:center" id="idventa_@{{newsaletemp.item_id}}" autocomplete="off"      ng-model="newsaletemp.low_price"  size="6"   maxlength="8" class="nuevoValor" ng-blur="updateSaleTempPriceSelling(newsaletemp)">
                              <!-- <input style="text-align:center" type="text" name="" value="@{{newsaletemp.item.selling_price}}" size="6"  autocomplete="off" id="idventa_@{{newsaletemp.item_id}}" ng-blur="updateSaleTempPriceSelling(newsaletemp)" > -->
                            </td>
                            <td>
                              {{--  <input type="text" style="text-align:center" id="elemento_de_venta_@{{newsaletemp.item_id}}" autocomplete="off"  id="quantity_@{{newsaletemp.item_id}}" ng-blur="updateSaleTemp(newsaletemp)"   ng-model="newsaletemp.quantity"  size="6" onkeypress="return valida(event)"  maxlength="3" class="nuevoValor">  --}}
                              <input type="text" style="text-align:center" id="elemento_de_venta_@{{newsaletemp.item_id}}" autocomplete="off"  id="quantity_@{{newsaletemp.item_id}}" ng-blur="updateSaleTemp(newsaletemp)"   ng-model="newsaletemp.quantity"  size="6" onkeypress="return valida(event)"  maxlength="20" class="nuevoValor">
                            </td>
                            <!--  Cambiamos al array del newsaletemp.item.low_price items-->
                            <td>@{{ newsaletemp.low_price * newsaletemp.quantity | currency:"Q"}}</td>
                            <td>
                              <button class="btn btn-danger btn-xs" type="button" ng-click="removeSaleTemp(newsaletemp.id)">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                              </button>
                            </td>
                            </tr>
                        </table>
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total" class="col-sm-4 control-label">{{trans('sale.add_payment')}}</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">Q</div>
                                                <input type="text" class="form-control" id="add_payment" ng-model="add_payment"/>
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
                                            <p class="form-control-static"><b>@{{sum(saletemp)| currency:"Q" }}</b></p>
                                        </div>
                                    </div>
                                    {!! Form::hidden('total_cost','@{{sum(saletemp)}}', Input::old('total_cost'), array('class' => 'form-control')) !!}
                                    <div class="form-group">
                                          <label for="amount_due" class="col-sm-4 control-label">{{trans('sale.amount_due')}}</label>
                                            <div class="col-sm-8">
                                              <p class="form-control-static">@{{add_payment - sum(saletemp) | currency:"Q"}}</p>
                                            </div>
                                    </div>
                                   </div>


                            </div>
                            {!! Form::close() !!}
                            <div class="col-md-12">
                              <div class="form-group">
                                <div class="col-sm-4" style="aling:center;"></div>
                                <div class="col-sm-4" style="aling:center;"></div>
                                <div class="col-sm-4" style="aling:center;">
                                  <button type="submit" class="btn btn-success btn-block"  id="idVenta">
                                    {{-- <button type="submit" class="btn btn-success btn-block" {{ ($valorRecibido ==0) ?  'disabled="disabled"' : '' }} id="idVenta"> --}}
                                      {{trans('sale.submit')}}
                                    </button>
                                  </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!--  Ventana modal de listado de clientes-->
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
                    <table class="table table-striped"  id="table_customers">
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
                            <button  type="button" name="button" class="btn btn-primary btn-xs" id="name_{{$value->name.'/'.$value->id}}" onclick="add_customers(this);" data-dismiss="modal">
                              <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                            </button>
                          </td>
                        </tr>
                        @endforeach

                      </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn  btn-danger" data-dismiss="modal">Cerrar!</button>
                </div>
            </div>
        </div>
    </div>
    <!--  Ventana modal de listado de proveedores-->
    <!-- Modal de nuevo empleado -->
    <div class="modal fade" id="modal-2" role="dialog" aria-labelledby="modalLabelsuccess">
        <div class="modal-dialog modal-lg" role="document">
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
                      <input type="text" name="dpi2" class="form-control" required id="dpi">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="modal-footer">
                      <button class="btn  btn-danger" data-dismiss="modal">Cerrar!</button>
                      <button class="btn  btn-primary" id="btnSaveCustomer">Guardar</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <!--  Fin del modal-->
    <!-- Modal existen -->
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
    <!-- Modal existen -->
</div>
<script type="text/javascript">
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
  var idSerie=document.getElementById('id_serie');
  idSerie.options[3].style.display="none";

  var id_correlativo=document.getElementById('id_correlativo');
  //id_correlativo.value=0;

  idSerie.addEventListener('change',function(){
    //valiDar el boton para poder guardar la venta


    var valor=idSerie.value;
    var path = document.getElementById('path').value;

    // Cambiar cuando se haga el push
            var str = window.location;
            var valorTotl = String(str);
            var res = valorTotl.split("/");
            var nuevo_valor = res[3];
            var nuevo_valor2 = res[4];
    if(valor>=1){
      $.ajax({
      method: 'POST', // Type of response and matches what we said in the route
      url: nuevo_valor+'/'+nuevo_valor2+'/api/'+valor+'/item/', // This is the url we gave in the route
      //url: path+'/api/'+valor+'/item/', // This is the url we gave in the route
      // a JSON object to send back
      success: function(response){ // What to do if we succeed
          // alert('valor recibido: '+response);
          if(response!=""){
            id_correlativo.value=parseInt(response)+1;
          }else{
            id_correlativo.value=1;
          }
      },
      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
  }else{
    id_correlativo.value=0;
  }

  });

  var obtenerValorcmbPago=function(e){
    var index=cmbPago.selectedIndex;
    var texto=cmbPago[index].text;
    var textoMayuscula=texto.toUpperCase();
    if(textoMayuscula==="CRÉDITO"){
      // console.log("si es Credito");
      // idSerie.options[0].disabled = true;
      idSerie.options[1].style.display="none";
      idSerie.options[2].style.display="none";
      idSerie.options[3].style.display="block";
      idSerie.value=0;
      id_correlativo.value=0;
    }else{
      // console.log("nel");
      // idSerie.options[0].disabled = false;
      idSerie.options[1].style.display="block";
      idSerie.options[2].style.display="block";
      idSerie.options[3].style.display="none";
      id_correlativo.value=0;
      idSerie.value=0;
    }
  }
  cmbPago.addEventListener('change',obtenerValorcmbPago);
  var invisible=document.getElementById('totalVenta');
  //alert('cantidad de la suma: '+invisible.value);
  // console.log(invisible.value);
</script>
<script type="text/javascript">
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

       if (document.getElementById(id_input_search.value)) {

         var agregar_producto = document.getElementById('nuevo_'+id_input_search.value);
          agregar_producto.click();
          id_input_search.value="";
          id_input_search.focus();
          console.log('si existe el producto');
        }else{
          id_input_search.value="";
          id_input_search.focus();
        }
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
  var
      selected = select_serie.value;
      if(selected==0 || name_customer.value=="")
      {
        if(name_customer.value=="")
        {
          var add_customer_btn=document.getElementById('add_customer_btn');
          add_customer_btn.click();
        }else
        {
          select_serie.focus();
        }
      }
      else
      {
        document.getElementById('id_save_sales').submit();

      }
});



</script>
@endsection
@section('footer_scripts')
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script type="text/javascript">


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
  btnSaveCustomer.addEventListener('click',function(){
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
         $.ajax({
          type:"post",
          url:'customers/addCustomerAjax',
          data:{
            _token: '{{csrf_token()}}',
            'nit_customer2':$('#nit_customer2').val(),
            'name_customer2':$('#name_customer2').val(),
            'address_customer2':address_customer2.value,
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
                $('#table_customers').append("<tr><td>" + data.id + "</td><td>" + data.nit_customer+ "</td><td>" + data.name + "</td><td></td><td></td><td><button  type='button' name='button' class='btn btn-primary btn-xs' id='name_"+data.name+"/"+data.id+"' onclick='add_customers(this);' data-dismiss='modal'><span class='glyphicon glyphicon-check' aria-hidden='true'></span></button>"+"</td></tr>");
                $("#modal-2").hide();
              }
              // $('#modal-2').hide();
            }
          }
        });
      }

  });
} );
</script>
@stop
