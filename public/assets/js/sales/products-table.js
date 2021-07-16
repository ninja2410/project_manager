
$(document).ready(function() {

    var nombre_bodega =$("#id_bodega option:selected").text();
    $("#name_almacen").val(nombre_bodega);
    $("#labelNombreBodega").text(nombre_bodega);

    listado_items =[];

    var table = $('#table_advanced').
    DataTable({
        "autoWidth": false,
        "ajax":{
            "method":"GET",
            "url":APP_URL+"/items/index_services_ajax_by_storage_price/",
            "dataSrc": "",
            "data": function ( d ) {
                d.id = cleanNumber($('#id_bodega').val());
                d.id_price = cleanNumber($('#id_price').val());
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
            { "data": "quantity" },
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
            // "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sEmptyTable":     "Seleccione Bodega/Precio",
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
        },
        "drawCallback": function( settings ) {
            // alert( 'DataTables has redrawn the table' );

        }
    });
    // }


    $('#id_bodega').change(function() {
        recargaProductos();
        $("#target > tbody").empty();
        cleanValues();
    });

    $('#id_price').change(function(){
        var id_price = cleanNumber($('#id_price').val());
        // console.log(id_price);
        if((id_price) && (id_price>0)) {

            recargaProductos();
        };
        $('#id_price').val(id_price);

    })

    function recargaProductos(){
        get_cart_items();
        $("#target > tbody").empty(); // Limpiamos elementos agregados anteriormente.
        cleanValues(); //FUnción definida en new_sale.js para borrar valores.
        $('body').loadingModal({
            text: 'Actualizando productos...'
        });
        $('body').loadingModal('show');
        table.ajax.reload();
        hideLoading();
        update_cart_items();
    }


    $( "#id_price" ).change(function() {
        var id_price = cleanNumber($(this).val());

        if(id_price && id_price>0)
        {
            $.get(APP_URL+'/api/getPaymentTypeByPrice/'+[id_price],function(data) {
                $('#id_pago').select2({
                    allowClear: true,
                    theme: "bootstrap",
                    placeholder: "Buscar"
                });
                $('#id_pago').empty();
                $('#id_pago').append('<option value="0">--Seleccione--</option>');
                $.each(data, function(index,pagos){
                    // var cuenta_default = $('#cuenta_default').val();
                    // if (cuenta_default==pagos.id) {
                    //     $('#account_id').append('<option value="'+ pagos.id +'" selected="selected">'+ pagos.name +' => Q. ' +pagos.pct_interes+'</option>');
                    // }else {
                        $('#id_pago').append('<option value="'+ pagos.id +'" type="'+pagos.type+'">'+ pagos.name +'</option>');
                    // }

                });
            });
        }
    });



    function update_cart_items() {
        if(listado_items.length>0){
            var bodega = $("#id_bodega").val().trim();
            var id_price = $("#id_price").val().trim();
            listado_items.forEach(function(item){
                showLoading("Cargando datos de artículos seleccionados");
                $.get(APP_URL+'/api/sales/search_id_storage_price?id='+ item.item_id + "&bodega_id=" + bodega + "&id_price=" + id_price, function (data) {
                    flag = true;
                    agregar(data, 0, item.quantity);/*Llamamos la funcion para agregar a la tabla de productos*/
                    hideLoading();
                });
            });
            listado_items=[];
        }
    }



} );
function get_cart_items () {
    var cuantos = $('#item_quantity').val();
    var bodega = $("#id_bodega").val().trim();
    listado_items =[];
    // for (let index = 1; index <= cuantos; index++) {
    //     var producto = document.getElementsByName('id_product_' +index)[0].value;
    //     if (document.getElementById('id_storage_'+bodega+'_id_item_'+producto+'_'+index) !== null){
    //         var producto = document.getElementsByName('id_product_' +index)[0].value;
    //         var cantidad = document.getElementById('id_storage_'+bodega+'_id_item_'+producto+'_'+index).value;
    //         listado_items.push({"item_id":producto, "quantity":cantidad});
    //         // console.log('index '+index +' id elemento '+producto + ' |'+cantidad);
    //         //console.log(listado_items);
    //     }
    // }
    let detalles = document.getElementsByClassName("_rowItem");
    for (let row of detalles){
        let producto = row.getElementsByClassName("_itemId")[0].value;
        let cantidad = row.getElementsByClassName("_itemQuantity")[0].value;
        listado_items.push({"item_id":producto, "quantity":cantidad});
    }
}
