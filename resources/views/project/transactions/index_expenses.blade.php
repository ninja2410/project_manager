@extends('layouts/default')
@section('title',trans('bank_expenses.expenses'))
@section('page_parent',trans('project.project'))

@section('header_styles')
  {{-- date time picker --}}
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
@stop
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16"
                                                 data-loop="true" data-c="#fff" data-hc="white"></i>
              {{trans('bank_expenses.expenses')}} | {{$account->account_name}}
            </h4>
            <div class="pull-right">
              <a href="{{ URL::to('project/stages_project/'.$project->id) }}" class="btn btn-sm btn-danger"><span
                        class="glyphicon glyphicon-arrow-left"></span> {{trans('project.return')}} </a>
              <a href="{{ URL::to('project/expenses/create/'.$account->id) }}"
                 class="btn btn-sm btn-default"><span
                        class="glyphicon glyphicon-plus"></span> {{trans('Nuevo gasto de proyecto')}}
              </a>
            </div>
          </div>
          <div class="panel-body">
            <hr/>
            {!! Form::open(array('url'=>'project/expenses/'.$account->id,'method'=>'get')) !!}
            @include('partials.expenses_filter_project')
            {!! Form::close() !!}

            <table class="table table-striped table-bordered" id="table1">
              <thead>
              <tr>
                <th style="width: 5%;">No</th>
                <th>Fecha</th>
                {{--                @if (isset($account))--}}
                {{--                <th>Etapa</th>--}}
                {{--                @else--}}
                {{--                <th>Cuenta</th>--}}
                {{--                @endif--}}
                <th>Proveedor</th>
                <th>Categoria</th>
                <th>Forma pago</th>
                <th>Monto</th>
                <th>Etapa</th>
                <th>Descripción</th>
                <th>Acciones</th>
              </tr>
              </thead>
              <tbody>
              @foreach($expenses as $i => $value)
                <tr>
                  <td>{{$i+1}}</td>
                  <td>{{$value->paid_at}}</td>
                  <td>{{!empty($value->supplier->company_name) ? $value->supplier->company_name : trans('general.na')}}</td>
                  <td>{{$value->category->name}}</td>
                  <td>{{$value->pago->name}}</td>
                  <td>@money($value->amount)</td>
                  <td>{{(isset($value->stage->name)? $value->stage->name : 'N/A')}}</td>
                  <td>
                    {{$value->description}}
                    @if(isset($value->bill_id))
                      <a href="{{url('receivings/complete/'.$value->bill_id)}}" target="_blank"><span
                                class="label label-sm label-success label-mini">Ver Documento</span></a>
                    @endif
                  </td>
                  <td>
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                              data-toggle-position="left" aria-expanded="false">
                        {{-- <span class="caret"></span> --}}
                        <i class="fa fa-ellipsis-h"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                          <a class="btn btn-warning" href="{{ URL::to('banks/expenses_accounts/' . $value->id ) }}"
                             data-toggle="tooltip" data-original-title="Detalles">
                            <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Detalles
                          </a>
                        </li>
                        <li>
                          @if ($value->status !='Inactivo' && $anul)
                            {{-- <button type="button" class="btn btn-danger" onclick="showModalConf('{{$value->route.'/'.$value->id}}', '{{$value->description}}')">
                                <span class="glyphicon glyphicon-remove-circle"> Anular</span>
                            </button> --}}
                            <a type="button" class="btn btn-danger"
                               onclick="showModalConf('{{url('banks/expenses_accounts', $value->id)}}', 'Anular gasto')"
                               data-toggle="tooltip" data-original-title="Anular">
                              <span class="glyphicon glyphicon-remove-circle"> Anular</span>
                            </a>
                          @endif
                        </li>
                        {{--                                                <li>--}}
                        {{--                                                    <a class="btn btn-info" href="#" data-toggle="tooltip" data-original-title="Conciliar">--}}
                        {{--                                                        <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;{{trans('revenues.conciliate').' '.$value->id}}--}}
                        {{--                                                    </a>--}}
                        {{--                                                </li>--}}
                        {{-- <li class="divider"></li> --}}
                        {{-- <li>
                          <a href="#">
                            Separated link
                          </a>
                        </li> --}}
                      </ul>
                    </div>
                  </td>
                </tr>
              @endforeach
              </tbody>
              <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align:right">Total:</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    {{--Begin modal--}}
    <span class="table-remove">
                  <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal" role="dialog"
                       aria-labelledby="modalLabelfade"
                       aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-danger">
                          <h4 class="modal-title">Confirmación anulación de transacción</h4>
                        </div>
                        <div class="modal-body">
                          <div class="text-center">
                            <p id="description"></p>
                            <br> ¿Desea anular la transacción?
                              <hr>
                              <strong>Nota:</strong> Si la transacción a anular está asociada a un documento de compra o venta, el mismo quedará registrado al crédito.
                          </div>
                        </div>
                        <div class="modal-footer" style="text-align:center;">
                          {!! Form::open(array('class' => 'pull-right', 'id' => 'confirmFrm')) !!}
                          {!! Form::hidden('_method','DELETE') !!} {{-- test --}}
                                  <a href="#">
                                    <button type="submit" class="btn  btn-info">Aceptar</button>
                                  </a>
                                <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                          {!! Form::close() !!}
                        </div>
                      </div>
                    </div>
                  </div>
    {{--End modal--}}
  </section>
@endsection

@section('footer_scripts')
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
          type="text/javascript"></script>
  <script type="text/javascript">
      $(document).ready(function () {
          var dateNow = new Date();
          $("#start_date").datetimepicker({
              sideBySide: true,
              locale: 'es',
              format: 'DD/MM/YYYY',
              defaultDate: dateNow
          }).parent().css("position :relative");
          $("#end_date").datetimepicker({
              sideBySide: true,
              locale: 'es',
              format: 'DD/MM/YYYY',
              defaultDate: dateNow
          }).parent().css("position :relative");

          setDataTable("table1", [5], "{{ asset('assets/json/Spanish.json') }}");
      });

      function showModalConf(url, description) {
          $('#description').append('');
          $('#description').append(description);
          $('#confirmFrm').attr('action', url);
          $('#modal').modal('show');
      }
  </script>
@stop
