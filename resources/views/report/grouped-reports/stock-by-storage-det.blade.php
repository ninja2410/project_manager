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
                <?php $total_general =0; ?>
                @foreach ($data as $key => $value)
                    <div class="panel-body">
                        <h4>
                           Bodega: {{$key}}
                        </h4>
                    </div>
                    <ul class="list-group" style="list-style-type: none;">
                    <?php $nuevo = $value->groupBy('NOMBRE'); $total_producto = 0;?>
                    @foreach ($nuevo as $k => $v) 
                    <li class="list-group-item" style="border: 1px solid #ddd;margin-top:0px;padding-top:0px;">
                        <h5>                        
                            Producto: {{$k}} 
                        </h5>
                        <table class="table table-hover" style="width:100%">
                            <thead>
                                <tr style="background-color: lightgray">
                                    <td style="width: 10%">Fecha</td>                                    
                                    <td style="width: 10%">Documento</td>
                                    <td style="width: 5%">Ingreso</td>
                                    <td style="width: 10%">Egreso</td>
                                    <td style="width: 10%">Existencia</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                @foreach ($v as $a => $b)                                 
                                    <?php $x = $b->toArray();   ?>
                                    @if($a==0) Inventario inial: {{ $x['QUANTITY'] }} @endif
                                    <tr style="font-size:12px">
                                        <td>{{$x['FECHA']}}</td>
                                        <td>{{$x['documento']}}</td>
                                        <td>{{$x['COMPRA']}}</td>
                                        <td style="font-size:11px">{{$x['VENTA']}}</td>                                        
                                        <?php if($a==0) $total = $x['QUANTITY'];
                                        $total = $total +$x['COMPRA']-$x['VENTA'];  ?>
                                        <td style="text-align: center">{{$total}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="page-break-before: always;">
                                <tr style="padding-top: 3px;border: 1px solid #ddd;">
                                    <td colspan="4" style="text-align:right;font-size:13px">TOTAL {{$k}}:</td>
                                    <td style="text-align: right;font-size:13px">{{$total}}</td>
                                <?php $total_producto +=$total; ?>
                                </tr>
                            </tfoot>
                        </table>
                    </li>
                    @endforeach
                    {{-- vendedor --}}  
                    {{-- <li class="list-group-item" style="border: 1px solid #ddd;margin-top:0px;padding-top:0px;">  --}}
                    <table class="table" style="width:100%">
                        <thead>
                            <tr style="background-color: lightgray">                                
                                <td colspan="4" style="text-align:right;font-size:13px">TOTAL {{$key}}:</td>
                                <td style="text-align: right;font-size:13px;padding-right:10px"><strong>{{$total_producto}}</strong></td>
                                <?php $total_general += $total_producto; ?>
                            </tr>
                        </thead>
                    </table>
                    {{-- </li> --}}
                    </ul>                    
                @endforeach 
                {{-- producto --}}
                <table class="table" style="width:100%">
                    <thead>
                        <tr style="background-color: lightgray">
                            <td colspan="4" style="text-align:right;font-size:13px">GRAN TOTAL:</td>
                            <td style="text-align: right;font-size:13px;padding-right:10px"><strong>{{$total_general}}</strong></td>
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