{{--Begin modal--}}
<div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="confirm_reconciliation" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">{{trans('reconciliation.confirm')}}</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    Datos correctos
                    <br>
                    {{trans('reconciliation.confirm_document')}}
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                {{-- {!! Form::open(array('url' => 'customers/' . $value->id, 'class' => 'pull-right')) !!}
                {!! Form::hidden('_method', 'DELETE') !!} --}}
                {{-- <button type="submit" class="btn  btn-info">Aceptar</button> --}}
                <a class="btn  btn-info" id="save_reconciliation">Aceptar</a>
                <a class="btn  btn-danger" data-dismiss="modal">Cancelar</a>
                {{-- <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                {!! Form::close() !!} --}}
            </div>
        </div>
    </div>
</div>
{{--End modal--}}
