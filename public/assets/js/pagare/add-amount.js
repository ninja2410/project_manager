function verify(caja){
  var valor=caja.value.replace(",", "");
  var maxFiador=parseFloat($('#maxFiador').val());
  var maxAutho=parseFloat($('#maxAutho').val());
  var maxAmount=parseFloat($('#maxAmount').val());
  var monto=$('#amount').val().replace(",", "");
  var compara=(parseFloat(valor)+parseFloat(monto));
  var status=$('#status').val();
  if (compara>maxAmount&&maxAmount!=-1) {
    toastr.error('El monto no puede ser mayor al monto permitido al cliente.')
    caja.value="";
    caja.focus();
    return;
  }
  if (compara>maxAutho&&status=="1") {
    toastr.error('El crédito debe ser autorizado por un usuario administrador.');
    $('#new_status').val("0");
  }
}
