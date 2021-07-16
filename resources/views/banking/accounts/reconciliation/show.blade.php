@extends('layouts/default')

@section('title',trans('reconciliation.show'))
@section('page_parent',trans('reconciliation.list'))

@section('header_styles')
@stop
@section('content')
    <style>
        table td {
            border-top: none !important;
        }

        .footer_summary {
            border-top: black 1px solid !important;
        }

        .money {
            text-align: right;
        }

        .report_summary {
            border-top: black 1px solid;
            border-bottom: black 3px double;
        }
    </style>
    <section class="content">
        <div class="" id="dvContents">
            <div class="row">
                <div class="col-md-8 col-md-offset-2" style="border:1px solid #e3e3e3;background-color: #f5f5f5;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="text-center">
                                <strong>{{!empty($parameters->name_company)?$parameters->name_company:trans('general.na') }}</strong><br>
                                {{ trans('general.address').' : '}} {{ !empty($parameters->address)?$parameters->address:trans('general.na') }}
                                <br>
                                {{ trans('general.phone_number').' : '}}{{ !empty($parameters->phone)?$parameters->phone:trans('general.na')}}
                                <h2>{{trans('reconciliation.show')}}</h2>
                            </div>
                        </div>
                        <br>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left">
                            <strong>{{trans('reconciliation.account')}}:
                                &nbsp;</strong> {{ $account->account_name  }}<br>
                            <strong>{{trans('accounts.number')}}:
                                &nbsp;</strong> {{ $account->account_number }}<br>
                            <strong>{{trans('accounts.account_type')}}:
                                &nbsp;</strong> {{ $account->type->name  }}<br>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left">

                            <strong>{{trans('accounts.user')}}:
                                {{$account->responsible->name}}<br>
                            </strong>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="text-center">
                                <h4><b>{{trans('reconciliation.details')}}</b></h4>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6" style="text-align:left">
                            <strong>{{trans('reconciliation.month')}}:
                                &nbsp;</strong> {{trans('months.'.$reconciliation->month)}}<br>
                            <strong>{{trans('reconciliation.year')}}:
                                &nbsp;</strong> {{ $reconciliation->year }}
                            <br>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6" style="text-align:left">
                            <strong>{{trans('reconciliation.user_created')}}: &nbsp;</strong>
                            {{$reconciliation->user->name.' '.$reconciliation->user->last_name}}<br>
                            <strong>{{trans('reconciliation.created_at')}}: &nbsp;</strong>
                            {{ date('d/m/Y', strtotime($reconciliation->created_at))  }}<br>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td><strong>{{trans('reconciliation.balance_state_real')}}</strong></td>
                                        <td class="money"><strong>@money($reconciliation->bank_balance)</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>(+){{trans('reconciliation.transit_revenue')}}</td>
                                        <td class="money">@money($reconciliation->transit_revenue)</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="footer_summary"></td>
                                        <td class="money footer_summary ">@money($reconciliation->bank_balance+$reconciliation->transit_revenue)</td>
                                    </tr>
                                    <tr>
                                        <td>(-){{trans('reconciliation.outsanding_payments')}}</td>
                                        <td></td>
                                        <td class="money">@money($reconciliation->outstanding_payments)</td>
                                    </tr>
                                    <tr class="summary">
                                        <td>{{trans('reconciliation.total_con')}}</td>
                                        <td></td>
                                        <td class="money footer_summary report_summary">@money($reconciliation->bank_balance+$reconciliation->transit_revenue-$reconciliation->outstanding_payments)</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('reconciliation.balance_system')}}</td>
                                        <td class="money">@money($reconciliation->start_balance)</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('reconciliation.total_revenues')}}</td>
                                        <td class="money">@money($reconciliation->transit_revenue+$reconciliation->recon_revenues)</td>
                                        <td class="money">@money($reconciliation->transit_revenue+$reconciliation->recon_revenues+$reconciliation->start_balance)</td>
                                    </tr>
                                    <tr>
                                        <td>(-){{trans('reconciliation.total_expenses')}}</td>
                                        <td class="footer_summary"></td>
                                        <td class="money footer_summary">@money($reconciliation->recon_expenses+$reconciliation->outstanding_payments)</td>
                                    </tr>
                                    <tr>
                                        <td>(+){{trans('reconciliation.voiced_payments')}}</td>
                                        <td></td>
                                        <td class="money">@money($cheques_vencidos)</td>
                                    </tr>
                                    <tr>
                                        <td>{{trans('reconciliation.total_con')}}</td>
                                        <td></td>
                                        <td class="money footer_summary report_summary">@money(($cheques_vencidos+$reconciliation->transit_revenue+$reconciliation->recon_revenues+$reconciliation->start_balance)
                                            -($reconciliation->recon_expenses+$reconciliation->outstanding_payments))</td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    @if ($reconciliation->comment!='')
                                        <strong>{{trans('reconciliation.comment')}}
                                            :</strong> {{$reconciliation->comment}}
                                    @endif
                                </div>
                            </div>
                            {{--                            table-responsive--}}
                        </div>
                        {{--                        col-md-12--}}
                    </div>
                </div>
            </div>
            <hr class="hidden-print"/>
        </div>
        <div class="row">
            <div class="col-md-4">&nbsp;</div>

            <div class="col-md-2">
                <button type="button" onclick="printReceipt();"
                        class="btn btn-info pull-right hidden-print">{{trans('reconciliation.print')}}</button>
            </div>
            <div class="col-md-2">
                <a href="{{ url("/bank_reconciliation/header") }}" type="button"
                   class="btn btn-danger hidden-print">{{trans('Cancelar')}}</a>
            </div>
            <div class="col-md-4">&nbsp;</div>
        </div>
        <br>
    </section>
@endsection
@section('footer_scripts')
    <script>
        function printReceipt() {
            var name_document = $('#name_document').text();
            var contents = $("#dvContents").html();
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({"position": "absolute", "top": "-1000000px"});
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title>' + name_document + '</title>');
            frameDoc.document.write('</head><body>');
            //Append the external CSS file.

            frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_reconciliation.css')}}" rel="stylesheet" type="text/css" />');
            //Append the DIV contents.
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 500);
        }
    </script>
@endsection
