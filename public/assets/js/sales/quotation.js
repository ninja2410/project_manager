async function load_quotation() {

    var itemsProcessed = 0;
    var id = $('#quotation_id').val();
    if (id != 0) {
        try {
            let message = await showLoading("Cargando datos de cotización");
            let quotation = await loadDetails(id);
        }
        catch (e) {
            console.log(e)
        }

    }
}

function loadDetails(id){
    /**
     * CONSULTAR DATOS DE LA COTIZACIÓN
     */
    $.ajax({
        url: APP_URL + '/quotation/header/getdetails/' + id,
        async:false,
        method: "GET",
        success: function (data) {
            var resp = JSON.parse(data);
            var header = JSON.parse(resp.header);
            details = JSON.parse(resp.details);
            /*SET DATOS DEL CLIENTE*/
            $("#customer_id").val(header.customer_id).trigger('change');
            $("#id_bodega").val($('#cellar_id').val()).trigger('change');
            $("#id_price").val(header.price_id).trigger('change');
            /*CARGAR EL DETALLE*/
            details.forEach(function (value, index) {
                buscar_codigo(value.code, $('#cellar_id').val(), header.price_id,value.price, value.quantity_sale);
            });
        },
        error: function (error) {
            alert('Ha ocurrido un error intente de nuevo');
        }
    });
}
