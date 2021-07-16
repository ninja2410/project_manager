var totalT = 0;
var registrosTrans = 0;
var editando = false;
function valDrop(string){
  var nw=string.split("-", 1);
  return nw[0];
}
function getResponsible(){
  var account=$('#selAccount').val();
  var url='../responsible/'+account;
  $.ajax({
    type: 'get',
    async: false,
    url: url,
    success: function(data) {
      $('#txtEncargado').val("");
      $('#txtEncargado').val(data);
    },
    error: function(error) {
      console.log('existe un error revisar:'+error);
    }
  });
}
function getResponsibleSur(){
  var account=$('#selAccountSurplus').val();
  var url='../responsible/'+account;
  $.ajax({
    type: 'get',
    async: false,
    url: url,
    success: function(data) {
      $('#txtEncargadoSur').val("");
      $('#txtEncargadoSur').val(data);
    },
    error: function(error) {
      console.log('existe un error revisar:'+error);
    }
  });
}
function getResponsibleEdit(){
  var account=$('#selEditAccount').val();
  var url='responsible/'+account;
  $.ajax({
    type: 'get',
    async: false,
    url: url,
    success: function(data) {
      $('#text_encargado').val("");
      $('#text_encargado').val(data);
    },
    error: function(error) {
      console.log('existe un error revisar:'+error);
    }
  });
}
$(document).ready(function() {
  $("form#main_input_box_transferencias").submit(function(event) {
    event.preventDefault();
    if (!validAccount($('select[name="selAccount"] option:selected').text())) {
      toastr.error("La cuenta ya ha sido ingresada");
      return;
    }
    if ((totalEfectivoActual-balance)>=(totalT+convertMoney($('#txtAmountTrans').val()))) {
      var deleteButton = " <a href='' class='tododelete_t redcolor'><span class='glyphicon glyphicon-trash'  style='color:red;'></span></a>";
      var striks = "<span class='striks'> |  </span>";
      var editButton = "<a href='' class='todoedit_t'><span class='glyphicon glyphicon-pencil'></span></a>";
      var twoButtons = "<div class='col-md-3 col-sm-3 col-xs-3  pull-right showbtns todoitembtns'>" + editButton + striks + deleteButton + "</div>";
      var oneButton = "<div class='col-md-3 col-sm-3 col-xs-3  pull-right showbtns todoitembtns'>" + deleteButton + "</div>";
      $(".list_of_items_transferencias").append("<div class='todolist_list showactions list2'>  " +
        "<div class='col-md-12 col-sm-12 col-xs-12 nopadmar custom_textbox1'>" +
        "<div class='col-md-3 todotext_t'>" +
        $('select[name="selAccount"] option:selected').text()+
        "</div>" +
        "<div class='col-md-3 todotext_t1'>Q " +
        number_format(convertMoney($("#txtAmountTrans").val()), 2) +
        "</div>" +
        "<div class='col-md-3 todotext_t2'>" +
        $("#txtEncargado").val()+
        "</div>" +
        twoButtons +
        "</div>");
      totalT += convertMoney($('#txtAmountTrans').val());
      registrosTrans++;
      actualizarTotalesTrans();
      $("#txtAmountTrans").val('');
      $("#txtEncargado").val('');
      $("#selAccount").focus();
    }
    else {
      $("#txtAmountTrans").val("");
      $("#txtAmountTrans").focus();
      toastr.error("El monto total a transferir no debe exceder el total de efectivo actual");
    }
  });
  getResponsible();
  getResponsibleSur();
});
$(document).on('click', '.tododelete_t', function(e) {
  e.preventDefault();
  text = $(this).closest('.todolist_list').find('.todotext_t').text();
  text1 = $(this).closest('.todolist_list').find('.todotext_t1').text();
  totalT -= convertMoney(text1);
  registrosTrans--;
  actualizarTotalesTrans();
  $(this).closest('.todolist_list').hide("slow", function() {
    $(this).remove();
  });
});
function validAccount(account){
  var rsp=true;
  $(".list2").each(function() {
    if ($(this).find('.todotext_t').text()) {
      if ($(this).find('.todotext_t').text()==account) {
        rsp= false;
      }
    }
  });
  return rsp;
}
$(document).on('click', '.todoedit_t .glyphicon-pencil', function(e) {
  e.preventDefault();
  if (editando) {
    toastr.error("Debe terminar de editar un pago antes de editar otro.");
    return;
  }
  var drop=$('#selAccount').clone();
  var tmp=$(this).closest('.todolist_list').find('.todotext_t').text();
  drop.val(valDrop(tmp));
  drop.removeAttr("name");
  drop.removeAttr("class");
  drop.removeAttr("onChange");
  drop.attr("name", "selEditAccount");
  drop.attr("id", "selEditAccount");
  drop.attr("onChange", "getResponsibleEdit()");
  editando = true;
  var text = '';
  var text2 = '';
  var text1 = '';
  var text3='';
  text2 = $(this).closest('.todolist_list').find('.todotext_t1').text();
  text3 = $(this).closest('.todolist_list').find('.todotext_t2').text();
  totalT -= convertMoney(text2);
  text2 = number_format(text2, 2);
  text = drop;
  text2 = "<input type='text' name='text_monto' value='" + text2 + "' class='mora' />";
  text3 = "<input type='text' id='text_encargado' name='text_encargado' value='" + text3 + "' class='mora' />";
  $(this).closest('.todolist_list').find('.todotext_t').html(text);
  $(this).closest('.todolist_list').find('.todotext_t1').html(text2);
  $(this).closest('.todolist_list').find('.todotext_t2').html(text3);
  $(this).closest('.todolist_list').find('.striked').hide();
  //$(this).html("<span class='glyphicon glyphicon-saved'></span> <span class='hidden-xs'></span>");
  $(this).removeClass('glyphicon-pencil').addClass('glyphicon-saved hidden-xs');
});

$(document).on('click', '.todoedit_t .glyphicon-saved', function(e) {
  e.preventDefault();
  editando = false;
  var text1 = $('select[name="selEditAccount"] option:selected').text();
  var text2 = $(this).closest('.todolist_list').find("input[type='text'][name='text_monto']").val();
  var text3 = $(this).closest('.todolist_list').find("input[type='text'][name='text_encargado']").val();
  if (text3=='') {
    toastr.error("Debe ingresar el nombre del encargado");
    $(this).closest('.todolist_list').find("input[type='text'][name='text_encargado']").focus();
  }
  if (text2 == '') {
    toastr.error("El valor del monto no debe quedar en blanco.");
    $(this).closest('.todolist_list').find("input[type='text'][name='text_monto']").focus();
    return;
  }
  if (!validAccount(text1)) {
    toastr.error("La cuenta ya ha sido ingresada");
    return;
  }
  totalT += convertMoney(text2);
  actualizarTotalesTrans();
  text2 = number_format(text2, 2);
  $(this).closest('.todolist_list').find('.todotext_t').html(text1);
  $(this).closest('.todolist_list').find('.todotext_t1').html("Q " + text2);
  $(this).closest('.todolist_list').find('.todotext_t2').html(text3);
  $(this).removeClass('glyphicon-saved hidden-xs').addClass('glyphicon-pencil');
  $(this).closest('.todolist_list').find('.striked').show();
});

function actualizarTotalesTrans() {
  $('#totalTransferencia').text("");
  $('#registrosTrans').text("");
  $('#montoTransf').text("");
  $('#totalTransferencia').append("Total Monto: Q " + number_format(totalT, 2));
  $('#registrosTrans').append("Total Transferencias: " + registrosTrans);
  $('#montoTransf').append("Total Transferencias: <br> Q "+number_format(totalT,2));
  calcularBalance();
}
function buildJsonSurplus(){
  var dats=[];
  var jsons={};
  dats.push({
    "account":$('#selAccountSurplus').val(),
    "amount":convertMoney($('#txtAmountTransSur').val()),
    "encargado":$('#txtEncargadoSur').val()
  });
  jsons=dats;
  $('#dTransferSurplus').val(JSON.stringify(jsons));
  calcularBalance();
}
function buildJsonTransfers() {
  var datat = [];
  var jsont = {};
  $(".list2").each(function() {
    if ($(this).find('.todotext_t').text()) {
      datat.push({
        "account": valDrop($(this).find('.todotext_t').text()),
        "amount": convertMoney($(this).find('.todotext_t1').text()),
        "responsible":$(this).find('.todotext_t2').text()
      });
    }
  });
  jsont = datat;
  $('#dTransfers').val(JSON.stringify(jsont));
}
