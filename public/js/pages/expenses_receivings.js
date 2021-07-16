var totalG = 0;
var registrosGastos = 0;
var totalEfectivoActual = 0;
var editandoG = false;

function showModal(){
    if ( parseFloat($('#total_general').val())<=0){
        toastr.error("No se pueden registrar gastos.","Debe ingresar el detalle de productos de la compra.");
    }
    else{
        $('#modalExpenses').modal('show');
    }
}

$(document).ready(function () {
    $("form#main_input_box_gastos").submit(function (event) {
        event.preventDefault();
        var deleteButton = " <a href='' class='tododelete_g redcolor'><span class='glyphicon glyphicon-trash'  style='color:red;'></span></a>";
        var striks = "<span class='striks'> |  </span>";
        var editButton = "<a href='' class='todoedit_g'><span class='glyphicon glyphicon-pencil'></span></a>";
        var twoButtons = "<div class='col-md-3 col-sm-3 col-xs-3  pull-right showbtns todoitembtns'>" + editButton + striks + deleteButton + "</div>";
        var oneButton = "<div class='col-md-3 col-sm-3 col-xs-3  pull-right showbtns todoitembtns'>" + deleteButton + "</div>";
        $(".list_of_items_gastos").append("<div class='todolist_list showactions list1'>  " +
            "<div class='col-md-12 col-sm-12 col-xs-12 nopadmar custom_textbox1'>" +
            "<div class='col-md-6 todotext_g'>" +
            $("#txtDescripcion").val() +
            "</div>" +
            "<div class='col-md-3 todotext_g1'>Q " +
            number_format(convertMoney($("#txtAmountGasto").val()), 2) +
            "</div>" +
            twoButtons +
            "</div>");
        totalG += convertMoney($('#txtAmountGasto').val());
        registrosGastos++;
        actualizarTotalesGastos();
        $("#txtDescripcion").val('');
        $("#txtAmountGasto").val('');
        $("#txtReferencia").val('');
        $("#txtDescripcion").focus();
        // $('#modelGastosTotal').val(totalG).change();
    });
    $(document).on('click', '.tododelete_g', function (e) {
        e.preventDefault();
        text = $(this).closest('.todolist_list').find('.todotext_g').text();
        text1 = $(this).closest('.todolist_list').find('.todotext_g1').text();
        totalG -= convertMoney(text1);
        registrosGastos--;
        actualizarTotalesGastos();
        $(this).closest('.todolist_list').hide("slow", function () {
            $(this).remove();
        });
    });
});

function number_format(amount, decimals) {
    amount += ""; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.-]/g, "")); // elimino cualquier cosa que no sea numero o punto

    decimals = decimals || 0; // por si la variable no fue fue pasada

    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) return parseFloat(0).toFixed(decimals);

    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = "" + amount.toFixed(decimals);

    var amount_parts = amount.split("."),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, "$1" + "," + "$2");

    return amount_parts.join(".");
}

function convertMoney(string) {
    var ns = string.replace(",", "");
    ns = ns.replace(" ", "");
    ns = ns.replace("Q", "");
    return parseFloat(ns);
}

$(document).on('click', '.todoedit_g .glyphicon-pencil', function (e) {
    e.preventDefault();
    if (editandoG) {
        toastr.error("Debe terminar de editar un pago antes de editar otro.");
        return;
    }
    editandoG = true;
    var text = '';
    var text2 = '';
    var text1 = '';
    var text3 = '';
    text = $(this).closest('.todolist_list').find('.todotext_g').text();
    text2 = $(this).closest('.todolist_list').find('.todotext_g1').text();
    text3 = $(this).closest('.todolist_list').find('.todotext_g2').text();
    totalG -= convertMoney(text2);
    text2 = number_format(text2, 2);
    text = "<input type='text' name='text_descripcion' value='" + text + "' onkeypress='return event.keyCode != 13;' />";
    text2 = "<input type='number' min='1' name='text_monto' value='" + text2 + "' class='mora' />";
    text3 = "<input type='text' name='text_referencia' value='" + text3 + "' />";
    $(this).closest('.todolist_list').find('.todotext_g').html(text);
    $(this).closest('.todolist_list').find('.todotext_g1').html(text2);
    $(this).closest('.todolist_list').find('.todotext_g2').html(text3);
    $(this).closest('.todolist_list').find('.striked').hide();
    //$(this).html("<span class='glyphicon glyphicon-saved'></span> <span class='hidden-xs'></span>");
    $(this).removeClass('glyphicon-pencil').addClass('glyphicon-saved hidden-xs');
});

$(document).on('click', '.todoedit_g .glyphicon-saved', function (e) {
    e.preventDefault();
    editandoG = false;
    var text1 = $(this).closest('.todolist_list').find("input[type='text'][name='text_descripcion']").val();
    var text2 = $(this).closest('.todolist_list').find("input[type='number'][name='text_monto']").val();
    var text3 = $(this).closest('.todolist_list').find("input[type='text'][name='text_referencia']").val();
    if (text1 === '') {
        toastr.error("La descripción no debe quedar en blanco.");
        $(this).closest('.todolist_list').find("input[type='text'][name='text_descripcion']").focus();
        return;
    }
    if (text2 === '') {
        toastr.error("El valor del monto no debe quedar en blanco.");
        $(this).closest('.todolist_list').find("input[type='number'][name='text_monto']").focus();
        return;
    }
    if (text3 == '') {
        toastr.error("La referencia del gasto no debe quedar en blanco.");
        $(this).closest('.todolist_list').find("input[type='text'][name='text_referencia']").focus();
        return;
    }
    totalG += convertMoney(text2);
    actualizarTotalesGastos();
    text2 = number_format(text2, 2);
    $(this).closest('.todolist_list').find('.todotext_g').html(text1);
    $(this).closest('.todolist_list').find('.todotext_g1').html("Q " + text2);
    $(this).closest('.todolist_list').find('.todotext_g2').html(text3);
    $(this).removeClass('glyphicon-saved hidden-xs').addClass('glyphicon-pencil');
    $(this).closest('.todolist_list').find('.striked').show();
});

function actualizarTotalesGastos() {
    $('#modelGastosTotal').val("");
    $('#totalGastos').text("");
    $('#totalGastosF').text("");
    $('#sumary_expenses').text("");
    $('#registrosGastos').text("");
    $('#modelGastosTotal').val(totalG);
    $('#totalGastos').append("Total Monto: Q " + number_format(totalG, 2));
    $('#totalGastosF').append("Total Gastos:<br> Q " + number_format(totalG, 2));
    $('#registrosGastos').append("Total Registros Gastos: " + registrosGastos);
    $('#sumary_expenses').append("Total Gastos: Q " + number_format(totalG, 2));
}

function buildJsonGastos() {
    var data = [];
    var json = {};
    $(".list1").each(function () {
        if ($(this).find('.todotext_g').text()) {
            data.push({
                "description": $(this).find('.todotext_g').text(),
                "amount": convertMoney($(this).find('.todotext_g1').text())
            });
        }
    });
    json = data;
    $('#dOutlays').val('');
    $('#dOutlays').val(JSON.stringify(json));
    calcPror();
}

function calcPror(){
    $(".id_products").each(function () {
        var id = $(this).val();
        var costo = parseFloat($('#selling_storage_1_id_item_'+id).val());
        var cantidad = $('#id_storage_1_id_item_'+id).val();
        var subtotal = parseFloat($('#total_storage_1_id_item_'+id).val());
        var total = parseFloat($('#total_general').val());
        // totalG
        var factor = subtotal/total;
        var g = (totalG*factor)/cantidad;
        $('#total_cost_1_id_item_'+id).val(parseFloat(costo+g).toFixed(2));
    });
}


