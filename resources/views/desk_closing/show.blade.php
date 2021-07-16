@extends('layouts/default')

@section('title',trans('desk_closing.resume'))
@section('page_parent',trans('desk_closing.desk'))


@section('header_styles')

<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
@stop

@section('content')

<section class="content" id="print">
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16"
                            data-loop="true" data-c="#fff" data-hc="white"></i>
                        {{trans('desk_closing.resume')}}
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('caja', trans('desk_closing.desk').':') !!}
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="calculator" data-size="16" data-c="#555555"
                                            data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input type="text" class="form-control" value="{{$desk->account_name}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('documento', trans('desk_closing.document').':') !!}
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="notebook" data-size="16" data-c="#555555"
                                            data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{$desk->name.' '.$desk->correlative}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('fechaInicial', trans('desk_closing.startDate').':') !!}
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                            data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input type="text" class="form-control" value="{{$desk->startDate}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('fechaFinal', trans('desk_closing.finalDate').':') !!}
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                            data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input type="text" class="form-control" value="{{$desk->finalDate}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('montoInicial', trans('desk_closing.startAmount').':') !!}
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="money" data-size="16" data-c="#555555"
                                            data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input type="text" class="form-control" value="@money($desk->initial_balance)"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('montoFinal', trans('desk_closing.finalAmount').':') !!}
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="money" data-size="16" data-c="#555555"
                                            data-hc="#555555" data-loop="true"></i>
                                    </div>
                                    <input type="text" class="form-control" value="@money($desk->final_balance)"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="Movimientos">
        <div class="col-md-12">
            <div class="panel panel-success" id="hidepanel1">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="arrow-right" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="white"></i>
                        Movimientos
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered" id="efectivo">
                                <thead>
                                    <th>Fecha</th>
                                    <th>Forma pago</th>
                                    <th>Descripción</th>
                                    <th>Referencia</th>
                                    <th>Estado</th>
                                    <th>{{trans('credit.debit')}}</th>
                                    <th>{{trans('credit.credit')}}</th>
                                    <th>{{trans('credit.balance')}}</th>
                                </thead>
                                <tbody>
                                <?php $saldo = 0; $credito=0;$debito=0;?>
                                @foreach($cuentas as $i => $value)
                                    <tr>
                                        <td>{{date('d/m/y',strtotime($value->paid_at))}}</td>
                                        {{-- <td>
                                            <span @if ($value->tipo=='Ingreso') class="label label-primary"
                                                @else class="label label-danger" @endif>{{ $value->tipo }}</span>
                                        </td> --}}
                                        <td>
                                            {{$value->payment_method}}
                                        </td>
                                        <td>{{$value->description}}</td>
                                        <td>{{$value->reference}}</td>
                                        <td style="font-size:12px;">
                                            @if($value->status == 'Inactivo')
                                                <span class="label label-danger">{{$value->status}}</span>
                                            @endif
                                            @if($value->status == 'No Conciliado')
                                                <span class="label label-info">{{$value->status}}</span>
                                            @endif
                                            @if($value->status == 'Conciliado')
                                                <span class="label label-success">{{$value->status}}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                        @if ($value->tipo=='Ingreso')
                                            @if ($value->status!='Inactivo')
                                                    <?php $credito=$value->amount; ?>
                                                @money($value->amount)
                                            @else
                                                @money(0)
                                            @endif
                                        @endif
                                        </td>
                                        <td class="text-right">
                                        @if ($value->tipo!='Ingreso')
                                            @if ($value->status!='Inactivo')
                                                    <?php $debito=$value->amount; ?>
                                                @money($value->amount)
                                            @else
                                                @money(0)
                                            @endif
                                        @endif
                                        </td>
                                        <?php $saldo += $credito - $debito; $credito=0;$debito=0;?>
                                        <td class="text-right">@money($saldo)</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <h3 class="text-center" id="TitleText"><strong><u>DESGLOCE DE FORMAS DE PAGO</u></strong></h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" id="hidepanel2">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="money" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="white"></i>
                        Efectivo
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon @if ($desk->cash_amount>0) glyphicon-chevron-up @else glyphicon-chevron-down @endif"></i>
                    </span>
                </div>
                <div class="panel-body" @if ($desk->cash_amount>0) style="display: block;" @else style="display: none;" @endif id="Efectivo">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered" id="efectivo">
                                <thead>
                                    <th style="width: 5%;">No.</th>
                                    <th style="text-align: center">Nombre</th>
                                    <th style="text-align: center">Cantidad</th>
                                    <th style="text-align: center">Total</th>
                                </thead>
                                <tbody>
                                    @foreach ($efectivo as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{$value->name}}</td>
                                        <td class="text-center">{{$value->money_quanity}}</td>
                                        <td class="text-right">@money($value->amount)</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right"><strong>@money($desk->cash_amount)</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success" id="hidepanel3">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="bank" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="white"></i>
                        Cheque
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon @if ($desk->check_amount>0) glyphicon-chevron-up @else glyphicon-chevron-down @endif"></i>
                    </span>
                </div>
                <div class="panel-body" @if ($desk->check_amount>0) style="display: block;" @else style="display: none;" @endif id="Cheque">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th style="text-align: center">No. Cheque</th>
                                    <th style="text-align: center">Descripción</th>
                                    <th style="text-align: center">Banco</th>
                                    <th style="text-align: center">Monto</th>
                                </thead>
                                <tbody>
                                    @foreach ($cheque as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$value->reference}}</td>
                                        <td class="text-center">{{$value->description}}</td>
                                        <td class="text-center">{{$value->bank_name}}</td>
                                        <td class="text-right">@money($value->amount)</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right"><strong>@money($desk->check_amount)</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" id="hidepanel4">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="piggybank" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="white"></i>
                        Depósito
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon @if ($desk->deposit_amount>0) glyphicon-chevron-up @else glyphicon-chevron-down @endif"></i>
                    </span>
                </div>
                <div class="panel-body" @if ($desk->deposit_amount>0) style="display: block;" @else style="display: none;" @endif id="Deposito">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th style="text-align: center">No. Depósito</th>
                                    <th style="text-align: center">Descripción</th>
                                    <th style="text-align: center">Banco</th>
                                    <th style="text-align: center">Monto</th>
                                </thead>
                                <tbody>
                                    @foreach ($deposito as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$value->reference}}</td>
                                        <td class="text-center">{{$value->description}}</td>
                                        <td class="text-center">{{$value->bank_name}}</td>
                                        <td class="text-right">@money($value->amount)</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right"><strong>@money($desk->deposit_amount)</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success" id="hidepanel5">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="credit-card" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="white"></i>
                        Tarjeta
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon @if ($desk->deposit_amount>0) glyphicon-chevron-up @else glyphicon-chevron-down @endif"></i>
                    </span>
                </div>
                <div class="panel-body" @if ($desk->deposit_amount>0) style="display: block;" @else style="display: none;" @endif id="Tarjeta">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th style="text-align: center">No. Transacción</th>
                                    <th style="text-align: center">Descripción</th>
                                    <th style="text-align: center">Nombre Tarjeta</th>
                                    <th style="text-align: center">Últimos 4 dígitos</th>
                                    <th style="text-align: center">Monto</th>
                                </thead>
                                <tbody>
                                    @foreach ($tarjeta as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$value->reference}}</td>
                                        <td class="text-center">{{$value->description}}</td>
                                        <td class="text-center">{{$value->card_name}}</td>
                                        <td class="text-center">{{$value->card_number}}</td>
                                        <td class="text-right">@money($value->amount)</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right"><strong>@money($desk->card_amount)</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" id="hidepanel6">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="paper-plane" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="white"></i>
                        Transferencia
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon @if ($desk->deposit_amount>0) glyphicon-chevron-up @else glyphicon-chevron-down @endif"></i>
                    </span>
                </div>
                <div class="panel-body" @if ($desk->deposit_amount>0) style="display: block;" @else style="display: none;" @endif id="Transferencia">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th style="text-align: center">No. Transacción</th>
                                    <th style="text-align: center">Descripción</th>
                                    <th style="text-align: center">Banco</th>
                                    <th style="text-align: center">Monto</th>
                                </thead>
                                <tbody>
                                    @foreach ($transferencia as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$value->reference}}</td>
                                        <td class="text-center">{{$value->description}}</td>
                                        <td class="text-center">{{$value->bank_name}}</td>
                                        <td class="text-right">@money($value->amount)</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-right"><strong>@money($desk->deposit_amount)</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success" id="hidepanel7">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="arrow-left" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="white"></i>
                        Transferir / Depositar
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th style="width: 5%;">No.</th>
                                    <th style="text-align: center">Descripción</th>
                                    <th style="text-align: center">Referencia</th>
                                    <th style="text-align: center">Cuenta destino</th>
                                    <th style="text-align: center">Monto</th>
                                </thead>
                                <tbody>
                                    @foreach ($movimiento as $key=>$value)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{$value->description}}</td>
                                        <td class="text-center">{{$value->reference}}</td>
                                        <td class="text-center">{{$value->account_name}}</td>
                                        <td class="text-right">@money($value->amount)</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" id="hidepanel8">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="check" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="white"></i>
                        Firmas
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6" id="firmas">
                            <div class="row">
                                <hr style="border-top: 1px solid black;" data-content="F:">
                            </div>
                            <div class="row">
                                <p>{{$usuario->name}}</p>
                            </div>
                        </div>
                        <div class="col-md-6" id="firmas2">
                            <div class="row">
                                <hr style="border-top: 1px solid black;" data-content="F:">
                            </div>
                            <div class="row">
                                <p>Encargado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-4"></div>
        <div class="col-lg-4" style="text-align: center;">
            <div class="form-group">
                <button type="submit" class="btn btn-success" onclick="printDiv('print')">
                    {{trans('button.print')}}
                </button>
                <a class="btn btn-danger" href="{{"http://poscacao.test/desk_closing/index/".$desk->account_id}}">
                    {{trans('button.cancel')}}
                </a>
            </div>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/general-function/currency_format.js') }}"></script>


<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
    type="text/javascript"></script>
<script>
    var A1=document.getElementById('Efectivo');
    var A2=document.getElementById('Cheque');
    var A3=document.getElementById('Deposito');
    var A4=document.getElementById('Tarjeta');
    var A5=document.getElementById('Transferencia');
    var M=document.getElementById('Movimientos');
    //A1.style.cssText="display: block";

    function printDiv(nombreDiv) {
        A1.style.display="block";
        A2.style.display="block";
        A3.style.display="block";
        A4.style.display="block";
        A5.style.display="block";
        M.style.display="none";
        var contents = $("#" + nombreDiv).html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>' + nombreDiv + '</title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.

        frameDoc.document.write('<link href="{{ asset('assets/css/desk_closing_style.css')}}" rel="stylesheet" type="text/css" /> ');
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
        M.style.display="block";        
    }
</script>
@stop