$('#add_customer_btn').click(function () {
    /**
     * Limpiar campos de formulario
     */
    clear_modal();
});

function clear_modal(){
    $('#name_supplier').val('');
    $('#nit_supplier').val('C/F');
    $('#name').val('');
    $('#address').val('Ciudad');
    $('#phone_number').val('');
    $('#email').val('');
    $('#credit').val('');
    $('#days_credit').val('');
    $('#name_on_checks').val('');
}

$('#btnSaveNewProvider').click(function () {
    if ($('#nit_supplier').val() == '') {
        toastr.error("El nit del proveedor es requerido");
        $('#nit_supplier').focus();
        return;
    }
    if ($('#name_supplier').val() == '') {
        toastr.error("El nombre de la empresa proveedora es requerido");
        $('#name_supplier').focus();
        return;
    }
    if ($('#name').val() == '') {
        toastr.error("El nombre del contacto del proveedor es requerido");
        $('#name').focus();
        return;
    }
    if ($('#address').val() == '') {
        toastr.error("La dirección del proveedor es requerida");
        $('#address').focus();
        return;
    }
    $('#modal-2 .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;margin-left: 35%;margin-top: 8%;"></i></span>');
    var url = APP_URL + '/suppliers/ajax';
    $.ajax({
        type: "post",
        url: url,
        data: $('#frmNewProvider').serialize(),
        success: function (data) {
            $('#span-loading').remove();
            if ((data.errors)) {
                console.log("existe un error revisar");
            } else {
                // console.log(data);
                if (data == "Ya existe un cliente con ese nombre") {
                    toastr.error("Cliente existente, por favor verifique.");
                    $("#name_supplier").focus();
                    //   alert("No se puede agregar ya existe un cliente con ese nombre");
                    // address_customer2.value = "Ciudad";
                } else {
                    //   var id = data.id;
                    //   var name = data.name;
                    $("#supplier_id").append('<option value="' + data.id + '" selected="selected">' + data.nit_supplier + ' | ' + data.company_name + '</option>');
                    $('#supplier_id').change();
                    // clean_customer();
                    $("#modal-2").modal('hide');
                    $(".modal-backdrop").remove();
                }
            }
        }
    });
});

/*
* CONSULTAR EL BENEFICIARIO DEL PROVEEDOR SELECCIONADO Y PONERLO EN EL CAMPO DEL FORMULARIO
* */
$('#supplier_id').change(function () {
    if ($(this).val()) {
        $.ajax(
            {
                type: 'GET',
                url: APP_URL + '/getSupplier/' + $(this).val(),
                success: function (data) {
                    $('#recipient').val(data.name_on_checks);
                },
                error: function (error) {
                    console.log(error);
                }
            }
        );
    }
    else{
        $('#recipient').val('');
    }
});