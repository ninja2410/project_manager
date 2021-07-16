<!-- BEGIN: Modal - SEARCH PRODUCTS -->
<div class="modal fade" id="modal-products" role="dialog" aria-labelledby="modalLabelsuccess">
    <div class="modal-dialog modal-lg" role="document" style="width: 1100px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalLabelsuccess">Busqueda de productos
                    <button type="button" class="close btn-danger" data-dismiss="modal" data-backdrop="false"
                            aria-label="Cerrar">
                        <span aria-hidden="true">Cerrar</span>
                    </button>
                    {{-- <button type="button" class="btn btn-success close" data-dismiss="modal" data-backdrop="false" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button> --}}
                </h4>

            </div>
            <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Productos</label>
                            <table class="table" id="table_advanced">
                                <thead>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Existencia</th>
                                <th>Costo</th>
                                <th>Agregar</th>
                                </thead>
                                <tbody>
                                @foreach($data_items as $value)
                                    <tr>
                                        <td>{{$value->code}}</td>
                                        <td>{{$value->item_name}}</td>
                                        <td>{{$value->quantity}}</td>
                                        <td>{{$value->cost_price}}</td>
                                        <td>
                                            @if($value->quantity>0)
                                                <button type="button" name="button"
                                                        class="btn btn-primary btn-xs" id="{{$value->id}}"
                                                        onclick="add(this)">
                                                            <span class="glyphicon glyphicon-share-alt"
                                                                  aria-hidden="true"></span>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
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
