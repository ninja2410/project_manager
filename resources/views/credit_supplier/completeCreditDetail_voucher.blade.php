@extends('layouts/default')

@section('title',trans('Impresión abono a proveedores'))
@section('page_parent',trans('credit_supplier.accounts'))

@section('header_styles')
@stop
@section('content')

    <style>
        table td {
            border-top: none !important;
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
                                <h3>{{trans('revenues.revenue_recepit')}}</h3>
                            </div>
                        </div>
                        <br>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left">
                            <strong>{{trans('credit_supplier.customer_name')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->credit->supplier->company_name}}<br>
                            <strong>{{trans('customer.nit')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->credit->supplier->nit_supplier}}<br>
                            <strong>{{trans('customer.address')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->credit->supplier->address}}<br>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left">

                            <strong>{{trans('customer.total_balance')}}: &nbsp;</strong>
                            @money($detalleCredito[0]->credit->supplier->balance)<br>
                            <strong>{{trans('customer.phone_number')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->credit->supplier->phone_number}}<br>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="text-center">
                                <h4><b>{{trans('revenues.payment_detail')}}</b></h4>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6" style="text-align:left">
                            <strong>{{trans('revenues.payment_method')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->payment->pago->name}}<br/>
                            <strong>{{trans('credit_supplier.account_paymemt')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->payment->account->account_name.' - '.$detalleCredito[0]->payment->account->account_number}}
                            <br/>
                            <strong>{{trans('revenues.amount')}}: &nbsp;</strong>
                            @money($detalleCredito[0]->payment->amount)<br/>                            
                            {{-- @if($detalleCredito[0]->payment->pago->id==4) <strong>{{trans('revenues.card_name')}}:
                                &nbsp;</strong>   {{ $detalleCredito[0]->payment->card_name}}<br/> @endif --}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6" style="text-align:left">
                            <strong>{{trans('revenues.paid_at')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->payment->paid_at}}<br/>
                            <strong>{{trans('revenues.ref')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->payment->reference}}<br/>
                            <strong>{{trans('revenues.description')}}:
                                &nbsp;</strong> {{ $detalleCredito[0]->payment->description}}<br/>
                            
                            {{-- @if($detalleCredito[0]->payment->pago->id==4) <strong>{{trans('revenues.card_number')}}:
                                &nbsp;</strong>   {{ $detalleCredito[0]->revenue->card_number}}<br/> @endif --}}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="text-center">
                                <h4><b>Detalle de abono</b></h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <br>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><strong>{{trans('Factura')}}</strong></th>
                                        <th style="text-align:center"><strong>{{trans('Fecha de pago')}}</strong></th>
                                        <th style="text-align:right"><strong>{{trans('Monto factura')}}</strong></th>
                                        <th style="text-align:right"><strong>{{trans('Abono')}}</strong></th>
                                        <th style="text-align:right"><strong>{{trans('Saldo')}}</strong></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php  $total = 0; $saldo = 0; ?>
                                    @foreach($detalleCredito as $i=> $value2)
                                        <tr>
                                            <td>{{$value2->credit->invoice->serie->document->name.' '.$value2->credit->invoice->serie->name.' - '.$value2->credit->invoice->correlative}}</td>
                                            <td style="text-align:center">{{ date('d/m/Y', strtotime($value2->paid_date)) }}</td>
                                            <td style="text-align:right">@money($value2->credit->invoice->total_cost)
                                            </td>
                                            <td style="text-align:right">@money($value2->amount)</td>
                                            <td style="text-align:right">
                                                @money($value2->credit->invoice->total_cost-$value2->credit->invoice->total_paid)
                                            </td>

                                            <td>&nbsp;
                                            </td>
                                            <?php $total += $value2->credit->invoice->total_cost;
                                            $saldo += $value2->credit->invoice->total_cost - $value2->credit->invoice->total_paid;
                                            ?>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr style="padding-top:10px">
                                        <td colspan="1">&nbsp;</td>
                                        <td style="text-align:right;border-top: 2px solid #ddd !important; "
                                        "><b>Totales:&nbsp;</b></td>
                                        <td colspan="1" style="text-align:right;border-top: 2px solid #ddd !important;">
                                            <b>@money($total)</b></td>
                                        <td colspan="1" style="text-align:right;border-top: 2px solid #ddd !important;">
                                            <b>@money($detalleCredito[0]->payment->amount)</b></td>
                                        <td colspan="1" style="text-align:right;border-top: 2px solid #ddd !important;">
                                            <b>@money($saldo)</b></td>

                                        <td style="border-top: 2px solid #ddd !important;">&nbsp;</td>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>{{-- table-responsive --}}
                        </div> {{-- col-md-12 --}}
                    </div>{{-- row --}}
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-md-12">
                </div>
            </div> --}}
            <hr class="hidden-print"/>

        </div>
        <div class="row">
            <div class="col-md-4">&nbsp;</div>

            <div class="col-md-2">
                <button type="button" onclick="printReceipt();"
                        class="btn btn-info pull-right hidden-print">{{trans('Imprimir comprobante')}}</button>
            </div>
            <div class="col-md-2">
                <a href="{{ url("/credit") }}" type="button"
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

            frameDoc.document.write('<link href="{{ asset('assets/css/pages/print_receipt.css')}}" rel="stylesheet" type="text/css" />');
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
