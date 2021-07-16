@extends('layouts/default')

@section('title',trans('route.new'))
@section('page_parent',trans('route.route'))
@section('header_styles')
  <!-- Validaciones -->
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
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
              {{trans('route.new')}}
            </h3>
            <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
          </div>
          <div class="panel-body">
            
            <form id="frmRol" action="{{url('routes')}}" method="post">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    {!! Form::label('name', trans('Nombre de la ruta').' *') !!}
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-align-justify"></i></div>
                      {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    {!! Form::label('amount', trans('route.amount').' *') !!}
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-money"></i></div>
                      {!! Form::number('amount', Input::old('amount'), array('class' => 'form-control')) !!}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    {!! Form::label('states', trans('route.state').' *') !!}
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-check"></i></div>
                      <select name="states" id="states" class="form-control">
                        @foreach($states as $key => $value)
                          <option value="{{$key}}" {{ old('states') == $key ? 'selected' : '' }}>{{$value}}</option>
                        @endForeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    {!! Form::label('description', trans('route.description')) !!}
                    <div class="input-group select2-bootstrap-prepend">
                      <div class="input-group-addon"><i class="fa fa-comment"></i></div>
                      {!! Form::text('description', Input::old('description'), array('class' => 'form-control')) !!}
                    </div>
                  </div>
                </div>
              </div>
              {{-- {{$route->costumers}} --}}
              {{-- {{$costumers}} --}}
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                    <label for="customers">{{ trans('route.costumer') }}
                      <span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
                      <span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
                    <select name="customers[]" id="customers" class="form-control select2" multiple="multiple">
                      @foreach($customers as $user)
                        <option value="{{ $user->id }}" {{ (in_array($user->id, old('customers', [])) || isset($route) && $route->costumers->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group {{ $errors->has('permissions') ? 'has-error' : '' }}">
                    <label for="users">{{ trans('route.managers').(' *') }}
                      <span class="btn btn-info btn-xs select-all">{{ trans('general.select_all') }}</span>
                      <span class="btn btn-info btn-xs deselect-all">{{ trans('general.deselect_all') }}</span></label>
                    <select name="users[]" id="users" class="form-control select2" multiple="multiple">
                      @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (in_array($user->id, old('users', [])) || isset($route) && $route->users->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </form>
            @include('partials.buttons',['cancel_url'=>"/routes"])
            {{--Begin modal--}}
            <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="confirmSave" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-info">
                    <h4 class="modal-title">Confirmación guardar</h4>
                  </div>
                  <div class="modal-body">
                    <div class="text-center">
                      Datos correctos
                      <br>
                      ¿Seguro que desea guardar?
                    </div>
                  </div>
                  <div class="modal-footer" style="text-align:center;">
                    <a class="btn  btn-info" id="btn_save_confirm" onclick="document.getElementById('frmRol').submit();">Aceptar</a>
                    <a class="btn  btn-danger" data-dismiss="modal" >Cancelar</a>
                  </div>
                </div>
              </div>
            </div>
            {{--End modal--}}
          </div>
        </div>
      </div>
  </section>
@endsection
@section('footer_scripts')
  <script language="javascript" type="text/javascript" src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
  <!-- Valiadaciones -->
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  <script type="text/javascript">
    $(document).ready(function () {
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
      //
      $('#customers').select2()
      // validaciones
      $('#frmRol')
              .find('[name="users[]"]')
              .select2()
              .change(function(e) {
                $('#frmRol').bootstrapValidator('revalidateField', 'users[]');
              })
              .end()
              .bootstrapValidator({
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
                        message: 'Debe ingresar el nombre de la ruta.'
                      },
                      stringLength: {
                        min: 3,
                        message: 'Debe ingrear por lo menos 3 caracteres.'
                      }
                    }
                  },
                  states: {
                    validators: {
                      callback: {
                        message: 'Selecciona un estado.',
                        callback: function (value, validator) {
                          var options = validator.getFieldElements('states').val();
                          return (options != null && options >= 1 && options <= 2);
                        }
                      }
                    }
                  },
                  'users[]': {
                    validators: {
                      callback: {
                        message: 'Debes seleccionar al menos a un  encargado.',
                        callback: function (value, validator) {
                          var options = validator.getFieldElements('users[]').val();
                          return (options != null && options.length >= 1 );
                        }
                      }
                    }
                  },
                  amount: {
                    validators: {
                      callback: {
                        message: 'La cantidad ingresada debe ser mayor o igual a 0.',
                        callback: function (value, validator) {
                          var amount = validator.getFieldElements('amount').val();
                          return (amount != null && amount >= 0);
                        }
                      }
                    }
                  },
                }
              });
    });
    var amount = document.getElementById('amount');
    amount.addEventListener('input', (e) => {
      amount.value = Math.abs(amount.value);
    });


    $("#frmRol").submit(function(ev){ev.preventDefault();});
    var idVenta=document.getElementById('btn_save');
    idVenta.addEventListener('click',function(){
      var $validator = $('#frmRol').data('bootstrapValidator').validate();
      if ($validator.isValid() ) {

        $('body').loadingModal('hide');
        this.style.display='inline';
        $('#confirmSave').modal('show');
        // alert('ok');
      }
    });
  </script>
@stop

