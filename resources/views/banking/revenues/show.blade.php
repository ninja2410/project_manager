@extends('layouts/default')
@section('title',trans('revenues.revenue_detail'))
@section('page_parent',trans('revenues.banks'))
@section('header_styles')
  <!-- Validaciones -->
  <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
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
              {{trans('revenues.revenue_detail')}}
            </h3>
            <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
          </div>

          <div class="panel-body">


            <div class="panel-body">
              <div class="col-md-2">
                <div class="panel-body">
                  <a href="{{ URL::previous() }}" class="btn btn-danger btn-large button-block">
                    {{-- <a href="{{ url('/banks/revenues') }}" class="btn btn-danger btn-large button-block"> --}}
                    <span class="livicon" data-name="undo" data-size="14" data-loop="true" data-c="#fff"
                          data-hc="white">&nbsp;&nbsp;</span>
                    Regresar
                  </a>
                  <br>
                  <br>
                  @if (isset($revenue->serie_id))
                    <a href="{{ URL::to('banks/revenues/print_voucher/' . $revenue->id.'/false' ) }}" class="btn btn-info btn-large button-block">
                      {{-- <a href="{{ url('/banks/revenues') }}" class="btn btn-danger btn-large button-block"> --}}
                      <span class="livicon" data-name="pen" data-size="14" data-loop="true" data-c="#fff"
                            data-hc="white">&nbsp;&nbsp;</span>
                      {{trans('revenues.print')}}
                    </a>
                  @endif
                  <br>
                  @if (strlen($revenue->deposit)==0)
                    <br>
                    <a class="btn btn-default" href="#" data-toggle="tooltip"
                       data-original-title="Agregar depósito" onclick="setRevenue('{{$revenue->id}}')">
                      <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;<br> {{trans('revenues.add_deposit_no')}}
                    </a>
                  @endif
                </div>
              </div>
              <div class="col-md-10">
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="users">
                      <tr>
                        <td>Fecha de ingreso</td>
                        <td>
                          <p class="user_name_max">{{ $revenue->paid_at }}</p>
                        </td>

                      </tr>
                      <tr>
                        <td>Monto</td>
                        <td>
                          <p class="user_name_max">@money($revenue->amount)</p>
                        </td>

                      </tr>
                      <tr>
                        <td>{{trans('revenues.account')}}</td>
                        <td>
                          {{ $revenue->account->account_name.' - '.$revenue->account->account_number }}
                        </td>
                      </tr>
                      <tr>
                        <td>
                          {{trans('revenues.invoice')}}
                        </td>
                        <td>
                          {{!empty($revenue->invoice->serie->document->name) ? $revenue->invoice->serie->document->name: trans('general.na')}}
                          {{!empty($revenue->invoice->serie->name) ? $revenue->invoice->serie->name: ''}}
                          {{!empty($revenue->invoice->correlative) ? $revenue->invoice->correlative: ''}}
                        </td>
                      </tr>
                      <tr>
                        <td>
                          {{trans('revenues.customer')}}
                        </td>
                        <td>
                          {{!empty($revenue->customer->name) ? $revenue->customer->name : trans('general.na')}}
                        </td>
                      </tr>
                      <tr>
                        <td>{{trans('revenues.payment_method')}}</td>
                        <td>
                          <strong>{{ $revenue->pago->name }}</strong>
                        </td>
                      </tr>
                      <tr>
                        <td>{{trans('revenues.bank_name')}}</td>
                        <td>
                          {{!empty($revenue->bank_name) ? $revenue->bank_name : trans('general.na')}}
                        </td>
                      </tr>
                      <tr>
                        <td>{{trans('revenues.same_bank')}}</td>
                        <td>
                          {{!empty($revenue->same_bank) ? ($revenue->same_bank==1?"Si":"No") : trans('general.na')}}
                        </td>
                      </tr>

                      <tr>
                        <td>{{trans('revenues.card_name')}}</td>
                        <td>
                          {{!empty($revenue->card_name) ? $revenue->card_name : trans('general.na')}}
                        </td>
                      </tr>
                      <tr>
                        <td>{{trans('revenues.card_number')}}</td>
                        <td>
                          {{!empty($revenue->card_number) ? $revenue->card_number : trans('general.na')}}
                        </td>
                      </tr>

                      <tr>
                        <td>{{trans('revenues.description')}}</td>
                        <td>
                          {{ $revenue->description }}
                        </td>
                      </tr>
                      <tr>
                        <td>{{trans('revenues.deposit_no')}}</td>
                        <td>
                          {{ $revenue->deposit }}
                        </td>
                      </tr>
                      <tr>
                        <td>{{trans('revenues.reference')}}</td>
                        <td>
                          {{ $revenue->reference }}
                        </td>
                      </tr>
                      @if(isset($revenue->invoice_id))
                        <tr>
                          <td>{{trans('expenses.document')}}</td>
                          <td><a href="{{url('sales/complete/'.$revenue->invoice_id)}}" target="_blank"><span
                                      class="label label-sm label-success label-mini">Ver Documento</span></a></td>
                        </tr>
                      @endif
                      @if(isset($revenue->retention->revenue_origin_id))
                        <tr>
                          <td>{{trans('revenues.revenues_origin')}}</td>
                          <td>
                            {{ $revenue->retention->revenue_origin->description }} <a
                                    href="{{URL::to('banks/revenues/' . $revenue->retention->revenue_origin->id )}}"
                                    target="_blank"><span
                                      class="label label-sm label-success label-mini">Ver Detalles</span></a>
                          </td>
                        </tr>
                      @endif
                      <tr>
                        <td>{{trans('revenues.status')}}</td>
                        <td>
                          @if ($revenue->status==2)
                            <span class="label label-danger">{{ trans('revenues.canceled') }}</span>
                          @else
                            @if ($revenue->reconcilied==1)
                              <span class="label label-success">{{ trans('bank_expenses.active_status') }}</span> @else
                              <span class="label label-danger">{{ trans('bank_expenses.inactive_status') }}</span> @endif
                          @endif
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  @include('partials.bank-transactions.deposit_modal', ['view'=>'show'])
    <!-- </div> -->
  </section>
@endsection
@section('footer_scripts')
  {{-- FORMATO DE MONEDAS --}}
  <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
  <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
  <!-- Valiadaciones -->
  <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>

  {{--  CUSTOM CACAO--}}
  <script src="{{ asset('assets/js/revenues/addDeposit.js') }}" type="text/javascript"></script>
@stop
