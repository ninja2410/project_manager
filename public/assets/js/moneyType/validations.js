$(document).ready(function(){
  $('#newMoney').bootstrapValidator({
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
                      message:'Debe ingresar nombre de ruta.'
                  }
              }
          },
          value:{
            validators:{
              notEmpty:{
                message:'Debe ingresar valor de moneda.'
              },
              regexp:{
                  regexp: /^[0-9]\d*(\.\d+)?$/,
                  message: 'Ingrese un valor válido'
              }
            }
          }
      }
  });
});
