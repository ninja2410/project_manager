var total = 0;
var mora = 0;
var registros = 0;
var editandoTrans = false;
var cashAnterior = 0;
var balance = 0;
var montoTransferido = 0;
var actionCash = 0;
var repeatCard = false;
var respVal = false;
var cashAnterior2 = 0;
var totalPagares = 0;
var percentTarget = 0;
var Target = 0;
var paymentsRoute = 0;
var jsonPagares = {};
var jsonCash = {};
var totalDesenbolsos=0;
var respVerifyBalance=0;
function validDesembolso(control){
  if (parseFloat(control.value)>parseFloat(control.placeholder)) {
      toastr.error("El monto no debe exceder el total pendiente de desembolso.");
      control.value="";
      control.focus();
  }
}
function desgloce() {
  //montoTransferido=convertMoney($('#montoTransferencia').val());
  totalEfectivo = 0;
  $(".type").each(function() {
    var val = $(this).attr("name");
    var id = $(this).attr("id");
    var q = $(this).val();
    var res = parseFloat(val) * parseFloat(q);
    totalEfectivo += res;
    $("#efectivoActual").val(number_format(totalEfectivo, 2));
  });
  buildJsonCash();
}
function desembolso() {
  totalDesenbolsos = 0;
  $(".desembolso").each(function() {
    console.log($(this));
    var id = $(this).attr("name");
    var val = $(this).val();
    var res = parseFloat(val);
    totalDesenbolsos += res;
  });
  $("#totalDesenbolsos").text("");
  $("#totalDesenbolsos").append(
    "Total Nuevos desembolsos:<br> Q " + number_format(totalDesenbolsos, 2)
  );
  actualizarTotalesGastos();
  buildJsonDesembolsos();
}
var resp=0;
function verifyBalance(){
  var route=$('#route').val();
  var sfecha = $("#fecha").val();
  var url = APP_URL+"/verify_balance/" + route + "/"+ sfecha;
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      respVerifyBalance = data;
    },
    error: function(error) {
      console.log("existe un error revisar:" + error);
    }
  });
}
function buildJsonCash() {
  var js = [];
  var data = {};
  $(".type").each(function() {
    var val = $(this).attr("name");
    var id = $(this).attr("id");
    var q = $(this).val();
    js.push({
      type_id: id,
      quantity: q
    });
  });
  $("#dCashDetail").val(JSON.stringify(js));
}
function buildJsonDesembolsos() {
  var js = [];
  var data = {};
  $(".desembolso").each(function() {
    var id = $(this).attr("name");
    var val = $(this).val();
    js.push({
      pagare_id: id,
      amount: val
    });
  });
  $("#dDesembolsos").val(JSON.stringify(js));
}
function guardarMonto() {
  montoTransferido = convertMoney($("#montoTransferencia").val());
  $("#montoTransf").text("");
  $("#montoTransf").append(
    "Total Transferencia: Q " + number_format(montoTransferido, 2)
  );
}
function selectAccount(control) {
  if (control.checked && control.value == "option1") {
    document.getElementById("frmCuentas").style.display = "block";
    actionCash = 1;
  } else {
    document.getElementById("frmCuentas").style.display = "none";
    actionCash = 2;
  }
}
function routeInfo() {
  var combo = document.getElementById("route");
  var selected = combo.options[combo.selectedIndex].text;
  $("#lblRuta").text("Ruta: " + selected);
  $("#lblFecha").text("Fecha: " + $("#fecha").val());
  $("#dDate").val($("#fecha").val());
  if ($("#fecha").val() != "") {
    getRoutePayments(combo.value)
  }
  getCreditsPending();
  actualizarTotalesGastos();
}
function getRoutePayments(route) {

  var sfecha = $("#fecha").val();
  var array = sfecha.split("/");
  var url = "payments/" + route + "/" + array[1] + "/" + array[2];
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      $("#paymentRouteInfo").text("");
      $("#paymentRouteInfo").text("Q " + number_format(data, 2));
      paymentsRoute = parseFloat(data);
    },
    error: function(error) {
      console.log("existe un error revisar:" + error);
    }
  });
  calcPercentTarget();
}
function calcPercentTarget() {
  $("#paymentRouteInfo").text("");
  $("#paymentRouteInfo").text("Q " + number_format(paymentsRoute, 2));
  percentTarget = (parseFloat(paymentsRoute) * 100) / parseFloat(targetRoute);
  $("#percentCobro").text("");
  $("#percentCobro").text(number_format(percentTarget, 2) + "%");
}
var ruta = $("#route").val();
function loadRoute() {

  var url = APP_URL + "/routes/lastCash/" + ruta;
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      cashAnterior = data.lastCash;
      $("#totalSaldoAnterior").text("");
      $("#targetRoute").text("");
      $("#totalSaldoAnterior").append(
        "Efectivo anterior:<br> Q " + number_format(cashAnterior, 2)
      );
      $("#targetRoute").append("Q " + number_format(data.target, 2));
      targetRoute = data.target;
    },
    error: function(error) {
      console.log("existe un error revisar");
    }
  });
  getCreditsPending();
  actualizarTotalesGastos();
  routeInfo();
}
function getCreditsPending(){
  url =APP_URL+ "/pagares_pending_balance/" + ruta+"/"+$('#fecha').val();
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      totalPagares = 0;
      jsonPagares = data;
      $.each(jsonPagares, function(i, item) {
        totalPagares += parseFloat(item.amount);
      });
    },
    error: function(error) {
      console.log("existe un error revisar:"+url);
    }
  });
  $("#totalPagares").text("");
  $("#totalPagares").append(
    "Total Nuevos Desembolsos: Q " + number_format(totalPagares, 2)
  );
  $("#totalNewPagares").text("");
  $("#totalNewPagares").append(
    "Total Nuevos Desembolsos: <br> Q " + number_format(totalPagares, 2)
  );
}
$(document).ready(function() {
  $("#fecha").datetimepicker({
    locale:'es',
    defaultDate:new Date(),
    format:'DD/MM/YYYY',
		widgetPositioning: {
	    horizontal: 'auto',
	    vertical: 'top'
	  }
  }).parent().css("position :relative ");
	routeInfo();
  //SELECCIONAR
  routeInfo();
  //DROPDOWN DE Rutas
  loadRoute();
  calcularBalance();
  actualizarTotales();
  $("form#main_input_box").submit(function(event) {
    event.preventDefault();
    var deleteButton =
      " <a href='' class='tododelete redcolor'><span class='glyphicon glyphicon-trash'></span></a>";
    var striks = "<span class='striks'> |  </span>";
    var editButton =
      "<a href='' class='todoedit'><span class='glyphicon glyphicon-pencil'></span></a>";
    var twoButtons =
      "<div class='col-md-3 col-sm-3 col-xs-3  pull-right showbtns todoitembtns'>" +
      editButton +
      striks +
      deleteButton +
      "</div>";
    var oneButton =
      "<div class='col-md-3 col-sm-3 col-xs-3  pull-right showbtns todoitembtns'>" +
      deleteButton +
      "</div>";
    $(".list_of_items").append(
      "<div class='todolist_list showactions list1'>  " +
        "<div class='col-md-12 col-sm-12 col-xs-12 nopadmar custom_textbox1'>" +
        "<div class='col-md-3 todotext'>" +
        $("#txtCard").val() +
        "<input type='hidden' class='card' value='" +
        $("#txtCard").val() +
        "'>" +
        "</div>" +
        "<div class='col-md-3 todotext1'>Q " +
        number_format(convertMoney($("#txtAmount").val()), 2) +
        "</div>" +
        "<div class='col-md-3 todotext2'>Q " +
        number_format(convertMoney($("#txtMora").val()), 2) +
        "</div>" +
        twoButtons +
        "</div>"
    );
    total += convertMoney($("#txtAmount").val());
    paymentsRoute += convertMoney($("#txtAmount").val());
    mora += convertMoney(number_format($("#txtMora").val()));
    registros++;
    actualizarTotales();
    $("#txtCard").val("");
    $("#txtAmount").val("");
    $("#txtMora").val("0");
    $("#txtCard").focus();
  });
});
$(document).on("click", ".tododelete", function(e) {
  e.preventDefault();
  text = $(this)
    .closest(".todolist_list")
    .find(".todotext")
    .text();
  text2 = $(this)
    .closest(".todolist_list")
    .find(".todotext2")
    .text();
  text1 = $(this)
    .closest(".todolist_list")
    .find(".todotext1")
    .text();
  total -= convertMoney(text1);
  paymentsRoute -= convertMoney(text1);
  mora -= convertMoney(text2);
  registros--;
  actualizarTotales();
  $(this)
    .closest(".todolist_list")
    .hide("slow", function() {
      $(this).remove();
    });
});
$(document).on("click", ".striked", function(e) {
  var id = $(this)
    .closest(".todolist_list")
    .attr("id");
  var hasClass = $(this)
    .closest(".todolist_list")
    .find(".todotext")
    .hasClass("strikethrough");
  var hasEdit = $(this)
    .closest(".todolist_list")
    .find(".todoedit")
    .hasClass("todoedit");
  var striks = "<span class='striks'> |  </span>";
  var check = "<input type='checkbox' class='striked' />";
  var editButton =
    "<a href='' class='todoedit'><span class='glyphicon glyphicon-pencil'></span> </a>";

  $.ajax({
    type: "POST",
    url: "admin/task/" + id + "/edit",
    data: {
      _token: $('meta[name="_token"]').attr("content"),
      finished: hasClass ? 0 : 1
    }
  });
  $(this)
    .closest(".todolist_list")
    .find(".todotext")
    .toggleClass("strikethrough");
  if (!hasClass) {
    $(this)
      .closest(".todolist_list")
      .find(".todoedit")
      .hide();
    $(this)
      .closest(".todolist_list")
      .find(".striks")
      .hide();
  } else {
    $(this)
      .closest(".todolist_list")
      .find(".todoedit")
      .show();
    $(this)
      .closest(".todolist_list")
      .find(".striks")
      .show();
  }

  if (!hasEdit) {
    $(this)
      .closest(".todolist_list")
      .find(".tododelete")
      .before(editButton + striks);
  }
});

$(document).on("click", ".todoedit .glyphicon-pencil", function(e) {
  e.preventDefault();
  if (editandoTrans) {
    toastr.error("Debe terminar de editar un pago antes de editar otro.");
    return;
  }
  editandoTrans = true;
  var text = "";
  var text2 = "";
  var text1 = "";
  text = $(this)
    .closest(".todolist_list")
    .find(".todotext")
    .text();
  text2 = $(this)
    .closest(".todolist_list")
    .find(".todotext2")
    .text();
  text1 = $(this)
    .closest(".todolist_list")
    .find(".todotext1")
    .text();
  total -= convertMoney(text1);
  paymentsRoute -= convertMoney(text1);
  mora -= convertMoney(text2);
  text1 = number_format(text1, 2);
  text2 = number_format(text2, 2);
  text =
    "<input type='text' name='text' value='" +
    text +
    "' onkeypress='return event.keyCode != 13;' />";
  text2 =
    "<input type='text' name='text2' value='" + text2 + "' class='mora' />";
  text1 =
    "<input class='money' type='text' name='text1' value='" + text1 + "' />";
  $(this)
    .closest(".todolist_list")
    .find(".todotext")
    .html(text);
  $(this)
    .closest(".todolist_list")
    .find(".todotext2")
    .html(text2);
  $(this)
    .closest(".todolist_list")
    .find(".todotext1")
    .html(text1);
  $(this)
    .closest(".todolist_list")
    .find(".striked")
    .hide();
  //$(this).html("<span class='glyphicon glyphicon-saved'></span> <span class='hidden-xs'></span>");
  $(this)
    .removeClass("glyphicon-pencil")
    .addClass("glyphicon-saved hidden-xs");
});

$(document).on("click", ".todoedit .glyphicon-saved", function(e) {
  e.preventDefault();
  var text1 = $(this)
    .closest(".todolist_list")
    .find("input[type='text'][name='text']")
    .val();
  editandoTrans = false;
  var text2 = $(this)
    .closest(".todolist_list")
    .find("input[type='text'][name='text1']")
    .val();
  var text3 = $(this)
    .closest(".todolist_list")
    .find("input[type='text'][name='text2']")
    .val();
  if (text1 == "") {
    toastr.error("El valor de la tarjeta no debe quedar en blanco.");
    $(this)
      .closest(".todolist_list")
      .find("input[type='text'][name='text']")
      .focus();
    return;
  }
  if (text2 == "") {
    toastr.error("El valor del monto no debe quedar en blanco.");
    $(this)
      .closest(".todolist_list")
      .find("input[type='text'][name='text1']")
      .focus();
    return;
  }
  validateCardEdit(text1);
  if (respVal) {
    total = convertMoney(text2) + parseFloat(total);
    mora = convertMoney(text3) + parseFloat(mora);
    actualizarTotales();
    text2 = number_format(text2, 2);
    text3 = number_format(text3, 2);
    $(this)
      .closest(".todolist_list")
      .find(".todotext")
      .html(text1);
    $(this)
      .closest(".todolist_list")
      .find(".todotext1")
      .html("Q " + text2);
    $(this)
      .closest(".todolist_list")
      .find(".todotext2")
      .html("Q " + text3);
    $(this)
      .removeClass("glyphicon-saved hidden-xs")
      .addClass("glyphicon-pencil");
    $(this)
      .closest(".todolist_list")
      .find(".striked")
      .show();
  }
});

function validateCardEdit(card) {
  $(".list1").each(function() {
    if (
      $(this)
        .find(".todotext")
        .text() == card
    ) {
      repeatCard = true;
      toastr.error("La tarjeta ya ha sido ingresada.");
      return;
    }
  });

  var url = "valid_Card/" + card + "/" + $("#route").val();
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      if (data == 1) {
        toastr.error(
          "Error.",
          "El número de tarjeta no esta asociado a ningún crédito!",
          {
            timeOut: 5000
          }
        );
        respVal = false;
      } else if (data == 2) {
        toastr.error("Error.", "El número de tarjeta no pertenece a la ruta!", {
          timeOut: 5000
        });
        respVal = false;
      } else {
        respVal = true;
      }
    },
    error: function(error) {
      console.log("existe un error revisar");
    }
  });
}
function validateCard(txtCard) {
  var numero = txtCard.value;
  $(".list1").each(function() {
    if (
      $(this)
        .find(".todotext")
        .text() == numero
    ) {
      document.getElementById("txtCard").value = "";
      $("#txtCard").focus();
      repeatCard = true;
      toastr.error("La tarjeta ya ha sido ingresada.");
      return;
    }
  });
  var url = "valid_Card/" + numero + "/" + $("#route").val();
  $.ajax({
    type: "get",
    async: false,
    url: url,
    success: function(data) {
      console.log(data);
      if (data == 1) {
        document.getElementById("txtCard").value = "";
        $("#txtCard").focus();
        document.getElementById("btn_save").disabled = true;
        toastr.error(
          "Error.",
          "El número de tarjeta no esta asociado a ningún crédito activo!",
          {
            timeOut: 5000
          }
        );
        return false;
      } else if (data == 2) {
        document.getElementById("txtCard").value = "";
        $("#txtCard").focus();
        document.getElementById("btn_save").disabled = true;
        toastr.error("Error.", "El número de tarjeta no pertenece a la ruta!", {
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

function convertMoney(string) {
  var ns = string.replace(",", "");
  ns = ns.replace(" ", "");
  ns = ns.replace("Q", "");
  return parseFloat(ns);
}

function number_format(amount, decimals) {
  amount += ""; // por si pasan un numero en vez de un string
  amount = parseFloat(amount.replace(/[^0-9\.-]/g, "")); // elimino cualquier cosa que no sea numero o punto

  decimals = decimals || 0; // por si la variable no fue fue pasada

  // si no es un numero o es igual a cero retorno el mismo cero
  if (isNaN(amount) || amount === 0) return parseFloat(0).toFixed(decimals);

  // si es mayor o menor que cero retorno el valor formateado como numero
  amount = "" + amount.toFixed(decimals);

  var amount_parts = amount.split("."),
    regexp = /(\d+)(\d{3})/;

  while (regexp.test(amount_parts[0]))
    amount_parts[0] = amount_parts[0].replace(regexp, "$1" + "," + "$2");

  return amount_parts.join(".");
}

function actualizarTotales() {
  $("#totalPagosF").text("");
  $("#total").text("");
  $("#totalPago").text("");
  $("#mora").text("");
  $("#registros").text("");
  $("#totalMora").text("");
  $("#totalEfectivo2").text("");
  $("#montoTransf").text("");
  $("#montoTransf").append(
    "Total transferencias: <br>" + number_format(totalT, 2)
  );
  $("#totalEfectivo2").append(
    "Efectivo a inicio del día: <br>" + number_format(cashAnterior2, 2)
  );
  $("#total").append("Total Monto: Q " + number_format(total, 2));
  $("#totalPagosF").append("Q " + number_format(total + mora, 2));
  $("#totalPago").append("Total Pagos:<br> Q " + number_format(total, 2));
  $("#mora").append("Total Mora: Q " + number_format(mora, 2));
  $("#totalMora").append("Total Mora:<br> Q " + number_format(mora, 2));
  $("#registros").append("Total Registros:" + registros);
  calcularBalance();
  calcPercentTarget();
}

function buildJsonPayments() {
  var data = [];
  var json = {};
  $(".list1").each(function() {
    if (
      $(this)
        .find(".todotext")
        .text()
    ) {
      data.push({
        card: $(this)
          .find(".todotext")
          .text(),
        amount: convertMoney(
          $(this)
            .find(".todotext1")
            .text()
        ),
        mora: convertMoney(
          $(this)
            .find(".todotext2")
            .text()
        )
      });
    }
  });
  json = data;
  $("#dPayments").val(JSON.stringify(json));
}
function SendDataBalance() {
  if (respVerifyBalance>0) {
    toastr.error("Ya existe un balance creado en la fecha especificada.");
    $("#modalConf").modal("hide");
    document.getElementById('fecha').focus();
    return;
  }
  if (registros == 0) {
    toastr.error("Debe registrar pagos a créditos.");
    $("#modalConf").modal("hide");
    return;
  }
  if (actionCash == 0) {
    toastr.error("Debe seleccionar la acción con el efectivo");
    $("#modalConf").modal("hide");
    return;
  }
  if (totalEfectivoActual == 0) {
    toastr.error("Debe ingresar el monto de efectivo actual.");
    $("#modalConf").modal("hide");
    return;
  }
  if (balance < 0) {
    toastr.error(
      "Balance descuadrado",
      "El balance no esta cuadrado correctamente."
    );
    $("#modalConf").modal("hide");
    return;
  }
  if ($("#dTransferSurplus").val() == ""&& balance>0) {
    toastr.error(
      "Acción con sobrante",
      "Debe elegir a donde transferir el dinero sobrante."
    );
    $("#modalConf").modal("hide");
    return;
  }
  buildJsonPayments();
  buildJsonGastos();
  buildJsonTransfers();
  $("#dRoute").val($("#route").val());
  $("#dCash").val(totalEfectivoActual);
  $("#dCash2").val(cashAnterior2);
  $("#dBalance").val(balance);
  $("#dTotalOutlays").val(totalG);
  $("#dActionCash").val(actionCash);
  $("#dTransfer").val(totalT);
  $("#dPagares").val(JSON.stringify(jsonPagares));
  $("#dLastCash").val(cashAnterior);
  $("#dComment").val($("#descriptionMessage").val());
  toastr.success("Enviando datos...");
  $("#frmData").submit();
}
