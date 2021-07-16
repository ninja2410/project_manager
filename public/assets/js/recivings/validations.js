function valida(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) {
        return true;
    }
    patron = /[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function noenter() {
    return !(window.event && window.event.keyCode == 13);
}

//boton de verificación de vender
var idVenta = document.getElementById('idVenta');
idVenta.addEventListener('click', function () {
    var select_serie = document.getElementById("id_serie");
    var supplier_id = document.getElementById('supplier_id');
    var item_quantity = document.getElementById('item_quantity');

    var cmbPago = document.getElementById('id_pago');
    var bodega = document.getElementById('id_bodega');
    selected = select_serie.value;
    if (selected == 0) {
        select_serie.focus();
        toastr.error("Seleccione serie de documento");
    } else if (parseInt(supplier_id.value) == 0) {
        supplier_id.focus();
        toastr.error("Seleccione o cree un proveedor para la compra");
    }
        // else if (parseInt(user_relation.value) == 0) {
        //     toastr.error("Seleccione vendedor");
        //     user_relation.focus();
        //     this.style.display = 'inline';
    // }
    else if (parseInt(bodega.value) == 0) {
        toastr.error("Seleccione bodega");
        bodega.focus();
    } else if (parseInt(cmbPago.value) == 0) {
        toastr.error("Debe seleccionar una forma de pago");
        cmbPago.focus();
        // document.getElementById('id_input_search').focus();
    } else if (parseInt(item_quantity.value) == 0) {
        toastr.error("Debe agregar productos a la compra");
        $('#codigo').focus();
        // document.getElementById('id_input_search').focus();
    } else {
        $.ajax({
            type: "post",
            url: APP_URL + '/existCorrelative',
            data: {
                _token: $('#token_').val(),
                'id_serie': $('#id_serie').val(),
                'correlative': $('#id_correlativo').val()
            },
            success: function (data) {
                if (data == 0) {
                    /* Si paso las validaciones del tab de venta y
                    * si el correlativo no ha sido utilizado
                    * mostramos la sección de pagos */
                    $('#tab_pago').show(); /*Hacemos visible el tab de pagos, que estaba oculto */
                    $('#link_pago').click(); /*Activamos el tab de pagos */

                    if (form_is_valid()) {
                        $('#idVenta').show();
                        // $("#modal-2").hide();
                        console.log('Levantar modal')
                        $('#confirmSale').modal('show');
                        // alert('guardar');

                    } else {
                        // $('#idVenta').show();/*Hacemos visible el boton*/
                        console.log('formulario INValido');
                    }


                } else {
                    toastr.error("El correlativo ya ha sido utilizado en otra factura", "Error");
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
});

function sendFrm() {
    showLoading();
    sendForm();
}

function showLoading() {
    $("#confirmSale").modal('hide');
    $('body').loadingModal({
        text: 'Guardando compra...'
    });
    // $('body').loadingModal('show');
    sleep(1000);
}

function sendForm() {
    // sendForm();
    $.ajax({
        type: "post",
        url: APP_URL + '/receivings',
        data: {
            _token: $('#token_').val(),
            'venta': $('#save_receivings').serialize(),
            'pago': $('#frm_payment').serialize()
        },
        success: function (data_) {
            var json = JSON.parse(data_);
            console.log(json);
            if (json.flag != 1) {
                // $('body').loadingModal('hide');
                toastr.error(json.message);
                $('#idVenta').show();
            } else {
                toastr.success("Compra realizada con exito!");
                location.href = APP_URL + '/' + json.url;
            }
            $('body').loadingModal('destroy');
        },
        error: function (error) {
            // $('body').loadingModal('hide');
            $('body').loadingModal('destroy');
            console.log('Murio:' + error);
            $('#idVenta').show();
        }
    });
}

function form_is_valid() {

    if ($('#account_id').hasClass('validation-cacao') && !$('#account_id').val()) {
        $('#account_id').focus();
        toastr.error("Debe seleccionar una cuenta");
        return false;
    }
    if ($('#paid').hasClass('validation-cacao')) {
        if (!$('#paid').val()) {
            $('#paid').focus();
            toastr.error("Debe ingresar el monto pagado");
            return false;
        }
        // console.log(' paid '+ parseFloat($('#paid').val()));
        // console.log(' total_general '+ parseFloat($('#total_general').val()));

        if (cleanNumber($('#paid').val()) < parseFloat($('#total_general').val())) {
            $('#paid').focus();
            toastr.error("Monto pagado debe ser mayor o igual al monto a pagar.");
            return false;
        }

    }

    if ($('#description').hasClass('validation-cacao') && !$('#description').val()) {
        $('#description').focus();
        toastr.error("Debe ingresar una descripciǿn");
        return false;
    }

    if ($('#recipient').hasClass('validation-cacao') && !$('#recipient').val()) {
        $('#recipient').focus();
        toastr.error("Debe ingresar nombre del beneficiacio de la transferencia/cheque.");
        return false;
    }

    if ($('#reference').hasClass('validation-cacao')) {
        var largo = $('#reference').val().length;
        if (!$('#reference').val() || largo < 1) {
            $('#reference').focus();
            toastr.error("Debe ingresar el número de cheque/transacción");
            return false;
        }
    }
    if ($('#bank_name').hasClass('validation-cacao') && !$('#bank_name').val()) {
        $('#bank_name').focus();
        toastr.error("Debe ingresar el nombre del banco");
        return false;
    }
    if ($('#same_bank').hasClass('validation-cacao') && !$('#same_bank').val()) {
        $('#same_bank').focus();
        toastr.error("Debe indicar si el cheque es del mismo banco o no ");
        return false;
    }


    if ($('#card_name').hasClass('validation-cacao')) {
        var largo = $('#card_name').val().length;
        if (!$('#card_name').val() || largo < 4) {
            $('#card_name').focus();
            toastr.error("Debe ingresar el nombre en la tarjeta");
            return false;
        }
    }
    if ($('#card_number').hasClass('validation-cacao')) {
        var largo = $('#card_number').val().length;
        if (!$('#card_number').val() || largo < 4 || largo > 4) {
            $('#card_number').focus();
            toastr.error("Debe ingresar los últimos 4 dígitos de la tarjeta");
            return false;
        }
    }
    if ($('#supplier_credit_amount').hasClass('validation-cacao')) {
        if (!$('#supplier_credit_amount').val()) {
            toastr.error("Proveedor debe tener crédito autorizado");
            return false;
        }
        console.log(' customer_credit ' + parseFloat($('#supplier_credit_amount').val()));
        console.log(' total_general ' + parseFloat($('#total_general').val()));

        if (cleanNumber($('#supplier_credit_amount').val()) < parseFloat($('#total_general').val())) {
            $('#supplier_credit').focus();
            toastr.error("Crédito autorizado (" + cleanNumber($('#supplier_credit_amount').val()) + ") debe ser mayor o igual al monto a pagar (" + parseFloat($('#total_general').val()) + ").");
            return false;
        }

    }
    if ($('#date_payments').hasClass('validation-cacao')) {
        var fecha_pago = $('#date_payments').val();
        var arrStartDate = fecha_pago.split("/");
        var date_pago = new Date(arrStartDate[2], (arrStartDate[1]-1), arrStartDate[0]);
        var fecha_factura = $('#date_tx').val();
        var arrEndDate = fecha_factura.split("/");
        var date_factura = new Date(arrEndDate[2], (arrEndDate[1]-1), arrEndDate[0]);

        var days_credit = $('#days_credit').val();
        var tmp = arrEndDate[0]+"/"+ arrEndDate[1]+"/"+ arrEndDate[2];
        var reference_date = new Date(editar_fecha(tmp, days_credit, 'd', '/'));
        if (fecha_pago == "") {
            toastr.error("Debe ingresar la fecha de pago");
            $('#date_payments').focus();
            return false;
        } else if (date_factura >= date_pago) {
            // $('#date_payments').focus();
            toastr.error("La fecha de pago debe ser mayor a la fecha de la factura");
            $('#date_payments').focus();
            return false;
        } else if (date_pago>reference_date){
            let formatted_date = appendLeadingZeroes(reference_date.getDate())+"/"+appendLeadingZeroes(reference_date.getMonth() + 1) + "/" +reference_date.getFullYear() ;
            toastr.error("La fecha de pago no debe exceder el límite de días de crédito para el proveedor seleccionado ["+formatted_date+"].");
            return false;
        }

    }
    return true;
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function editar_fecha(fecha, intervalo, dma, separador) {

    var separador = separador || "-";
    var arrayFecha = fecha.split(separador);
    var dia = arrayFecha[0];
    var mes = arrayFecha[1];
    var anio = arrayFecha[2];

    var fechaInicial = new Date(anio, mes - 1, dia);
    var fechaFinal = fechaInicial;
    if(dma=="m" || dma=="M"){
        fechaFinal.setMonth(fechaInicial.getMonth()+parseInt(intervalo));
    }else if(dma=="y" || dma=="Y"){
        fechaFinal.setFullYear(fechaInicial.getFullYear()+parseInt(intervalo));
    }else if(dma=="d" || dma=="D"){
        fechaFinal.setDate(fechaInicial.getDate()+parseInt(intervalo));
    }else{
        return fecha;
    }
    dia = fechaFinal.getDate();
    mes = fechaFinal.getMonth() + 1;
    anio = fechaFinal.getFullYear();

    dia = (dia.toString().length == 1) ? "0" + dia.toString() : dia;
    mes = (mes.toString().length == 1) ? "0" + mes.toString() : mes;

    return anio + "-" + mes + "-" + dia
}

$('#supplier_id').change(function () {
    if ($(this).val()) {
        $.ajax(
            {
                type: 'GET',
                url: APP_URL + '/getSupplier/' + $(this).val(),
                success: function (data) {
                    if (data){
                        $('#recipient').val(data.name_on_checks);
                        $('#supplier_credit').val(parseFloat(data.max_credit_amount-data.balance).toFixed(2));
                        $('#supplier_credit_amount').val(parseFloat(data.max_credit_amount-data.balance).toFixed(2));
                        $('#id_pago').trigger('change');
                    }
                    else{
                        $('#supplier_credit').val(0);
                        $('#id_pago').trigger('change');
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            }
        );
    } else {
        $('#recipient').val('');
        $('#supplier_credit').val(0);
    }
});


function appendLeadingZeroes(n){
    if(n <= 9){
        return "0" + n;
    }
    return n
}
