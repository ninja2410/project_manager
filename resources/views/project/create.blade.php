@extends('layouts/default')

@section('title', trans('project.create'))
@section('page_parent',trans('project.create'))
@section('header_styles')
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
  <!-- Toast -->
  <link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
  {{-- date time picker --}}
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
  <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>
  <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet"/>
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">
              <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                 data-loop="true"></i>
              {{trans('project.create')}}
            </h3>
            <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
          </div>
          <div class="panel-body">

            {!! Form::open(array('url' => 'project/projects','id'=>'frmAdd')) !!}
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="name" class="control-label">{{trans('project.name')}}</label>
                  <div class="input-group">
                    <span class="input-group-addon">T</span>
                    {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('lblCode', trans('project.code'), array('class'=>'control-label')) !!}
                  <div class="input-group">
                    <span class="input-group-addon"><li class="glyphicon glyphicon-barcode"></li></span>
                    {!! Form::text('code', Input::old('code'),array('class' => 'form-control')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('lblDate', 'Fecha') !!}
                  <div class="input-group">
                                        <span class="input-group-addon"><li
                                                  class="glyphicon glyphicon-calendar"></li></span>
                    {!! Form::text('date', Input::old('date'), array('class' => 'form-control date')) !!}
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                {{-- <div class="form-group">
                  <label for="customer_id">{{trans('sale.customer')}}</label>
                  <div class="input-group">
                    <input type="hidden" name="customer_id" value="0" id="customer_id">
                    <input type="hidden" name="user_relation" value="0" id="user_relation">
                    <input type="text" name="name_customer" style="width:375px; padding-right:5px;" value="" id="name_customer" class="form-control" disabled>
                    <b style="color:white;"> a </b> <a href="#" style="font-size: 14px;" id="add_customer_btn" class="btn btn-raised btn-success btn-xs" data-toggle="modal" data-target="#modal-1">Agregar</a>
                  </div>
                </div> --}}
                <div class="form-group">
                  <label for="customer_id" class="control-label">{{trans('sale.customer')}}</label>
                  <div class="input-group select2-bootstrap-prepend">
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                    <select class="form-control" name="customer_id" id="customer_id">
                      <option value="">Seleccione cliente ó agregue uno ---></option>
                      @foreach($customer as $value)
                        <option value="{!! $value->id !!}">{{ $value->name }}
                        </option>
                      @endforeach
                    </select>
                    <div class="input-group-btn" data-toggle="tooltip"
                         data-original-title="Crear cliente">
                      <a href="#" style="font-size: 14px" id="add_customer_btn"
                         class="btn btn-default btn-icon" data-toggle="modal" data-target="#modal-2"
                         title="Crear cliente"><i class="fa fa-plus"></i></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  {!! Form::label('lblPrice', trans('project.price')) !!}
                  <div class="input-group">
                    <span class="input-group-addon">Q</span>
                    {!! Form::text('price', Input::old('price'), array('class' => 'form-control money')) !!}
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label for="type_id" class="control-label">{{trans('project.type_id')}}</label>
                  <div class="input-group select2-bootstrap-prepend">
                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                    <select class="form-control" name="type_id" id="type_id">
                      <option value="">Seleccione tipo de proyecto ---></option>
                      @foreach($types as $value)
                        <option value="{!! $value->id !!}">{{ $value->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="description" class="control-label">{{trans('project.description')}}</label>
                  <div class="input-group">
                    <span class="input-group-addon">D</span>
                    <textarea name="description" class="form-control" id="description" rows="2"></textarea>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <br>
                <div class="col-lg-6">
                  <label for="description" class="control-label">{{trans('project.create_cellar')}}</label>
                </div>
                <div class="col-lg-6"></div>
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-cog"></i></span>
                    <input type="checkbox" checked name="create_cellar" class="form-control" id="create_cellar">
                  </div>
                </div>
                <div class="col-lg-6">
                  <label for="description" class="control-label">{{trans('project.create_account')}}</label>
                </div>
                <div class="col-lg-6"></div>
                <div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-cog"></i></span>
                    <input type="checkbox" checked name="create_account" class="form-control" id="create_account">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              @include('partials.buttons',['cancel_url'=>"project/projects"])
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--  Ventana modal de listado de Customers-->
  <div class="modal fade" id="modal-1" role="dialog" aria-labelledby="modalLabelsuccess">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title" id="modalLabelsuccess">Listado de clientes</h4>
        </div>
        <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
          <div class="pull-left">
            <a href="#" id="add_customer_btn_2" class="btn btn-raised btn-success btn-xs"
               data-toggle="modal" data-target="#modal-2"
               data-dismiss="modal">Nuevo cliente</a>
          </div>
          <br>
          <table class="table table-bordered table-striped" id="table_customers">
            <thead>
            <td>No.</td>
            <td>Nit</td>
            <th>Nombre</th>
            <th>E-mail</th>
            <th>Teléfono</th>
            <th>Agregar</th>
            </thead>
            <tbody>
            @foreach($customer as $i=> $value)
              <tr>
                <td>{{$i+1}}</td>
                <td>{{$value->nit_customer}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->phone_number}}</td>
                <td>
                <!-- <button  type="button" name="button" class="btn btn-primary btn-xs" id="name_{{$value->nit_customer.'/'.$value->id}}" onclick="add_supplier(this);" > -->
                  <button type="button" name="button" class="btn btn-primary btn-xs"
                          id="name_{{$value->name.'/'.$value->id}}" onclick="add_customers(this);"
                          data-dismiss="modal">
                    <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                  </button>
                </td>
              </tr>
            @endforeach

            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!--  Ventana modal de listado de Customers-->
  <!-- Modal new customer -->
  <div class="modal fade" id="modal-2" name="modal-2" role="dialog" aria-labelledby="modalLabelsuccess">
    <div class="modal-dialog modal-lg" role="document">
      <form method="post" id="idFormNewCustomer">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h4 class="modal-title" id="modalLabelsuccess">Agregar nuevo cliente</h4>
          </div>
          <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="nit_customer">Nit cliente: *</label>
                <input type="text" name="nit_customer2" value="c/f" class="form-control" required
                       id="nit_customer2">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="name_customer">Nombre: *</label>
                <input type="text" name="name_customer2" value="" class="form-control" required
                       id="name_customer2">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="address_customer">Dirección: *</label>
                <input type="text" name="address_customer2" value="Ciudad" class="form-control" required
                       id="address_customer2">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="address_customer">DPI:</label>
                <input type="text" name="dpi2" maxlength="13" class="form-control" id="dpi">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="address_customer">Email:</label>
                <input type="text" name="email2" class="form-control" id="email">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="address_customer">Teléfono:</label>
                <input type="text" name="phone" maxlength="8" class="form-control" id="phone">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="modal-footer">
                <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn  btn-primary" id="btnSaveCustomer">Guardar</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!--  Fin del modal new customer-->

  <!-- Modal exists customer -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title ">Error!!!!!!</h4>
        </div>
        <div class="modal-body">
          <p>Ya existe un empleado con ese nombre.</p>
        </div>
        <div class="modal-footer bg-danger">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>

    </div>
  </div>
@endsection
@section('footer_scripts')
  {{-- FORMATO DE MONEDAS --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>

  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
  <script type="text/javascript" src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
          type="text/javascript"></script>
  <script type="text/javascript " src="{{ asset('assets/js/route/validations.js')}} "></script>
  <script language="javascript" type="text/javascript"
          src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
  <script type="text/javascript"
          src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
  <script type="text/javascript">
      $(".date").datetimepicker({
          sideBySide: true,
          locale: 'es',
          format: 'DD/MM/YYYY',
          defaultDate: new Date()
      }).parent().css("position :relative");
      $('.js-example-basic-single').select2();
      $(document).ready(function () {
          var cleave = new Cleave('.money', {
              numeral: true,
              numeralPositiveOnly: true,
              numeralThousandsGroupStyle: 'thousand'
          });
          $('#frmAdd')
              // .find('[name="customer_id"]')
              //       .select2()
              //       // Revalidate the color when it is changed
              //       .change(function(e) {
              //           $('#frmAdd').bootstrapValidator('revalidateField', 'customer_id');
              //       })
              //       .end()
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
                                  message: 'Debe ingresar nombre.'
                              }
                          }
                      },

                      customer_id: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe seleccionar un cliente.'
                              }
                          }
                      },
                      type_id: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe seleccionar el tipo de proyecto.'
                              }
                          }
                      },
                      code: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe ingresar código de proyecto.'
                              }
                          }
                      },
                      description: {
                          validators: {
                              notEmpty: {
                                  message: 'Debe ingresar una descripción del proyecto.'
                              }
                          }
                      }
                      // name_customer: {
                      //   validators: {
                      //     notEmpty: {
                      //       message: 'Debe seleccionar un cliente.'
                      //     }
                      //   }
                      // }
                  }
              }).on('change', '[name="customer_id"]', function () {
              console.log('cambio');
              $('#frmAdd').bootstrapValidator('revalidateField', 'customer_id');
          })
          ;

          $.fn.select2.defaults.set("width", "100%");
          $('select').select2({
              allowClear: true,
              theme: "bootstrap",
              placeholder: "Buscar"
          });
          document.getElementById("idFormNewCustomer").onkeypress = function (e) {
              var key = e.charCode || e.keyCode || 0;
              if (key == 13) {
                  e.preventDefault();
              }
          }
      });
      btnSaveCustomer = document.getElementById('btnSaveCustomer');
      btnSaveCustomer.addEventListener('click', function () {
          nit_customer2 = document.getElementById('nit_customer2');
          name_customer2 = document.getElementById('name_customer2');
          var address_customer2 = document.getElementById('address_customer2');

          if (nit_customer2.value == "") {
              nit_customer2.focus();
          } else if (name_customer2.value == "") {
              name_customer2.focus();
          } else if (address_customer2.value == "") {
              address_customer2.focus();
          } else {
              $('#modal-2 .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;margin-left: 35%;margin-top: 8%;"></i></span>');
              // alert("Si se puede guardar el campo");
              // console.log("Llama Ajax");
              $.ajax({
                  type: "post",
                  url: '{{url('/customers/addCustomerAjaxPos')}}',
                  data: {
                      _token: '{{csrf_token()}}',
                      'nit': $('#nit_customer2').val(),
                      'name': $('#name_customer2').val(),
                      'dpi': $('#dpi').val(),
                      'email': $('#email').val(),
                      'phone': $('#phone').val(),
                      'address': address_customer2.value,
                  },
                  success: function (data) {
                      $('#span-loading').remove();
                      if ((data.errors)) {
                          console.log("existe un error revisar");
                      } else {
                          // console.log(data);
                          if (data == "Ya existe un cliente con ese nombre") {
                              toastr.error("Cliente existente, por favor verifique.");
                              // alert("No se puede agregar ya existe un cliente con ese nombre");
                              $("#name_customer2").focus();
                          } else {
                              $("#customer_id").append('<option value="' + data.id + '" selected="selected">' + data.nit_customer + ' | ' + data.name + '</option>').trigger('change');
                              document.getElementById('nit_customer2').value = 'C/F';
                              document.getElementById('name_customer2').value = '';
                              document.getElementById('address_customer2').value = '';
                              document.getElementById('phone').value = '';

                              // var id=data.id;
                              // var name=data.name;
                              // document.getElementById('customer_id').value=id;
                              // document.getElementById('name_customer').value=name;
                              // $('#table_customers').append("<tr><td>" + data.id + "</td><td>" + data.nit_customer+ "</td><td>" + data.name + "</td><td></td><td></td><td><button  type='button' name='button' class='btn btn-primary btn-xs' id='name_"+data.name+"/"+data.id+"' onclick='add_customers(this);' data-dismiss='modal'><span class='glyphicon glyphicon-check' aria-hidden='true'></span></button>"+"</td></tr>");

                              $("#modal-2").hide();
                              $(".modal-backdrop").remove();
                          }
                          // console.log("Aqui ya lo debio ocultar, se intenta nuevamente  ");
                          // $('#modal-2').hide();
                      }
                  }
              });
          }

      });

      function add_customers(value_receiving) {
          var customer_id = document.getElementById('customer_id');
          var name_customer = document.getElementById('name_customer');
          var name = value_receiving.id.split("_");
          var name_id = name[1].split("/");
          // alert(name_id[0]+' '+name_id[1]);
          customer_id.value = name_id[1];
          name_customer.value = name_id[0];

      }
  </script>
@endsection
