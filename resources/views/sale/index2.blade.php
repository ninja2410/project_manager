@extends('app')
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

                <div class="row" ng-controller="SearchItemCtrl" ng-init="nuevo('<?php echo $valorRecibido  ?>')">
                    <div class="col-md-3">
                        <label>{{trans('sale.search_item')}} <input ng-model="searchKeyword" class="form-control"></label>
                        <table class="table table-hover">
                        <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">
                        <td>@{{item.item_name}}</td>
                             <td>
                             <button class="btn btn-success btn-xs" ng-if="item.quantity>0" type="button" ng-click="addSaleTemp(item, newsaletemp)">
                             <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                             </button>
                             </td>
                        </tr>
                        </table>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            {!! Form::open(array('url' => 'sales', 'class' => 'form-horizontal')) !!}
                                <div class="col-md-5">
                                  <!-- Serie de la factura normal -->
                                  <div class="form-group">
                                      <label for="invoice" class="col-sm-3 control-label">{{trans('Serie')}}</label>
                                      <div class="col-sm-8" >
                                      <?php if(isset($_GET['idFac'])){
                                          $idFactura=$_GET['idFac'];}else{
                                            $idFactura=0;
                                          }?>

                                      <select class="form-control" name="serie_id" id="id_serie" >
                                        <option value="0">Seleccione una serie</option>
                                        @foreach($serieFactura as $value)
                                        <option value="{!!$value->id!!}"{{ ($idFactura==$value->id)? 'selected="selected"' :''}} >{!!$value->nombre!!} - {!!$value->name!!}</option>
                                        @endforeach
                                      </select>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label for="invoice" class="col-sm-3 control-label">{{trans('Correlativos')}}</label>
                                      <div class="col-sm-4" >
                                        <?php if(isset($_GET['correlativo'])){$valorCorrelative=$_GET['correlativo']; }
                                          else{$valorCorrelative=0;}?>
                                        <input type="text" name="correlativo_num" value="<?php echo  $valorCorrelative;?>" id="id_correlativo" class="form-control">
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
                                        <label for="customer_id" class="col-sm-4 control-label">{{trans('sale.customer')}}</label>
                                        <div class="col-sm-8">
                                        <?php if(isset($_GET['idCliente'])){
                                          $idCliente=$_GET['idCliente'];}else{
                                            $idCliente=1;
                                          }?>
                                        <select class="form-control" name="customer_id" id="id_customer"   >
                                          @foreach($customer as $value)
                                          <option value="{!! $value->id !!}"{{ ($idCliente==$value->id)? 'selected="selected"' :''}} >{{ $value->name }}</option>
                                          @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer_id" class="col-sm-4 control-label">Pago:</label>
                                        <div class="col-sm-8">
                                        {!! Form::select('id_pago', $pagoss, Input::old('id'), array('id'=>'idPagos','class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="customer_id" class="col-sm-4 control-label">Bodega</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="id_bodega" id="id_bodega"  onchange="cambioBodega()" >
                                              <option value="0">Seleccione bodega</option>
                                              @foreach($almacen as $value)
                                              <option value="{!! $value->id !!}" {{ ($valorRecibido == $value->id) ?  'selected="selected"' : '' }}>{{ $value->name }}
                                              </option>
                                              @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <table class="table table-bordered">
                            <tr>
                              <th>{{trans('sale.item_id')}}</th>
                              <th>{{trans('sale.item_name')}}</th>
                              <th>{{trans('sale.price')}}</th>
                              <th>{{trans('sale.quantity')}}</th>
                              <th>{{trans('sale.total')}}</th>
                              <th>&nbsp;</th>
                            </tr>
                            <tr ng-repeat="newsaletemp in saletemp" id="ventaNuevoElemento">
                            <td>@{{newsaletemp.item_id}}</td>
                            <td>@{{newsaletemp.item.item_name}}</td>
                            <td>@{{newsaletemp.item.selling_price | currency:"Q"}}</td>
                            <td>
                              <input type="text" style="text-align:center" id="elemento_de_venta" autocomplete="off" name="quantity_@{{newsaletemp.item_id}}" id="quantity_@{{newsaletemp.item_id}}" ng-change="updateSaleTemp(newsaletemp)"   ng-model="newsaletemp.quantity"  size="6" onkeypress="return valida(event)"  maxlength="3" class="nuevoValor">
                            </td>
                            <td>@{{newsaletemp.item.selling_price * newsaletemp.quantity | currency:"Q"}}</td>
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
                                    <div class="form-group">
                                        <div class="col-sm-4" style="aling:center;">
                                            <button type="submit" class="btn btn-success btn-block"  id="idVenta">
                                            {{-- <button type="submit" class="btn btn-success btn-block" {{ ($valorRecibido ==0) ?  'disabled="disabled"' : '' }} id="idVenta"> --}}
                                              {{trans('sale.submit')}}
                                            </button>
                                        </div>
                                    </div>
                            </div>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  var elemento_de_venta=document.getElementById('elemento_de_venta');

  var cmbPago=document.getElementById('idPagos');
  //idVenta.disabled=true;
  var result=document.getElementById('id_bodega');
  if(result.value==0){
    document.getElementById('id_bodega').style.background = "red";
    document.getElementById('id_bodega').style.color = "white";
  }
  var idSerie=document.getElementById('id_serie');
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
      console.log("si");
    }else{
      console.log("nel");
    }
  }
  cmbPago.addEventListener('change',obtenerValorcmbPago);
  var invisible=document.getElementById('totalVenta');
  //alert('cantidad de la suma: '+invisible.value);
  // console.log(invisible.value);
</script>
<script type="text/javascript">
    function cambioBodega(){
      // location.reload();
    /*if(idSerie.value>0 && result.value>0){
      idVenta.disabled=false;
    }else{
      idVenta.disabled=true;
    }*/
      var idFactura=document.getElementById('id_serie').value;
      var numCorrelative=document.getElementById('id_correlativo').value;
      var idCustomer=document.getElementById('id_customer').value;
      var idBodega = document.getElementById("id_bodega").value;
      // console.log(result);
    //  location.href='?id='+result+'&cambio=yes';
    window.location.replace("./bodega_product/"+idFactura+"/"+idBodega+"/"+idCustomer+"/"+numCorrelative);
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
</script>
@endsection
