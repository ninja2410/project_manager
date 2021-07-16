let totalReceived = 0;
let totalCost = 0;
$(document).ready(function () {
    updateLabelTotal();
});

function updateAmount(control){
    let oldValue = cleanNumber(control.getAttribute('oldValue'));
    let newValue = cleanNumber(control.value);
    let maxValue = cleanNumber(control.getAttribute('max'));
    if (newValue > maxValue){
        toastr.error("No puede ingrear una cantidad mayor al monto del débito inicial.");
        control.value = oldValue;
        return;
    }
    control.setAttribute("oldValue", newValue);
    updateTotalAmount();
}

function updateTotalAmount(){
    let newTotal = 0;
    $('.amount_confirm').toArray().forEach(function(field){
        newTotal += cleanNumber(field.value);
    });
    $('#total_amount_confirmed').val(newTotal.toFixed(2));
}

function updateTotal(control) {
    let oldValue = cleanNumber(control.getAttribute('oldValue'));
    let newValue = cleanNumber(control.value);
    let maxValue = cleanNumber(control.getAttribute('max'));
    let cost = cleanNumber(control.getAttribute('cost_price'));
    let detail_id = cleanNumber(control.getAttribute('detail_id'));
    if (isNaN(newValue)){
        toastr.error("Debe ingresar un número válido.");
       // control.value = oldValue;
        return;
    }
    if (newValue<0){
        toastr.error("Debe ingresar un número mayor que 0");
        control.value = oldValue;
        return;
    }
    if (newValue>maxValue){
        toastr.error("No puede exceder de la cantidad de producto enviado");
        control.value = oldValue;
        return;
    }
    else{
        control.setAttribute('oldValue', newValue);
    }
    totalReceived -= (oldValue);
    totalReceived += (newValue);
    //actualizar costo del traslado
    totalCost -= cleanNumber(oldValue * cost);
    totalCost += cleanNumber(newValue * cost);
    $('#cost_detail_'+detail_id).html("Q "+cleanNumber(newValue * cost).toFixed(2));
    updateLabelTotal();
}

function updateLabelTotal(){
    document.getElementById('totalReceived').value = number_format(totalReceived, 2);
    document.getElementById('total_cost_recived').value = 'Q '+number_format(totalCost, 2);
    document.getElementById('ref_received_amount').value =number_format(totalCost, 2);

}

$('#btnSettAll').click(function () {
    $('.receivedQuantity').toArray().forEach(function(field){
        field.value = field.getAttribute('max');
        const e = new Event("input");
        field.dispatchEvent(e);
    });
});

$('#btnUnsettAll').click(function () {
    $('.receivedQuantity').toArray().forEach(function(field){
        field.value = 0;
        const e = new Event("input");
        field.dispatchEvent(e);
    });
});

$("#codigo").keydown(function(event){
    if( event.which == 13)
    {
        event.preventDefault();
        searchCode($(this).val());
        $(this).val('');
        $(this).focus();
    };
});

function searchCode(code){
    document.getElementById(code).value ++;
    $('#'+code).trigger("input");
}

function confirm(){
    if ($('#set_accounts').val()=="true"){
        let cost_received = cleanNumber($('#total_cost_recived').val());
        let cost_confirmed = cleanNumber($('#total_amount_confirmed').val());
        if (cost_confirmed != cost_received){
            toastr.error("El monto total recibido no coincide con el monto confirmado.");
            $('#tabPayment').click();
            return;
        }
    }

    $('#confirmSave').modal("show");
}

function sendForm(){
    showLoading("Enviando datos de traslado...");
    var data = [];
    var details = [];
    var json = {};
    $(".receivedQuantity").each(function(){
        data.push({
            "detail_id": $(this).attr("detail_id"),
            "quantity_recived": cleanNumber($(this).val())
        });
    });
    json = data;

    $(".amount_confirm").each(function(){
        details.push({
            "payment_id": $(this).attr("payment_id"),
            "amount_confirm": cleanNumber($(this).val())
        });
    });
    $('#details_received').val(JSON.stringify(json));
    $('#detail_payments').val(JSON.stringify(details));
    $('#frm').submit();
}
