{{--Begin modal--}}
<div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="confirmSave" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Confirmación guardar</h4>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    Datos correctos
                    <br>
                    ¿Seguro que desea guardar?
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                <a class="btn  btn-info" id="btn_save_confirm" onclick="document.getElementById('addCustomerPayment').submit();">Aceptar</a>
                <a class="btn  btn-danger" data-dismiss="modal" >Cancelar</a>
            </div>
        </div>
    </div>
</div>
{{--End modal--}}
