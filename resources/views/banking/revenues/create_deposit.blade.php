@extends('layouts/default')
@section('title',trans('revenues.new_revenue'))
@section('page_parent',trans('revenues.banks'))

@section('header_styles')
    <!-- Validaciones -->
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    <!--  Calendario -->
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    {{-- select 2 --}}
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Toast -->
    <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <section class="content">
        <!-- <div class="container"> -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('revenues.new_revenue')}}
                        </h3>
                        <span class="pull-right clickable">
							<i class="glyphicon glyphicon-chevron-up"></i>
						</span>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => $url, 'files' => true, 'id'=>'frmSup')) !!}


                        <input type="hidden" name="token" value="{{csrf_token()}}" id="token_">

						@include('partials.bank-transactions.revenues')

                        <input type="hidden" name="balance" id="balance">
                        <input type="hidden" name="currency" id="currency" value="Q">
                        <input type="hidden" name="currency_rate" id="currency_rate" value="1">
                        {{-- <input type="hidden" name="payment_method" id="payment_method" value="1"> --}}
                        <input type="hidden" name="status" id="status" value="5"> {{-- No conciliado --}}
                        <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">


                        <input type="hidden" name="jRetentions" id="jRetentions" value="">
                        <br>
                        <div class="col-lg-12">
                            @include('partials.buttons',['cancel_url'=>"/banks/accounts"])
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
        @include('partials.new-customer-sale')
    </section>
@endsection

@section('footer_scripts')

    {{-- Toastr --}}
    <script type="text/javascript" src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
    {{-- Select2 --}}
    <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
    <!--  Calendario -->
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} "
            type="text/javascript"></script>
    {{-- FORMATO DE MONEDAS --}}
    <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
    <script src="{{asset('assets/js/retention/createRevenue.js')}} "></script>
    <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
    <!-- Valiadaciones -->
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>

    {{-- CUSTOM JS --}}
    <script type="text/javascript" src="{{ asset('assets/js/sales/customer_add.js')}} "></script>
    <script type="text/javascript" src="{{ asset('assets/js/pages/validation_serie.js')}} "></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#account_name').focus();

            $('select').select2({
                allowClear: true,
                theme: "bootstrap",
                placeholder: "Buscar"
            });
            const form = document.getElementById('frmSup');
            $('#frmSup')
                .bootstrapValidator({
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    message: 'Valor no valido',
                    fields: {
                        payment_method: {
                            validators: {
                                notEmpty: {
                                    message: 'Debe seleccionar el metodo de pago.'
                                }
                            }
                        },
                        amount: {
                            validators: {
                                notEmpty: {
                                    message: 'Debe ingresar el monto.'
                                },
                                stringLength: {
                                    min: 1,
                                    message: 'Debe ingresar por lo menos 1 d??gito.'
                                },
                                between: {
                                    min: 1,
                                    max: 9999999999,
                                    message: 'Debe ingresar al menos 1 Q'
                                },
                                // regexp: {
                                //     regexp: /^\d+(\.\d{1,2})?$/,
                                //     message: 'Ingrese un n??mero v??lido.'
                                // }
                            }
                        },
                        receipt_number: {
                            validators: {
                                notEmpty: {
                                    message: 'Debe ingresar el n??mero de recibo.'
                                },
                                stringLength: {
                                    min: 1,
                                    message: 'Debe ingresar por lo menos 1 d??gito.'
                                },
                                between: {
                                    min: 1,
                                    max: 9999999999,
                                    message: 'Debe ingresar al menos 1'
                                },
                                regexp: {
                                    regexp: /^\d+(\.\d{1,2})?$/,
                                    message: 'Ingrese un n??mero v??lido.'
                                }
                            }
                        },
                        deposit: {
                            validators: {
                                stringLength: {
                                    min: 10,
                                    message: 'Debe ingresar por lo menos 10 d??gitos.'
                                },
                                regexp: {
                                    regexp: /^\d+?$/,
                                    message: 'Ingrese un n??mero v??lido.'
                                },
                                remote: {
                                    message: 'El No. de dep??sito ya esta ingresado en el sistema',
                                    data: function(validator) {
                                        return {
                                            deposit: validator.getFieldElements('deposit').val()
                                        };
                                    },
                                    url: APP_URL+'/banks/deposit/verify/',
                                }
                            }
                        },
                        account_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Debe seleccionar la cuenta.'
                                }
                            }
                        },
                        paid_at: {
                            validators: {
                                date: {
                                    format: 'DD/MM/YYYY',
                                    message: 'Fecha inv??lida'
                                }
                            }
                        },
                        description: {
                            validators: {
                                notEmpty: {
                                    message: 'Debe ingresar la descripci??n.'
                                }
                            }
                        },
                        bank_name: {
                            enabled: false,
                            validators: {
                                notEmpty: {
                                    message: 'Debe ingresar el nombre del banco.'
                                },
                                stringLength: {
                                    min: 5,
                                    message: 'Debe ingresar por lo menos 5 caract??res.'
                                }
                            }
                        },
                        same_bank: {
                            enabled: false,
                            validators: {
                                notEmpty: {
                                    message: 'Debe confirmar si el cheque es del mismo banco.'
                                }
                            }
                        },
                        reference: {
                            enabled: false,
                            validators: {
                                notEmpty: {
                                    message: 'Ingrese el n??mero de cheque/transacci??n/dep??sito.'
                                },
                                stringLength: {
                                    min: 2,
                                    message: 'Debe ingresar por lo menos 2 caract??res.'
                                }
                            }
                        },
                        card_name: {
                            enabled: false,
                            validators: {
                                notEmpty: {
                                    message: 'Debe ingresar el nombre en la tarjeta.'
                                },
                                stringLength: {
                                    min: 7,
                                    message: 'Debe ingresar por lo menos 7 d??gitos.'
                                }
                            }
                        },

                        card_number: {
                            enabled: false,
                            validators: {
                                notEmpty: {
                                    message: 'Debe ingresar los ??ltimos 4 d??gitos de la tarjeta.'
                                },
                                stringLength: {
                                    min: 4,
                                    max: 4,
                                    message: 'Debe ingresar 4 d??gitos.'
                                }
                            }
                        }
                    }
                })
                .on('change', '[name="payment_method"]', function () {
                    /*jramirez 2019.09.19
                    * Cuando cambie el tipo de pago habilitaremos o no ciertos validators (validaciones)
                    */
                    var forma_pago = Number($(this).val());
                    /*Por default cada vez que cambie deshabilitamos los validators especificos*/
                    $('#frmSup').bootstrapValidator('enableFieldValidators', 'card_name', false, null);
                    $('#frmSup').bootstrapValidator('enableFieldValidators', 'card_number', false, null);
                    $('#frmSup').bootstrapValidator('enableFieldValidators', 'reference', false, null);
                    $('#frmSup').bootstrapValidator('enableFieldValidators', 'same_bank', false, null);
                    $('#frmSup').bootstrapValidator('enableFieldValidators', 'bank_name', false, null);

                    /*Dependiendo de la forma de pago habilitamos ciertos validators*/
                    switch (forma_pago) {
                        case 2:
                        case 5:
                            /*cheque*/ /*transferencia*/
                            $('#frmSup').bootstrapValidator('enableFieldValidators', 'bank_name', true, null);
                            $('#frmSup').bootstrapValidator('enableFieldValidators', 'same_bank', true, null);
                            $('#frmSup').bootstrapValidator('enableFieldValidators', 'reference', true, null);
                            // console.log(' cheque/transfer '+forma_pago);
                            break;
                        case 3: /* Deposito */
                            $('#frmSup').bootstrapValidator('enableFieldValidators', 'reference', true, null);
                            // console.log(' Dep??sito '+forma_pago);
                            break;
                        case 4:
                            /*Tarjeta*/
                            $('#frmSup').bootstrapValidator('enableFieldValidators', 'card_name', true, null);
                            $('#frmSup').bootstrapValidator('enableFieldValidators', 'card_number', true, null);
                            $('#frmSup').bootstrapValidator('enableFieldValidators', 'reference', true, null);
                            // console.log(' Tarjeta '+forma_pago);
                            break;
                    }
                });
            cambiopago($('#payment_method').val())
        });

        function cambiopago(pago_id) {
            showLoading('Cargando listado de cuentas...');
            // console.log('account_id antes: '+account_id);

            if (pago_id) {
                $.get(APP_URL + '/banks/get-account-type/' + [pago_id] + '/deposit', function (data) {
                    //console.log('respuesta ajax cambiopaciente : '+data);
                    $('#account_id').empty();
                    $('#account_id').append('<option value="">Seleccione cuenta</option>');
                    $.each(data, function (index, accounts) {
                        $('#account_id').append('<option value="' + accounts.id + '">' + accounts.name + ' - ' + accounts.pct_interes + '</option>');
                    });
                    hideLoading();
                });
            } else {
                $('select[name="account_id"]').empty();
                hideLoading();
            }
            ;
            $('#payment_method').val(pago_id);
            // console.log('Adm id: '+$('#account_id').val());
        };

        var dateNow = new Date();
        $("#paid_at ").datetimepicker({
            sideBySide: true,
            locale: 'es',
            format: 'DD/MM/YYYY',
            defaultDate: dateNow
        }).parent().css("position :relative ");

        $('.money_efectivo2').toArray().forEach(function(field){
            new Cleave(field, {
                numeral: true,
                numeralPositiveOnly: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        });

        // var cleave = new Cleave('.money_efectivo2', { numeral: true, numeralThousandsGroupStyle: 'thousand' });

    </script>

@stop

