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
                <?php $total_general =0;$total_costo=0;$total_profit=0; ?>
                @foreach ($data as $key => $value)
                    <div class="panel-body">
                        <h4>
                           Producto: {{$key}}
                        </h4>
                    </div>
                    <ul class="list-group" style="list-style-type: none;">
                    <li class="list-group-item" style="border: 1px solid #ddd;margin-top:0px;padding-top:0px;">                        
                        <table class="table table-hover" style="width:100%">
                            <thead>
                                <tr style="background-color: lightgray">
                                    <td style="width: 10%">Fecha</td>
                                    <td style="width: 15%">Forma pago</td>
                                    <td style="width: 10%">Documento</td>                                    
                                    <td style="width: 5%">Cant.</td>
                                    <td style="width: 10%">Total Venta</td>
                                    <td style="width: 10%">Total Costo</td>
                                    <td style="width: 10%">Rentabilidad</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $venta = 0; $costo=0; $profit=0; ?>
                                @foreach ($value as $a => $b)
                                    <?php // $x = $b->toArray(); ?>
                                    <tr style="font-size:12px">
                                        <td>{{$b['sale_date']}}</td>
                                        <td>{{$b['pago']}}</td>
                                        <td>{{$b['document_and_correlative']}}</td>
                                        <td style="text-align: center">{{$b['quantity']}}</td>                                        
                                        <td style="text-align: right">@money($b['selling_price'])</td>
                                        <td style="text-align: right">@money($b['cost_price'])</td>
                                        <td style="text-align: right">@money($b['profit'])</td>
                                        <?php $venta +=$b['selling_price']; $costo +=$b['cost_price']; 
                                            $profit +=$b['profit'];?>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="page-break-before: always;">
                                <tr style="padding-top: 3px;border: 1px solid rgb(130, 130, 130);">
                                    <td colspan="4" style="text-align:right;font-size:13px">TOTAL {{$key}}:</td>
                                    <td style="text-align: right;font-size:13px">@money($venta)</td>
                                    <td style="text-align: right;font-size:13px">@money($costo)</td>
                                    <td style="text-align: right;font-size:13px">@money($profit)</td>
                                <?php $total_general +=$venta; $total_costo +=$costo; $total_profit +=$profit; ?>
                                </tr>
                            </tfoot>
                        </table>
                    </li>                    
                    </ul>
                @endforeach 
                {{-- vendedor --}}
                <table class="table" style="width:100%;padding-inline-start: 40px;">
                    <thead>
                        <tr style="background-color: lightgray">                           
                            <td colspan="4" style="text-align:right;font-size:13px;width:40%;">GRAN TOTAL:</td>
                            <td style="text-align: right;font-size:13px;padding-right:10px"><strong>@money($total_general)</strong></td>
                            <td style="text-align: right;font-size:13px;padding-right:10px"><strong>@money($total_costo)</strong></td>
                            <td style="text-align: right;font-size:13px;padding-right:10px"><strong>@money($total_profit)</strong></td>
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