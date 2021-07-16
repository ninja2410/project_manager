//Verificar que no haya creado clase igual
function verifyClass(){
  var rsp=false;
  var url="../class/verifyClass/"+$('#arrears').val();
  $.ajax({
    type:'get',
    async: false,
    url:url,
    success:function(data){
      if (data!=1){
        toastr.error("Ya existe una clase con el mismo número de atrasos.");
      }
    },
    error:function(error){
      console.log('existe un error revisar');
    }
  });
}
$(document).ready(function(){
  $('#renovation').keyup(function(){
    if ($(this).val()=="0") {
      toastr.info("La renovación no será permitida para esta clase de clientes.");
    }
  });
  $('#newClassCustomer').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        message: 'Valor no valido',
        fields:{
            name:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar el nombre de la clase. '
                    }
                }
            },
            color:{
              validators:{
                notEmpty:{
                  message:'Debe seleccionar color de la clase.'
                }
              }
            },
            arrears:{
              validators:{
                notEmpty:{
                  message:'Debe ingresar número de cuotas atrasadas permitidas.'
                },
                regexp:{
                    regexp: /^[0-9]*$/,
                    message: 'Ingrese un número válido'
                }
              }
            },
            pctRen:{
              validators:{
                notEmpty:{
                  message:'Debe ingresar porcentaje de cuotas pagadas para renovación.'
                },
                regexp:{
                    regexp: /^[0-9]\d*(\.\d+)?$/,
                    message: 'Ingrese un número válido'
                }
              }
            },
            pctAmountRen:{
              validators:{
                notEmpty:{
                  message:'Debe ingresar porcentaje de monto de renovación permitido.'
                },
                regexp:{
                    regexp: /^[0-9]\d*(\.\d+)?$/,
                    message: 'Ingrese un número válido'
                }
              }
            },
        }
    });
});
