  $(document).ready(function() {
    $('#formPayment').bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        message: 'Valor no valido',
        fields:{
            amountRecived:{
                validators:{
                    notEmpty:{
                        message:'Debe ingresar un monto a pagar.'
                    }
                }
            }
        }
    });
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
                    }
                }
            },
            dpi:{
              validators:{
        				notEmpty:{
        					message:'Debe ingresar DPI.'
        				},
        				stringLength:{
        					min:13,
        					max:13,
        					message:'DPI inválido, debe tener 13 dígitos.'
        				},
        				regexp:{
        					regexp:/^[0-9]+$/,
        					message:'DPI inválido, solo debe ingresar digitos del 0 al 9'
        				}
        			}
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
        				notEmpty:{
        					message:'Debe ingresar un numero de teléfono'
        				},
        				stringLength:{
        					min:8,
                  max:8,
        					message:'El número de telefono debe tener 8 digitos. '
        				},
        				regexp:{
        					regexp: /^[0-9]+$/,
        					message: 'Ingrese un número válido.'
        				}
        			}
            },
            marital_status:{
              validators:{
                notEmpty:{
                  message:'Debe seleccionar un estado civil.'
                }
              }
            },
            birthdate:{
              validators:{
                notEmpty:{
                  message:'Debe ingresar una fecha de nacimiento.'
                }
              }
            },
            refer1Nombre:{
        			validators:{
        				notEmpty:{
        					message:'Debe ingresar nombre de referencia'
        				}
        			}
        		},
        		refer1Direccion:{
        			validators:{
        				notEmpty:{
        					message:'Debe ingresar una dirección'
        				}
        			}
        		},
        		refer1Telefono:{
        			validators:{
        				notEmpty:{
        					message:'Debe ingresar un numero de teléfono'
        				},
        				stringLength:{
        					min:8,
                  max:8,
        					message:'El número de telefono debe tener 8 digitos. '
        				},
        				regexp:{
        					regexp: /^[0-9]+$/,
        					message: 'Ingrese un número válido.'
        				}
        			}
        		},
            refer2Nombre:{
        			validators:{
        				notEmpty:{
        					message:'Debe ingresar nombre de referencia'
        				}
        			}
        		},
        		refer2Direccion:{
        			validators:{
        				notEmpty:{
        					message:'Debe ingresar una dirección'
        				}
        			}
        		},
        		refer2Telefono:{
        			validators:{
        				notEmpty:{
        					message:'Debe ingresar un numero de teléfono'
        				},
        				stringLength:{
        					min:8,
                  max:8,
        					message:'El número de telefono debe tener 8 digitos. '
        				},
        				regexp:{
        					regexp: /^[0-9]+$/,
        					message: 'Ingrese un número válido.'
        				}
        			}
            }
        }
    });

    $('#formPagare').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        message: 'Valor no valido',
        fields: {
            // customer_name:{
            //     validators:{
            //         notEmpty:{
            //             message:'Seleccione un cliente'
            //         }
            //     }
            // },
            // customer_id:{
            //     validators:{
            //         callback:{
            //             callback:function(value,validator){
            //                 var customer_id=$('#customer_id').val();
            //                 if(customer_id==0){
            //                     return false;
            //                     $('#btnListCustomers').click();
            //                 }else{
            //                     return true;
            //                 }
            //             }
            //         }
            //     }
            // },
            amount:{
                validators:{
                    notEmpty:{
                        message:'El monto del crédito es un campo requerido'
                    },
                    callback:{
                        message:'El monto del crédito debe de ser mayor a cero',
                        callback:function(value,validator){
                            var valor=$("#amount").val();
                            if(valor==0){
                                return false;
                            }else {
                                return true;
                            }
                        }
                    }
                }
            },
            cuotas:{
                validators:{
                    notEmpty:{
                        message:'El número de cuotas es un campo requerido'
                    },
                    regexp:{
                      regexp: /^[0-9]+$/,
                      message:'Número inválido de cuotas.'
                    },
                    callback:{
                        message:'El número de cuotas debe de ser mayor a cero',
                        callback:function(value,validator){
                            var valor=$('#cuotas').val();
                            if(valor==0){
                                return false;
                            }else {
                                return true;
                            }
                        }
                    }
                }
            },
            dias_mora:{
                validators:{
                    notEmpty:{
                        message:'El número de días de mora es un campo requerido'
                    },
                    regexp:{
                      regexp: /^[0-9]+$/,
                      message:'Número inválido de cuotas.'
                    },
                    callback:{
                        message:'El número de días de mora debe de ser mayor a cero',
                        callback:function(value,validator){
                            var valor=$('#dias_mora').val();
                            if(valor==0){
                                return false;
                            }else {
                                return true;
                            }
                        }
                    }
                }
            },
            intervalo:{
                validators:{
                    notEmpty:{
                        message:'El número de días de intervalo de pagos es un campo requerido'
                    },
                    regexp:{
                      regexp: /^[0-9]+$/,
                      message:'Número inválido de días de intervalo.'
                    },
                    callback:{
                        message:'El número de días de intervalo de pagos debe de ser mayor a cero',
                        callback:function(value,validator){
                            var valor=$('#intervalo').val();
                            if(valor==0){
                                return false;
                            }else {
                                return true;
                            }
                        }
                    }
                }
            },
            ptc_interes:{
                validators:{
                    notEmpty:{
                        message:'El % de interes es un campo requerido'
                    },
                    callback:{
                        message:'El % de interes debe der ser mayor a cero',
                        callback:function(value,validator){
                            var valor=$('#ptc_interes').val();
                            if(valor==0){
                                return false;
                            }else {
                                return true;
                            }
                        }
                    }
                }
            },
            mora:{
                validators:{
                    notEmpty:{
                        message:'El % de mora es un campo requerido'
                    },
                    callback:{
                        message:'El % de mora debe de ser mayor a cero',
                        callback: function(value,validator){
                            var valor=$('#mora').val();
                            if(valor==0){
                                return false;
                            }else {
                                return true;
                            }
                        }
                    }
                }
            },
            garantia:{
                validators:{
                    notEmpty:{
                        message:'La garantia es un campo requerido'
                    }
                }
            },

            fiador: {
                validators: {
                    stringLength: {
                        min: 3,
                        message: 'El mínimo es de 3 letras.'
                    },
                    notEmpty: {
                        message: 'El nombre del fiador es requerido'
                    },
                    regexp:{
                        regexp: /^[a-zA-Záéíóú ]*$/,
                        message: 'Solo se aceptan letras'

                    }
                }
            },
            fiador_direccion:{
                validators:{
                    notEmpty:{
                        message:'La dirección del fiador es requerida'
                    }
                }
            },
            dpi_fiador:{
                validators:{
                    notEmpty:{
                        message:'El DPI del fiador es requerido'
                    },
                    stringLength:{
                        max:13,
                        min:13,
                        message:'El mínimo y máximo de caracteres es 13'
                    },
                    regexp:{
                        regexp: /^[0-9]+$/,
                        message:'Solo se aceptan números'
                    }
                },

            },
            telefono_fiador:{
                validators:{
                    stringLength:{
                        max:8,
                        min:8,
                        message:'El mínimo y máximo es de caracteres es 8'
                    },
                    notEmpty:{
                      message:'El teléfono del fiador es requerido'
                    },
                    regexp:{
                        regexp: /^[0-9]+$/,
                        message:'Solo se aceptan números'
                    }
                }
            }
        }
    });
  // .on('success.form.bv', function(e) {
  //           $('#success_message').slideDown({ opacity: "show" }, "slow") // Do something ...
  //           $('#formPagare').data('bootstrapValidator').resetForm();
  //
  //           // Prevent form submission
  //           e.preventDefault();
  //
  //           // Get the form instance
  //           var $form = $(e.target);
  //
  //           // Get the BootstrapValidator instance
  //           var bv = $form.data('bootstrapValidator');
  //
  //           // Use Ajax to submit form data
  //           $.post($form.attr('action'), $form.serialize(), function(result) {
  //               console.log(result);
  //           }, 'json');
  //       });


//   $('#formnewCustomer').bootstrapValidator({
//     feedbackIcons: {
//         valid: 'glyphicon glyphicon-ok',
//         invalid: 'glyphicon glyphicon-remove',
//         validating: 'glyphicon glyphicon-refresh'
//     },
//     message: 'Valor no valido',
//     fields:{
//         customer_nit:{
//             validators:{
//                 notEmpty:{
//                     message:'El Nit es un campo requerido'
//                 }
//             }
//         }
//     }
// }).on('success.form.bv',function(e){
//     $('#success_message').slideDown({opacity:"show"},"slow")
//     $('#formnewCustomer').data('bootstrapValidator').resetForm();
//     e.preventDefault();

//             // Get the form instance
//             var $form = $(e.target);

//             // Get the BootstrapValidator instance
//             var bv = $form.data('bootstrapValidator');

//             // Use Ajax to submit form data
//             $.post($form.attr('action'), $form.serialize(), function(result) {
//                 console.log(result);
//             }, 'json');
//         });
});
