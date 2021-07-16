$(document).ready(function() {
  var renClient = document.getElementsByName("renovation");
  if (renClient[0].value != "-") {
    funcion_alerta(renClient[0]);
  }
  if ($("#lastCreditJson").val() != "") {
    vm.cargarCreditoAnterior();
    vm.generarCuotas();
  }
});

function verifyFiador(){
  var rsp = false;
  var url =  APP_URL+"/verifyFiador/" + $("#fiador_dpi").val();
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      if (data>0) {
        toastr.error("El DPI de fiador ya ha sido registrado en otro crédito activo.");
        document.getElementById('fiador_dpi').value="";
        document.getElementById('fiador_dpi').focus();
      }
    },
    error: function(error) {
      console.log("existe un error revisar");
    }
  });
  return rsp;
}

function confirAuth() {
  console.log("enviando");
  $("#botones").hide();
  $("#loading").show();
  document.getElementById("formPagare").submit();
}

function verifyBusiness() {
  var rsp = false;
  var url = "../verifybusiness/" + $("#customer_id").val();
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      if (data == 1) {
        rsp = true;
      } else {
        rsp = false;
      }
    },
    error: function(error) {
      console.log("existe un error revisar");
    }
  });
  return rsp;
}
function validFia() {
  //Funcion para validar fiador dependiendo del parametro
  var mnt = document.getElementById("guarantor").value;
  var cmnt = document.getElementById("amount").value;

  if (parseFloat(cmnt.replace(",", "")) > mnt) {
    document.getElementById("messsageMaxFiador").style.display = "inline";
    return false;
  } else {
    document.getElementById("messsageMaxFiador").style.display = "none";
    return true;
  }
}
function valDataFiador() {
  var bandera = true;
  if ($("#fiador_name").val() == "" || $("#fiador_name").val().length < 3) {
    toastr.error("Debe ingresar nombre de fiador");
    document.getElementById("fiador_name").focus();
    bandera = false;
    return false;
  }
  if (
    $("#fiador_direccion").val() == "" ||
    $("#fiador_direccion").val().length < 3
  ) {
    toastr.error("Debe ingresar dirección de fiador");
    document.getElementById("fiador_direccion").focus();
    bandera = false;
    return false;
  }
  if (
    $("#fiador_telefono").val() == "" ||
    $("#fiador_telefono").val().length < 8
  ) {
    toastr.error("Debe ingresar teléfono de fiador");
    document.getElementById("fiador_telefono").focus();
    bandera = false;
    return false;
  }
  if ($("#fiador_dpi").val() == "" || $("#fiador_dpi").val().length < 13) {
    toastr.error("Debe ingresar teléfono de fiador");
    document.getElementById("fiador_dpi").focus();
    bandera = false;
    return false;
  }
  return bandera;
}

function vWorkReferences() {
  if ($("#refLab1Nombre").val() == "") {
    toastr.error("Debe ingresar nombre de referencia laboral");
    document.getElementById("refLab1Nombre").focus();
    return false;
  } else if ($("#refLab1Telefono").val() == "") {
    toast.error("Debe ingresar teléfono de referencia laboral");
    document.getElementById("refLab1Telefono").focus();
    return false;
  } else if ($("#refLab2Nombre").val() == "") {
    toastr.error("Debe ingresar nombre de referencia laboral");
    document.getElementById("refLab2Nombre").focus();
    return false;
  } else if ($("#refLab2Telefono").val() == "") {
    toast.error("Debe ingresar teléfono de referencia, laboral");
    document.getElementById("refLab2Telefono").focus();
    return false;
  } else if ($("#refLab3Nombre").val() == "") {
    toastr.error("Debe ingresar nombre de referencia laboral");
    document.getElementById("refLab3Nombre").focus();
    bandera = false;
    return false;
  } else if ($("#refLab3Telefono").val() == "") {
    toast.error("Debe ingresar teléfono de referencia laboral");
    document.getElementById("refLab3Telefono").focus();
    return false;
  }
  return true;
}

function valCard() {
  var url = "../valid_Card/" + $("#creditCodigo").val();
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      if (data > 0) {
        document.getElementById("creditCodigo").value = "";
        $("#creditCodigo").focus();
        document.getElementById("btn_save").disabled = true;
        toastr.error("Error.", "El número de tarjeta ya existe!", {
          timeOut: 5000
        });
        return false;
      } else {
        document.getElementById("btn_save").disabled = false;
        return true;
      }
    },
    error: function(error) {
      console.log("existe un error revisar");
    }
  });
}
function editarCliente() {
  var errores = false;
  if (document.getElementById("new_customer_name").value == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El nombre del cliente es un campo requerido", {
      timeOut: 5000
    });
    document.getElementById("new_customer_name").focus();
  } else if ($("#dpi").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El DPI es un campo requerido", { timeOut: 5000 });
    document.getElementById("dpi").focus();
  } else if ($("#customer_address").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-botton-left"
    };
    toastr.error("Error.", "La dirección es un campo requerido", {
      timeOut: 5000
    });
    document.getElementById("customer_address").focus();
  } else if ($("#newCustomer_phone").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El teléfono es un campo requerido", {
      timeOut: 5000
    });
    document.getElementById("newCustomer_phone").focus();
  } else if ($("#birthdate").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "La fecha de nacimiento es requerida", {
      timeOut: 5000
    });
    document.getElementById("birthdate").focus();
  } else if ($("#ref1_name").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El nombre de referencia es requerido.", {
      timeOut: 5000
    });
    document.getElementById("ref1_name").focus();
  } else if ($("#ref1_phone").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El teléfono de referencia es un campo requerido", {
      timeOut: 5000
    });
    document.getElementById("ref1_phone").focus();
  } else if ($("#ref1_phone").val().length != 8) {
    toastr.error("Error.", "El teléfono de referencia debe tener 8 dígitos", {
      timeOut: 5000
    });
    document.getElementById("ref1_phone").focus();
  } else if ($("#ref2_name").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El nombre de referencia es requerido.", {
      timeOut: 5000
    });
    document.getElementById("ref2_name").focus();
  } else if ($("#ref2_phone").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El teléfono de referencia es un campo requerido", {
      timeOut: 5000
    });
    document.getElementById("ref2_phone").focus();
  } else if ($("#ref3_name").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El nombre de referencia es requerido.", {
      timeOut: 5000
    });
    document.getElementById("ref3_name").focus();
  } else if ($("#ref3_phone").val() == "") {
    toastr.options = {
      closeButton: true,
      positionClass: "toast-bottom-left"
    };
    toastr.error("Error.", "El teléfono de referencia es un campo requerido", {
      timeOut: 5000
    });
    document.getElementById("ref3_phone").focus();
  } else if (verificarDatos()) {
  } else {
    if ($("#inDependency").val() == "1") {
      if (!vm.verifyWork() && !vWorkReferences()) {
        errores = true;
      } else {
        errores = false;
      }
    } else if ($("#inDependency").val() == "0") {
      if (!vm.verifyDataBussines()) {
        errores = true;
      } else {
        errores = false;
      }
    }
    if (errores) {
      return;
    }
    console.log("enviando");
    var url = "../customers/editCustomerAjax";
    axios
      .post(url, {
        work_address: $("#work_address").val(),
        work_name: $("#work_name").val(),
        work_number: $("#work_phone").val(),
        refer1Nombre: $("#ref1_name").val(),
        refer2Nombre: $("#ref2_name").val(),
        refer3Nombre: $("#ref3_name").val(),
        refer1Direccion: $("#ref1_address").val(),
        refer2Direccion: $("#ref2_address").val(),
        refer3Direccion: $("#ref3_address").val(),
        refer1Telefono: $("#ref1_phone").val(),
        refer2Telefono: $("#ref2_phone").val(),
        refer3Telefono: $("#ref3_phone").val(),
        refLab1Nombre: $("#refLab1Nombre").val(),
        refLab1Direccion: $("#refLab1Direccion").val(),
        refLab1Telefono: $("#refLab1Telefono").val(),
        refLab2Nombre: $("#refLab2Nombre").val(),
        refLab2Direccion: $("#refLab2Direccion").val(),
        refLab2Telefono: $("#reflab2Telefono").val(),
        refLab3Nombre: $("#refLab3Nombre").val(),
        refLab3Direccion: $("#refLab3Direccion").val(),
        refLab3Telefono: $("#reflab3Telefono").val(),
        fam1_name: $("#fam1_name").val(),
        fam1_address: $("#fam1_address").val(),
        fam1_phone: $("#fam1_phone").val(),
        fam2_name: $("#fam2_name").val(),
        fam2_address: $("#fam2_address").val(),
        fam2_phone: $("#fam2_phone").val(),
        fam3_name: $("#fam3_name").val(),
        fam3_address: $("#fam3_address").val(),
        dependency: $("#inDependency").val(),
        fam3_phone: $("#fam3_phone").val(),
        busines_name: $("#business_name").val(),
        busines_address: $("#business_address").val(),
        business_phone: $("#business_phone").val(),
        business_description: $("#business_description").val(),
        business_maps: $("#business_maps").val(),
        id_customer: $("#id_customer").val(),
        edit: $("#chkEdit").is(":checked"),
        nit: $("#customer_nit").val(),
        name: $("#new_customer_name").val(),
        address_customer2: $("#customer_address").val(),
        phone_customer: $("#newCustomer_phone").val(),
        dpi: $("#dpi").val(),
        marital_status: $("#marital_status").val(),
        newbirthday: $("#birthdate").val()
      })
      .then(respose => {
        document.getElementById("reference_credit").value = respose.data;
        $("#modalAddCustomerClose").click();
        register_reference = true;
      })
      .catch(error => {
        alert(error);
      });
  }
}
var register_reference = false;
function verificarDatos() {
  var bandera = false;
  if ($("#customer_nit").val() != "C/F" && $("#customer_nit").val() != "c/f") {
    $.ajax({
      type: "post",
      async: false,
      url: "../customers/verify",
      data: {
        customer_id: $("#id_customer").val(),
        _token: $("#token").val(),
        nit: $("customer_nit").val()
      },
      success: function(data) {
        console.log(data);
        if (data != 1) {
          toastr.error("El nit ya está registrado en el cliente: " + data);
          document.getElementById("customer_nit").focus();
          bandera = true;
        }
      },
      error: function(error) {
        console.log("existe un error revisar");
      }
    });
  }

  $.ajax({
    type: "post",
    async: false,
    url: "../customers/verify",
    data: {
      customer_id: $("#id_customer").val(),
      dpi: $("#dpi").val(),
      _token: $("#token").val()
    },
    success: function(data) {
      console.log(data);
      if (data != 1) {
        toastr.error("El DPI ya está registrado en el cliente: " + data);
        document.getElementById("dpi").focus();
        bandera = true;
      }
    },
    error: function(error) {
      console.log("existe un error revisar");
    }
  });
  return bandera;
}

function SoloNumeros(evt) {
  if (window.event) {
    //asignamos el valor de la tecla a keynum
    keynum = evt.keyCode; //IE
  } else {
    keynum = evt.which; //FF
  }
  //comprobamos si se encuentra en el rango numérico y que teclas no recibirá.
  if (
    (keynum > 47 && keynum < 58) ||
    keynum == 8 ||
    keynum == 13 ||
    keynum == 6
  ) {
    return true;
  } else {
    return false;
  }
}
function verTipo() {
  var fiador = document.getElementById("dFiador");
  var garantia = document.getElementById("dgarantias");
  var tipo = document.getElementById("description").value;
  if (tipo == "Fiduciario") {
    fiador.style.display = "inline";
    garantia.style.display = "none";
  } else {
    fiador.style.display = "none";
    garantia.style.display = "inline";
  }
}

Vue.filter("monedaformat", function(value) {
  return numeral(value).format("0,0.00");
});
Vue.use(VueCleave);

function loading() {
  var x = 0;

  if ($("#amount").val() == "0") {
    toastr.error("Debe ingreasr un monto de credito válido.");
    document.getElementById("amount").focus();
    x++;
    return;
  }
  if ($("#cuotas").val() == "0") {
    toastr.error("Debe ingreasr un numero de cuotas válido.");
    document.getElementById("cuotas").focus();
    x++;
    return;
  }
  if ($("#dias_mora").val() == "0" || $("#dias_mora").val() == "") {
    toastr.error("Debe ingreasr un numero de días de mora válido.");
    document.getElementById("dias_mora").focus();
    x++;
    return;
  }
  if ($("#ptc_interes").val() == "0") {
    toastr.error("Debe ingreasr un monto de interes válido.");
    document.getElementById("ptc_interes").focus();
    x++;
    return;
  }
  if ($("#mora").val() == "0") {
    toastr.error("Debe ingreasr una mora válida.");
    document.getElementById("mora").focus();
    x++;
    return;
  }
  if ($("#intervalo").val() == "0") {
    toastr.error("Debe ingreasr un intérvalo de días válido.");
    document.getElementById("intervalo").focus();
    x++;
    return;
  }

  if (x == 0) {
    setTimeout(function() {
      toastr.success("Calculando pagos por favor espere...");
      document.getElementById("generate_credit").style.display = "none";
      document.getElementById("submitLoading").style.display = "inline";
    }, 100);

    setTimeout(function() {
      vm.generarTabla();
      document.getElementById("generate_credit").style.display = "inline";
      document.getElementById("submitLoading").style.display = "none";
    }, 400);
  }
}

var vm = new Vue({
  el: "#contenedor",
  created: function() {
    // this.cargadoClientes();
  },
  data: {
    montoCredito: $("#sale_amount").val(),
    numeroCuotas: 0,
    porcInteres: 0,
    porcMora: 0,
    price_garantia: 0,
    mostrar: true,
    desembolso: 0,
    pendiente: 0,
    options: {
      montoCredito: {
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 9,
        numeralDecimalScale: 2
      },
      desembolso: {
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 9,
        numeralDecimalScale: 2
      },
      pendiente: {
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 9,
        numeralDecimalScale: 2
      },
      numeroCuotas: {
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 3
      },
      intervalo: {
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 3
      },
      porcInteres: {
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 3,
        numeralDecimalScale: 2
      },
      price_garantia: {
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 9,
        numeralDecimalScale: 2
      }
    },
    montoInteres: 0,
    totalInteres: 0,
    totalConInteres: 0,
    intervalo: 1,
    totalCuotas: 0,
    totalCuotasConInteres: 0,
    all_data: [],
    all_customers: [],
    customer_name: "",
    customer_id: 0,
    newCustomer_name: "",
    newCustomer_address: "",
    newCustomer_nit: "C/F",
    newCustomer_phone: "",
    newCustomerReference1name: "",
    newCustomerReference2name: "",
    requireAuth: false,
    newCustomerReference3name: "",
    newCustomerReference1addrees: "",
    newCustomerReference2addrees: "",
    newCustomerReference3addrees: "",
    newCustomerReference1phone: "",
    newCustomerReference2phone: "",
    newCustomerReference3phone: "",
    newCustomer_dpi: "",
    newCustomer_marital_status: "",
    newCustomer_email: "",
    work_name: "",
    work_address: "",
    work_phone: "",
    dependVal: "2",
    newCustomerReferenceLab1name: "",
    newCustomerReferenceLab1addrees: "",
    newCustomerReferenceLab1phone: "",
    newCustomerReferenceLab2name: "",
    newCustomerReferenceLab2addrees: "",
    newCustomerReferenceLab2phone: "",
    newCustomerReferenceLab3name: "",
    newCustomerReferenceLab3addrees: "",
    newCustomerReferenceLab3phone: "",
    fam1_name: "",
    fam1_address: "",
    fam1_phone: "",
    fam2_name: "",
    fam2_address: "",
    fam2_phone: "",
    fam3_name: "",
    fam3_address: "",
    fam3_phone: "",
    business_name: "",
    business_address: "",
    business_phone: "",
    business_maps: "",
    business_description: "",
    formSubmited: false,
    errores: "",
    garantia: "",
    fiador_name: "",
    fiador_address: "",
    fiador_phone: "",
    fechaPago: "fecha",
    //garantias
    all_garantia: [],
    bandera: 0,
    name_garantia: "",
    depend: "",
    price_garantia: 0,
    category_garantia: "",
    description_garantia: ""
  },
  methods: {
    generarCuotas: function() {
      this.totalCuotas = this.montoCredito / this.numeroCuotas;
      this.totalCuotas = Math.round(this.totalCuotas * 100) / 100;
      //Monto de intereses
      this.totalInteres =
        parseFloat(this.montoCredito) * (parseFloat(this.porcInteres) / 100);
      this.totalInteres = Math.round(this.totalInteres * 100) / 100;
      //total con intereses
      this.totalConInteres = parseFloat(this.montoCredito) + this.totalInteres;
      //Total de interes de cuotas
      this.totalCuotasConInteres =
        parseFloat(this.totalInteres) / parseInt(this.numeroCuotas);
      this.totalCuotasConInteres =
        Math.round(this.totalCuotasConInteres * 100) / 100;
      this.pendiente = this.montoCredito - this.desembolso;
    },
    generarTabla: function() {
      // document.getElementById("generate_credit").style.display = "none";
      // document.getElementById("submitLoading").style.display = "inline";

      if (this.numeroCuotas % 1 == 0) {
        this.all_data = [];
        //funcion general
        //validamos las banderas
        var laboral = document.getElementById("radioNot");
        var noLaborales = document.getElementById("radioYes");
        var type_credit = document.getElementById("type_credit").value;

        //obtenemos las fechas
        var fecha_pagos = $("#date_payments").val();
        var arrayDate = fecha_pagos.split("/");
        var diaMes_nuevo = arrayDate[0];
        var mes_nuevo = arrayDate[1];
        var anio_nuevo = arrayDate[2];
        fecha_pagos = diaMes_nuevo + "-" + mes_nuevo + "-" + anio_nuevo;
        var mes_insertar = mes_nuevo;
        var mes_insertar2;
        var dia_nuevo;
        var ni = 0;
        var mesReal;
        if (laboral.checked || type_credit == "mounth") {
          //funcion normal
          if (type_credit != "mounth") {
            var contador = 0;
            finsemana = true;
            while (contador < parseInt(this.numeroCuotas)) {
              $("#alert").html("Generando pago " + (contador + 1));
              var url = "";
              $.ajax({
                type: "get",
                async: false,
                url: "../pagares/send/" + fecha_pagos + "/false",
                success: function(data) {
                  document.getElementById("resultDate").value = data;
                  fecha_pagos = data;
                },
                error: function(error) {
                  console.log("existe un error revisar");
                }
              });
              mesReal = document.getElementById("resultDate").value;
              this.all_data.push({
                fecha_pago: mesReal,
                monto_coutas: this.totalCuotas,
                monto_interes: this.totalCuotasConInteres,
                total_pago: this.totalCuotas + this.totalCuotasConInteres
              });
              fecha_pagos = editar_fecha(
                fecha_pagos,
                $("#intervalo").val(),
                "d"
              );
              contador++;
            }
          } else {
            var contador = 1;
            var fechaTemporal = fecha_pagos;
            while (contador < parseInt(this.numeroCuotas)) {
              fechaTemporal = fecha_pagos;
              $.ajax({
                type: "get",
                async: false,
                url: "../pagares/send/" + fecha_pagos + "/true",
                success: function(data) {
                  document.getElementById("resultDate").value = data;
                  fecha_pagos = data;
                },
                error: function(error) {
                  console.log("existe un error revisar");
                }
              });
              mesReal = document.getElementById("resultDate").value;
              this.all_data.push({
                fecha_pago: mesReal,
                monto_coutas: this.totalCuotas,
                monto_interes: this.totalCuotasConInteres,
                total_pago: this.totalCuotas + this.totalCuotasConInteres
              });
              fecha_pagos = editar_fecha(fechaTemporal, 1, "m");
              contador++;
            }
          } // calular fechas segun meses
        } else if (noLaborales.checked && type_credit != "mounth") {
          //CALCULO DE CUOTAS SIN TOMAR EN CUENTA LOS SABADOS
          finsemana = false;
          var fechaTemporal = fecha_pagos;
          var contador = 0;
          var festivo = false;
          var contadorFechas = 0;
          while (contador < parseInt(this.numeroCuotas)) {
            fechaTemporal = fecha_pagos;
            $.ajax({
              type: "get",
              async: false,
              url: "../pagares/send/" + fecha_pagos + "/true",
              success: function(data) {
                if (fecha_pagos != data) {
                  festivo = true;
                } else {
                  festivo = false;
                }
                document.getElementById("resultDate").value = data;
                fecha_pagos = data;
              },
              error: function(error) {
                console.log("existe un error revisar");
              }
            });

            mesReal = document.getElementById("resultDate").value;
            this.all_data.push({
              fecha_pago: mesReal,
              monto_coutas: this.totalCuotas,
              monto_interes: this.totalCuotasConInteres,
              total_pago: this.totalCuotas + this.totalCuotasConInteres
            });
            contador++;
            if (festivo&&$('#intervalo').val()>1) {
              fecha_pagos = editar_fecha(
                fechaTemporal,
                $("#intervalo").val(),
                "d"
              );
            }
            else{
              fecha_pagos = editar_fecha(
                fecha_pagos,
                $("#intervalo").val(),
                "d"
              );
            }

            contadorFechas++;
          }
        }
      } else {
        toastr.error("Error", "Debe ingresar un numero entero de cuotas.");
      }
      document.getElementById("generate_credit").style.display = "inline";
      document.getElementById("submitLoading").style.display = "none";
    },
    limpiarTabla: function() {
      this.all_data = [];
    },
    verifyWork: function() {
      if ($("#work_name").val() == "") {
        toastr.error("Debe ingresar nombre de lugar de trabajo.");
        return false;
      }
      if ($("#work_address").val() == "") {
        toastr.error("Debe ingresar dirección de lugar de trabajo.");
        return false;
      }
      if ($("#work_phone").val() == "") {
        toastr.error("Debe ingresar teléfono de lugar de trabajo");
        return false;
      }
      return true;
    },
    cargadoClientes: function() {
      this.all_customers = [];
      var urlCustomers = "../customers/getCustomer";
      axios
        .get(urlCustomers)
        .then(response => {
          this.all_customers = response.data;
        })
        .then(response => {
          $("#tableCustomers").DataTable({
            language: {
              url: "../assets/js/datatables/Spanish.js"
            },
            destroy: true,
            pageLength: 5,
            bInfo: false,
            lengthChange: false,
            bAutoWidth: false,
            language: {
              search: "_INPUT_",
              searchPlaceholder: "Buscar..."
            }
          });
        })
        .catch(function(error) {
          alert(error);
        });
    },
    seleccionarCliente: function(cliente_name, cliente_id) {
      console.log("Seleccionando");
      register_reference = true;
      var url = "../customers/getReference";
      axios
        .post(url, {
          id: cliente_id
        })
        .then(respose => {
          console.log(respose.data);
          register_reference = true;
        })
        .catch(error => {
          alert(error);
        });
      this.customer_name = cliente_name;
      this.customer_id = cliente_id;
    },
    crearCliente: function() {
      var errores = false;
      if (document.getElementById("new_customer_name").value == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error("Error.", "El nombre del cliente es un campo requerido", {
          timeOut: 5000
        });
        document.getElementById("new_customer_name").focus();
        errores = true;
      } else if (this.newCustomer_dpi == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error("Error.", "El DPI es un campo requerido", {
          timeOut: 5000
        });
        document.getElementById("dpi").focus();
        errores = true;
      } else if (this.newCustomer_address == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-botton-left"
        };
        toastr.error("Error.", "La dirección es un campo requerido", {
          timeOut: 5000
        });
        document.getElementById("customer_address").focus();
        errores = true;
      } else if (this.newCustomer_phone == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error("Error.", "El teléfono es un campo requerido", {
          timeOut: 5000
        });
        document.getElementById("newCustomer_phone").focus();
        errores = true;
      } else if ($("#birthdate").val() == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error("Error.", "La fecha de nacimiento es requerida", {
          timeOut: 5000
        });
        document.getElementById("birthdate").focus();
        errores = true;
      } else if (
        $("#inDependency").val() == "1" &&
        !this.verifyWork() &&
        !vWorkReferences()
      ) {
        errores = true;
      } else if (
        $("#inDependency").val() == "0" &&
        !this.verifyDataBussines()
      ) {
        errores = true;
      } else if (this.newCustomerReference1name == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error("Error.", "El nombre de referencia es requerido.", {
          timeOut: 5000
        });
        document.getElementById("ref1_name").focus();
        errores = true;
      }
      // else if(this.newCustomerReference1addrees==''){
      // 	toastr.options = {
      // 		"closeButton": true,
      // 		"positionClass": "toast-bottom-left"
      // 	}
      // 	toastr.error('Error.', 'La dirección de referencia es requerida', {timeOut: 5000});
      // 	document.getElementById('ref1_address').focus();
      // }
      else if (this.newCustomerReference1phone == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error(
          "Error.",
          "El teléfono de referencia es un campo requerido",
          { timeOut: 5000 }
        );
        document.getElementById("ref1_phone").focus();
        errores = true;
      } else if (this.newCustomerReference1phone.length != 8) {
        toastr.error(
          "Error.",
          "El teléfono de referencia debe tener 8 dígitos",
          { timeOut: 5000 }
        );
        document.getElementById("ref1_phone").focus();
        errores = true;
      } else if (this.newCustomerReference2name == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error("Error.", "El nombre de referencia es requerido.", {
          timeOut: 5000
        });
        document.getElementById("ref2_name").focus();
        errores = true;
      }
      // else if(this.newCustomerReference2addrees==''){
      // 	toastr.options = {
      // 		"closeButton": true,
      // 		"positionClass": "toast-bottom-left"
      // 	}
      // 	toastr.error('Error.', 'La dirección de referencia es requerida', {timeOut: 5000});
      // 	document.getElementById('ref2_address').focus();
      // }
      else if (this.newCustomerReference2phone == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error(
          "Error.",
          "El teléfono de referencia es un campo requerido",
          { timeOut: 5000 }
        );
        document.getElementById("ref2_phone").focus();
        errores = true;
      } else if (this.newCustomerReference3name == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error("Error.", "El nombre de referencia es requerido.", {
          timeOut: 5000
        });
        document.getElementById("ref3_name").focus();
        errores = true;
      }
      // else if(this.newCustomerReference3addrees==''){
      // 	toastr.options = {
      // 		"closeButton": true,
      // 		"positionClass": "toast-bottom-left"
      // 	}
      // 	toastr.error('Error.', 'La dirección de referencia es requerida', {timeOut: 5000});
      // 	document.getElementById('ref3_address').focus();
      // }
      else if (this.newCustomerReference3phone == "") {
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.error(
          "Error.",
          "El teléfono de referencia es un campo requerido",
          { timeOut: 5000 }
        );
        document.getElementById("ref3_phone").focus();
        errores = true;
      } else if (this.newCustomerReference2phone.length != 8) {
        toastr.error(
          "Error.",
          "El teléfono de referencia debe tener  dígitos",
          { timeOut: 5000 }
        );
        document.getElementById("ref2_phone").focus();
        errores = true;
      } else if (this.newCustomerReferenceLab1phone != "") {
        if (this.newCustomerReferenceLab1phone.length != 8) {
          toastr.error(
            "Error.",
            "El teléfono de referencia debe tener  dígitos",
            { timeOut: 5000 }
          );
          document.getElementById("refLab1Telefono").focus();
          errores = true;
        }
      } else if (document.getElementById("inDependency").value == "") {
        toastr.error("Error.", "Debe seleccionar una relación de dependencia", {
          timeOut: 5000
        });
        document.getElementById("radio1").focus();
        errores = true;
      } else if (this.newCustomerReferenceLab2phone != "") {
        if (this.newCustomerReferenceLab2phone.length != 8) {
          toastr.error(
            "Error.",
            "El teléfono de referencia debe tener  dígitos",
            { timeOut: 5000 }
          );
          document.getElementById("refLab2Telefono").focus();
          errores = true;
        }
      }
      if ($("#inDependency").val() == "1" && !vWorkReferences()) {
        errores = true;
      }
      if (!errores) {
        console.log("enviando cliente");
        var url = "../customers/addCustomerAjax";
        axios
          .post(url, {
            name: this.newCustomer_name,
            nit_customer: this.newCustomer_nit,
            address_customer2: this.newCustomer_address,
            phone_customer: this.newCustomer_phone,
            refer1Nombre: this.newCustomerReference1name,
            refer2Nombre: this.newCustomerReference2name,
            refer3Nombre: this.newCustomerReference3name,
            refer1Direccion: this.newCustomerReference1addrees,
            refer2Direccion: this.newCustomerReference2addrees,
            refer3Direccion: this.newCustomerReference3addrees,
            refer1Telefono: this.newCustomerReference1phone,
            refer2Telefono: this.newCustomerReference2phone,
            refer3Telefono: this.newCustomerReference3phone,
            refLab1Nombre: this.newCustomerReferenceLab1name,
            refLab1Direccion: this.newCustomerReferenceLab1addrees,
            refLab1Telefono: this.newCustomerReferenceLab1phone,
            refLab2Nombre: this.newCustomerReferenceLab2name,
            refLab2Direccion: this.newCustomerReferenceLab2addrees,
            refLab2Telefono: this.newCustomerReferenceLab2phone,
            refLab3Nombre: this.newCustomerReferenceLab3name,
            refLab3Direccion: this.newCustomerReferenceLab3addrees,
            refLab3Telefono: this.newCustomerReferenceLab3phone,
            work_address: this.work_address,
            fam1_name: this.fam1_name,
            fam1_address: this.fam1_address,
            fam1_phone: this.fam1_phone,
            fam2_name: this.fam2_name,
            fam2_address: this.fam2_address,
            fam2_phone: this.fam2_phone,
            fam3_name: this.fam3_name,
            fam3_address: this.fam3_address,
            business_name: this.business_name,
            business_phone: this.business_phone,
            business_address: this.business_address,
            business_description: this.business_description,
            business_maps: this.business_maps,
            fam3_phone: this.fam3_phone,
            //work_name:$('#work_name').val(),
            work_name: this.work_name,
            email: this.newCustomer_email,
            work_number: this.work_phone,
            dpi: this.newCustomer_dpi,
            depend: document.getElementById("inDependency").value,
            marital_status: this.newCustomer_marital_status,
            newbirthday: $("#birthdate").val()
          })
          .then(respose => {
            if (respose.data == "Ya existe un cliente con ese nombre") {
              toastr.options = {
                closeButton: true,
                positionClass: "toast-bottom-left"
              };
              toastr.error("Error.", "Cliente existente", { timeOut: 5000 });
              $("#customer_name").focus();
            } else {
              register_reference = true;
              var dir = "../customers/getReference";
              axios
                .post(dir, {
                  id: respose.data.id
                })
                .then(rsp => {
                  document.getElementById("reference_credit").value = rsp.data;
                })
                .catch(error => {
                  alert(error);
                });
              document.getElementById("customer_id").value = respose.data.id;
              document.getElementById("customer_name").value =
                respose.data.name;
              document.getElementById("idCustomerGalery").value = parseInt(
                respose.data.id
              );
              //Hacer aparecer el botón
              document.getElementById("dGalery").style.display = "inline";
              //limpiamos los datos
              this.newCustomer_name = "";
              this.newCustomer_address = "";
              this.newCustomer_nit = "C/F";
              this.newCustomer_phone = "";
              this.newCustomerReference1name = "";
              this.newCustomerReference2name = "";
              this.newCustomerReference3name = "";
              this.newCustomerReference1addrees = "";
              this.newCustomerReference2addrees = "";
              this.newCustomerReference3addrees = "";
              this.newCustomerReference1phone = "";
              this.newCustomerReference2phone = "";
              this.newCustomerReference3phone = "";
              this.newCustomer_dpi = "";
              this.newCustomer_marital_status = "";
              this.work_name = "";
              this.work_address = "";
              this.work_phone = "";
              this.newCustomer_email = "";
              this.newCustomerReferenceLab1name = "";
              this.newCustomerReferenceLab1addrees = "";
              this.newCustomerReferenceLab1phone = "";
              this.newCustomerReferenceLab2name = "";
              this.ewCustomerReferenceLab2addrees = "";
              this.newCustomerReferenceLab2phone = "";
              this.newCustomerReferenceLab3name = "";
              this.ewCustomerReferenceLab3addrees = "";
              this.newCustomerReferenceLab3phone = "";
              this.fam1_name = "";
              this.fam1_address = "";
              this.fam1_phone = "";
              this.fam2_name = "";
              this.fam2_address = "";
              this.fam2_phone = "";
              this.fam3_name = "";
              this.fam3_address = "";
              this.fam3_phone = "";
              this.business_maps = "";
              this.business_phone = "";
              this.business_name = "";
              this.business_address = "";
              this.business_description = "";
              $("#formnewCustomer").trigger("reset");
              $("#modalAddCustomerClose").click();
              $("#modalListCustomerClose").click();
            }
          })
          .catch(error => {
            alert(error);
          });
      }

      this.cargadoClientes();
    },
    verificarMonto: function() {
      this.desembolso = this.montoCredito;
      var maximo = parseFloat($("#maxAmountAuth").val());
      var cMax = parseFloat($("#maxAmount").val());
      validFia();
      if (cMax != -9) {
        if (this.montoCredito > cMax) {
          this.montoCredito = 0;
          toastr.error(
            "El monto máximo permitido para el cliente es de: Q" +
              new Intl.NumberFormat("es-GT").format(cMax)
          );
        }
      }
      if (this.montoCredito > maximo) {
        toastr.error("El crédito requiere autorización. ");
        document.getElementById("maxAmountMessage").style.display = "inline";
        this.requireAuth = true;
      } else {
        document.getElementById("maxAmountMessage").style.display = "none";
        this.requireAuth = false;
      }
    },
    validacionesFormPrincipal: function(event) {
      //        if(this.customer_name==''){
      //     if($('#customer_name').val()==''|| $('#customer_name').val()=='' || $('#customer_id').val()==0 || $('#customer_id').val()==''){
      // 	$('#btnListCustomers').click();
      // 	event.preventDefault();
      // 	return -1;
      // }
      // if(this.montoCredito==0){
      // 		//this.errores='El monto del crédito no puede ser 0';
      // 		//$('#btnModalErrors').click();
      // 		toastr.options = {
      // 			"closeButton": true,
      // 			"positionClass": "toast-bottom-left"
      // 		}
      // 		toastr.error('Error.', 'El monto del crédito no puede ser 0', {timeOut: 5000});
      // 		$('#amount').focus();
      // 		event.preventDefault();
      // 		return -1;
      // 	}
      // 	if(this.numeroCuotas==0){
      // 		// this.errores='Tiene que tener al menos una cuota';
      // 		// $('#btnModalErrors').click();
      // 		toastr.options = {
      // 			"closeButton": true,
      // 			"positionClass": "toast-bottom-left"
      // 		}
      // 		toastr.error('Error.', 'Tiene que tener al menos una cuota', {timeOut: 5000});
      // 		$('#cuotas').focus();
      // 		event.preventDefault();
      // 		return -1;
      // 	}
      // 	if(this.porcInteres==0){
      // 		// this.errores='El interes no es correcto';
      // 		// $('#btnModalErrors').click();
      // 		toastr.options = {
      // 			"closeButton": true,
      // 			"positionClass": "toast-bottom-left"
      // 		}
      // 		toastr.error('Error.', 'El interes no es correcto', {timeOut: 5000});
      // 		$('#ptc_interes').focus();
      // 		event.preventDefault();
      // 		return -1;
      // 	}
      // 	if(this.garantia==''){
      // 		toastr.options = {
      // 			"closeButton": true,
      // 			"positionClass": "toast-bottom-left"
      // 		}
      // 		toastr.error('Error.','El campo garantia es requerido', {timeOut: 5000});
      // 		$('#garantia').focus();
      // 		event.preventDefault();
      // 		return -1;
      // 	}
      // 	if(this.fiador_name==''){
      // 		toastr.options = {
      // 			"closeButton": true,
      // 			"positionClass": "toast-bottom-left"
      // 		}
      // 		toastr.error('Error.', 'El nombre del fiador es requerido', {timeOut: 5000});
      // 		$('#fiador').focus();
      // 		event.preventDefault();
      // 		return -1;
      // 	}
      // 	if(this.fiador_address==''){
      // 		toastr.options = {
      // 			"closeButton": true,
      // 			"positionClass": "toast-bottom-left"
      // 		}
      // 		toastr.error('Error.', 'La dirección es requerida', {timeOut: 5000});
      // 		$('#fiador_direccion').focus();
      // 		event.preventDefault();
      // 		return -1;
      // 	}
    },

    guardarFormulario: function() {
      this.bandera = 0;
      if (!valCard()) {
        this.bandera = 1;
      }
      if ($("#customer_name").val() == "") {
        toastr.error("Error", "Debe seleccionar un cliente");
        $("#btnListCustomers").click();
        this.bandera = 1;
        event.preventDefault();
      }
      if ($('#route').val()=="") {
        toastr.error("Error", "Debe seleccionar una ruta asociada al crédito.");
        document.getElementById('route').focus();
        this.bandera = 1;
      }
      console.log("Despues de ver al cliente: " + this.bandera);
      if ($("#description").val() != "Fiduciario") {
        if (this.all_garantia.length < 3) {
          toastr.error("Error", "Debe ingresar por lo menos 3 garantías");
          this.bandera = 1;
          $("#btnAddGaratias").click();
        } else {
          this.bandera = 0;
        }
      } else {
        if (!validFia()) {
          if (!verifyBusiness()) {
            if (!valDataFiador()) {
              toastr.info("El fiador es un campo requerido");
              this.bandera = 1;
              return;
            } else {
              this.bandera = 0;
            }
          } else {
            this.bandera = 0;
          }
        } else {
          this.bandera = 0;
        }
      }
      if (register_reference == false && $("#lastCredit").val() != "-") {
        //VIENE DE UNA RENOVACIÓN
        toastr.error("Debe actualizar datos del cliente");
        $("#btnListCustomers").click();
        this.bandera = 1;
      }
      if (register_reference == false && $("#lastCredit").val() == "+") {
        //VIENE DE UNA COMPRA AL CRÉDITO
        toastr.error("Debe actualizar datos del cliente");
        $("#btnListCustomers").click();
        this.bandera = 1;
      }
      if (this.all_data.length == 0) {
        toastr.error("Error", "Debe generar pagos de crédito");
        this.bandera = 1;
      }
      if (this.requireAuth == true) {
        $("#modalAuth").modal("show");
        this.bandera = 1;
      }
      if (this.bandera == 0) {
        console.log("enviando");
        $("#botones").hide();
        $("#loading").show();
        document.getElementById("formPagare").submit();
      }
    },
    limpiarErrores: function() {
      this.errores = "";
    },
    ocultarCollpse: function() {
      if ($("#demo").hasClass("in")) {
        $("#demo").removeClass("in");
      }
    },
    cargarCreditoAnterior: function() {
      if ($("#lastCreditJson").val() != "") {
        var jsonCredit = JSON.parse($("#lastCreditJson").val());
        console.log(jsonCredit);
        this.montoCredito = parseFloat(rsp);
        this.numeroCuotas = parseInt(jsonCredit.cuotas);
        $("#dias_mora").val(jsonCredit.days_mora);
        this.porcInteres = parseFloat(jsonCredit.ptc_interes);
        this.porcMora = parseInt(jsonCredit.mora);
        this.desembolso=this.montoCredito;
      }
    },
    verifyDataBussines: function() {
      if ($("#business_name").val() == "") {
        toastr.error("Debe ingresar nombre de negocio.");
        return false;
      }
      if ($("#business_address").val() == "") {
        toastr.error("Debe ingresar dirección de negocio.");
        return false;
      }
      if ($("#business_phone").val() == "") {
        toastr.error("Debe ingresar teléfono de negocio.");
        return false;
      }
      return true;
    },
    verificarFechas: function(fecha) {
      //axios inicio
      var resultado;
      var url = "../pagares/send/date";
      axios
        .post(url, {
          date: fecha
        })
        .then(response => {
          if (response.data == "yes") {
            resultado = "existe";
          } else {
            resultado = "no existe";
          }
        })
        .catch(function(error) {
          alert(error);
        }); //fin de axios
      return resultado;
    },
    addGarantia: function() {
      var bootstrapValidator = $("#form_add_garantia").data(
        "bootstrapValidator"
      );
      bootstrapValidator.validate();
      if (bootstrapValidator.isValid()) {
        this.category_garantia = $("#name_categoria").val();
        this.all_garantia.push({
          nameGarantia: this.name_garantia,
          categoryGarantia: this.category_garantia,
          priceGarantia: this.price_garantia,
          descriptionGarantia: this.description_garantia
        });
        var tmp = JSON.stringify(this.all_garantia);
        $("#garantias").val(tmp);
        this.name_garantia = "";
        this.price_garantia = 0;
        this.description_garantia = "";
        $("#form_add_garantia").bootstrapValidator("resetForm", true);
        $("#form_add_garantia").data("bootstrapValidator");

        $("#btnCloseAddGarantia").click();
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.success("Completado.", "Garantia insertada correctamente", {
          timeOut: 5000
        });
      }
    },
    updateGarantia: function(garantia, indice) {
      $("#closeModal").click();
      $("#modalEditGarantia").modal("show");
      this.name_garantia = garantia.nameGarantia;
      $("#name_categoria_editar option").each(function() {
        var categoria = $(this).attr("value");
        if (categoria === garantia.categoryGarantia) {
          $(this).prop("selected", true);
        }
      });
      this.category_garantia = garantia.categoryGarantia;
      this.price_garantia = garantia.priceGarantia;
      this.description_garantia = garantia.descriptionGarantia;
      $("#indice").val(indice);
    },
    editarGarantia: function() {
      var indice = $("#indice").val();
      var bootstrapValidator = $("#form_editar_garantia").data(
        "bootstrapValidator"
      );
      bootstrapValidator.validate();
      if (bootstrapValidator.isValid()) {
        this.all_garantia[indice].nameGarantia = this.name_garantia;
        this.all_garantia[indice].categoryGarantia = $(
          "#name_categoria_editar"
        ).val();
        this.all_garantia[indice].priceGarantia = this.price_garantia;
        this.all_garantia[
          indice
        ].descriptionGarantia = this.description_garantia;
        $("#btnCloseEditarGarantia").click();
        toastr.options = {
          closeButton: true,
          positionClass: "toast-bottom-left"
        };
        toastr.success("Completado.", "Garantia actualizada correctamente", {
          timeOut: 5000
        });
      }
    },
    limpiar: function() {
      this.name_garantia = "";
      this.price_garantia = "";
      this.description_garantia = "";
    }
  }
});
var rsp;
function funcion_alerta(elemento) {
  var idElement = elemento.id;
  var arrayId = idElement.split("_");

  $("#mesMaxAmount").html("");
  //VERIFICAR SI CLIENTE CUMPLE
  var url = "../verifyCustomer/" + arrayId[1];
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      rsp = data.monto;
      if (data.monto > 0) {
        if (data.free == false) {
          if ($("#lastCredit").val() != "-") {
            toastr.success(
              "El crédito no debe ser mayor a Q" +
                new Intl.NumberFormat("es-GT").format(data.monto)
            );
            document.getElementById("maxAmount").value = data.monto;
            document.getElementById("messsageMaxAmount").style.display =
              "inline";
            $("#mesMaxAmount").append(
              "Q " + new Intl.NumberFormat("es-GT").format(data.monto)
            );
          } else {
            toastr.error(
              "El cliente tiene un crédito activo, no puede crearse un crédito nuevo."
            );
            rsp = -1;
          }
        } else {
          toastr.success(
            "El crédito no debe ser mayor a Q" +
              new Intl.NumberFormat("es-GT").format(data.monto)
          );
          document.getElementById("maxAmount").value = data.monto;
          document.getElementById("messsageMaxAmount").style.display = "inline";
          $("#mesMaxAmount").append(
            "Q " + new Intl.NumberFormat("es-GT").format(data.monto)
          );
        }
      } else {
        if (data.monto == -1) {
          toastr.error("El cliente tiene un crédito activo actualmente.");
          document.getElementById("maxAmount").value = -9;
        }
        if (data.monto == -3) {
          //toastr.info("El cliente no tiene créditos activos");
          document.getElementById("messsageMaxAmount").style.display = "none";
          document.getElementById("maxAmount").value = -9;
        }
        if (data.monto > 0) {
          toastr.success(
            "El crédito no debe ser mayor a Q" +
              new Intl.NumberFormat("es-GT").format(data.monto)
          );
          document.getElementById("maxAmount").value = data.monto;
          document.getElementById("messsageMaxAmount").style.display = "inline";
          $("#mesMaxAmount").append(
            "Q " + new Intl.NumberFormat("es-GT").format(data.monto)
          );
        }
      }
    },
    error: function(error) {
      console.log("existe un error revisar: " + error);
    }
  });
  //---------------------------
  if (rsp != -1) {
    var url = "../customers/getReference";
    axios
      .post(url, {
        id: arrayId[1]
      })
      .then(respose => {
        if (JSON.stringify(respose.data) == "{}") {
          toastr.error("El cliente no tiene referencias, debe actualizarlo.");
          $("#reference_credit").val("");
          $("#lastCustomerName").val("");
          $("#customer_name").val("");
          document.getElementById("idCustomerGalery").value = "";
          document.getElementById("dGalery").style.display = "none";
        } else {
          document.getElementById("reference_credit").value = respose.data;
        }
      })
      .catch(error => {
        alert(error);
      });

    document.getElementById("customer_id").value = parseInt(arrayId[1]);
    if ($("#lastCustomerName").val() == "-") {
      var nameCustomer = document.getElementById("customerName_" + arrayId[1])
        .value;
    } else {
      var nameCustomer = document.getElementById("lastCustomerName").value;
    }
    document.getElementById("customer_name").value = nameCustomer;
    if ($("#reference_credit").val() != "") {
      register_reference = true;
    }
    document.getElementById("idCustomerGalery").value = parseInt(arrayId[1]);
    //Hacer aparecer el botón
    document.getElementById("dGalery").style.display = "inline";
  }
}

$("#form_add_garantia").submit(function(ev) {
  ev.preventDefault();
});
$("#form_editar_garantia").submit(function(ev) {
  ev.preventDefault();
});

$("#form_add_garantia").bootstrapValidator({
  feedbackIcons: {
    valid: "glyphicon glyphicon-ok",
    invalid: "glyphicon glyphicon-remove",
    validating: "glyphicon glyphicon-refresh"
  },
  message: "Valor no valido",
  fields: {
    garantina_name: {
      validators: {
        notEmpty: {
          message: "No tiene que ser vacio"
        },
        stringLength: {
          max: 200,
          min: 4,
          message: "El maximo es de 200 caracteres y el minimo es de 4"
        }
      }
    },
    price_garantia: {
      validators: {
        notEmpty: {
          message: "El precio es requerido"
        }
      }
    },
    name_categoria: {
      validators: {
        callback: {
          message: "Debe de seleccionar una categoria",
          callback: function(value, validator) {
            var caracteristicas = $("#name_categoria").val();
            if (caracteristicas == null) {
              return false;
            } else {
              return true;
            }
          }
        }
      }
    },
    description_garantia: {
      validators: {
        notEmpty: {
          message: "La descripcion es requerida"
        },
        stringLength: {
          min: 3,
          message: "El minimo de caracteres es de 3"
        }
      }
    }
  }
});
$("#form_editar_garantia").bootstrapValidator({
  feedbackIcons: {
    valid: "glyphicon glyphicon-ok",
    invalid: "glyphicon glyphicon-remove",
    validating: "glyphicon glyphicon-refresh"
  },
  message: "Valor no valido",
  fields: {
    garantina_nameEditar: {
      validators: {
        notEmpty: {
          message: "No tiene que ser vacio"
        },
        stringLength: {
          max: 200,
          min: 4,
          message: "El maximo es de 200 caracteres y el minimo es de 4"
        }
      }
    },
    price_garantiaEditar: {
      validators: {
        notEmpty: {
          message: "El precio es requerido"
        }
      }
    },
    name_categoriaEditar: {
      validators: {
        callback: {
          message: "Debe de seleccionar una categoria",
          callback: function(value, validator) {
            var caracteristicas = $("#name_categoria_editar").val();
            if (caracteristicas == null) {
              return false;
            } else {
              return true;
            }
          }
        }
      }
    },
    description_garantiaEditar: {
      validators: {
        notEmpty: {
          message: "La descripcion es requerida"
        },
        stringLength: {
          min: 3,
          message: "El minimo de caracteres es de 3"
        }
      }
    }
  }
});
/*VALIDAR FORMULARIO NUEVO CLIENTE*/
$("#formnewCustomer").bootstrapValidator({
  feedbackIcons: {
    valid: "glyphicon glyphicon-ok",
    invalid: "glyphicon glyphicon-remove",
    validating: "glyphicon glyphicon-refresh"
  },
  message: "Valor no valido",
  fields: {
    customer_nit: {
      validators: {
        callback: {
          message: "Debe escribir un NIT válido",
          callback: function(value, validator) {
            var caracteristicas = $("#customer_nit").val();
            if (caracteristicas == "") {
              return false;
            } else {
              if (caracteristicas != "C/F") {
                if (caracteristicas.length < 7) {
                  return false;
                } else {
                  return true;
                }
              } else {
                return true;
              }
            }
          }
        }
      }
    },
    customer_name: {
      validators: {
        notEmpty: {
          message: "Debe ingresar nombre."
        }
      }
    },
    dpi: {
      validators: {
        notEmpty: {
          message: "Debe ingresar DPI."
        },
        stringLength: {
          min: 13,
          max: 13,
          message: "DPI inválido, debe tener 13 dígitos."
        },
        regexp: {
          regexp: /^[0-9]+$/,
          message: "DPI inválido, solo debe ingresar digitos del 0 al 9"
        }
      }
    },
    customer_address: {
      validators: {
        notEmpty: {
          message: "Debe ingresar una dirección."
        }
      }
    },
    marital_status: {
      validators: {
        callback: {
          message: "Debe de seleccionar un estado civil",
          callback: function(value, validator) {
            var caracteristicas = $("#marital_status").val();
            if (caracteristicas == null) {
              return false;
            } else {
              return true;
            }
          }
        }
      }
    },
    newCustomer_phone: {
      validators: {
        notEmpty: {
          message: "Debe ingresar un numero de teléfono"
        },
        stringLength: {
          min: 8,
          max: 8,
          message: "El número de telefono debe tener 8 digitos. "
        },
        regexp: {
          regexp: /^[0-9]+$/,
          message: "Ingrese un número válido."
        }
      }
    },
    business_phone: {
      validators: {
        stringLength: {
          min: 8,
          max: 8,
          message: "El número de telefono debe tener 8 digitos. "
        },
        regexp: {
          regexp: /^[0-9]+$/,
          message: "Ingrese un número válido."
        }
      }
    },
    work_phone: {
      validators: {
        stringLength: {
          min: 8,
          max: 8,
          message: "El número de telefono debe tener 8 digitos. "
        },
        regexp: {
          regexp: /^[0-9]+$/,
          message: "Ingrese un número válido."
        }
      }
    }
  }
});

$("#formeditCustomer").bootstrapValidator({
  feedbackIcons: {
    valid: "glyphicon glyphicon-ok",
    invalid: "glyphicon glyphicon-remove",
    validating: "glyphicon glyphicon-refresh"
  },
  message: "Valor no valido",
  fields: {
    customer_nit: {
      validators: {
        callback: {
          message: "Debe escribir un NIT válido",
          callback: function(value, validator) {
            var caracteristicas = $("#customer_nit").val();
            if (caracteristicas == "") {
              return false;
            } else {
              if (caracteristicas != "C/F") {
                if (caracteristicas.length < 7) {
                  return false;
                } else {
                  return true;
                }
              } else {
                return true;
              }
            }
          }
        }
      }
    },
    customer_name: {
      validators: {
        notEmpty: {
          message: "Debe ingresar nombre."
        }
      }
    },
    dpi: {
      validators: {
        notEmpty: {
          message: "Debe ingresar DPI."
        },
        stringLength: {
          min: 13,
          max: 13,
          message: "DPI inválido, debe tener 13 dígitos."
        },
        regexp: {
          regexp: /^[0-9]+$/,
          message: "DPI inválido, solo debe ingresar digitos del 0 al 9"
        }
      }
    },
    customer_address: {
      validators: {
        notEmpty: {
          message: "Debe ingresar una dirección."
        }
      }
    },
    marital_status: {
      validators: {
        callback: {
          message: "Debe de seleccionar un estado civil",
          callback: function(value, validator) {
            var caracteristicas = $("#marital_status").val();
            if (caracteristicas == null) {
              return false;
            } else {
              return true;
            }
          }
        }
      }
    },
    newCustomer_phone: {
      validators: {
        notEmpty: {
          message: "Debe ingresar un numero de teléfono"
        },
        stringLength: {
          min: 8,
          max: 8,
          message: "El número de telefono debe tener 8 digitos. "
        },
        regexp: {
          regexp: /^[0-9]+$/,
          message: "Ingrese un número válido."
        }
      }
    },
    referNombre: {
      validators: {
        notEmpty: {
          message: "Debe ingresar nombre de referencia"
        }
      }
    },
    referDireccion: {
      validators: {
        notEmpty: {
          message: "Debe ingresar una dirección"
        }
      }
    },
    work_phone: {
      validators: {
        stringLength: {
          min: 8,
          max: 8,
          message: "El número de telefono debe tener 8 digitos. "
        },
        regexp: {
          regexp: /^[0-9]+$/,
          message: "Ingrese un número válido."
        }
      }
    },
    referTelefono: {
      validators: {
        notEmpty: {
          message: "Debe ingresar un numero de teléfono"
        },
        stringLength: {
          min: 8,
          message: "El número de telefono debe tener 8 digitos. "
        },
        regexp: {
          regexp: /^[0-9]+$/,
          message: "Ingrese un número válido."
        }
      }
    }
  }
});
