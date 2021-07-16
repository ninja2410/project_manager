@extends('layouts/default')


@section('title',trans('credit.statement_full'))

@section('page_parent',trans('credit.credits'))

@section('header_styles')
{{-- date time picker --}}
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css" />

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop
@section('content')
<section class="content">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16" data-loop="true"
                        data-c="#fff" data-hc="white"></i>
                    {{trans('credit.statement_full').' : '.strtoupper($customer->name)}}
                </h4>
                <span class="pull-right clickable">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </span>
            </div>

            <div class="panel-body table-responsive">

                {!! Form::open(array('url'=>'credit/statement/'.$customer->id,'method'=>'get')) !!}
                @include('partials.statement_filter')
                {!! Form::close() !!}
                <table style="width:100%">
                    <tr>
                        <td style="text-align:center;">
                            <h4>{{trans('credit.statement_full').' del '.trans('credit.customer_name').' : '.strtoupper($customer->name)}}
                            </h4>
                            @if($status == '7')
                            <font id="nota">Nota: total de ventas pendientes de pago</font>
                            @else
                            @if($all==0)
                            <font id="nota">Nota: se muestran transacciones sin restricción de fecha</font>
                            @else
                            <font id="nota"> Nota: se muestran transacciones del {{date('d/m/Y',strtotime($fecha1))}} al
                                {{date('d/m/Y',strtotime($fecha2))}}</font>
                            @endif
                            @endif
                            <a class="btn btn-info" href="{{ URL::to('credit/' . $customer->id . '/edit') }}"
                                data-toggle="tooltip" data-original-title="Pagar">
                                <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;{{trans('credit.pay')}}
                            </a>
                        </td>
                        {{-- <td style="width:10%"></td>
                        <td style="text-align:left"><h4>{{trans('credit.balance').' : '}}@money($customer->balance)
                        </h4>
                        </td> --}}
                    </tr>
                </table>
                <br>
                <table class="table table-striped table-bordered compact" id="table1">
                    <thead>
                        <tr>
                            <th></th>
                            <th data-priority="2" style="width: 5%;">No.</th>
                            <th data-priority="2" style="width: 8%;text-align:center;">{{trans('credit.date')}}</th>
                            <th data-priority="3" style="width: 15%;text-align:center;">{{trans('credit.document')}}
                            </th>
                            <th data-priority="4" style="text-align:center;">{{trans('credit.bank_ptype')}}</th>
                            <th data-priority="4" style="text-align:center;">{{trans('credit.reference')}}</th>
                            <th data-priority="4" style="width: 8%;text-align:center;">{{trans('credit.due_date')}}</th>
                            <th style="width: 9%; text-align:center;" data-priority="5">{{trans('credit.amount')}}</th>
                            <th style="width: 9%; text-align:center;" data-priority="5">{{trans('credit.payment')}}</th>
                            {{-- <th style="width: 9%; text-align:center;">{{trans('credit.credit')}}</th> --}}
                            <th style="width: 9%; text-align:center;">{{trans('credit.balance')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $saldo = 0; $tipo_anterior=""; $saldo_acum=0;?>
                        @foreach($statement as $i=> $value)
                        <tr @if($value->type_doc == "FACT") style="font-weight:bold;" @endif>
                            <td></td>
                            <td>{{$i+1}}</td>
                            <td style="font-size:11px;">{{date('d/m/Y',strtotime($value->date))}}</td>
                            <td style="font-size:11px;"> {{-- Documento --}}
                                <a target="_blank" @if($value->type_doc == "FACT")
                                    href="{{route('completesale',$value->id.'?return=true')}}"
                                    @endif
                                    @if($value->type_doc == "RECB")
                                    href="{{url('credit/completeCredit_print_payment',$value->document_id)}}"
                                    @endif
                                    @if($value->type_doc == "NCRE")
                                    href="{{url('credit_note',$value->document_id)}}"
                                    @endif
                                    @if($value->type_doc == "REV")
                                    href="{{url('banks/revenues',$value->document_id)}}"
                                    @endif
                                    @if($value->type_doc == "EXP")
                                    href="{{url('banks/expenses',$value->document_id)}}"
                                    @endif
                                    data-toggle="tooltip" data-original-title="Ir a
                                    documento">{{$value->document_and_correlative}}
                                </a>
                            </td>
                            <td style="font-size:10px;">
                                @if($value->receipt_number!= null)
                                {{strtoupper($value->bank_name)}}
                                @else
                                {{ $value->payment_type }}
                                @endif
                            </td>
                            <td style="font-size:9px;"><a @if($value->type_doc == "RECB")
                                    href="{{URL::to('credit/completeCredit_print_payment',$value->revenue_id)}}"
                                    data-toggle="tooltip" data-original-title="Ver" @endif
                                    @if($value->type_doc == "NCRE")
                                    href="{{url('credit_note',$value->document_id)}}"
                                    @endif target="_blank"
                                    >{{ $value->receipt_number }}</a>
                            </td>
                            {{-- credit/completeCredit_print_payment/ --}}
                            <td style="font-size:10px;" @if($value->vencimiento<1) class="overdue" @endif
                                    style="font-size:10px;text-align:center">@if($value->due_date==null) N/A @else
                                    {{  date('d/m/Y', strtotime($value->due_date)) }}@endif</td>
                            <td style="text-align:right">@money($value->amount_debit)</td>
                            <td style="text-align:right">@money($value->payment)</td>
                            <?php 
                            if($value->type_doc == "FACT") {
                                $saldo = $value->amount_debit- $value->payment;                                
                            } else {
                                $saldo += $value->amount_debit- $value->payment;
                            } 
                            $saldo_acum+= $value->amount_debit- $value->payment;
                            ?>
                            <td style="text-align:right">@money($saldo)</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #d9d3d3;">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            {{-- <th ></th> --}}
                            <th colspan="2" style="text-align:right">TOTALES&nbsp;&nbsp;&nbsp;</th>
                            <th colspan="1" style="text-align:right"></th>
                            <th colspan="1" style="text-align:right"></th>
                            {{-- <th colspan="1" style="text-align:right"></th> --}}
                            <th colspan="1" style="text-align:right">@money($saldo_acum)</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
</section>
@endsection
@section('footer_scripts')
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript">
</script>


<script type="text/javascript">
    $(document).ready(function () {
          $('[data-toggle="tooltip"]').tooltip();
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


          setDataTable("table1", [7, 8], "{{asset('assets/json/Spanish.json')}}", $('#nota').text(), 20);

        $('input[type=checkbox][name=all]').change(function() {
            if ($(this).prop("checked")) {
                $('.fecha').show();
                console.log('1');
            }
            else {
                $('.fecha').hide();
                console.log('0');
            }
        });




        });
</script>
@stop