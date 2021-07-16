class Header_sale {
    /**
     * Constructor de clase encabezado de factura
     * @param sale_id
     */
    constructor(sale_id) {
        this.sale_id = sale_id;
        this.data_sale = {};
        this.total_documento = 0;
    }

    /**
     * MODIFICA EL TOTAL DE DESCUENTO APLICADO A LA NOTA DE CRÉDITO, ACTUALIZA TOTAL
     * @param val: Valor que modifica al total de descuento
     */
    total_desc(val) {
        this.total_documento += cleanNumber(val);
        document.getElementById('total_general').value = "Q " + cleanNumber(this.total_documento).toFixed(2);
    }

    get total_doc() {
        return this.total_documento;
    }

    /**
     * Función asíncrona que permite leer los datos de la venta
     * @returns {Promise<*>}
     */
    async loadSale() {
        showLoading("Cargando información de venta seleccionada...");
        let url = APP_URL + '/sales/get_details_ajax/' + this.sale_id;
        let resp;
        let ajx = await $.ajax({
            type: "get",
            url: url,
            success: function (data) {
                resp = data;
            },
            error: function (error) {
                console.log("existe un error revisar:" + error);
            }
        });
        return resp;
    }

    /**
     * lectura de detalles de venta
     * @returns {{}}
     */
    get details() {
        return this.data_sale;
    }

    /**
     * LEE Y CONFIGURA LOS DATOS DE LA VENTA Y SUS DETALLES
     */
    setSale() {
        let resp = this.loadSale();
        let ldetails;
        let head;
        resp.then(function (value) {
            let tmp = JSON.parse(value);
            let detailHtml;
            ldetails = tmp.details;
            head = tmp.header;
            let nameDocument = head.documento + " " + head.serie + "-" + head.correlative;
            $('#credit_note_sale').val(nameDocument);

            ldetails.forEach(function (value) {
                let tmpDet = new Detail(value.quantity, value.item_name, value.id, value.upc_ean_isbn, value.selling_price);
                detailHtml += tmpDet.render;
            });
            let tmpDetDsc = new Detail(1, "Descuento a documento:" + nameDocument,
                "N/A", "N/A", head.total_cost);
            detailHtml += tmpDetDsc.renderGd;


            $("#target tbody tr").remove();
            $('#target tbody').append(detailHtml);
            $('#type_nc').trigger("change");
            $('#total_sale').val(number_format(head.total_cost), 2);
            total_factura = parseFloat(head.total_cost);
            total_acumulado = parseFloat(tmp.acum);
            $('#sale_id').val(head.id);
            $('#pending_amount').val(number_format((head.total_cost-head.nc_amount), 2));
            document.getElementById('total_general').value = 0;
            $("#sale_info > tbody").empty();
            let sale_info = '<tr>' +
                '<td>'+head.cliente+'</td>' +
                '<td>'+head.sale_date+'</td>' +
                '<td>Q '+number_format(head.total_cost, 2)+'</td>' +
                '<td>Q '+number_format(head.nc_amount, 2)+'</td>' +
                '<td>'+head.pago+'</td>' +
                '</tr>';
            $('#sale_info > tbody').append(sale_info);
            $("#type_nc").prop("disabled", false);
            hideLoading();
        });
        this.data_sale = ldetails;
    }

    sendDocument() {
        let type = document.getElementById('type_nc').value;
        let detail_descuento = [];
        let detail_devolucion = [];
        let json_descuento = {};
        let json_devolucion = {};

        if (type !== 3) {
            $(".descuento_input").each(function () {
                if (+$(this).val() > 0) {
                    detail_descuento.push({
                        "detail_id": $(this).attr("id_detail"),
                        "value": $(this).val()
                    });
                }
            });

            $(".devolucion_input").each(function () {
                if (+$(this).val()>0){
                    detail_devolucion.push({
                        "detail_id": $(this).attr("id_detail"),
                        "value": $(this).val()
                    });
                }
            });
            json_descuento = JSON.stringify(detail_descuento);
            json_devolucion = JSON.stringify(detail_devolucion);
            document.getElementById('descuentos_json').value = json_descuento;
            document.getElementById('devoluciones_json').value = json_devolucion;
        }
    }
}
