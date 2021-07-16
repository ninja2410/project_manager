<!-- BEGIN: Modal - SEARCH PRODUCTS -->
<div class="modal fade" id="modal-products" role="dialog" aria-labelledby="modalLabelsuccess">
    <div class="modal-dialog modal-lg"  role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalLabelsuccess">Busqueda de productos
                    <button type="button" class="close btn-danger" data-dismiss="modal" data-backdrop="false" aria-label="Cerrar">
                        <span aria-hidden="true">Cerrar</span>
                    </button>
                </h4>
            </div>
            <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
                <div class="row">
                    <center>
                        <!-- <div class="col-lg-12">
                          <div class="form-group">
                            <h2 id="title_bodega">Bodega: </h2>
                          </div>
                        </div> -->
                    </center>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <table class="table table-bordered" id="table_advanced" style="font-size: 11px; width: 100%;">
                                <thead>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Tamaño</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                                </thead>
                                {{-- <tbody>
                                @foreach($list_products as $i => $value)
                                    <tr>
                                        <td>{!! $value->upc_ean_isbn !!}</td>
                                        <td><a data-toggle="tooltip" data-original-title="Ver imagen del producto" href="#" onclick="showImage('{!! asset('images/items/') . '/' . $value->avatar !!}', '{{$value->item_name}}')">{!! $value->item_name !!}</a></td>
                                        <td>{!! $value->description !!}</td>
                                        <td>{!! $value->size !!}</td>
                                        <td style="text-align:right;">@money($value->cost_price)</td>
                                        <td>
                                            <input type="hidden" id="id_{{ $value->id }}" value="{{ $value->id }}">
                                            <input type="hidden" id="name_almacen" value="">
                                            <input type="hidden" id="existencias_{{ $value->id }}" value="{{ $value->quantity }}">
                                            <input type="hidden" id="nombre_{{ $value->id }}" value="{{ $value->item_name }}">
                                            <input type="hidden" id="precio_{{ $value->id }}" value="{{ $value->cost_price }}">
                                            <input type="hidden" id="low_price_{{ $value->id }}" value="{{ $value->low_price }}">
                                            <input type="hidden" id="is_kit_{{ $value->id }}" value="{{ $value->is_kit }}">
                                            <input type="hidden" id="stock_action_{{ $value->id }}" value="{{ $value->stock_action }}">
                                            <button type='button' name='button'  onclick='add(this);' value="{{ $value->quantity }}" class='btn btn-primary btn-xs' id="{{ $value->id }}">
                                                <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Modal - SEARCH PRODUCTS -->
