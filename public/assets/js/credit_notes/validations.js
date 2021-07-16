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
    this.style.display = 'none';
    var select_serie = document.getElementById("id_serie");
    var customer_id = document.getElementById('customer_id');
    var item_quantity = document.getElementById('item_quantity');
    var user_relation = document.getElementById('user_relation');
    console.log(user_relation.value);
    var cmbPago = document.getElementById('id_pago');
    var bodega = document.getElementById('id_bodega');
    selected = select_serie.value;
    if (selected == 0) {
        select_serie.focus();
        toastr.error("Seleccione serie de documento");
        this.style.display = 'inline';
    } else if (parseInt(customer_id.value) == 0) {
        customer_id.focus();
        toastr.error("Seleccione o cree un cliente para la cotización");
        this.style.display = 'inline';
    } else if (parseInt(user_relation.value) == 0) {
        toastr.error("Seleccione vendedor");
        user_relation.focus();
        this.style.display = 'inline';
    } else if (parseInt(item_quantity.value) == 0) {
        toastr.error("Debe agregar productos a la cotización");
        $('#codigo').focus();
        this.style.display = 'inline';
        // document.getElementById('id_input_search').focus();
    } else {
        $('#confirmSale').modal('show');
    }
});

function sendFrm() {
    showLoading('Guardando cotización');
    sendForm();
}

function sendForm() {
    // sendForm();
    $.ajax({
        type: "post",
        url: APP_URL + '/quotation/header',
        data: $('#save_quotation').serialize(),
        success: function (data_) {
            var json = JSON.parse(data_);
            console.log(json);
            if (json.flag != 1) {
                // $('body').loadingModal('hide');
                toastr.error(json.message);
                hideLoading();

            } else {
                toastr.success("Cotización realizada con exito!");
                location.href = APP_URL + '/' + json.url;
            }
        },
        error: function (error) {
            // $('body').loadingModal('hide');
            hideLoading();
            console.log('Murio:' + error);
        }
    });
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

$('#supplier_id').change(function () {
    if ($(this).val()) {
        $.ajax(
            {
                type: 'GET',
                url: APP_URL + '/getSupplier/' + $(this).val(),
                success: function (data) {
                    $('#recipient').val(data.name_on_checks);
                    $('#supplier_credit').val(data.max_credit_amount);
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
