{{--Begin modal--}}
<div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="load_sale_modal" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Configuración de cotización</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    Para poder cargar los datos de la cotización debe seleccinar una bodega.
                    <br>
                    <hr>
                    <strong>Nota:</strong> Si las existencias de la bodega seleccionada no tiene la existencia suficiente deberá modificar las cantidades de la cotización, de lo contrario se mostrará el formulario de ventas.
                </div>
                <div class="row">
                    <div class="form-group">
                        {!! Form::label('id_bodega', trans('sale.storage'), ['class' => 'control-label']) !!}
                        <div class="input-group select2-bootstrap-prepend">
                            <div class="input-group-addon"><i class="fa fa-archive"></i></div>
                            <select class="form-control" name="id_bodegas" id="id_bodega">
                                <option value="">Seleccione bodega</option>
                                @foreach($almacen as $value)
                                    <option value="{!! $value->id !!}">
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                {{-- {!! Form::open(array('url' => 'customers/' . $value->id, 'class' => 'pull-right')) !!}
                {!! Form::hidden('_method', 'DELETE') !!} --}}
                {{-- <button type="submit" class="btn  btn-info">Aceptar</button> --}}
                <a class="btn  btn-info" onclick="setCellar();">Aceptar</a>
                <a class="btn  btn-danger" data-dismiss="modal">Cancelar</a>
                {{-- <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                {!! Form::close() !!} --}}
            </div>
        </div>
    </div>
</div>
{{--End modal--}}
