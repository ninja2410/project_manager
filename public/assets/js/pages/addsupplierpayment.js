$('#addCustomerPayment').bootstrapValidator({
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    message: 'Valor no valido',
    fields:{
        total_pagos:{
            validators:{
                notEmpty:{
                    message:'Debe ingresar el abono a las facturas pendientes.'
                },
                stringLength:{
                    min:1,
                    message:'Debe ingresar por lo menos 1 dígito.'
                },
                between: {
                    min: 0.01,
                    max: 9999999999,
                    message: 'Debe ingresar al menos 0.01 Q'
                },
                regexp:{
                    regexp: /^\d+(\.\d{1,2})?$/,
                    message: 'Ingrese un número válido.'
                }
            }
        },
        payment_method:{
            validators:{
                notEmpty:{
                    message:'Debe seleccionar el metodo de pago.'
                }
            }
        },
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
                    min: 0.01,
                    max: 9999999999,
                    message: 'Debe ingresar al menos 1 Q'
                },
                regexp:{
                    regexp: /^\d+(\.\d{1,2})?$/,
                    message: 'Ingrese un número válido.'
                },
                callback: {
                    message: 'Abono debe ser igual o menor al saldo.',
                    callback: function (value,validator,$field) {
                        var saldo = cleanNumber($('#balance_supplier').val());
                        console.log('valor '+cleanNumber(value)+' saldo '+saldo);
                        if (cleanNumber(value)>saldo){
                            return false;
                        }
                        return true;
                    }
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
        },
        monto_pago:{
            validators:{
                notEmpty:{
                    message:'Debe ingresar el monto.'
                },
                stringLength:{
                    min: 0.01,
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
        total_abono:{
            validators:{
                // notEmpty:{
                //     message:'Debe ingresar el monto.'
                // },
                stringLength:{
                    min:1,
                    message:'Debe ingresar por lo menos 1 dígito.'
                },
                between: {
                    min: 0.01,
                    max: 9999999999,
                    message: 'Debe ingresar al menos 1 Q'
                },
                regexp:{
                    regexp: /^\d+(\.\d{1,2})?$/,
                    message: 'Ingrese un número válido.'
                }
            }
        }
        // ,



    }
})
.on('change', '[name="payment_method"]', function() {
    /*jramirez 2019.09.19
    * Cuando cambie el tipo de pago habilitaremos o no ciertos validators (validaciones)
    */
    var forma_pago = Number($(this).val());
    // console.log('cambio pago '+forma_pago);
    /*Por default cada vez que cambie deshabilitamos los validators especificos*/
    $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'card_name',false,null);
    $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'card_number',false,null);
    $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'reference',false,null);
    $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'same_bank',false,null);
    $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'bank_name',false,null);

    /*Dependiendo de la forma de pago habilitamos ciertos validators*/
    switch (forma_pago) {
        case 2:
        case 5:
        /*cheque*/ /*transferencia*/
        $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'bank_name',true,null);
        $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'same_bank',true,null);
        $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'reference',true,null);
        // console.log(' cheque/transfer '+forma_pago);
        break;
        case 3: /* Deposito */
        $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'reference',true,null);
        // console.log(' Depósito '+forma_pago);
        break;
        case 4:
        /*Tarjeta*/
        $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'card_name',true,null);
        $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'card_number',true,null);
        $('#addCustomerPayment').bootstrapValidator('enableFieldValidators', 'reference',true,null);
        // console.log(' Tarjeta '+forma_pago);
        break;
    }
});

// $(".addPayment").change(function(){
//     console.log('cambio input');
//     var $validator = $('#addCustomerPayment').data('bootstrapValidator').validate();
//     if ($validator.isValid()) {
//         document.getElementById("addCustomerPayment").submit();
//     }
// });

$('#rootwizard').bootstrapWizard({
    'tabClass': 'nav nav-pills',
    'onNext': function(tab, navigation, index) {
        var $validator = $('#addCustomerPayment').data('bootstrapValidator').validate();
        return $validator.isValid();
    },
    onTabClick: function(tab, navigation, index) {
        return false;
    },
    onTabShow: function(tab, navigation, index) {
        var $total = navigation.find('li').length;
        var $current = index + 1;

        // If it's the last tab then hide the last button and show the finish instead
        if ($current >= $total) {
            $('#rootwizard').find('.pager .next').hide();
            $('#rootwizard').find('.pager .finish').show();
            $('#rootwizard').find('.pager .finish').removeClass('disabled');
        } else {
            $('#rootwizard').find('.pager .next').show();
            $('#rootwizard').find('.pager .finish').hide();
        }
    }});

    $('#rootwizard .finish').click(function () {
        if ($('#account_id').hasClass('validation-cacao')) {
            if (!$('#account_id').val() ) {
                $('#account_id').focus();
                toastr.error("Debe seleccionar una cuenta.");
                return false;
            }
        }
        if ($('#recipient').hasClass('validation-cacao')) {
            if (!$('#recipient').val() ) {
                $('#recipient').focus();
                toastr.error("Debe ingresar el nombre del beneficiario de la transacción.");
                return false;
            }
        }
        var $validator = $('#addCustomerPayment').data('bootstrapValidator').validate();

        // var addPayment = 0;
        // $('.addPayment').each(function(){
        //     addPayment += parseFloat(this.value);
        // });
        // var monto_pago = cleanNumber($('#monto_pago').val());
        // console.log('total '+addPayment);
        // if ($validator.isValid() && (monto_pago===cleanNumber(addPayment) ) ) {
        if ($validator.isValid() ) {
            // document.getElementById("addCustomerPayment").submit();
            $('#confirmSave').modal('show');
            console.log('submit');
        }
        // else
        // {
        //     toastr.error("El pago a facturas debe ser igual al monto del abono. (Q "+monto_pago+")" );
        // }

    });
    $('#total_pagos').on('ifChanged', function(event){
        $('#addCustomerPayment').bootstrapValidator('revalidateField', 'total_pagos');
    });
    $('#addCustomerPayment').keypress(
        function(event){
            if (event.which == '13') {
                event.preventDefault();
            }
        });
