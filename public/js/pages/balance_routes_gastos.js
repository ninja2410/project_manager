var totalG = 0;
var registrosGastos = 0;
var totalEfectivoActual=0;
var editandoG = false;
$(document).ready(function() {
  $("form#main_input_box_gastos").submit(function(event) {
    event.preventDefault();
    var deleteButton = " <a href='' class='tododelete_g redcolor'><span class='glyphicon glyphicon-trash'  style='color:red;'></span></a>";
    var striks = "<span class='striks'> |  </span>";
    var editButton = "<a href='' class='todoedit_g'><span class='glyphicon glyphicon-pencil'></span></a>";
    var twoButtons = "<div class='col-md-3 col-sm-3 col-xs-3  pull-right showbtns todoitembtns'>" + editButton + striks + deleteButton + "</div>";
    var oneButton = "<div class='col-md-3 col-sm-3 col-xs-3  pull-right showbtns todoitembtns'>" + deleteButton + "</div>";
    $(".list_of_items_gastos").append("<div class='todolist_list showactions list1'>  " +
      "<div class='col-md-12 col-sm-12 col-xs-12 nopadmar custom_textbox1'>" +
      "<div class='col-md-3 todotext_g'>" +
      $("#txtDescripcion").val() +
      "</div>" +
      "<div class='col-md-3 todotext_g1'>Q " +
      number_format(convertMoney($("#txtAmountGasto").val()), 2) +
      "</div>" +
      "<div class='col-md-3 todotext_g2'>" +
      $("#txtReferencia").val() +
      "</div>" +
      twoButtons +
      "</div>");
    totalG += convertMoney($('#txtAmountGasto').val());
    registrosGastos++;
    actualizarTotalesGastos();
    $("#txtDescripcion").val('');
    $("#txtAmountGasto").val('');
    $("#txtReferencia").val('');
    $("#txtDescripcion").focus();
  });
});
$(document).on('click', '.tododelete_g', function(e) {
  e.preventDefault();
  text = $(this).closest('.todolist_list').find('.todotext_g').text();
  text1 = $(this).closest('.todolist_list').find('.todotext_g1').text();
  totalG -= convertMoney(text1);
  registrosGastos--;
  actualizarTotalesGastos();
  $(this).closest('.todolist_list').hide("slow", function() {
    $(this).remove();
  });
});

$(document).on('click', '.todoedit_g .glyphicon-pencil', function(e) {
  e.preventDefault();
  if (editandoG) {
    toastr.error("Debe terminar de editar un pago antes de editar otro.");
    return;
  }
  editandoG = true;
  var text = '';
  var text2 = '';
  var text1 = '';
  var text3='';
  text = $(this).closest('.todolist_list').find('.todotext_g').text();
  text2 = $(this).closest('.todolist_list').find('.todotext_g1').text();
  text3 = $(this).closest('.todolist_list').find('.todotext_g2').text();
  totalG -= convertMoney(text2);
  text2 = number_format(text2, 2);
  text = "<input type='text' name='text_descripcion' value='" + text + "' onkeypress='return event.keyCode != 13;' />";
  text2 = "<input type='text' name='text_monto' value='" + text2 + "' class='mora' />";
  text3 = "<input type='text' name='text_referencia' value='" + text3 + "' />";
  $(this).closest('.todolist_list').find('.todotext_g').html(text);
  $(this).closest('.todolist_list').find('.todotext_g1').html(text2);
  $(this).closest('.todolist_list').find('.todotext_g2').html(text3);
  $(this).closest('.todolist_list').find('.striked').hide();
  //$(this).html("<span class='glyphicon glyphicon-saved'></span> <span class='hidden-xs'></span>");
  $(this).removeClass('glyphicon-pencil').addClass('glyphicon-saved hidden-xs');
});

$(document).on('click', '.todoedit_g .glyphicon-saved', function(e) {
  e.preventDefault();
  editandoG = false;
  var text1 = $(this).closest('.todolist_list').find("input[type='text'][name='text_descripcion']").val();
  var text2 = $(this).closest('.todolist_list').find("input[type='text'][name='text_monto']").val();
  var text3=$(this).closest('.todolist_list').find("input[type='text'][name='text_referencia']").val();
  if (text1 === '') {
    toastr.error("La descripción no debe quedar en blanco.");
    $(this).closest('.todolist_list').find("input[type='text'][name='text_descripcion']").focus();
    return;
  }
  if (text2 === '') {
    toastr.error("El valor del monto no debe quedar en blanco.");
    $(this).closest('.todolist_list').find("input[type='text'][name='text_monto']").focus();
    return;
  }
  if (text3=='') {
    toastr.error("La referencia del gasto no debe quedar en blanco.");
    $(this).closest('.todolist_list').find("input[type='text'][name='text_referencia']").focus();
    return;
  }
  totalG += convertMoney(text2);
  actualizarTotalesGastos();
  text2 = number_format(text2, 2);
  $(this).closest('.todolist_list').find('.todotext_g').html(text1);
  $(this).closest('.todolist_list').find('.todotext_g1').html("Q " + text2);
  $(this).closest('.todolist_list').find('.todotext_g2').html(text3);
  $(this).removeClass('glyphicon-saved hidden-xs').addClass('glyphicon-pencil');
  $(this).closest('.todolist_list').find('.striked').show();
});

function actualizarTotalesGastos() {
  $('#totalGastos').text("");
  $('#totalGastosF').text("");
  $('#registrosGastos').text("");
  $('#totalGastosrpt').text("");
  $('#totalGastos').append("Total Monto: Q " + number_format(totalG, 2));
  $('#totalGastosF').append("Total Gastos:<br> Q " + number_format(totalG, 2));
  $('#totalGastosrpt').append("Q " + number_format(totalG+parseFloat(totalPagares)+totalDesenbolsos, 2));
  $('#registrosGastos').append("Total Registros Gastos:" + registrosGastos);
  calcularBalance();
}

function buildJsonGastos() {
  var data = [];
  var json = {};
  $(".list1").each(function() {
    if ($(this).find('.todotext_g').text()) {
      data.push({
        "description": $(this).find('.todotext_g').text(),
        "reference": $(this).find('.todotext_g2').text(),
        "amount": convertMoney($(this).find('.todotext_g1').text())
      });
    }
  });
  json = data;
  $('#dOutlays').val(JSON.stringify(json));
  $('#selAccountSurplus').val($('#selAccountSurplus').val());
  $('#txtAmountTransSur').val();
}
function calcularBalance(){
  cashAnterior2=convertMoney($('#efectivoInicio2').val());
  totalEfectivoActual=convertMoney($('#efectivoActual').val());
  balance=(parseFloat(totalG)+totalEfectivoActual+totalDesenbolsos
  +parseFloat(totalPagares))-(parseFloat(cashAnterior)
  +parseFloat(cashAnterior2)+parseFloat(total)
  +parseFloat(mora));
  if (balance<0) {
    document.getElementById('balanceTotal').style.color="rgb(223, 29, 41)";
    document.getElementById('sobrante').style.display="none";
  }
  else{
    if (balance>0&&$('#efectivoActual').val()!="0") {
      document.getElementById('sobrante').style.display="inline";
      $('#txtAmountTransSur').val("");
      $('#txtAmountTransSur').val(number_format(balance, 2));
    }
    else{
      document.getElementById('sobrante').style.display="none";
    }
    document.getElementById('balanceTotal').style.color="rgb(22, 5, 163)";
  }

  $('#balanceTotal').text("");
  $('#totalEfectivo').text("");
  $('#totalCashF').text("");
  $('#totalEfectivo2').text("");
  $('#totalEfectivo2').append("Efectivo a inicio del día: <br>"+number_format(cashAnterior2,2));
  if ($('#dTransferSurplus').val()!="") {
    $('#totalCashF').append("Q"+number_format(totalEfectivoActual-totalT-balance,2));
  }
  else{
    $('#totalCashF').append("Q"+number_format(totalEfectivoActual-totalT,2));
  }

  $('#totalEfectivo').append("Efectivo:<br>Q"+number_format(totalEfectivoActual,2));
  $('#balanceTotal').append("Q "+number_format(balance,2));
}
