$('#newCustomer').bootstrapValidator({
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    message: 'Valor no valido',
    fields:{
        nit_customer:{
            validators:{
                notEmpty:{
                    message:'Debe ingresar NIT del cliente. '
                },
                stringLength: {
                    min: 3,
                    max: 15,
                    message: 'El NIT debe contener 3 caracteres como mínimo (C/F).'
                },
                // regexp: {
                //     regexp: /^[0-9-C/F?c/f?a-z?A-Z?]+$/,
                //     message: 'El NIT solo puede contener números y guiones'
                // },
                callback: {
                    message: 'El NIT ingresado no es válido',
                    callback: function (value) {
                        return nitValid(value);
                    }
                }
            }
        },
        max_credit_amount:{
            validators:{
                notEmpty:{
                    message:'Debe ingresar el monto de crédito del cliente (0.00 si no tiene crédito). '
                },
                between: {
                    min: 0,
                    max: 9999999999,
                    message: 'Debe ingresar al menos Q 100'
                },
                regexp: {
                    regexp: /^[0-9-.?]+$/,
                    message: 'El monto solo puede contener dígitos'
                },
                callback: {
                    message: 'Días de crédito debe ser mayor a 0.',
                    callback: function (value,validator,$field) {
                        var dias = cleanNumber($('#days_credit').val());
                        console.log('valor '+cleanNumber(value)+' dias '+dias);
                        if ((cleanNumber(value)>0) && (dias<1)){
                            return false;
                        }
                        return true;
                    }
                }
            }
        },
        days_credit:{
            validators:{
                notEmpty:{
                    message:'Debe ingresar los días de crédito. '
                },
                between: {
                    min: 0,
                    max: 180,
                    message: 'Mínimo es 0 día(s)/ máximo 180 días de crédito.'
                },
                regexp: {
                    regexp: /^[0-9]+$/,
                    message: 'El monto solo puede contener dígitos'
                }
            }
        },
        dpi:{
            validators:{
                stringLength:{
                    min:13,
                    max:13,
                    message:'DPI inválido, debe tener 13 dígitos.'
                },
                regexp:{
                    regexp:/^[0-9]+$/,
                    message:'DPI inválido, solo debe ingresar digitos del 0 al 9'
                }
            },
            required: false,
            minlength: 13
        },
        name:{
            validators:{
                notEmpty:{
                    message:'Debe ingresar nombre.'
                }
            }
        },
        address:{
            validators:{
                notEmpty:{
                    message:'Debe ingresar dirección.'
                }
            }
        },
        phone_number:{
            validators:{
                stringLength:{
                    min:8,
                    max:50,
                    message:'El número de telefono debe tener 8 digitos. '
                },
                regexp:{
                    regexp: /^[0-9/?a-z?A-Z?]+$/,
                    message: 'Ingrese un número válido.'
                }
            },
            required: false
        },
        ruta: {
            enabled:false,
            validators:{
                notEmpty:{
                    message:'Ruta requerida.'
                },

            }
        },

    }
}).
on('change', '[name="max_credit_amount"]', function() {
    $('#newCustomer').bootstrapValidator('revalidateField', 'days_credit');
})
    .on('change', '[name="days_credit"]', function() {
        $('#newCustomer').bootstrapValidator('revalidateField', 'days_credit');
        $('#newCustomer').bootstrapValidator('revalidateField', 'max_credit_amount');
    });
$('#rootwizard').bootstrapWizard({
    'tabClass': 'nav nav-pills',
    'onNext': function(tab, navigation, index) {
        var $validator = $('#newCustomer').data('bootstrapValidator').validate();
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
    var $validator = $('#newCustomer').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("newCustomer").submit();
    }

});
$('#activate').on('ifChanged', function(event){
    $('#newCustomer').bootstrapValidator('revalidateField', $('#activate'));
});
$('#newCustomer').keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    }
);

