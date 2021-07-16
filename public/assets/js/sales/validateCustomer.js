$(document).ready(function() {
    $('#idFormNewCustomer').bootstrapValidator({
    	feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	name_customer2:{
        		validators:{
                    stringLength: {
                        min:4,
                        message:'El minimo de letras es de 4'
                    },
                    notEmpty: {
                        message: 'El nombre del cliente es requerido'
                    }
                }
            },
            nit_customer2:{
                validators:{
                    stringLength:{
                        min:3,
                        max:8,
                        message:'El minimo de caracteres es de 3 y el maximo de 8'

                    },
                    notEmpty:{
                        message:'El nit es requerido'
                    }
                }
            },
            address_customer2:{
                validators:{
                    stringLength:{
                        min:3,
                        message:'El minimo de caracteres es de 3'
                    }
                },
                notEmpty:{
                    message:'La direccion es requerida'
                }
            }
        }
    })
   });
