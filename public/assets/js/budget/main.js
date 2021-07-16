let counter_headers = 0;
let counter_details = 0;
const current = "Q ";
let budget = null;
$(document).ready(function () {
    $.fn.editable.defaults.mode = 'popup';
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('#token').val()
        }
    });
    $('.example-popover').popover({
        container: 'body'
    });

    var margenSuperior = 15;
    var posicion = $("#items_panel").offset();
    $(window).scroll(function() {
        if ($(window).scrollTop() > posicion.top) {
            $("#items_panel").stop().animate({
                marginTop: $(window).scrollTop() - posicion.top + margenSuperior
            });
        } else {
            $("#items_panel").stop().animate({
                marginTop: 0
            });
        }
    });
    initialDragandDrop();
    $('#username').editable();
    setDropableHeader();


    budget = new Budget();
    document.getElementById('budgetDetailContainer').addEventListener("DOMSubtreeModified", function () {
        budget.updateFooter();
    });

    setInterval(evalLineTemplates, 5000);
    let tabs = document.getElementsByClassName("calcTab");
    for (let tab of tabs){
        tab.addEventListener("click", function () {
            budget.renderSummarys();
        });
    }
    $(".date").datetimepicker({
        sideBySide: true,
        locale: 'es',
        format: 'DD/MM/YYYY',
        defaultDate: new Date()
    }).parent().css("position :relative");

    var cleave = new Cleave('.money', {
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'thousand'
    });
    $('select').select2({
        allowClear: false,
        theme: "bootstrap",
        placeholder: "Buscar"
    });
    //ACTIVAR AUTOGUARDADO
    //setInterval('showAutosave()',10000);
});

function sendForm(){
    $('#confirmSave').modal('hide');
    showLoading("Guardando información de presupuestos");
    $('#loading_autosave').removeClass("hidden");
    try{
        $('#data').val(budget.buildJson());
        $('#head').val(JSON.stringify(budget));
        $.ajax({
            type: "post",
            url: $('#frmSend').attr("action"),
            data: $('#frmSend').serialize(),
            success: function (data_) {
                var json = JSON.parse(data_);
                console.log(json);
                if (json.flag != 1) {
                    // $('body').loadingModal('hide');
                    toastr.error(json.message);
                    console.log(json.message);
                    hideLoading();

                } else {
                    toastr.success("Presupuesto guardado con exito!");
                    location.href = APP_URL + '/' + json.url;
                }
                hideLoading();
            },
            error: function (error) {
                // $('body').loadingModal('hide');
                hideLoading();
                console.log('Murio:' + error);
            }
        });
    }catch (e) {
        console.log(e);
    }
}

function _sendForm() {
    $('#_confirmSave').modal('hide');
    $('#confirmSave').modal('show');
}


function showConf() {
    if (budget.headers.length == 0){
        toastr.error("Debe ingresar detalles al presupuesto.");
        return;
    }
    if(budget.totalCost <= 0){
        toastr.error("El presupuesto debe tener un costo total mayor a 0.");
        return;
    }
    if ($('#date').val() == ''){
        toastr.error("Debe ingresar una fecha válida para el presupuesto");
        $('#date').focus();
        return;
    }
    if (cleanNumber($('#days').val()) <= 0){
        toastr.error("El númedo de días de vigencia del presupuesto debe tener un valor mayor a 0");
        $('#days').focus();
        return;
    }
    if (budget.hasError){
        toastr.error("Existen renglones con errores en precios unitarios.");
        $('#_confirmSave').modal('show');
        return;
    }
    $('#confirmSave').modal('show');
}
