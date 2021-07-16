// document.getElementById('total_pago').value=0;
document.getElementById('name_customer').value="";
//cambio de vendedor
var cmbPago=document.getElementById('id_pago');
var result=document.getElementById('id_bodega');
var id_correlativo=document.getElementById('id_correlativo');
$('#id_serie').change(function(){
  var id_serie=$(this).val();
  if(id_serie!=0){
    $.ajax({
      method:'get',
          url:APP_URL+'/getCorrelativeSale/'+id_serie,
          success:function(data){
            $('#id_correlativo').val(data);
          },
          error:function(error){
            console.log(error);
          }
    });
  }else {
    $('#id_correlativo').val(0);
  }
});

  function valida(e){
  tecla = (document.all) ? e.keyCode : e.which;
  if (tecla==8){
      return true;
  }
  patron =/[0-9]/;
  tecla_final = String.fromCharCode(tecla);
  return patron.test(tecla_final);
}

//boton de verificación de vender
var idVenta=document.getElementById('idVenta');
idVenta.addEventListener('click',function(){
  this.style.display='none';
var select_serie = document.getElementById("id_serie");
var name_customer=document.getElementById('name_customer');
var customer_id=document.getElementById('customer_id');
var item_quantity=document.getElementById('item_quantity');
var user_relation=document.getElementById('employee');
    selected = select_serie.value;
    if(selected==0)
    {
      select_serie.focus();
      toastr.error("Seleccione serie de documento");
      this.style.display='inline';
    }
    else if(name_customer.value==""){
      toastr.error("Seleccione o cree un cliente para la venta");
      $('#modal-1').modal("show");
      this.style.display='inline';
    }
    else if(parseInt(user_relation.value)==0){
      toastr.error("Seleccione vendedor");
      user_relation.focus();
      this.style.display='inline';
    }
    else if(parseInt(item_quantity.value)==0)
    {
      toastr.error("Debe agregar productos a la venta");
      this.style.display='inline';
      // document.getElementById('id_input_search').focus();
    }
    else
    {
      $.ajax({
        type:"post",
        url:APP_URL+'/existCorrelative',
        data:{
          _token: $('#token_').val(),
          'id_serie':$('#id_serie').val(),
          'correlative':$('#id_correlativo').val()
        },
        success:function(data){
          if(data==0){
            $('body').loadingModal({
              text: 'Guardando factura...'
            });
            $('body').loadingModal('show');
            sleep(1000);
            sendForm();
          }else {
            toastr.error("El correlativo ya ha sido utilizado en otra factura", "Error");
            $('#id_correlativo').focus();
            this.style.display='inline';
          }
        },
        error:function(error){
          console.log(error);
        }
      });
    }
});

function sendForm(){
  $.ajax({
    type:"post",
    url:APP_URL+'/sales',
    data:$('#id_save_sales').serialize(),
    success:function(data_){
      var json = JSON.parse(data_);
      console.log(json);
      if (json.flag!=1) {
        toastr.error(json.mensaje);
          $('body').loadingModal('destroy');
      }
      else{
        toastr.success("Venta realizada con exito!");
        location.href = APP_URL+'/'+json.url;
      }
    },
    error:function(error){
      console.log(error);
    }
  });
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
