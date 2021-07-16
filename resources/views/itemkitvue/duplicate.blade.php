@extends('layouts/default')

@section('title',trans('itemkit.kit_duplicate'))
@section('page_parent',trans('itemkit.kit'))

@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/vue-multiselect.min.css') }}" rel="stylesheet" type="text/css" />
{{-- <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" /> --}}
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
                            {{trans('itemkit.kit_duplicate')}}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>

                    <div class="panel-body">
                        <div class="row">

                            <div class="alert alert-danger" v-if="error">
                                <li v-for="e in errores" v-text="e"></li>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('upc_ean_isbn', trans('item.upc_ean_isbn')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="code" data-size="16" data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <input type="text" class="form-control" style="position:static" name="code" v-model="code"
                                                value="{{Input::old('code')}}" ref="code" v-on:keyup.enter="changeInput('name')"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('item_name', trans('itemkit.item_kit_name').' *') !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="responsive-menu" data-size="16"
                                                        data-c="#555555" data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="item_kit_name"
                                                value="{{Input::old('item_kit_name')}}" v-model="nombreKit" style="position:static" ref="name" v-on:keyup.enter="changeInput('categoria')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('size', trans('item.category')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="tag" data-size="16" data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <multiselect v-model="categoria" :options="categorias"
                                                    placeholder="Seleccione Categoria" label="name" track-by="name" ref="categoria" @select="changeInput('size')">
                                                </multiselect>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('size', trans('itemkit.size')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="resize-big-alt" data-size="16"
                                                        data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <input type="text" class="form-control" name="item_kit_name"
                                                v-model="size" style="position:static" ref="size" v-on:keyup.enter="changeInput('description')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('size', trans('itemkit.description')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="align-justify" data-size="16"
                                                        data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <input type="text" class="form-control"
                                                v-model="description" style="position:static" ref="description" v-on:keyup.enter="changeInput('productos')">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('avatar', trans('item.choose_avatar')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="image" data-size="16"
                                                        data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <input type="file" @change="onFilePicked" accept="image/*" ref="image" v-on:keyup.enter="changeInput('productos')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                {!! Form::label('productos', trans('itemkit.search_item')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="tag" data-size="16" data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <multiselect v-model="item" :options="items"
                                                    placeholder="Seleccione Producto"
                                                    :custom-label="nameWithLang" ref="productos" @input="addItemKit" :loading="loading"></multiselect>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                {!! Form::label('quantity', trans('itemkit.quantity')) !!}
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="livicon" data-name="plus-alt" data-size="16"
                                                        data-c="#555555"
                                                        data-hc="#555555"
                                                        data-loop="true"></i>
                                                    </div>
                                                    <input type="number" class="form-control" name="quantity"
                                                    v-model="quantity" @keyup.enter="addItemKit" v-on:keyup="absQuantity" @click="absQuantity" style="position:static" ref="cantidad"/>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-3">
                                            <label for="item_kit_serach_item" class="control-label">Agregar
                                                Producto</label>
                                            <div>
                                                <button class="btn btn-primary btn-block" v-on:click="addItemKit">Agregar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>{{trans('itemkit.code')}}</th>
                                            <th>{{trans('itemkit.item_name')}}</th>
                                            <th>{{trans('itemkit.cost_price')}}</th>
                                            <th>{{trans('itemkit.quantity')}}</th>
                                            <th>{{trans('itemkit.total')}}</th>
                                            {{-- <th v-for="price in prices" :key="price.id" v-text="price.name"></th> --}}
                                            <th>{{trans('itemkit.actions')}}</th>
                                        </tr>
                                        <tr v-for="kitItem in kitItems" :key="kitItem.id">
                                            <th v-text="kitItem.upc_ean_isbn"></th>
                                            <th v-text="kitItem.item_name"></th>
                                            <th v-text="kitItem.cost_price"></th>
                                            <th><input type="number" class="form-control" name="quantity"
                                                v-model="kitItems[getIndex(kitItems, kitItem.id)].quantity" v-on:keyup="changeQuantity(getIndex(kitItems, kitItem.id))"/></th>
                                                {{-- <th v-for="myprice in kitItem.prices" v-text="myprice"></th> --}}
                                                <th v-text="'Q '+kitItem.total"></th>
                                            <th><button class="btn btn-danger glyphicon glyphicon-trash" v-on:click="removeKit(kitItem)"></button></th>
                                        </tr>
                                    <th colspan="4" class="text-right">{{trans('itemkit.kit_cost')}}</th>
                                            <th v-text="'Q '+sumaCost"></th>
                                    </table>
                                </div>
                                <hr>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Precios</th>
                                            <th>Tipo de pago asociados</th>
                                            <th style="text-align:center;">Suma Precios de Venta</th>
                                            <th style="text-align:center;">% Utilidad</th>
                                            <th style="text-align:center;">Precio</th>                                            
                                            <th style="text-align:center;">Utilidad Q</th>
                                        </tr>
                                        <tr v-for="(price,index) in prices">
                                            <th v-text="price.name"></th>
                                            <th v-text="price.pago"></th>
                                            <th v-text="'Q'+subtotal[index]" class="text-right"></th>
                                            <th>
                                                <div class="input-group">
                                                    <div class="input-group-addon">%</div>
                                                    <input type="number" class="form-control" v-model="utility[index]" v-on:input="sumUtility(index)" style="position:static"/>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="input-group">
                                                    <div class="input-group-addon">Q</div>
                                                    <input type="number" class="form-control" v-model="priceSale[index]" v-on:input="sumPrice(index)" style="position:static"/>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="input-group">
                                                    <div class="input-group-addon">Q</div>
                                                    <input type="number" class="form-control" v-model="utilitySale[index]" v-on:input="sumUtilitySale(index)" style="position:static"/>
                                                </div>
                                            </th>
                                            {{-- <th><input type="number" class="form-control"
                                                v-model="utility[index]" v-on:input="sumUtility(index)"/></th>
                                            <th><input type="number" class="form-control"
                                                v-model="priceSale[index]" v-on:input="sumPrice(index)"/></th>                                            
                                            <th><input type="number" class="form-control"
                                                        v-model="utilitySale[index]" v-on:input="sumUtilitySale(index)" /></th> --}}
                                        </tr> 
                                    
                                    </table>
                                    <p><strong>Suma Precios de Venta:</strong> Suma de los precios de venta de cada producto</p>
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
                                                        <a class="btn btn-danger" href="/item-kits-vue">
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
                ¿Desea guardar el Kit?
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

{!! Html::script('js/item.kits.vue.duplicate.js', array('type' => 'text/javascript')) !!}

@endsection
@section('footer_scripts')

<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>

<script type="text/javascript">
    // $('#item_kit_form').bootstrapValidator({
        // feedbackIcons: {
        //     valid: 'glyphicon glyphicon-ok',
        //     invalid: 'glyphicon glyphicon-remove',
        //     validating: 'glyphicon glyphicon-refresh'
        // },
        // fields: {
        //     item_kit_name: {
        //         validators: {
        //             notEmpty: {
        //                 message: 'Debe ingresar nombre del kit'
        //             }
        //         }
        //     },
            // selling_price: {
            //     validators: {
            //         notEmpty: {
            //             message: 'Debe ingresar un precio de venta.'
            //         },
            //         regexp:{
            //             regexp: /^\d*\.?\d*$/,
            //             message: 'Ingrese un número válido'
            //         }
            //     }
            // }
    //     }
    // });
</script>
@stop