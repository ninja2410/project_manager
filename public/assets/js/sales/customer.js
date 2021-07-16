function add_customers(value_receiving) {
  var customer_id = document.getElementById('customer_id');
  var name_customer = document.getElementById('name_customer');
  var name = value_receiving.id.split("_");
  var name_id = name[1].split("/");
  customer_id.value = name_id[1];
  name_customer.value = name_id[0];
}

$(document).ready(function() {
  $('#table_customers').DataTable({
    "bLengthChange": false,
    // "bFilter": true,
    "bInfo": false,
    "bAutoWidth": false,
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Buscar...",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      },
    },
    "columnDefs": [{
      "visible": false,
      "searchable": false
    }]
  });

  btnSaveCustomer = document.getElementById('btnSaveCustomer');
  btnSaveCustomer.addEventListener('click', function() {
    nit_customer2 = document.getElementById('nit_customer2');
    name_customer2 = document.getElementById('name_customer2');
    var address_customer2 = document.getElementById('address_customer2');

    if (nit_customer2.value == "") {
      nit_customer2.focus();
      toastr.error("Ingrese nit del cliente.");
    } else if (name_customer2.value == "") {
      name_customer2.focus();
      toastr.error("Ingrese nombre de cliente.");
    } else if (address_customer2.value == "") {
      address_customer2.focus();
      toastr.error("Ingrese dirección del cliente.")
    } else {
      // alert("Si se puede guardar el campo");
      // console.log("Llama Ajax");
      $.ajax({
        type: "post",
        url: APP_URL+'/customers/addCustomerAjaxPos',
        data: {
          _token: $('#token_').val(),
          'nit': $('#nit_customer2').val(),
          'name': $('#name_customer2').val(),
          'dpi': $('#dpi').val(),
          'email': $('#email').val(),
          'phone': $('#phone').val(),
          'address': address_customer2.value,
        },
        success: function(data) {
          if ((data.errors)) {
            console.log("existe un error revisar");
          } else {
            // console.log(data);
            if (data == "Ya existe un cliente con ese nombre") {
              alert("No se puede agregar ya existe un cliente con ese nombre");
              address_customer2.value = "Ciudad";
            } else {
              var id = data.id;
              var name = data.name;
              document.getElementById('customer_id').value = id;
              document.getElementById('name_customer').value = name;
              $('#table_customers').dataTable().fnDestroy();
              $('#table_customers').append("<tr role='row' class><td class>" + data.id + "</td><td>" + data.nit_customer + "</td><td>" + data.name + "</td><td></td><td></td><td><button  type='button' name='button' class='btn btn-primary btn-xs' id='name_" + data.name + "/" + data.id + "' onclick='add_customers(this);' data-dismiss='modal'><span class='glyphicon glyphicon-check' aria-hidden='true'></span></button>" + "</td></tr>");
              $('#table_customers').dataTable();
              // console.log("Ahora debe ocultar el modal");
              $("#modal-2").hide();
              $(".modal-backdrop").remove();
            }
          }
        }
      });
    }

  });
});
