@extends('layouts/default')

@section('title',trans('desk_closing.new'))
@section('page_parent',trans('desk_closing.desk'))
@section('header_styles')
<!-- Validaciones -->
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
<link href="{{ asset('css/vue-multiselect.min.css') }}" rel="stylesheet" type="text/css" />

@stop
@section('content')
<div id="app">
  <template>
    <section class="content" v-if="show_error==false">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">
                <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                  data-loop="true"></i>
                {{trans('desk_closing.new')}}
              </h3>
              <span class="pull-right clickable">
                <i class="glyphicon glyphicon-chevron-up"></i>
              </span>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('caja', trans('desk_closing.desk')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="calculator" data-size="16" data-c="#555555" data-hc="#555555"
                          data-loop="true"></i>
                      </div>
                      <input type="text" class="form-control" v-model="Desk.account_name" readonly>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('date', trans('desk_closing.date')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="calendar" data-size="16" data-c="#555555" data-hc="#555555"
                          data-loop="true"></i>
                      </div>
                      <input type="text" class="form-control" v-model="Desk.updated_at" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('serie', trans('desk_closing.serie')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="list" data-size="16" data-c="#555555" data-hc="#555555"
                          data-loop="true"></i>
                      </div>
                      <select class="form-control" v-model="serie" :ref="'document'" @input="toWizard">
                        <option :value="0" selected disabled>Seleccione una serie</option>
                        <option v-for="s in series" :key="s.series" :value="s.id" v-text="s.name"></option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('Correlativo', trans('desk_closing.correlative')) !!}
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="livicon" data-name="list" data-size="16" data-c="#555555" data-hc="#555555"
                          data-loop="true"></i>
                      </div>
                      <input type="number" class="form-control" v-model="correlativo" @input="documentoSelect"
                        :ref="'serie'" v-on:keyup.enter="toWizard">
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row col-lg-offset-1">
                <div class="col-lg-2 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                  <div class="lightbluebg no-radius">
                    <div class="panel-body squarebox square_boxs">
                      <div class="nopadmar">
                        <div class="row">
                          <div class="square_box pull-center">
                            <span>{{ trans('desk_closing.cash') }}</span>
                          </div>
                        </div>
                        <div class="row">
                          <span v-text="efectivo"></span>
                        </div>
                        <div class="row">
                          <i class="livicon pull-rigth" data-name="money" data-l="true" data-c="#fff" data-hc="#fff"
                            data-s="70"></i>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 margin_10 animated fadeInRightBig" data-toggle="tooltip"
                  data-original-title="Proveedores">

                  <div class="palebluecolorbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                      <div class="nopadmar">
                        <div class="row">
                          <div class="square_box pull-center">
                            <span>{{ trans('desk_closing.cheque') }}</span>
                          </div>
                        </div>
                        <div class="row">
                          <span v-text="cheque"></span>
                        </div>
                        <div class="row">
                          <i class="livicon pull-rigth" data-name="bank" data-l="true" data-c="#fff" data-hc="#fff"
                            data-s="70"></i>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 margin_10 animated fadeInRightBig" data-toggle="tooltip"
                  data-original-title="Articulos">

                  <div class="lightbluebg no-radius">
                    <div class="panel-body squarebox square_boxs">
                      <div class="nopadmar">
                        <div class="row">
                          <div class="square_box pull-center">
                            <span class="">{{ trans('desk_closing.deposit') }}</span>
                          </div>
                        </div>
                        <div class="row">
                          <span v-text="deposito"></span>
                        </div>
                        <div class="row">
                          <i class="livicon pull-rigth" data-name="piggybank" data-l="true" data-c="#fff" data-hc="#fff"
                            data-s="70"></i>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 margin_10 animated fadeInRightBig" data-toggle="tooltip"
                  data-original-title="Articulos">

                  <div class="palebluecolorbg no-radius">
                    <div class="panel-body squarebox square_boxs">
                      <div class="nopadmar">
                        <div class="row">
                          <div class="square_box pull-center">
                            <span>{{ trans('desk_closing.card') }}</span>
                          </div>
                        </div>
                        <div class="row">
                          <span v-text="card"></span>
                        </div>
                        <div class="row">
                          <i class="livicon pull-rigth" data-name="credit-card" data-l="true" data-c="#fff"
                            data-hc="#fff" data-s="70"></i>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 margin_10 animated fadeInRightBig" data-toggle="tooltip"
                  data-original-title="Articulos">

                  <div class="lightbluebg no-radius">
                    <div class="panel-body squarebox square_boxs">
                      <div class="nopadmar">
                        <div class="row">
                          <div class="square_box pull-center">
                            <span>{{ trans('desk_closing.transfer') }}</span>
                          </div>
                        </div>
                        <div class="row">
                          <span v-text="transferencia"></span>
                        </div>
                        <div class="row">
                          <i class="livicon pull-rigth" data-name="paper-plane" data-l="true" data-c="#fff"
                            data-hc="#fff" data-s="70"></i>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <hr>
              <div id="rootwizard">
                <ul>
                  <li class="nav-item" v-show="numberDiferencia!=0"><a href="#tab1" data-toggle="tab" class="nav-link"
                      v-on:click="wizard=1">{{ trans('desk_closing.cash') }}</a>
                  </li>
                  <li class="nav-item" v-show="numberDiferencia!=0"><a href="#tab2" data-toggle="tab"
                      class="nav-link ml-2" v-on:click="wizard=2">{{ trans('desk_closing.cheque') }}</a>
                  </li>
                  <li class="nav-item" v-show="numberDiferencia!=0"><a href="#tab3" data-toggle="tab"
                      class="nav-link ml-2" v-on:click="wizard=3">{{ trans('desk_closing.deposit') }}</a>
                  </li>
                  <li class="nav-item" v-show="numberDiferencia!=0"><a href="#tab4" data-toggle="tab"
                      class="nav-link ml-2" v-on:click="wizard=4">{{ trans('desk_closing.card2') }}</a>
                  </li>
                  <li class="nav-item" v-show="numberDiferencia!=0"><a href="#tab5" data-toggle="tab"
                      class="nav-link ml-2" v-on:click="wizard=5">{{ trans('desk_closing.transfer') }}</a>
                  </li>
                  <li class="nav-item" v-show="numberDiferencia!=0"><a href="#tab6" data-toggle="tab"
                      class="nav-link ml-2" v-on:click="wizard=6">{{ trans('desk_closing.movements') }}</a>
                  </li>
                  <li class="nav-item"><a href="#tab7" data-toggle="tab" class="nav-link ml-2"
                      v-on:click="wizard=7">{{ trans('desk_closing.balan') }}</a>
                  </li>
                  <li class="nav-item"><a :style="numberDiferencia!=0?'pointer-events: none;':''" href="#tab8" data-toggle="tab" class="nav-link ml-2"
                      v-on:click="wizard=8">{{ trans('desk_closing.move') }}</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <hr>
                  <ul class="pager wizard">
                    <li class="previous" v-show="wizard>1&&wizard<7"><a v-on:click="previuosWizard" v-text="listNames[wizard-2]"></a></li>
                    <li class="previous" v-show="(numberDiferencia==0&&wizard==8)||(numberDiferencia!=0&&wizard==7)"><a v-on:click="previuosWizard" v-text="listNames[wizard-2]"></a></li>
                    <li class="next"><a v-on:click="nextWizard" v-text="listNames[wizard]" v-if="wizard<7"></a></li>
                    <li class="next"><a v-on:click="nextWizard" v-text="listNames[wizard]" v-if="wizard==7 && numberDiferencia==0"></a></li> 
                  </ul>
                  <div class="tab-pane" id="tab1">
                    <div class="row">
                      <div class="col-lg-8 col-lg-offset-2">
                        <table class="table table-bordered">
                          <tr>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                          </tr>
                          <tr v-for="(billete,index) in billetes">
                            <th v-text="billete.name"></th>
                            <th><input type="number" class="form-control"
                                v-model="billetes[getIndex(billetes,billete.id)].quantity"
                                @input="getQuantity(getIndex(billetes,billete.id))" :ref="'efectivo'+index"
                                v-on:keyup.enter="setFocus('efectivo'+(index+1),billetes.length)"
                                @keydown="onKeydown" /></th>
                            <th :rowspan="billetes.length+1" v-text="efectivo" v-show="index==0" class="text-center h4"
                              style="vertical-align:middle;"></th>
                          </tr>
                          <tr>
                            <th class="text-right">Total</th>
                            <th v-text="efectivo"></th>

                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab2">
                    <div class="row">
                      <div class="col-lg-12 text-center">
                        <button class="btn btn-info" @click="seleccionar(1)">Seleccionar todo</button>
                        <button class="btn btn-info" @click="seleccionar(0)">Desleccionar</button>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-lg-12">
                        <table class="table table-bordered">
                          <tr>
                            <th>No. Cheque</th>
                            <th>Descripción</th>
                            <th>Banco</th>
                            <th>Monto</th>
                            <th>Agregar</th>
                          </tr>
                          <tr v-for="item,index in Cheque" :key="item.id">
                            <th v-text="item.reference"></th>
                            <th v-text="item.description"></th>
                            <th v-text="item.bank_name==''?'No ingresado':item.bank_name"></th>
                            <th v-text="money(item.amount)" class="text-right"></th>
                            <th><input type="checkbox" class="form-control"
                                @input="addtoSum(getIndex(revenues,item.id))" :ref="'cheque'+index"
                                v-on:keyup.enter="setFocus('cheque'+(index+1),Cheque.length)"
                                v-model="revenues[getIndex(revenues,item.id)].selected" /></th>
                          </tr>
                          <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <th class="text-right" v-text="cheque"></th>
                            <th></th>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab3">
                    <div class="row">
                      <div class="col-lg-12 text-center">
                        <button class="btn btn-info" @click="seleccionar(1)">Seleccionar todo</button>
                        <button class="btn btn-info" @click="seleccionar(0)">Desleccionar</button>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-lg-12">
                        <table class="table table-bordered">
                          <tr>
                            <th>No. Depósito</th>
                            <th>Descripción</th>
                            <th>Banco</th>
                            <th>Monto</th>
                            <th>Agregar</th>
                          </tr>
                          <tr v-for="item,index in Deposito" :key="item.id">
                            <th v-text="item.reference"></th>
                            <th v-text="item.description"></th>
                            <th v-text="item.bank_name==''?'No ingresado':item.bank_name"></th>
                            <th v-text="money(item.amount)" class="text-right"></th>
                            <th><input type="checkbox" class="form-control"
                                @input="addtoSum(getIndex(revenues,item.id))" :ref="'deposito'+index"
                                v-on:keyup.enter="setFocus('deposito'+(index+1),Deposito.length)"
                                v-model="revenues[getIndex(revenues,item.id)].selected" /></th>
                          </tr>
                          <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <th class="text-right" v-text="deposito"></th>
                            <th></th>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab4">
                    <div class="row">
                      <div class="col-lg-12 text-center">
                        <button class="btn btn-info" @click="seleccionar(1)">Seleccionar todo</button>
                        <button class="btn btn-info" @click="seleccionar(0)">Desleccionar</button>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-lg-12">
                        <table class="table table-bordered">
                          <tr>
                            <th>No. Transacción</th>
                            <th>Descripción</th>
                            <th>Nombre tarjeta</th>
                            <th>Ultimos 4 digitos</th>
                            <th>Monto</th>
                            <th>Agregar</th>
                          </tr>
                          <tr v-for="item,index in Card" :key="item.id">
                            <th v-text="item.reference"></th>
                            <th v-text="item.description"></th>
                            <th v-text="item.card_name==''?'No ingresado':item.card_name"></th>
                            <th v-text="item.card_number==''?'No ingresado':item.card_number"></th>
                            <th v-text="money(item.amount)" class="text-right"></th>
                            <th><input type="checkbox" class="form-control"
                                @input="addtoSum(getIndex(revenues,item.id))" :ref="'card'+index"
                                v-on:keyup.enter="setFocus('card'+(index+1),Card.length)"
                                v-model="revenues[getIndex(revenues,item.id)].selected" /></th>
                          </tr>
                          <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th class="text-right" v-text="card"></th>
                            <th></th>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab5">
                    <div class="row">
                      <div class="col-lg-12 text-center">
                        <button class="btn btn-info" @click="seleccionar(1)">Seleccionar todo</button>
                        <button class="btn btn-info" @click="seleccionar(0)">Desleccionar</button>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-lg-12">
                        <table class="table table-bordered">
                          <tr>
                            <th>No. Transacción</th>
                            <th>Descripción</th>
                            <th>Banco</th>
                            <th>Monto</th>
                            <th>Agregar</th>
                          </tr>
                          <tr v-for="item,index in Transferencia" :key="item.id">
                            <th v-text="item.reference"></th>
                            <th v-text="item.description"></th>
                            <th v-text="item.bank_name==''?'No ingresado':item.bank_name"></th>
                            <th v-text="money(item.amount)" class="text-right"></th>
                            <th><input type="checkbox" class="form-control"
                                @input="addtoSum(getIndex(revenues,item.id))" :ref="'transferencia'+index"
                                v-on:keyup.enter="setFocus('transferencia'+(index+1),Transferencia.length)"
                                v-model="revenues[getIndex(revenues,item.id)].selected" /></th>
                          </tr>
                          <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <th class="text-right" v-text="transferencia"></th>
                            <th></th>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab6">
                    <table class="table table-bordered " id="table_advanced">
                      <thead>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Referencia</th>
                        <th>Forma pago</th>
                        <th>Débito</th>
                        <th>Crédito</th>
                        <th>Total</th>
                      </thead>
                      <tbody>
                        {{-- EFECTIVO --}}
                        <tr v-for="myefectivo in inEfectivo">
                          <td v-text="fecha(myefectivo.paid_at)"></td>
                          <td v-text="myefectivo.description"></td>
                          <td v-text="myefectivo.reference"></td>
                          <td v-text="myefectivo.payment_method"></td>
                          <td v-if="myefectivo.tipo=='Gasto'"></td>
                          <td v-text="money(myefectivo.amount)"></td>
                          <td v-if="myefectivo.tipo=='Ingreso'"></td>
                          <td></td>
                        </tr>
                        <tr style="background-color: #e0e0e0;" v-if="inEfectivo.length>0">
                          <td></td>
                          <td colspan="2"><strong v-text="'#Transacciones con Efectivo: '+inEfectivo.length"></strong></td>
                          <td colspan="3"></td>

                          <td v-if="pagos.length>0"><strong v-text="money(pagos[0].value)"></strong></td>
                        </tr>
                        {{-- FIN EFECTIVO --}}
                        {{-- CHEQUE --}}
                        <tr v-for="mycheque in inCheque">
                          <td v-text="fecha(mycheque.paid_at)"></td>
                          <td v-text="mycheque.description"></td>
                          <td v-text="mycheque.reference"></td>
                          <td v-text="mycheque.payment_method"></td>
                          <td v-if="mycheque.tipo=='Gasto'"></td>
                          <td v-text="money(mycheque.amount)"></td>
                          <td v-if="mycheque.tipo=='Ingreso'"></td>
                          <td></td>
                        </tr>
                        <tr style="background-color: #e0e0e0;" v-if="inCheque.length>0">
                          <td></td>
                          <td colspan="2"><strong v-text="'#Transacciones con Cheque: '+inCheque.length"></strong></td>
                          <td colspan="3"></td>

                          <td v-if="pagos.length>0"><strong v-text="money(pagos[1].value)"></strong></td>
                        </tr>
                        {{-- FIN CHEQUE --}}
                        {{-- DEPOSITO --}}
                        <tr v-for="mydeposito in inDeposito">
                          <td v-text="fecha(mydeposito.paid_at)"></td>
                          <td v-text="mydeposito.description"></td>
                          <td v-text="mydeposito.reference"></td>
                          <td v-text="mydeposito.payment_method"></td>
                          <td v-if="mydeposito.tipo=='Gasto'"></td>
                          <td v-text="money(mydeposito.amount)"></td>
                          <td v-if="mydeposito.tipo=='Ingreso'"></td>
                          <td></td>
                        </tr>
                        <tr style="background-color: #e0e0e0;" v-if="inDeposito.length>0">
                          <td></td>
                          <td colspan="2"><strong v-text="'##Transacciones con Depósito: '+inDeposito.length"></strong></td>
                          <td colspan="3"></td>

                          <td v-if="pagos.length>0"><strong v-text="money(pagos[2].value)"></strong></td>
                        </tr>
                        {{-- FIN DEPOSITO --}}
                        {{-- CARD --}}
                        <tr v-for="mycard in inCard">
                          <td v-text="fecha(mycard.paid_at)"></td>
                          <td v-text="mycard.description"></td>
                          <td v-text="mycard.reference"></td>
                          <td v-text="mycard.payment_method"></td>
                          <td v-if="mycard.tipo=='Gasto'"></td>
                          <td v-text="money(mycard.amount)"></td>
                          <td v-if="mycard.tipo=='Ingreso'"></td>
                          <td></td>
                        </tr>
                        <tr style="background-color: #e0e0e0;" v-if="inCard.length>0">
                          <td></td>
                          <td colspan="2"><strong v-text="'#Transacciones con Tarjeta de Crédito/Debito: '+inCard.length"></strong></td>
                          <td colspan="3"></td>

                          <td v-if="pagos.length>0"><strong v-text="money(pagos[3].value)"></strong></td>
                        </tr>
                        {{-- FIN CARD --}}
                        {{-- TRANSFERENCIA --}}
                        <tr v-for="mytransferencia in inTransferencia">
                          <td v-text="fecha(mytransferencia.paid_at)"></td>
                          <td v-text="mytransferencia.description"></td>
                          <td v-text="mytransferencia.reference"></td>
                          <td v-text="mytransferencia.payment_method"></td>
                          <td v-if="mytransferencia.tipo=='Gasto'"></td>
                          <td v-text="money(mytransferencia.amount)"></td>
                          <td v-if="mytransferencia.tipo=='Ingreso'"></td>
                          <td></td>
                        </tr>
                        <tr style="background-color: #e0e0e0;" v-if="inTransferencia.length>0">
                          <td></td>
                          <td colspan="2"><strong v-text="'#Transacciones con Transferencia: '+inTransferencia.length"></strong></td>
                          <td colspan="3"></td>

                          <td v-if="pagos.length>0"><strong v-text="money(pagos[4].value)"></strong></td>
                        </tr>
                        {{-- FIN TRANSFERENCIA --}}
                      </tbody>
                      <tfoot>
                        <tr style="background-color: #000000;">
                          <th colspan="5"></th>
                          <th style="text-align:right; color:#e0e0e0; font-size:16px;">TOTAL GENERAL</th>
                          <th class="text-right" v-text="ingreso" style="color:#e0e0e0; font-size:16px;"></th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <div class="tab-pane" id="tab7">
                    <div class="row" v-for="p in pagos">
                      <div class="col-md-4" v-if="p.id!=1">
                        <div class="form-group">
                          <label v-text="p.name + '(s) ingresado'"></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-bank" v-show="p.id==2"></i>
                              <i class="fa fa-level-down" v-show="p.id==3"></i>
                              <i class="fa fa-credit-card" v-show="p.id==4"></i>
                              <i class="fa fa-paper-plane" v-show="p.id==5"></i>
                            </div>

                            <input type="text" class="form-control" :value="cheque" readonly v-show="p.id==2">
                            <input type="text" class="form-control" :value="deposito" readonly v-show="p.id==3">
                            <input type="text" class="form-control" :value="card" readonly v-show="p.id==4">
                            <input type="text" class="form-control" :value="transferencia" readonly v-show="p.id==5">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2" v-if="p.id==1">
                        <div class="form-group" :class="p.diferencia!=0?'has-error has-feedback':'has-success has-feedback'">
                          <label v-text="p.name + '(s) ingresado'"></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-money" v-show="p.id==1"></i>
                            </div>
                            <input type="text" class="form-control" v-model="efectivo" v-show="p.id==1"
                              :readonly="flagEfectivo" v-if="flagEfectivo">
                            <input type="number" @input="isNumberKey" @blur="calcularSuma" class="form-control" v-model="efectivoNumber" v-show="p.id==1"
                              :readonly="flagEfectivo" v-else min="0">
                          </div>
                          <small class="help-block" data-bv-validator="notEmpty" data-bv-for="amount" data-bv-result="INVALID" v-if="p.diferencia!=0">Verifique la cantidad ingresada.</small>
                        </div>
                      </div>
                      <div class="col-md-2" v-if="p.id==1">
                        <div class="form-group">
                          <label v-text="'Ingreso manual'"></label>
                          <div class="input-group">
                            <i :class="flagEfectivo==true?'btn btn-success fa fa-check btn-lg':'btn btn-danger fa fa-close btn-lg'"
                              @click="flagEfectivo==true?flagEfectivo=false:flagEfectivo=true"></i>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label v-text="p.name + '(s) en sistema'"></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-money" v-show="p.id==1"></i>
                              <i class="fa fa-bank" v-show="p.id==2"></i>
                              <i class="fa fa-level-down" v-show="p.id==3"></i>
                              <i class="fa fa-credit-card" v-show="p.id==4"></i>
                              <i class="fa fa-paper-plane" v-show="p.id==5"></i>
                            </div>
                            <input type="text" class="form-control" :value="money(p.value)" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group"
                          :class="p.diferencia!=0?'has-error has-feedback':'has-success has-feedback'">
                          <label v-text="'Diferencia'"></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-list"></i>
                            </div>
                            <input type="text" class="form-control" :value="money(p.diferencia)" readonly>
                            <span
                              :class="p.diferencia!=0?'glyphicon glyphicon-remove form-control-feedback':'glyphicon glyphicon-ok form-control-feedback'"
                              aria-hidden="true"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group"
                          :class="numberDiferencia!=0?'has-error has-feedback':'has-success has-feedback'">
                          <label v-text="'TOTAL INGRESADO'" class="font-weight-bold"></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-plus"></i>
                            </div>
                            <input type="text" class="form-control" :value="totalIngreso" readonly>
                            <span
                              :class="numberDiferencia!=0?'glyphicon glyphicon-remove form-control-feedback':'glyphicon glyphicon-ok form-control-feedback'"
                              aria-hidden="true"></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group"
                          :class="numberDiferencia!=0?'has-error has-feedback':'has-success has-feedback'">
                          <label v-text="'TOTAL SISTEMA'" class="font-weight-bold"></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-plus"></i>
                            </div>
                            <input type="text" class="form-control" :value="totalSistema" readonly>
                            <span
                              :class="numberDiferencia!=0?'glyphicon glyphicon-remove form-control-feedback':'glyphicon glyphicon-ok form-control-feedback'"
                              aria-hidden="true"></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group"
                          :class="numberDiferencia!=0?'has-error has-feedback':'has-success has-feedback'">
                          <label v-text="'TOTAL DIFERENCIA'" class="font-weight-bold"></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-plus"></i>
                            </div>
                            <input type="text" class="form-control" :value="totalDiferencia" readonly>
                            <span
                              :class="numberDiferencia!=0?'glyphicon glyphicon-remove form-control-feedback':'glyphicon glyphicon-ok form-control-feedback'"
                              aria-hidden="true"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab8">
                    <template v-if="Desk.fixed_amount>0">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label v-text="'Monto fijo'"></label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-money"></i>
                            </div>
                            <input type="text" class="form-control" :value="money(Desk.fixed_amount)" readonly style="position:static">
                          </div>
                        </div>
                      </div>
                    </template>
                    <template v-for="c,index in seleccionCuentas">
                      <div class="row">
                        <div class="col-md-8">
                          <div class="form-group">
                            <label v-text="'Ingresos'"></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-list"></i>
                              </div>
                              <multiselect :disabled="selectCuenta[index].verificacion" @input="changeVueMultiselect(index)" v-model="selectCuenta[index].cuentas" :options="c.cuentas" placeholder="Seleccione los ingresos" :multiple="true" :custom-label="nameWithLang" :group-select="true" label="nameWithLang" track-by="id"></multiselect>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label v-text="'Monto a depositar'"></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-money"></i>
                              </div>
                              <input type="text" class="form-control" style="position:static" v-model="selectCuenta[index].monto" :readonly="true" v-if="selectCuenta[index].estado">
                              <input type="text" class="form-control" style="position:static" v-model="selectCuenta[index].amount" :readonly="false" v-on:blur="changeInput(index)" v-else>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label v-text="'Cuentas bancarias'"></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bank"></i>
                              </div>
                              <multiselect :disabled="selectCuenta[index].verificacion" v-model="selectCuenta[index].bancos" :options="c.bancos" placeholder="Seleccione cuenta bancaria" label="account_name" track-by="id" :custom-label="nameBank"></multiselect>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label v-text="'# de referefencia / depósito'"></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-slack"></i>
                              </div>
                              <input :disabled="selectCuenta[index].verificacion" type="number" class="form-control" style="position:static" v-model="selectCuenta[index].reference">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-1" v-if="!selectCuenta[index].verificacion">
                          <div class="form-group">
                            <label v-text="'Confirmar'"></label>
                            <div class="input-group">
                              <button class="btn btn-success fa fa-plus-circle btn-block" @click="nextMovimiento(index)"></button>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-1" v-if="!selectCuenta[index].verificacion && index>0">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                          <div class="form-group">
                            <label v-text="'Quitar'"></label>
                            <div class="input-group">
                              <button class="btn btn-danger fa fa-close btn-block" @click="previousMovimiento(index)"></button>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-1" v-if="successMovimiento && index==(selectCuenta.length-1)">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                          <div class="form-group">
                            <label v-text="'Cancelar'"></label>
                            <div class="input-group">
                              <button class="btn btn-danger fa fa-close btn-block" @click="previousMovimiento(index)"></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                    </template>
                    <template>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label v-text="'Efectivo en caja'"></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-money"></i>
                              </div>
                              <input type="text" class="form-control" :value="totalesCaja[myindice]" readonly style="position:static">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label v-text="'Cheques en caja'"></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bank"></i>
                              </div>
                              <input type="text" class="form-control" :value="totalesCheque[myindice]" readonly style="position:static">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label v-text="'Total a depositar'"></label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-money"></i>
                              </div>
                              <input type="text" class="form-control" :value="totalDepositar" readonly style="position:static">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row" v-if="successMovimiento">
                        <div class="col-md-12">
                          <button class="btn btn-success btn-block" v-on:click="save">Guardar</button>
                        </div>
                      </div>
                    </template>
                  </div>
                  <ul class="pager wizard">
                    <li class="previous" v-show="wizard>1&&wizard<7"><a v-on:click="previuosWizard" v-text="listNames[wizard-2]"></a></li>
                    <li class="previous" v-show="(numberDiferencia==0&&wizard==8)||(numberDiferencia!=0&&wizard==7)"><a v-on:click="previuosWizard" v-text="listNames[wizard-2]"></a></li>
                    <li class="next"><a v-on:click="nextWizard" v-text="listNames[wizard]" v-if="wizard<7"></a></li>
                    <li class="next"><a v-on:click="nextWizard" v-text="listNames[wizard]" v-if="wizard==7 && numberDiferencia==0"></a></li> 
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="content" v-else>
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">
                <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                  data-loop="true"></i>
                {{trans('desk_closing.no_exist')}}
              </h3>
              <span class="pull-right clickable">
                <i class="glyphicon glyphicon-chevron-up"></i>
              </span>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-12">
                  <p class="text-justify" style="font-size:150%">Actualmente no hay ventas registradas a esta caja, ingrese ventas para poder realizar un cierre.</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-md-offset-5">
                  <a class="btn btn-primary" onclick="location.reload()">Recargar</a>
                  <a class="btn btn-danger" href="/banks/cash_register">Cancelar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    {{--Begin modal--}}
    <div class="modal fade modal-fade-in-scale-up in" tabindex="-1" id="modalDelete" role="dialog"
      aria-labelledby="modalLabelfade" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <h4 class="modal-title">Confirmación Guardar</h4>
          </div>
          <div class="modal-body">
            <div class="text-center">
              <p id="name_item"></p>
              <br>
              ¿Desea guardar este cierre de caja?
            </div>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-lg-6" style="text-align: right;">
                <button v-on:click="saveCashRegister" class="btn btn-info">
                  Guardar
                </button>
              </div>
              <div class="col-lg-6" style="text-align: left;">
                <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{--End modal--}}
  </template>
</div>
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
@endsection
@section('footer_scripts')
{!! Html::script('js/vue.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/vue.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/vue-multiselect.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/axios.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/pages/desk_closing/deskclosing.js', array('type' => 'text/javascript')) !!}
<script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
<!-- Valiadaciones -->
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
<script type="text/javascript">
  $('#newCustomer').bootstrapValidator({
    feedbackIcons: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },
    message: 'Valor no valido',
    fields: {

      name: {
        validators: {
          notEmpty: {
            message: 'Debe ingresar nombre.'
          }
        }
      },
    }
  }).
    on('change', '[name="max_credit_amount"]', function () {
      $('#newCustomer').bootstrapValidator('revalidateField', 'days_credit');
    })
    .on('change', '[name="days_credit"]', function () {
      $('#newCustomer').bootstrapValidator('revalidateField', 'days_credit');
      $('#newCustomer').bootstrapValidator('revalidateField', 'max_credit_amount');
    });
  $('#rootwizard').bootstrapWizard({
    'tabClass': 'nav nav-pills',
    'onNext': function (tab, navigation, index) {
      return true;
    },
    onTabClick: function (tab, navigation, index) {
      return true;
    },
    onTabShow: function (tab, navigation, index) {
      var $total = 8;
      var $current = index + 1;

      // If it's the last tab then hide the last button and show the finish instead
      if ($current >= $total) {

        $('#rootwizard').find('.pager .next').hide();
        $('#rootwizard').find('.pager .finish').show();
        $('#rootwizard').find('.pager .finish').removeClass('disabled');
      } else {
        $('#rootwizard').find('.pager .next').show();
        $('#rootwizard').find('.pager .finish').hide();
      }
    }
  });
</script>
@stop