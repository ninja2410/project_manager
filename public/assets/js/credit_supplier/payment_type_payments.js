$(document).ready(function(){

    $('#addCustomerPayment')
    .bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        message: 'Valor no valido',
        fields:{
            amount:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar el monto.'
                    },
                    stringLength:{
                        min:1,
                        message:'Debe ingresar por lo menos 1 dígito.'
                    },
                    between: {
                        min: 1,
                        max: 9999999999,
                        message: 'Debe ingresar al menos 1 Q'
                    },
                    regexp:{
                        regexp: /^\d+(\.\d{1,2})?$/,
                        message: 'Ingrese un número válido.'
                    }
                }
            },
            account_id:{
                validators:{
                    notEmpty:{
                        message:'Debe seleccionar la cuenta.'
                    }
                }
            },
            payment_method:{
                validators:{
                    notEmpty:{
                        message:'Debe seleccionar forma de pago.'
                    }
                }
            },
            category_id:{
                validators:{
                    notEmpty:{
                        message:'Debe seleccionar la categoria.'
                    }
                }
            },
            taxe_category:{
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'Debe seleccionar el tipo de aplicación del impuesto.'
                    }
                }
            },
            units:{
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'Debe ingresar las unidades para aplicar el impuesto.'
                    }
                }
            },

            total_cost:{
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'El monto total del impuesto es requerido.'
                    }
                }
            },

            paid_at: {
                validators: {
                    date: {
                        format: 'DD/MM/YYYY',
                        message: 'Fecha inválida'
                    }
                }
            },
            description:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar la descripción.'
                    }
                }
            },
            recipient: {
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'Debe ingresar el nombre del beneficiario.'
                    },
                    stringLength:{
                        min:5,
                        message:'Debe ingresar por lo menos 5 caractéres.'
                    }
                }
            },
            reference: {
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'Ingrese el número de cheque/transacción/depósito.'
                    },
                    stringLength:{
                        min:2,
                        message:'Debe ingresar por lo menos 2 caractéres.'
                    }
                }
            }
        }
    })
});


/**
* Cuando cambia de tipo de pago:
* - Se cambia el texto en el tab de pagos
* - Se agregan/remueven clases (css) de validaciǿn a ciertos campos dependiendo del seleccionado(tipo de pago)
*/
$( "#id_pago" ).change(function() {
    var forma_pago = Number($(this).val());
    $('.recipient').removeClass('validation-cacao').addClass('not-validate').hide();
    $('.referencia').removeClass('validation-cacao').addClass('not-validate').hide();

    /**
    * Por default desahilitamos el monto a pagar
    */
    $('#paid').prop("disabled",true);

    /*Dependiendo de la forma de pago habilitamos ciertos validators*/
    switch (forma_pago) {
        /**Efectivo */
        case 1:
        // $('#paid').prop("disabled",false);
        break;
        case 2:
        /*cheque*/ 
        $('.recipient').addClass('validation-cacao').show();
        $('.referencia').addClass('validation-cacao').show();
        var label = document.getElementById('reference_id');
        label.innerHTML="# de cheque"  
        break;
        case 3: /* Deposito */
        $('.referencia').addClass('validation-cacao').show();
        var label = document.getElementById('reference_id');
        label.innerHTML="# de depósito"  
        // console.log(' Depósito '+forma_pago);
        break;
        case 4:
        // /*Tarjeta*/
        // $('.tarjeta').addClass('validation-cacao').show();
        // $('#card_number').addClass('validation-cacao');
        $('.referencia').addClass('validation-cacao').show();
        var label = document.getElementById('reference_id');
        label.innerHTML="# de transacción"  
        // console.log(' Tarjeta '+forma_pago);
        break;
        case 5:
        /*transferencia*/
        $('.recipient').addClass('validation-cacao').show();
        $('.referencia').addClass('validation-cacao').show();
        var label = document.getElementById('reference_id');
        label.innerHTML="# de transferencia"  
        // console.log(' cheque/transfer '+forma_pago);
        break;                
    }

});

function fecha_pago() {
    $('#date_payments').blur(function(){
        if($('#date_payments').val()=="") {
            $('#date_payments').val(get_date_today());
        } });
    }



    /**
    * Cuando ingresa el monto a pagar
    */
    $( "#paid" ).focusout(function() {

        if (!$('#paid').val()) {
            $('#paid').focus();
            toastr.error("El monto pagado no debe estar vacio");
        }
        var monto_pagado = cleanNumber($(this).val());
        var total = parseFloat($('#total_general').val());
        // console.log(' paid '+ parseFloat($('#paid').val()));
        // console.log(' total_general '+ parseFloat($('#total_general').val()));

        if(monto_pagado<total){
            $('#paid').val(monto_pagado);
            $('#paid').focus();
            toastr.error("El monto pagado debe ser igual o mayor al monto a pagar.");
        }
        else {
            var cambio = monto_pagado-total;
            $('#change').val(cambio.toFixed(2));
        }
    });
