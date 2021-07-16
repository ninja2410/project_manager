let serie;
let no;
document.getElementById('receipt_number').addEventListener('input', function () {
     serie = document.getElementById('serie_id').value;
     no = $(this).val();
    if (serie==''){
        return;
    }
    vrData()
});

$('#serie_id').on('select2:select', function (e) {
    no  = document.getElementById('receipt_number').value;
    serie = $(this).val();
    if (serie==''){
        return;
    }
    vrData();
});

function vrData(){
    showLoading("Verificando correlativo");
    $.get(APP_URL + '/serie/verify/' + [serie]+'/'+[no], function (data) {
        let dat = JSON.parse(data);
        if (dat.counter>0){
            toastr.error("Ya existe un documento con el correlativo ingresado. ");
            document.getElementById('receipt_number').value = dat.recomend;
        }
        hideLoading();
    });
}
