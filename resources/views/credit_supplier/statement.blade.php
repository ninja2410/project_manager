@extends('layouts/default')

@section('title',trans('credit.statement_full'))
@section('page_parent',trans('credit.credits'))

@section('header_styles')
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}"/>
@stop
@section('content')
    <section class="content">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16"
                                                         data-loop="true" data-c="#fff" data-hc="white"></i>
                        {{trans('credit.statement_full').' : '.strtoupper($customer->company_name)}}
                    </h4>
                    <span class="pull-right clickable">
                    <i class="glyphicon glyphicon-chevron-up"></i>
                </span>
                </div>

                <div class="panel-body table-responsive">
                    
                    {!! Form::open(array('url'=>'credit_suppliers/statement/'.$customer->id,'method'=>'get')) !!}
                    @include('partials.statement_filter')
                    {!! Form::close() !!}
                    <table style="width:100%">
                        <tr>
                            <td style="text-align:center;">
                                <h4>{{trans('credit.statement_full').' del '.trans('credit_supplier.customer_name').' : '.strtoupper($customer->company_name)}}</h4>
                            </td>
                        </tr>
                        <tr><td style="text-align:center;">
                            <a class="btn btn-info" href="{{ URL::to('credit_suppliers/' . $customer->id . '/edit') }}" data-toggle="tooltip" data-original-title="Pagar">
                                <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;{{trans('credit_supplier.pay')}}
                            </a>
                        </td></tr>
                    </table>
                    <br>
                    <table class="table table-striped table-bordered compact" id="table1">
                        <thead>
                        <tr>
                            <th></th>
                            <th data-priority="2" style="width: 5%;">{{trans('credit.date')}}</th>
                            <th data-priority="3" style="width: 25%;">{{trans('credit.document')}}</th>
                            {{-- <th data-priority="4">{{trans('credit.payment_type')}}</th> --}}
                            <th data-priority="4">{{trans('credit.reference')}}</th>
                            <th data-priority="4" style="width: 10%;">{{trans('credit.due_date')}}</th>
                            {{-- <th data-priority="4">{{trans('credit.status')}}</th> --}}
                            <th style="width: 9%;">{{trans('credit.amount')}}</th>
                            <th style="width: 9%;" data-priority="5">{{trans('credit.payment')}}</th>
                            <th style="width: 9%;">{{trans('credit.balance')}}</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $saldo = 0; $tipo_anterior=""; $saldo_acum=0;?>
                        @foreach($statement as $i=> $value)
                            <tr @if($value->type_doc == "FACT") style="font-weight:bold;" @endif>
                                <td></td>
                                <td style="font-size:12px;">{{ $value->date }}</td>
                                <td style="font-size:12px;">
                                    <a @if($value->type_doc == "FACT")
                                    href="{{route('completereceivings',$value->id)}}" 
                                    @endif
                                    @if($value->type_doc == "RECB")
                                    href="{{URL::to('credit_suppliers/printPayment',$value->expense_id)}}"
                                    @endif
                                    data-toggle="tooltip" data-original-title="Ir a documento">{{$value->document_and_correlative}}</a>                                    
                                </td>
                                <td style="font-size:10px;"><a  href="{{URL::to('credit_suppliers/printPayment',$value->expense_id)}}" data-toggle="tooltip" data-original-title="Ver">@if($value->type_doc == "RECB"){{ $value->payment_type }}@endif</a></td>
                                <td style="font-size:12px;" @if($value->vencimiento<1) style="background-color: #f0b9b6;"
                                    @endif>{{$value->due_date != ''? date('d/m/Y', strtotime($value->due_date)):''}}</td>                                
                                <td style="text-align:right">@money($value->amount_debit)</td>
                                <td style="text-align:right">@money($value->amount_credit)</td>
                                <?php 
                                    if($value->type_doc == "FACT") {
                                        $saldo = $value->amount_debit- $value->amount_credit;                                
                                    } else {
                                        $saldo += $value->amount_debit- $value->amount_credit;
                                    } 
                                    $saldo_acum+= $value->amount_debit- $value->amount_credit;
                                ?>
                                <?php //$saldo += $value->amount_debit - $value->amount_credit;?>
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
                            {{-- <th></th> --}}
                            <th colspan="1" style="text-align:right">TOTALES</th>
                            <th colspan="1" style="text-align:right"></th>
                            <th colspan="1" style="text-align:right"></th>
                            <th colspan="1" style="text-align:right">@money($saldo_acum)</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>


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

            setDataTable("table1", [5,6], "{{asset('assets/json/Spanish.json')}}",$('#nota').text(),20);

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
