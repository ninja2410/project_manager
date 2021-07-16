$(document).ready(function(){
  if($('#total_invoice').val()==0){
    $('#submit').attr("disabled", true);
  }else{
    $('#submit').attr("disabled", false);
  }
});

function correlativo(serie){
  $.ajax({
     type: "GET",
     url: '../../invoice/correlative/'+serie.value,
     success: function(data)
     {
       if (data!="") {
         $('#txtNo').val(data);
       }
     }
  });
}

var dateNow = new Date();
$("#datepicker").datetimepicker({
  sideBySide: true,
  locale:'es',
  format:'DD/MM/YYYY',
  defaultDate:dateNow
}).parent().css("position :relative ");
function verify(control){
  $.ajax({
     type: "POST",
     url: '../../invoice/verify',
     data:{
       "_token": $('#token').val(),
       "number":control.value,
       "serie":$('#serie').val()
     },
     success: function(data)
     {
       if (data!="") {
         toastr.error(data);
         $('#submit').attr("disabled", true);
         document.getElementById('txtNo').focus();
       }
       else{
         $('#submit').attr("disabled", false);
       }
     }
});
}
function detalles(sel){
  if (sel.value==2) {
    document.getElementById('det1').style.display="none";
    document.getElementById('det2').style.display="none";
    document.getElementById('resume').style.display="table-row";
  }
  else{
    document.getElementById('resume').style.display="none";
    document.getElementById('det1').style.display="table-row";
    document.getElementById('det2').style.display="table-row";
  }
}

if ($('#total_ref').val()<=0) {
  $('#submit').hide();
}
else{
  $('#submit').show();
}
function validar()
{
  var sel=document.getElementById('t_invoice').value;
  if (sel==2) {
    //resumido
    if ($('#txtResumen').val()=="") {
      toastr.error('Debe ingresar una descripción de factura válida');
      document.getElementById('txtResumen').focus();
    }
    else{
      console.log('enviando');
      document.getElementById('formulario').submit();
    }
  }
  else {
    if ($('#txtDet1').val()=="") {
      toastr.error('Debe ingresar una descripción de factura válida');
      document.getElementById('txtDet1').focus();
    }
    else{
      if ($('#txtDet2').val()=="") {
        toastr.error('Debe ingresar una descripción de factura válida');
        document.getElementById('txtDet2').focus();
      }
      else{
        console.log('enviando');
        document.getElementById('formulario').submit();
      }
    }
  }
}
