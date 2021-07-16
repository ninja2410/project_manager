
$(document).ready(function() {
    
    var nombre_bodega =$("#id_bodega option:selected").text();    
    $("#name_almacen").val(nombre_bodega);
    $("#labelNombreBodega").text(nombre_bodega);
    
    var table = $('#table_advanced').DataTable({
        "autoWidth": false,
        "pageLength": 10,
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     ">>",
                "sPrevious": "<<"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },   
    });
    
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
                               
            
    //     ],
    //     "pageLength": 10,
    //     "language": {
    //         "url":" {{ asset('assets/json/Spanish.json') }}"
    //     }                                
    // });
    // $('#id_bodega').change(function() {
    //     $("#target > tbody").empty(); // Limpiamos elementos agregados anteriormente.
    //     cleanValues(); //FUnción definida en new_sale.js para borrar valores.
    //     $('body').loadingModal({
    //         text: 'Actualizando productos...'
    //     });
    //     $('body').loadingModal('show');
    //     table.ajax.reload();
    //     $('body').loadingModal('hide');
    // } );
    
    
    
} );