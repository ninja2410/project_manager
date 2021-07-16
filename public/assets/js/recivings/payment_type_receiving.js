$(document).ready(function(){

    $('#frm_payment')
    .bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        message: 'Valor no valido',
        fields:{
            paid:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar el monto pagado.'
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
            bank_name: {
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'Debe ingresar el nombre del banco.'
                    },
                    stringLength:{
                        min:5,
                        message:'Debe ingresar por lo menos 5 caractéres.'
                    }
                }
            },
            same_bank: {
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'Debe confirmar si el cheque es del mismo banco.'
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
            },
            card_name: {
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'Debe ingresar el nombre en la tarjeta.'
                    },
                    stringLength:{
                        min:7,
                        message:'Debe ingresar por lo menos 7 dígitos.'
                    }
                }
            },

            card_number:{
                enabled:false,
                validators:{
                    notEmpty:{
                        message:'Debe ingresar los últimos 4 dígitos de la tarjeta.'
                    },
                    stringLength:{
                        min:4,
                        max:4,
                        message:'Debe ingresar 4 dígitos.'
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
    var paymen_type = cleanNumber($(this).children(':selected').attr('type'));
    console.log('type '+paymen_type);
    if(forma_pago && forma_pago>0)
    {
        var pago_texto = $( "#id_pago option:selected" ).text();
        // $('#payment_method').val(forma_pago);
        $('#payment_method').val(pago_texto);
        $('#payment_method').prop( "disabled", true );
    }
    else{
        $('#payment_method').val("");
        $('#tab_pago').hide();
    }

    /*Por default cada vez que cambie deshabilitamos los validators especificos*/
    // $('#card_name').removeClass('validation-cacao').addClass('not-validate');
    // $('#card_number').removeClass('validation-cacao').addClass('not-validate');
    // $('#reference').removeClass('validation-cacao').addClass('not-validate');
    // $('#same_bank').removeClass('validation-cacao').addClass('not-validate');
    // $('#bank_name').removeClass('validation-cacao').addClass('not-validate');

    $('.tarjeta').removeClass('validation-cacao').addClass('not-validate').hide();
    $('.referencia').removeClass('validation-cacao').addClass('not-validate').hide();
    $('.mismo_banco').removeClass('validation-cacao').addClass('not-validate').hide();
    $('.banco').removeClass('validation-cacao').addClass('not-validate').hide();
    $('.credito').removeClass('validation-cacao').addClass('not-validate').hide();
    $('.recipient').removeClass('validation-cacao').addClass('not-validate').hide();
    $('.referencia').removeClass('validation-cacao').addClass('not-validate').hide();
    /**
    * Por default desahilitamos el monto a pagar
    */
    $('#paid').prop("disabled",true);

    /*Dependiendo de la forma de pago habilitamos ciertos validators*/
    switch (paymen_type) {
        /**Efectivo */
        case 1:
        $('#paid').prop("disabled",false);
        break;
        case 2:
        case 5:
        /*cheque*/ /*transferencia*/
        $('.recipient').addClass('validation-cacao').show();
        $('.referencia').addClass('validation-cacao').show();
        // console.log(' cheque/transfer '+forma_pago);
        break;
        case 3: /* Deposito */
        $('.referencia').addClass('validation-cacao').show();
        // console.log(' Depósito '+forma_pago);
        break;
        case 4:
        // /*Tarjeta*/
        // $('.tarjeta').addClass('validation-cacao').show();
        // $('#card_number').addClass('validation-cacao');
        $('.referencia').addClass('validation-cacao').show();
        // console.log(' Tarjeta '+forma_pago);
        break;
        case 6:
        /*Credito*/
        if($('#supplier_credit').val()<=0)
        {
            toastr.warning("Proveedor no tiene crédito!");
            $(this).val(0);
            $(this).trigger('change');
            $(this).focus();
        }
        else {
            console.log('si tiene credito');
            $('#paid').prop("disabled",false);
            $('#paid').val(0.00);
            $('#paid').prop("disabled",true);
            // $('#customer_credit').prop("disabled",true);
            $('#account_id').removeClass('validation-cacao').addClass('not-validate');
            $('#paid').removeClass('validation-cacao').addClass('not-validate');

            $('.credito').addClass('validation-cacao').removeClass('not-validate').show();
            $('#supplier_credit_amount').val(+$('#supplier_credit').val());
            fecha_pago();
        }
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
