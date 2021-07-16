$(document).ready(function() {

    var table = $('#table_advanced').DataTable({
        "ajax":{
            "method":"GET",
            "url": APP_URL+"/sales/get_active_sales_ajax",
            "dataSrc": "",
            "headers": {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "customer"},
            { "data": "sale_date"},
            { "data": "document" },
            { "data": "pago" },
            { "data": "total_cost",
            render: function ( data, type, row ) {
                return 'Q '+ data;
            } },
            { "data": "nc_amount" },
            { "data": "balance",
                render: function ( data, type, row ) {
                    return 'Q '+ data;
                } },
            { "data": null, render: function ( data, type, row ) {
                return '<td>'+
                '<button type="button" name="button" sale_id="'+row.sale_id+'" onclick="add(this);" class="btn btn-primary btn-xs">'+
                '<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>'+
                '</button>'+
                '</td>';
            } },


        ],
        "pageLength": 10,
        "language": {
            // "url":" {{ asset('assets/json/Spanish.json') }}"
            search: "_INPUT_",
            searchPlaceholder: "Buscar...",
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Next",
                "sPrevious": "Previous"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
    // }

    function recargaProductos(){
        // var bodega_id = cleanNumber($('#id_bodega').val());
        // var pago_id = cleanNumber($('#id_pago').val());
        // if ((bodega_id>0) && (pago_id>0)) {
        $("#target > tbody").empty(); // Limpiamos elementos agregados anteriormente.
        cleanValues(); //FUnción definida en new_sale.js para borrar valores.
        $('body').loadingModal({
            text: 'Actualizando productos...'
        });
        $('body').loadingModal('show');
        table.ajax.reload();
        $('body').loadingModal('hide');
        // }
    }

    function cleanValues()
    {
        $('#total_general').val(0.00);
        $('#total_general2').val(0.00);
        $('#amount').val(0.00);
        $('#paid').val(0.00);
        $('#change').val('0.00');
        contador=0;
        $('#item_quantity').val(contador);

    }
} );
