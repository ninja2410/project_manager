$(document).ready(function () {
    // var cleave = new Cleave('.number', {
    //     numeral: true,
    //     numeralPositiveOnly: true,
    //     numeralThousandsGroupStyle: 'none'
    // });

    $('#frmDeposit')
        .bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            message: 'Valor no valido',
            fields: {
                deposit: {
                    validators: {
                        stringLength: {
                            min: 5,
                            message: 'Debe ingresar por lo menos 5 caractéres.'
                        },
                        // regexp: {
                        //     regexp: /^\d+?$/,
                        //     message: 'Ingrese un número válido.'
                        // },
                        remote: {
                            message: 'El No. de depósito ya esta ingresado en el sistema',
                            data: function (validator) {
                                return {
                                    deposit: validator.getFieldElements('deposit').val()
                                };
                            },
                            url: APP_URL + '/banks/deposit/verify/',
                        }
                    }
                }
            }
        });
});

let setRevenue = function (revenue_id) {
    document.getElementById('revenue_id').value = revenue_id;
    document.getElementById('deposit').value = '';
    $('#addDepositModal').modal("show");
}

let verifyDeposit = function () {
    var validator = $('#frmDeposit').data('bootstrapValidator').validate();
    if (validator.isValid() ) {
        $('#addDepositModal').modal('hide');
        showLoading("Guardando deposito...");
        document.getElementById('frmDeposit').submit();
    }
}