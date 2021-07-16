//#region LOGICA DE VUELTOS
$('#bank_balance').keyup(function () {
    $('#pending').val('');
    $('#pending').val(Math.abs  (cleanNumber($('#bank_balance').val())-cleanNumber($('#countable_balance').val())).format(2));
    $('#bank_print_balance').val($(this).val());
});
//#endregion

//#region LÓGICA DE DOCUMENTOS CONCILIADOS
function setdefaultValue() {
    table.rows('.1').select();
}

function showLoading(message) {
    $("#confirm").modal('hide');
    $('body').loadingModal({
        text: message
    });
}

function sendFrm() {
    showLoading('Guardando documentos conciliados...');
    buildJson();
}

function buildJson() {
    var ingresos = [];
    var egresos = [];
    var json_ingresos = {};
    var json_egresos = {};

    table.rows().iterator('row', function (context, index) {
        var node = $(this.row(index).node());
        var conciliado = false;
        if (node.hasClass('selected')) {
            conciliado = true;
        } else {
            conciliado = false;
        }

        if (node.attr('tipo') == 'Ingreso') {
            ingresos.push({
                "id": node.attr('id'),
                "conciliado": conciliado
            })
        } else {
            egresos.push({
                "id": node.attr('id'),
                "conciliado": conciliado
            })
        }
        json_ingresos = ingresos;
        json_egresos = egresos;
        $('#ingresos').val('');
        $('#ingresos').val(JSON.stringify(json_ingresos));
        $('#egresos').val('');
        $('#egresos').val(JSON.stringify(json_egresos));
    });

    if (ingresos.length == 0 && egresos.length == 0){
        toastr.error("Debe haber transacciones en el listado para poder realizar la operación.");
        hideLoading();
        return;
    }

    //ENVIANDO DATOS AJAX
    $.ajax({
        type: "post",
        url: APP_URL + '/bank_reconciliation/save_documents',
        data: $('#frmSend').serialize(),
        success: function (data_) {
            var json = JSON.parse(data_);
            if (json.flag == 1) {
                toastr.success(json.message);
                location.reload();
            } else {
                toastr.error(json.message);
            }
            $('body').loadingModal('destroy');
        },
        error: function (error) {
            $('body').loadingModal('destroy');
            toastr.error("Ocurrió un error, revisar consola.");
            console.log('Error: ' + error);
        }
    });


    //$('body').loadingModal('destroy');
}

//#endregion LÓGICA DE DOCUMENTOS CONCILIADOS

//#region GESTION DE ENCABEZADO (CERRAR MES)
$('#cerrarMes').click(function () {
    if ($('#bank_balance').val()==''){
        toastr.error("Debe ingresar el balance bancario");
        $('#bank_balance').focus();
        return;
    }
    if (cleanNumber($('#pending').val()) != 0){
        toastr.error("El saldo pendiente de conciliar debe ser 0.");
        return;
    }
    $('#tab_Cerrar').css('display', 'inline');
    $('#conciliation_id').val($('#conciliation_id_ref').val());
    $('#initial_balance').val($('#start_balance').val());
    $('#initial_balance').prop('readonly', true);
    //VERIFICAR DATOS

    $('#confirm_reconciliation').modal('show');
});

$('#confirm_reconciliation_btn').click(function () {
    //VERIFICAR DATOS
    if ($('#bank_balance').val()==''){
        toastr.error("Debe ingresar el balance bancario");
        $('#bank_balance').focus();
        return;
    }
    $('#confirm_reconciliation').modal('show');
});

$('#save_reconciliation').click(function () {
    $('#confirm_reconciliation').modal('hide');
    showLoading('Guardando datos de cierre de mes...');
    $.ajax({
        type: "post",
        url: APP_URL + '/bank_reconciliation/header',
        data: $('#frmClose').serialize(),
        success: function (data_) {
            var json = JSON.parse(data_);
            if (json.flag == 1){
                window.location = APP_URL+'/bank_reconciliation/header/'+json.id;
                toastr.success(json.message);
            }
            else{
                toastr.error(json.message);
            }
            $('body').loadingModal('destroy');
        },
        error: function (error) {
            $('body').loadingModal('destroy');
            toastr.error("Ocurrió un error, revisar consola.");
            console.log('Error: ' + error);
        }
    });
});
//#endregion GESTION DE ENCABEZADO
