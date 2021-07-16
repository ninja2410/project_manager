@extends('layouts/default')
@section('title',trans('bank_expenses.new_expense'))
@section('page_parent',trans('bank_expenses.banks'))

@section('header_styles')
  <!-- Validaciones -->
  {{--
      <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />  --}}
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
  <!--  Calendario -->
  <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
  {{-- select 2 --}}
  <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                 data-loop="true"></i>
              @if(isset($nombre_proyecto)) Proyecto: <strong>{{$nombre_proyecto}}</strong>
              @else {{trans('bank_expenses.new_expense')}} @endif
            </h3>
            <span class="pull-right clickable">
							<i class="glyphicon glyphicon-chevron-up"></i>
						</span>
          </div>
          <div class="panel-body">

            @if(isset($nombre_proyecto))
              {!! Form::open(array('url' => 'project/expenses/store', 'files' => true, 'id'=>'frmSup')) !!}
            @else
              {!! Form::open(array('url' => 'banks/expenses', 'files' => true, 'id'=>'frmSup')) !!}
            @endif
            <div class="row">
              @include('partials.bank-transactions.expenses')
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group {{ $errors->first('avatar', 'has-error') }}">
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                  </div>
                  <span class="btn btn-default btn-file" style="text-align: left;">
						{!! Form::label('photo', trans('expenses.photo').' ') !!}
						<input id="pic" name="avatar" type="file" class="form-control"/>
					</span>
                  <a href="#" class="btn btn-danger fileinput-exists" id="clearFile"
                     data-dismiss="fileinput">Quitar</a>
                </div>
                <span class="help-block">{{ $errors->first('avatar', ':message') }}</span>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('route_id', trans('credit_notes.credit_note_singular')) !!}
                  <div class="input-group select2-bootstrap-prepend">
                    <div class="input-group-addon"><i class="fa fa-folder-open"></i></div>
                    <select class="form-control" title="{{trans('credit_notes.credit_note')}}" name="credit_note_id" id="credit_note_id">
                      <option value="">--Seleccione--</option>
                      @foreach($credit_notes as $cn)
                        <option value="{!! $cn->id !!}"
                                @if(old('credit_note_id') === $cn->id) selected="selected" @endif maxAmount="{{$cn->amount - $cn->amount_applied}}">{{ $cn->serie->name.'-'.$cn->correlative }} - @money($cn->amount - $cn->amount_applied)</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
            @include('banking.expenses.taxes')
            {{-- <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('card_name', trans('revenues.card_name')) !!}
                        <input type="text" class="form-control" id="card_name" name="card_name">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Form::label('card_number', trans('revenues.card_number')) !!}
                        {!! Form::text('card_number', Input::old('card_number'), array('class'=> 'form-control', 'placeholder'=>'XXXX')) !!}
                    </div>
                </div>

            </div> --}}
            <div class="row">
              <div class="col-lg-6">
                @if(isset($stages))
                  <div class="form-group">
                    {!! Form::label('category_id', trans('bank_expenses.stage')) !!}
                    <select class="form-control" title="trans('bank_expenses.stage')" name="stage_id" id="stage_id">
                      <option value="">-Seleccione etapa-</option>
                      @foreach($stages as $item)
                        <option value="{!! $item->id !!}">{{ $item->name }}</option>
                      @endforeach
                    </select>
                  </div>
                @endif
              </div>

            </div>


            <input type="hidden" name="currency" id="currency" value="Q">
            <input type="hidden" name="currency_rate" id="currency_rate" value="1">
            {{-- <input type="hidden" name="payment_method" id="payment_method" value="1">							 --}}
            <input type="hidden" name="status" id="status" value="5"> {{-- No conciliado --}}
            <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">

            <br>
            <div class="col-lg-12">
              @include('partials.buttons',['cancel_url'=>"/banks/expenses"])
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
    <!-- </div> -->
  </section>
  @include('receiving.new_provider_modal')
@endsection
@section('footer_scripts')
  <!--  Calendario -->

  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} "
          type="text/javascript"></script>
  <!-- Valiadaciones -->
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  {{-- FORMATO DE MONEDAS --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
  {{-- Select2 --}}
  <script type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js')}} "></script>
  {{--	LOGICA DE IMPUESTOS	--}}
  <script type="text/javascript" src="{{ asset('assets/js/expenses/taxes.js')}} "></script>
  {{--	AGREGAR NUEVO PROVEEDOR--}}
  <script type="text/javascript" src="{{ asset('assets/js/recivings/add_new_suppliers.js')}} "></script>
  <script type="text/javascript">
      $(document).ready(function () {
          var isTax = false;
          $('#user_assigned_id').change(function () {
              showLoading("Verificando ruta asignada al usuario...");
              $.get(APP_URL + '/route_employee/' + $(this).val(), function (data) {
                  if (data == -1) {
                      toastr.error("El usuario no esta asignado a ninguna ruta.");
                      $('#route_id').val(null).trigger('change');
                  } else {
                      $('#route_id').val(data).trigger('change');
                  }
                  hideLoading();
              });
          });

          $('#clearFile').click(function () {
              $('#pic').val('');
          });

          $('#account_name').focus();
          $('select').select2({
              allowClear: true,
              theme: "bootstrap",
              placeholder: "Buscar"
          });
          $.fn.select2.defaults.set("width", "100%");
          $('#category_id').trigger("change");
          $('#frmSup').bootstrapValidator({
              feedbackIcons: {
                  valid: 'glyphicon glyphicon-ok',
                  invalid: 'glyphicon glyphicon-remove',
                  validating: 'glyphicon glyphicon-refresh'
              },
              message: 'Valor no valido',
              fields: {
                  amount: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar el monto.'
                          },
                          stringLength: {
                              min: 1,
                              message: 'Debe ingresar por lo menos 1 dígito.'
                          },
                          between: {
                              min: 1,
                              max: 9999999999,
                              message: 'Debe ingresar al menos 1 Q'
                          },
                          regexp: {
                              regexp: /^\d+(\.\d{1,2})?$/,
                              message: 'Ingrese un número válido.'
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
                  payment_method: {
                      validators: {
                          notEmpty: {
                              message: 'Debe seleccionar forma de pago.'
                          }
                      }
                  },
                  category_id: {
                      validators: {
                          notEmpty: {
                              message: 'Debe seleccionar la categoria.'
                          }
                      }
                  },
                  taxe_category: {
                      enabled: false,
                      validators: {
                          notEmpty: {
                              message: 'Debe seleccionar el tipo de aplicación del impuesto.'
                          }
                      }
                  },
                  units: {
                      enabled: false,
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar las unidades para aplicar el impuesto.'
                          }
                      }
                  },

                  total_cost: {
                      enabled: false,
                      validators: {
                          notEmpty: {
                              message: 'El monto total del impuesto es requerido.'
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
                  description: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar la descripción.'
                          }
                      }
                  },
                  recipient: {
                      enabled: false,
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar el nombre del beneficiario.'
                          },
                          stringLength: {
                              min: 5,
                              message: 'Debe ingresar por lo menos 5 caractéres.'
                          }
                      }
                  },
                  reference: {
                      enabled: false,
                      validators: {
                          notEmpty: {
                              message: 'Ingrese el número de cheque/transacción/depósito.'
                          },
                          stringLength: {
                              min: 2,
                              message: 'Debe ingresar por lo menos 2 caractéres.'
                          }
                      }
                  }
              }
          }).on('change', '[name="payment_method"]', function () {
              /*jramirez 2019.09.19
              * Cuando cambie el tipo de pago habilitaremos o no ciertos validators (validaciones)
              */
              var forma_pago = cleanNumber($(this).val());
              var paymen_type = cleanNumber($(this).children(':selected').attr('type'));
              console.log('tipo ' + forma_pago);
              /*Por default cada vez que cambie deshabilitamos los validators especificos*/
              $('#frmSup').bootstrapValidator('enableFieldValidators', 'recipient', false, null);
              $('#frmSup').bootstrapValidator('enableFieldValidators', 'reference', false, null);
              /*Dependiendo de la forma de pago habilitamos ciertos validators*/
              switch (forma_pago) {
                  case 2:
                  case 5:
                      /*cheque*/ /*transferencia*/
                      $('#frmSup').bootstrapValidator('enableFieldValidators', 'recipient', true, null);
                      $('#frmSup').bootstrapValidator('enableFieldValidators', 'reference', true, null);
                      // console.log(' cheque/transfer '+forma_pago);
                      break;
                  case 3: /* Depósito */
                  case 4: /* Tarjeta */
                      $('#frmSup').bootstrapValidator('enableFieldValidators', 'reference', true, null);
                      // console.log(' Depósito '+forma_pago);
                      break;
              }
              cambiopago(forma_pago);
          });
          cambiopago($('#payment_method').val());
      });

      function cambiopago(pago_id) {
          // console.log('account_id antes: '+account_id);
          showLoading('Cargando listado de cuentas...');
          if (pago_id) {
              $.get(APP_URL + '/banks/get-account-type/' + [pago_id] + '/0', function (data) {
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
          sideBySide: true, locale: 'es', format: 'DD/MM/YYYY', defaultDate: dateNow
      }).parent().css("position :relative ");

      var balance = document.getElementById('account_id');
      balance.addEventListener('change', function () {
          console.log($("#account_id option:selected").text());
          // $("#bank_name").val();
          // console.log($("#bank_name").val());
      });
      $('#credit_note_id').change(function () {
        if($(this).val()){
            let maxValue = cleanNumber($('select[name="credit_note_id"] option:selected').attr('maxAmount'));
            let txtAmount = document.getElementById('expense_amount');
            txtAmount.setAttribute('maxValue', maxValue);
            txtAmount.value = maxValue;
        }
        else{
            let txtAmount = document.getElementById('expense_amount');
            txtAmount.removeAttribute('maxValue');
            txtAmount.value = '';
        }
          $('#frmSup').data('bootstrapValidator')
              .updateStatus($('#expense_amount'), 'NOT_VALIDATED')
              .validateField($('#expense_amount'));
      });

      $('#expense_amount').change(function () {
        if($(this).attr('maxValue')){
            let max = cleanNumber($(this).attr('maxValue'));
            if($(this).val() > max){
                toastr.error(`El monto del gasto no debe ser mayor al monto no aplicado de la nota de crédito seleccionada (Q ${max.format(2)}).`);
                $(this).val(max);
            }
        }
      });

  </script>
@stop
