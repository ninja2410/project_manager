function enviarPorcentaje(){
    $.ajax({
       type: "POST",
       url: '../../percent/store',
       data: $("#form_editar_percent").serialize(),
       success: function(data)
       {
       }
  });
  setTimeout(
  function()
  {
    toastr.success('Porcentaje registrado con exito!');
  }, 500);
  setTimeout(
  function()
  {
    location.reload(true);
  }, 1000);
}
