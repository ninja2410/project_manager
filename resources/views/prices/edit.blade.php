@extends('layouts/default')

@section('title',trans('item.edit_price'))
@section('page_parent',trans('item.items'))


@section('header_styles')
  <!-- Validaciones -->
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
@stop
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
              {{trans('item.edit_price')}}
            </h3>
            <span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
          </div>
          <div class="panel-body">

            {!! Form::model($prices, array('route' => array('prices.update', $prices->id), 'method' => 'PUT', 'files' => true,'id'=>'frmPrices')) !!}
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('name', trans('item.name').' *', array('class' => 'control-label')) !!}
                  {!! Form::text('name', old('name',$prices->name), array('class' => 'form-control')) !!}
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('pct', trans('item.price_pct').' *', array('class' => 'control-label')) !!}
                  {!! Form::text('pct', old('pct',$prices->pct), array('class' => 'form-control number')) !!}
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  {!! Form::label('pct_min', trans('item.price_pct_min').' *', array('class' => 'control-label')) !!}
                  {!! Form::text('pct_min', old('pct_min',$prices->pct_min), array('class' => 'form-control number')) !!}
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('amount', trans('item.price_amount'), array('class' => 'control-label')) !!}
                  {!! Form::text('amount', old('amount',$prices->amount), array('class' => 'form-control number')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('amount_min', trans('item.price_amount'), array('class' => 'control-label')) !!}
                  {!! Form::text('amount_min', old('amount_min',$prices->amount_min), array('class' => 'form-control number')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('cant_min', trans('item.cant_min'), array('class' => 'control-label')) !!}
                  {!! Form::text('cant_min', old('cant_min',$prices->cant_min), array('class' => 'form-control number')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('cant_max', trans('item.cant_max'), array('class' => 'control-label')) !!}
                  {!! Form::text('cant_max', old('cant_max',$prices->cant_max), array('class' => 'form-control number')) !!}
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('date_min', trans('item.date_min'), array('class' => 'control-label')) !!}
                  {!! Form::text('date_min', old('date_min',$prices->date_min), array('class' => 'form-control fechas')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('date_max', trans('item.date_max'), array('class' => 'control-label')) !!}
                  {!! Form::text('date_max', old('date_max',$prices->date_max), array('class' => 'form-control fechas')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('order', trans('item.order'), array('class' => 'control-label')) !!}
                  {!! Form::text('order', old('order',$prices->order), array('class' => 'form-control number')) !!}
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('main', trans('item.main'), array('class' => 'control-label')) !!}
                  <select id="main" name="main" class="form-control">
                    <option value="0" @if(Input::old('main')=='0') selected="selected" @endif>No</option>
                    <option value="1" @if(Input::old('main')=='1') selected="selected" @endif>Si</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <label for="pagos" class="control-label">{{ trans('item.pagos') }}*
                  <span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
                  <span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
                <select name="pagos[]" id="pagos" class="form-control select2" multiple="multiple">
                  @foreach($pagos as $id => $name)
                    <option value="{{ $id }}" {{ (in_array($id, old('pagos', [])) || isset($prices) && $prices->pagos->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  {!! Form::label('active', trans('item.active'),  array('class' => 'control-label')) !!}
                  <select id="active" name="active" class="form-control">
                    <option value="1" @if(Input::old('active')=='1') selected="selected" @endif>Si</option>
                    {{-- <option value="0" @if(Input::old('active')=='0') selected="selected" @endif>No</option>										 --}}
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <label for="pagos" class="control-label">{{ trans('item.list_items') }}*
                  <span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
                  <span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
                <select name="items[]" id="items" class="form-control select2" multiple="multiple"
                        @if(isset($prices->system) && ($prices->system==1)) readonly="readonly" @endif>
                  @foreach($items as $id => $name)
                    <option value="{{ $id }}" {{ (in_array($id, old('items', [])) || isset($prices) && $prices->items->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>

                  @endforeach
                </select>
              </div>
            </div>
            <br>
            @include('partials.buttons',['cancel_url'=>"/prices"])
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('footer_scripts')
  {{-- FORMATO DE MONEDAS --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
  <script language="javascript" type="text/javascript"
          src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
  <!-- Valiadaciones -->
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }} " type="text/javascript "></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }} "
          type="text/javascript "></script>
  <script type="text/javascript">
      $(document).ready(function () {
          $('.number').toArray().forEach(function (field) {
              new Cleave(field, {
                  numeral: true,
                  numeralPositiveOnly: true,
                  numeralThousandsGroupStyle: 'none'
              });
          });
          $('.select-all').click(function () {
              let $select2 = $(this).parent().siblings('.select2')
              $select2.find('option').prop('selected', 'selected')
              $select2.trigger('change')
          })
          $('.deselect-all').click(function () {
              let $select2 = $(this).parent().siblings('.select2')
              $select2.find('option').prop('selected', '')
              $select2.trigger('change')
          })
          $('.select2').select2()
          // validaciones
          $('#frmPrices').bootstrapValidator({
              feedbackIcons: {
                  valid: 'glyphicon glyphicon-ok',
                  invalid: 'glyphicon glyphicon-remove',
                  validating: 'glyphicon glyphicon-refresh'
              },
              message: 'Valor no valido',
              fields: {
                  name: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar el nombre del precio.'
                          },
                          stringLength: {
                              min: 3,
                              message: 'Debe ingrear por lo menos 3 caracteres.'
                          }
                      }
                  },
                  order: {
                      notEmpty: {
                          message: 'Debe de ingresar el orden a mostrar.'
                      }
                  },
                  pct: {
                      validators: {
                          stringLength: {
                              min: 1,
                              max: 6,
                              message: 'El porcentaje debe tener 1 digitos al menos. '
                          }
                      },
                      required: false
                  },
                  pct_min: {
                      validators: {
                          stringLength: {
                              min: 1,
                              max: 6,
                              message: 'El porcentaje debe tener 1 digitos al menos. '
                          },
                          callback: {
                              message: 'El porcentaje minimo no debe ser mayor al porcentaje.',
                              callback: function (value) {
                                  let val = cleanNumber(value);
                                  let ptc = cleanNumber($('#pct').val());
                                  return val <= ptc;
                              }
                          }
                      },
                      required: false
                  },
                  amount: {
                      validators: {
                          stringLength: {
                              min: 1,
                              max: 5,
                              message: 'El monto debe tener 1 digitos al menos. '
                          }
                      },
                      required: false
                  },
                  amount_min: {
                      validators: {
                          stringLength: {
                              min: 1,
                              max: 5,
                              message: 'El monto debe tener 1 digitos al menos. '
                          }
                      },
                      required: false
                  },
                  cant_min: {
                      validators: {
                          stringLength: {
                              min: 1,
                              max: 5,
                              message: 'La cantidad debe tener 1 digitos al menos. '
                          }
                      },
                      required: false
                  },
                  cant_max: {
                      validators: {
                          stringLength: {
                              min: 1,
                              max: 5,
                              message: 'La cantidad debe tener 1 digitos al menos. '
                          }
                      },
                      required: false
                  },
                  adress: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar la direccion.'
                          },
                          stringLength: {
                              min: 3,
                              message: 'Debe ingrear por lo menos 3 caracteres.'
                          }
                      }
                  },
                  users: {
                      validators: {
                          notEmpty: {
                              message: 'Debe ingresar la direccion.'
                          }
                      }
                  }
              }
          });
      });
  </script>
@stop


