let hVenta;
let total_factura;
let total_acumulado;

/**
 * Permite llamar y configurar la venta seleccionada del modal de listado de ventas
 * @param control
 */
function add(control){
    let sale_id = $(control).attr("sale_id");
    /**
     * GENERAR HEADER DE VENTA
     */
    hVenta= new Header_sale(sale_id);
    hVenta.setSale();
    $('#modal-products').modal('hide');
}

/**
 * Permite llenar y configurar la venta seleccionada desde el listado de ventas de forma automática
 * @param sale_id
 */
function loadSaleDefault(sale_id){
    hVenta= new Header_sale(sale_id);
    hVenta.setSale();
}

/**
 * Por medio de una clase cambia la visibilidad de las columnas dependiendo de la acción que el usuarioo realice con la venta.
 * @param control
 */
function setDetails(control){
    let action = parseInt(control.value);
    let searchClass;
    switch (action) {
        case 1:{
            searchClass = "descuento";
            break;
        }
        case 4:{
            searchClass = "descuento";
            break;
        }
        case 2:{
            searchClass = "devolucion";
            break;
        }
        case 3:{
            searchClass = "anulacion";
            break;
        }
    }

    if (action == 4){
        $(".standar_row").each(function() {
            $(this).addClass('hide');
        });
        $(".dsc_row").each(function() {
            $(this).removeClass('hide');
        });
    }
    else{
        $(".standar_row").each(function() {
            $(this).removeClass('hide');
        });
        $(".dsc_row").each(function() {
            $(this).addClass('hide');
        });
    }

    if (action===3){
        hVenta.total_desc(number_format(total_factura, 2));
    }
    else{
        hVenta.total_documento = 0;
        $('#total_general').val(0);
    }
    $(".input_custom").each(function() {
        $(this).val(0);
        $(this).attr("oldValue", 0);
    });
    $(".detControl").each(function() {
        $(this).addClass("hide");
        $(this).val(0);
        $(this).attr("oldValue", 0);
    });
    $("."+searchClass).each(function() {
        $(this).removeClass("hide");
    });
    $('.number').toArray().forEach(function(field){
        new Cleave(field, {
            numeral: true,
            numeralPositiveOnly: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    });
}

/**
 * LISTENER VALIDACIONES DE CAMPOS ANTES DE GUARDAR NOTA DE CRÉDITO
 */
$('#idVenta').click(function () {
    let type = +document.getElementById('type_nc').value;
    let comment = document.getElementById('comment').value;
    total_acumulado = cleanNumber(document.getElementById('pending_amount').value);
    if (typeof hVenta == 'undefined'){
        toastr.error("Debe seleccionar un documento de venta para continuar.");
        return;
    }
    if (hVenta.total_documento <= 0){
        toastr.error("La nota de crédito no puede guardarse con un monto 0.");
        return;
    }
    if (type===''){
        toastr.error("Debe seleccionar motivo de nota de crédito.");
        document.getElementById('type_nc').focus();
        return;
    }
    if (hVenta.total_documento > total_acumulado){
        toastr.error("El monto de la nota de crédito ("+formatoMoneda(hVenta.total_documento, 'Q ')+") mas la suma de notas de crédito emitidas a la factura ("+formatoMoneda(total_acumulado, 'Q ')+") no puede ser mayor al monto de la factura de venta seleccionada ("+formatoMoneda(total_factura, 'Q ')+").");
        return;
    }
    if (comment===""){
        toastr.error("Debe ingresar un comentario al documento.");
        document.getElementById('comment').focus();
        return;
    }
    /**
     * PASO TODAS LAS VALIDACIONES
     */
    $('#confirmSale').modal('show');
});

/**
 * ENVIAR DOCUMENTO
 */
function sendFrm(){
    showLoading("Guardando documento...");
    $('#confirmSale').modal('hide');
    hVenta.sendDocument();
    $('#save_credit_note').submit();
}
