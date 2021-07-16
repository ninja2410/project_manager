// function add_customers(value_receiving) {
//   var customer_id = document.getElementById('customer_id');
//   var name_customer = document.getElementById('name_customer');
//   var name = value_receiving.id.split("_");
//   var name_id = name[1].split("/");
//   customer_id.value = name_id[1];
//   name_customer.value = name_id[0];
// }

$(document).ready(function () {
    $("#modal-2").on("shown.bs.modal", function () {
        $("#nit_customer2").focus();
    });
    let flag = false;

    function clean_customer() {
        document.getElementById("nit_customer2").value = "";
        document.getElementById("name_customer2").value = "";
        document.getElementById("address_customer2").value = "";
        document.getElementById("phone").value = "";
    }

    btnSaveCustomer = document.getElementById("btnSaveCustomer");
    btnSaveCustomer.addEventListener("click", function (e) {
        nit_customer2 = document.getElementById("nit_customer2");
        name_customer2 = document.getElementById("name_customer2");
        var address_customer2 = document.getElementById("address_customer2");
        var ruta = document.getElementById('ruta');
        if (typeof (ruta) != 'undefined' && ruta != null) {
            flag = true;
        }
        if (nit_customer2.value == "") {
            nit_customer2.focus();
            toastr.error("Ingrese nit del cliente.");
            return;
        }
        if (!nitValid(nit_customer2.value)) {
            toastr.error("El NIT ingreado es inválido.");
            nit_customer2.focus();
            return;
        }
        if (name_customer2.value == "") {
            name_customer2.focus();
            toastr.error("Ingrese nombre de cliente.");
            return;
        }
        if (address_customer2.value == "") {
            address_customer2.focus();
            toastr.error("Ingrese dirección del cliente.");
            return;
        }
        if (flag == true && (ruta.value === "Seleccione ruta" || ruta.value === "0")) {
            ruta.focus();
            toastr.error("Seleccione una ruta.");
            return;
        }
        $("#modal-2 .modal-header").before(
            '<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;margin-left: 35%;margin-top: 8%;"></i></span>');

        nit_customer2 = document.getElementById('nit_customer2');
        name_customer2 = document.getElementById('name_customer2');
        var address_customer2 = document.getElementById('address_customer2');

        $.ajax({
            type: "post",
            url: APP_URL + "/customers/addCustomerAjaxPos",
            data: {
                _token: $("#token_").val(),
                nit: $("#nit_customer2").val(),
                name: $("#name_customer2").val(),
                dpi: $("#dpi").val(),
                email: $("#email").val(),
                phone: $("#phone").val(),
                ruta: (flag == true) ? ruta.value : 'No hay data',
                address: address_customer2.value,
            },
            success: function (data) {
                $("#span-loading").remove();
                if (data.errors) {
                    console.log("existe un error revisar");
                } else {
                    // console.log(data);
                    if (data == "Ya existe un cliente con ese nombre") {
                        toastr.error("Cliente existente, por favor verifique.");
                        $("#name_customer2").focus();
                    } else {
                        $("#customer_id").append(
                            '<option value="' +
                            data.id +
                            '" selected="selected" max_credit_amount="' +
                            (data.max_credit_amount - data.balance) +
                            '" days_credit="0">' +
                            data.nit_customer +
                            " | " +
                            data.name +
                            "</option>"
                        );
                        clean_customer();
                        $("#modal-2").hide();
                        $(".modal-backdrop").remove();
                    }
                }
            }/* Success */

        });/**Ajax */
    }); /**else  */
});
