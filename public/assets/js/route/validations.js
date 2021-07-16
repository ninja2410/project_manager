$(document).ready(function(){
  $('#newRoute').bootstrapValidator({
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
          }
      }
  });
});
