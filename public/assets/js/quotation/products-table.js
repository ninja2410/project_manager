let listado_items = [];
$(document).ready(function() {
    var nombre_bodega =$("#id_bodega option:selected").text();
    $("#name_almacen").val(nombre_bodega);
    $("#labelNombreBodega").text(nombre_bodega);

    var table = $('#table_advanced').DataTable({
        "autoWidth": false,
        "ajax":{
            "method":"GET",
            "url": APP_URL+"/items/index_services_ajax_by_pago/",
            "dataSrc": "",
            "data": function ( d ) {
                d.price_id = cleanNumber($('#price_id').val());
            },
            "headers": {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "upc_ean_isbn"},
            { "data": "item_name" },
            { "data": "description" },
            { "data": "size" },
            { "data": "selling_price",
            render: function ( data, type, row ) {
                return 'Q '+ data;
            } },
            { "data": null, render: function ( data, type, row ) {
                return '<td><input type="hidden" id="id_'+row.id+'" value="'+row.id+'">'+
                '<input type="hidden" id="existencias_'+row.id+'" value="'+row.quantity+'">'+
                '<input type="hidden" id="nombre_'+row.id+'" value="'+row.item_name+'">'+
                '<input type="hidden" id="precio_'+row.id+'" value="'+row.selling_price+'">'+
                '<input type="hidden" id="low_price_'+row.id+'" value="'+row.low_price+'">'+
                '<input type="hidden" id="is_kit_'+row.id+'" value="'+row.is_kit+'">'+
                '<input type="hidden" id="stock_action_'+row.id+'" value="'+row.stock_action+'">'+
                '<button type="button" name="button"  onclick="add(this);" value="'+row.quantity+'" class="btn btn-primary btn-xs" id="'+row.id+'">'+
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


    $('#price_id').change(function(){
        var id_price = cleanNumber($(this).val());

        if(id_price && id_price>0) {
            // console.log('Adm id: '+$('#account_id').val());
            load();
            recargaProductos();
        }
    });

    async function load(){
        await get_cart_items;
        await update_cart_items;
        console.log('Ejecutando');
    }
    var update_cart_items = new Promise(function (resolve, reject){
        let price = $('#price_id').val();
        if (listado_items.length > 0){
            listado_items.forEach(function (item) {
                showLoading("Actualizando producto seleccionado");
                $.get(APP_URL+"/quotation/search_id?id=" + item.item_id+"&price_id="+price, function (data) {
                    agregar(data, item.quantity);/*Llamamos la funcion para agregar a la tabla de productos*/
                    hideLoading();
                    resolve('okey');
                });
            });
        }
    });

    function recargaProductos(){
        // var bodega_id = cleanNumber($('#id_bodega').val());
        // var pago_id = cleanNumber($('#id_pago').val());
        // if ((bodega_id>0) && (pago_id>0)) {
        showLoading("Actualizando listado de productos");
        $("#target > tbody").empty(); // Limpiamos elementos agregados anteriormente.
        cleanValues(); //FUnción definida en new_sale.js para borrar valores.
        table.ajax.reload();
        hideLoading();
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

    // var table = $('#table_advanced').DataTable({
    //     "pageLength": 10,
    //     "language": {
    //         "sProcessing":     "Procesando...",
    //         "sLengthMenu":     "Mostrar _MENU_ registros",
    //         "sZeroRecords":    "No se encontraron resultados",
    //         "sEmptyTable":     "Ningún dato disponible en esta tabla",
    //         "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    //         "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    //         "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    //         "sInfoPostFix":    "",
    //         "sSearch":         "Buscar:",
    //         "sUrl":            "",
    //         "sInfoThousands":  ",",
    //         "sLoadingRecords": "Cargando...",
    //         "oPaginate": {
    //             "sFirst":    "Primero",
    //             "sLast":     "Último",
    //             "sNext":     ">>",
    //             "sPrevious": "<<"
    //         },
    //         "oAria": {
    //             "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
    //             "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    //         }
    //     },
    // });

    //     },
    // });
    // var table = $('#table_advanced').DataTable({
    //     "ajax":{
    //         "method":"GET",
    //         "url":"../items/index_services_ajax_all/",
    //         "dataSrc": "",
    //         // "data": function ( d ) {
    //         //     d.id = cleanNumber($('#id_bodega').val());
    //         // },
    //         "headers": {
    //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
    //         }
    //     },
    //     "columns": [
    //         { "data": "upc_ean_isbn"},
    //         { "data": "item_name" },
    //         { "data": "description" },
    //         { "data": "size" },
    //         { "data": "quantity" },
    //         { "data": "selling_price",
    //         render: function ( data, type, row ) {
    //             return 'Q '+ data;
    //         } },
    //         { "data": null, render: function ( data, type, row ) {
    //           return '<td><input type="hidden" id="id_'+row.id+'" value="'+row.id+'">'+
    //                   '<input type="hidden" id="existencias_'+row.id+'" value="'+row.quantity+'">'+
    //                   '<input type="hidden" id="nombre_'+row.id+'" value="'+row.item_name+'">'+
    //                   '<input type="hidden" id="precio_'+row.id+'" value="'+row.selling_price+'">'+
    //                   '<input type="hidden" id="low_price_'+row.id+'" value="'+row.low_price+'">'+
    //                   '<input type="hidden" id="is_kit_'+row.id+'" value="'+row.is_kit+'">'+
    //                   '<input type="hidden" id="stock_action_'+row.id+'" value="'+row.stock_action+'">'+
    //                   '<button type="button" name="button"  onclick="add(this);" value="'+row.quantity+'" class="btn btn-primary btn-xs" id="'+row.id+'">'+
    //                     '<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>'+
    //                   '</button>'+
    //                 '</td>';
    //         } },
});
var get_cart_items = new Promise(function (resolve, reject){
    let products = document.getElementsByClassName('id_products');
    for (let prod of products){
        var cantidad = cleanNumber(document.getElementById('id_storage_1_id_item_' +prod.value).value);
        listado_items.push({
            item_id:prod.value,
            quantity: cantidad
        });
    }
    listado_items.reverse();
    resolve('ready');
});
