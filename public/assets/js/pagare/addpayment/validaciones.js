  $(document).ready(function() {
    $('#save_payment').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        message: 'Valor no valido',
        fields: {
            totalPago:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar un monto mayor a 0.'
                      },
                      regexp:{
                        regexp: /^[0-9.,]+$/,
                        message:'Debe ingresar un monto válido. '
                      }
                    }
                }
            }
        })
});
// .on('success.form.bv', function(e) {
// 	  $('#success_message').slideDown({ opacity: "show" }, "slow") // Do something ...
// 	  $('#save_payment').data('bootstrapValidator').resetForm();
// 	  e.preventDefault();
// 	  var $form = $(e.target);
// 	  var bv = $form.data('bootstrapValidator');
//     $.post($form.attr('action'), $form.serialize(), function(result) {
//     console.log(result);
//     }, 'json');
//   });
// });
