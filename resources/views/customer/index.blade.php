@extends('layouts/default')

@section('title',trans('customer.list_customers'))
@section('page_parent',trans('customer.customers'))

@section('header_styles')
  {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css') }}" /> --}}

  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}"/>
@stop
@section('content')
    <?php $permisos=Session::get('permisions'); $array_p = array_column(json_decode(json_encode($permisos), True), 'ruta');  ?>
    <section class="content">
      <div class="row">
        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16" data-loop="true"
                                                 data-c="#fff" data-hc="white"></i>
              {{trans('customer.list_customers')}}
            </h4>
            <div class="pull-right">
              @if(in_array('customers/create',$array_p))
                <a href="{{ URL::to('customers/create') }}" class="btn btn-sm btn-default"><span
                          class="glyphicon glyphicon-plus"></span> {{trans('customer.new_customer')}} </a>
              @endif
            </div>
          </div>

          <div class="panel-body table-responsive">
            {{--  --}}
            <div class="table-responsive-lg table-responsive-sm table-responsive-md">
              <table class="table table-striped table-bordered" id="table1">
                <thead>
                <tr>
                  <th></th>
                  {{--              <th data-priority="1001" style="width: 5%;">{{trans('No.')}}</th>--}}
                  <th>{{trans('customer.nit_customer')}}</th>
                  <th>{{trans('customer.customer_code')}}</th>
                  <th>{{trans('customer.name')}}</th>
                  <th>{{trans('customer.email')}}</th>
                  <th>{{trans('customer.phone_number')}}</th>
                  <th>{{trans('customer.has_credit')}}</th>
                  <th>{{trans('customer.positive_balance')}}</th>
                  <th>{{trans('customer.balance')}}</th>
                  <th>{{trans('customer.actions')}}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
          {{--Begin modal--}}
          <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modalDelete" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header bg-danger">
                  <h4 class="modal-title">Confirmación Eliminar</h4>
                </div>
                <div class="modal-body">
                  <div class="text-center">
                    <h4 id="nameCustomer"></h4>
                    <br>
                    ¿Desea eliminar cliente?
                  </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                  {!! Form::open(array('url' => '', 'class' => 'pull-right', 'id'=>'frmDelete')) !!}
                  {!! Form::hidden('_method', 'DELETE') !!}
                  <button type="submit" class="btn  btn-info">Aceptar</button>
                  <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                  {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>
          {{--End modal--}}
        </div>
      </div>
    </section>
@endsection
@section('footer_scripts')
  <script type="text/javascript">
      $(document).ready(function () {
          var table = $('#table1').DataTable({
              pageLength: 30,
              xscrollable:true,
              "language": {"url": "{{ asset('assets/json/Spanish.json') }}"},
              // "processing": true,
              "deferRender": true,
              "ajax": APP_URL + '/getCustomers',
              "columnDefs": [
                  {
                      "targets": [0],
                      "visible": false,
                      "searchable": false
                  }
              ],
              "columns": [
                  {"data": "id"},
                  {"data": "nit_customer"},
                  {"data": "customer_code"},
                  {"data": "name"},
                  {"data": "email"},
                  {"data": "phone_number"},
                  {"data": "max_credit_amount"},
                  {"data": "positive_balance"},
                  {
                      "data": "balance", render: function (data, type, row) {
                          return '<a '+(row.max_credit_amount > 0? 'href="{{url('credit/statement/')}}/'+row.id+'"' : '')+'> Q ' + cleanNumber(data).toFixed(2)+'</a>';
                      }
                  },
                  {
                      "defaultContent": "", render: function(data, type, row){
                          return '<div class="input-group-btn">' +
                              '                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">' +
                              '                        <i class="fa fa-ellipsis-h"></i>' +
                              '                    </button>' +
                              '                    <ul class="dropdown-menu dropdown-menu-right">' +
                              '                        <li>' +
                              '                            <a class="btn btn-success" href="{{url('customers/profile/')}}/'+ row.id +'">{{trans('customer.show')}}</a>' +
                              '                        </li>' +
                              '                        <li>' +
                              '                            <a class="btn btn-info" @if(in_array('customers/edit',$array_p)) href="{{ url('customers/')}}/'+row.id+'/edit" @else disabled="disabled" @endif >{{trans('general.edit')}}</a>' +
                              '                        </li>' +
                              '                        <li>' +
                              '                            <a class="btn btn-primary" @if(in_array('customers/statement',$array_p)) href="{{ url('credit/statement/')}}/'+row.id+'" @else disabled="disabled" @endif >{{trans('credit.statement_full')}}</a>' +
                              '                        </li>' +
                              '                        <li>' +
                              '                            <a class="btn btn-danger" @if(in_array('customers/delete',$array_p)) onclick="modalDelete('+row.id+',\''+(row.name)+'\')" @else disabled="disabled" @endif>{{trans('general.delete')}}</a>' +
                              '                        </li>' +
                              '                    </ul>' +
                              '                </div>';
                      }

                  }
              ],
              dom: 'Bfrtip',
              buttons: [
                  {
                      extend: 'collection',
                      text: 'Exportar/Imprimir',
                      buttons: [
                          {
                              extend: 'copy',
                              text: 'Copiar',
                              title: document.title,
                              exportOptions: {
                                  columns: 'th:not(:last-child)'
                              }
                          },
                          {
                              extend: 'excel',
                              title: document.title,
                              exportOptions: {
                                  columns: 'th:not(:last-child)'
                              }
                          },
                          {
                              extend: 'pdf',
                              title: document.title,
                              exportOptions: {
                                  columns: 'th:not(:last-child)'
                              },
                              customize: function(doc) {
                                  doc.styles.tableHeader.fontSize = 8;
                                  doc.defaultStyle.fontSize = 6;
                                  doc.styles.tableFooter.fontSize = 8;
                              }
                          },
                          {
                              extend: 'print',
                              text: 'Imprimir',
                              title: document.title,
                              exportOptions: {
                                  columns: 'th:not(:last-child)'
                              }
                          }

                      ]
                  }
              ],
          });
      });
      let modalDelete = function(id,name){
          $('#nameCustomer').text(name);
          $('#frmDelete').attr("url", APP_URL+'/customers/'+id);
          $('#frmDelete').attr("action", APP_URL+'/customers/'+id);
          $('#modalDelete').modal('show');
      };
      $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
      });
  </script>
@stop
