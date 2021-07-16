<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ public_path('assets/css/pdf-reports.css') }}" rel="stylesheet" />    

    <style>
        /** Define the margins of your page **/
        @page {
            margin: 0cm 0cm;
        }

        footer {
            position: fixed; 
            bottom: -80px; 
            left: 0px; 
            right: 0px;
            height: 1.5cm; 

            /** Extra personal styles **/
            background-color: {{$color}};
            color: white;
            text-align: center;
            line-height: 1cm;
        }
    </style>

</head>
<main>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="row"> 
                    <div class="col-xs-4" style="display: inline-block;padding-top:0px;">
                        <img src="{{ public_path('images/system/logo.png') }}" alt="Cacao-ERP" style="max-width: 190px;">
                    </div>
                    <div class="col-xs-6" style="display: inline-block;">
                        <div style="text-align: center">
                            <h3 style="{{$color}}" >{{ $title }}</h3>
                            <h4> <label for="Choose Report"  style="{{$text_color}}">Fechas : {{ $meta['fecha'] }}</label></h4>
                        </div>
                    </div>                    
                </div>
                <?php  $total_general =0; ?>
                @foreach ($data as $key => $value)
                    <div class="panel-body">
                        <h4>
                           Cliente: {{$key}}
                        </h4>
                    </div>
                    <ul class="list-group" style="list-style-type: none;">
                    <?php $nuevo = $value->groupBy('pago'); $total_cliente = 0;?>
                    @foreach ($nuevo as $k => $v) 
                    <li class="list-group-item" style="border: 1px solid #ddd;margin-top:0px;padding-top:0px;">
                        <h5>                        
                            Forma de pago: {{$k}}
                        </h5>
                        <table class="table table-hover" style="width:100%">
                            <thead>
                                <tr style="background-color: lightgray">
                                    {{-- <td style="width: 10%">Cliente</td> --}}
                                    {{-- <td style="width: 10%">Forma pago</td> --}}
                                    <td style="width: 10%">Fecha</td>
                                    <td style="width: 10%">Documento</td>
                                    <td style="width: 25%">Producto</td>
                                    <td style="width: 5%">Cantidad</td>
                                    <td style="width: 10%">P.Venta</td>
                                    <td style="width: 10%">Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                @foreach ($v as $a => $b) 
                                    <?php $x = $b->toArray(); ?>
                                    <tr style="font-size:12px">
                                        <td>{{$x['sale_date']}}</td>
                                        <td>{{$x['document_and_correlative']}}</td>
                                        <td style="font-size:11px">{{$x['item_name']}}</td>
                                        <td style="text-align: center">{{$x['quantity']}}</td>
                                        <td style="text-align: right">@money($x['selling_price'])</td>
                                        <td style="text-align: right">@money($x['total_selling'])</td>
                                        <?php $total +=$x['total_selling'];  ?>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="page-break-before: always;">
                                <tr style="padding-top: 3px;border: 1px solid #ddd;">
                                <td colspan="5" style="text-align:right;font-size:13px">TOTAL {{$k}}:</td>
                                <td style="text-align: right;font-size:13px">@money($total)</td>
                                <?php $total_cliente +=$total; ?>
                                </tr>
                            </tfoot>
                        </table>
                    </li>
                    @endforeach
                    {{-- pago --}}  
                    {{-- <li class="list-group-item" style="border: 1px solid #ddd;margin-top:0px;padding-top:0px;">  --}}
                    <table class="table" style="width:100%">
                        <thead>
                            <tr style="background-color: lightgray">
                                <tr style="padding-top: 3px;border: 1px solid #ddd;">
                                <td colspan="5" style="text-align:right;font-size:13px">TOTAL {{$key}}:</td>
                                <td style="text-align: right;font-size:13px;padding-right:10px"><strong>@money($total_cliente)</strong></td>
                                <?php $total_general += $total_cliente; ?>
                            </tr>
                        </thead>
                    </table>
                    {{-- </li> --}}
                    </ul>                    
                @endforeach 
                {{-- cliente --}}
                <table class="table" style="width:100%">
                    <thead>
                        <tr style="background-color: lightgray">
                            <tr style="padding-top: 3px;border: 1px solid #ddd;">
                            <td colspan="5" style="text-align:right;font-size:13px">GRAN TOTAL:</td>
                            <td style="text-align: right;font-size:13px;padding-right:10px"><strong>@money($total_general)</strong></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
</main>
<footer>
    {{$company_name}} using Cacao-ERP, Copyright &copy; <?php echo date("Y");?> 
</footer>