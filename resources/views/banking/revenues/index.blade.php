@extends('layouts/default')
@section('title',trans('menu.deposits'))
@section('page_parent',trans('revenues.banks'))

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
            <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16" data-loop="true"
                                                 data-c="#fff" data-hc="white"></i>
              {{trans('menu.deposits')}}
            </h4>
            <div class="pull-right">
              <a href="{{ URL::to('banks/deposits/create') }}" class="btn btn-sm btn-default"><span
                        class="glyphicon glyphicon-plus"></span> {{trans('menu.deposits_create')}} </a>
            </div>
          </div>
          <div class="panel-body">

            {!! Form::open(array('url'=>'banks/deposits','method'=>'get')) !!}
            @include('partials.revenues+filter')
            {!! Form::close() !!}

            <table class="table table-striped table-bordered" id="table1">
              <thead>
              <tr>
                <th></th>
                <th style="width: 5%;">No</th>
                <th>Fecha</th>
                <th>Cuenta</th>
                <th>Forma Pago</th>
                <th style="width: 9%;">{{trans('revenues.receipt_number')}}</th>
                <th style="width: 9%;">{{trans('revenues.deposit_no')}}</th>
                <th>Descripcion</th>
                <th>Monto</th>
                <th style="width: 5%;">Estado</th>
                <th style="width: 5%;">Acciones</th>
              </tr>
              </thead>
              <tbody>
              @foreach($revenues as $i => $value)
                <tr>
                  <td></td>
                  <td>{{$i+1}}</td>
                  <td style="font-size:12px">{{$value->paid_at}}</td>
                  <td style="font-size:12px">{{$value->account->account_name}}</td>
                  <td style="font-size:12px">{{!empty($value->pago->name) ? $value->pago->name : trans('general.na')}}</td>
                  <td style="font-size:12px">{{$value->receipt_number}}</td>
                  <td style="font-size:12px">
                    @if(strlen($value->deposit)==0)
                    <a style="color:red;" href="#" data-toggle="tooltip"
                               data-original-title="Agregar # depósito" onclick="setRevenue('{{$value->id}}')"><u>Agregar #</u></a>
                    @else
                    {{$value->deposit}}
                    @endif
                  </td>
                  <td style="font-size:12px">{{$value->description}}</td>
                  <td style="text-align: right;">@money($value->amount)</td>
                  <td style="font-size:12px">
                    @if ($value->status==4)
                      <span class="label label-success">{{ trans('bank_expenses.active_status') }}</span> @else
                      <span class="label label-danger">{{ trans('bank_expenses.inactive_status') }}</span> @endif
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
                          <a class="btn btn-warning" href="{{ URL::to('banks/revenues/' . $value->id ) }}"
                             data-toggle="tooltip" data-original-title="Detalles">
                            <span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;Detalles
                          </a>
                        </li>
                        <li>
                          <a class="btn btn-info" href="#" data-toggle="tooltip" data-original-title="Conciliar">
                            <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;{{trans('revenues.conciliate').' '.$value->id}}
                          </a>
                        </li>
                        @if (strlen($value->deposit)==0)
                          <li>
                            <a class="btn btn-default" href="#" data-toggle="tooltip"
                               data-original-title="Agregar depósito" onclick="setRevenue('{{$value->id}}')">
                              <span class="glyphicon glyphicon-tag"></span>&nbsp;&nbsp;{{trans('revenues.add_deposit_no')}}
                            </a>
                          </li>
                        @endif
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
                <th colspan="7" style="text-align:right">Total:</th>
                <th colspan="4"></th>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    @include('partials.bank-transactions.deposit_modal', ['view'=>'index'])
  </section>
@endsection

@section('footer_scripts')
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
          type="text/javascript"></script>
  {{-- FORMATO DE MONEDAS --}}
  {{-- <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script> --}}
  <!-- Valiadaciones -->
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>

{{--  CUSTOM CACAO--}}
  <script src="{{ asset('assets/js/revenues/addDeposit.js') }}" type="text/javascript"></script>

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
          setDataTable("table1", [8], "{{asset('assets/json/Spanish.json')}}");
      });

  </script>
@stop
