$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('#token').val()
        }
    });
    $('select').select2({
        allowClear: true,
        theme: "bootstrap",
        placeholder: "Buscar"
    });


    $('#frmNew').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        message: 'Valor no valido',
        fields:{
            name:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar nombre de elemento.'
                    }
                }
            },
            size:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar unidad de medida.'
                    }
                }
            },
            categorie_id:{
                validators:{
                    notEmpty:{
                        message:'Debe seleccionar una categoría.'
                    }
                }
            },
            description:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar descripción de elemento.'
                    }
                }
            }
        }
    });
    initialDragandDrop();
    setDetailLineTemplate('allowServices', 'lServices');
    setDetailLineTemplate('allowProducts', 'lProducts');

    document.getElementById("frmNew").onkeypress = function (e) {
        var key = e.charCode || e.keyCode || 0;
        if (key == 13) {
            e.preventDefault();
        }
    };
});

function send(){
    let val = buildJson();
    var validator = $('#frmNew').data('bootstrapValidator').validate();
    if (val<=0){
        toastr.error("Debe al menos un artículo al detalle del renglón.");
        $('#modalConf').modal('hide');
        return;
    }
    if (validator.isValid() ) {
        showLoading("Guardando renglón...");
        document.getElementById('frmNew').submit();
    }
    else{
        $('#modalConf').modal('hide');
    }
}
function buildJson(){
    var data = [];
    var json = {};
    let items = document.getElementById('selected_items_accordion').getElementsByClassName('quantity_item');
    for (let item of items){
        var id = item.getAttribute("item_id");
        var qty = cleanNumber(item.textContent);
        data.push({
            "item_id": id,
            "quantity": qty
        });
    }
    json = data;
    $('#itemDetail').val(JSON.stringify(json));
    return data.length;
}
