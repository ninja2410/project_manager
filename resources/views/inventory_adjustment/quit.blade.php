@extends('layouts/default')

@section('title',trans('inventory_adjustment.inventory_exit'))
@section('page_parent',trans('inventory_adjustment.inventory'))

@section('header_styles')
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
{{-- vue --}}
<link href="{{ asset('css/vue-multiselect.min.css') }}" rel="stylesheet" type="text/css" />
{{-- <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" /> --}}
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
type="text/css" />
@stop
@section('content')
<div id="app">
    <template>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                                data-loop="true"></i>
                            {{trans('inventory_adjustment.inventory_exit')}}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('serie', trans('inventory_adjustment.serie')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="list" data-size="16" data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <select class="form-control" style="position:static" v-model="serie" ref="serie">
                                                        <option :value="0" selected disabled>{{trans('inventory_adjustment.selection_serie')}}</option>
                                                        <option v-for="s in series" :key="s.series" :value="s.id" v-text="s.name"></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('Correlativo', trans('inventory_adjustment.correlative')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="list" data-size="16" data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <input type="number" class="form-control" v-model="correlativo" style="position:static">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('bodega', trans('inventory_adjustment.almacen')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-archive"></i>
                                                    </div>
                                                    <select class="form-control" v-model="bodega" style="position:static" ref="bodega">
                                                        <option :value="0" selected disabled>{{trans('inventory_adjustment.selection_almacen')}}</option>
                                                        <option v-for="bode in bodegas" :key="bode.id" :value="bode.id" v-text="bode.name"></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('Fecha', trans('inventory_adjustment.date')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <input type="text" name="date" id="date" class="form-control" ref="date" style="position:static">
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('descripcion', trans('inventory_adjustment.description')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="align-justify" data-size="16"
                                                        data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <textarea class="form-control" rows="3" v-model="description" style="position:static"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                {!! Form::label('productos', trans('inventory_adjustment.search_product')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="tag" data-size="16" data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <multiselect v-model="item" :options="items"
                                                    placeholder="Seleccione Producto"
                                                    :custom-label="nameWithLang" 
                                                    ref="productos" @close="addItem" 
                                                    :loading="loading" @search-change="val => read(val)"></multiselect>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        <label for="itemSerch" class="control-label">{{trans('inventory_adjustment.product')}}</label>
                                            <div>
                                                <button class="btn btn-primary btn-block" v-on:click="addItem">Agregar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <thead>
                                             <tr>
                                                 <th style="width: 5%;" class="text-center">No.</th>
                                                 <th style="width: 15%;" class="text-center">{{trans('inventory_adjustment.code')}}</th>
                                                 <th style="width: 35%;" class="text-center">{{trans('inventory_adjustment.product')}}</th>
                                                 <th style="width: 10%;" class="text-center">{{trans('inventory_adjustment.cost_price')}}</th>
                                                 <th style="width: 10%;" class="text-center">{{trans('inventory_adjustment.existence')}}</th>
                                                 <th style="width: 10%;" class="text-center">{{trans('inventory_adjustment.quantity')}}</th>
                                                 <th style="width: 10%;" class="text-center">{{trans('inventory_adjustment.new_existence')}}</th>
                                                 <th style="width: 5%;" class="text-center">{{trans('inventory_adjustment.actions')}}</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             <tr v-for="(Item,index) in Items" :key="Item.id">
                                                 <td v-text="index+1"></td>
                                                 <td v-text="Item.upc_ean_isbn"></td>
                                                 <td v-text="Item.item_name"></td>
                                                 <td v-text="money(Item.cost_price)" class="text-right"></td>
                                                 <td v-text="Item.existence"></td>
                                                 <td><input type="number" class="form-control" v-model="Items[getIndex(Items,Item.id)].quantity" @blur="sumNuevaExistence(getIndex(Items,Item.id))"/></td>
                                                 <td v-text="Item.newExistence"></td>
                                                 <td><button class="btn btn-danger glyphicon glyphicon-trash" v-on:click="removeItem(Item)"></button></td>
                                             </tr>
                                         </tbody>
                                         <tfoot>
                                             <tr>
                                                 <td colspan="3" class="text-right"><strong>TOTALES: </strong></td>
                                                 <td class="text-right"><strong v-text="amount"></strong></td>
                                                 <td></td>
                                                 <td><strong v-text="quantity"></strong></td> 
                                                 <td></td>
                                                 <td></td>
                                             </tr>
                                         </tfoot>
                                     </table>
                                </div>
                                <hr>    
                                <div class="row">
                                    <div class="col-sm-12">
                                        {{-- <button type="submit" class="btn btn-primary btn-block">{{trans('itemkit.submit')}}</button>
                                        --}}
                                        <div class="row">
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-4" style="text-align: center;">
                                                <div class="form-group">
                                                        <button class="btn btn-primary" v-on:click="save">
                                                            {{trans('button.save')}}
                                                        </button>
                                                        <a class="btn btn-danger" href="/inventory_adjustment/index/output">
                                                            {{trans('button.cancel')}}
                                                        </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-4"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </template>
    {{--Begin modal--}}
    <div class="modal fade modal-fade-in-scale-up in" tabindex="-1" id="modalDelete" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true" >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-info">
              <h4 class="modal-title">Confirmación Guardar</h4>
            </div>
            <div class="modal-body">
              <div class="text-center">
                <p id="name_item"></p>
                <br>
                ¿Desea guardar el ajuste de inventario?
              </div>
            </div>
            <div class="modal-footer" >
              <div class="row">
                <div class="col-lg-6" style="text-align: right;">
                  <button v-on:click="saveAll" class="btn btn-info">
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
</div>
{!! Html::script('js/vue.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/vue.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/vue-multiselect.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/axios.min.js', array('type' => 'text/javascript')) !!}

{!! Html::script('js/pages/inventory_adjustment/inventory_adjustment_vue.js', array('type' => 'text/javascript')) !!}

@endsection
@section('footer_scripts')

<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
{{-- TOAST --}}
<script type="text/javascript" src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
<!-- Calendario  -->
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    var dateNow = new Date();
    $("#date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
    $('#date').blur(function(){
        if($('#date').val()=="") {
            $('#date').val(get_date_today(0));
    } });
</script>
@stop