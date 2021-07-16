<!DOCTYPE html>
<html>

<head>
    <title>Login | {{trans('dashboard.empresa')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- global level css -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/src/js/jquery.min.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
    
    <!-- page level css -->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/login.css') }}" /> --}}
    <!-- end of page level css -->
</head>

{{-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script> --}}
<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="py-5"> 
                    <img src="{{ asset('images/system/logo.png') }}" alt="Cacao-ERP" style="max-width: 180px;">
                    <div class="text-center">
                        <h3 style="color:#2C3E50" >{{ $title }}</h3>
                        {{-- <h4> <label for="Choose Report"  style="color:#E74C3C">{{ $meta['fecha'] }}</label></h4> --}}
                    </div>
                </div>
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h2 class="panel-title">
                        Fechas : {{ $meta['fecha'] }}
                    </h2>
                </div>
                @foreach ($data as $key => $value)
                    <div class="panel-body">
                        <h4>
                           Cliente: {{$key}}
                        </h4>
                    </div>
                    <ul class="list-group">
                    <?php $nuevo = $value->groupBy('pago'); $total_cliente = 0;?>
                    @foreach ($nuevo as $k => $v) 
                    <li class="list-group-item">
                        <h5>                        
                            Forma de pago: {{$k}}
                        </h5>
                        <table class="table table-hover">
                            <thead>
                                <tr>
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
                                    <tr>
                                        {{-- <td>{{$x['customer_name']}}</td> --}}
                                        {{-- <td>{{$x['pago']}}</td> --}}
                                        <td>{{$x['sale_date']}}</td>
                                        <td>{{$x['document_and_correlative']}}</td>
                                        <td>{{$x['item_name']}}</td>
                                        <td>{{$x['quantity']}}</td>
                                        <td>{{$x['selling_price']}}</td>
                                        <td>{{$x['total_selling']}}</td>
                                        <?php $total +=$x['total_selling'];  ?>
                                    </tr>
                                    {{-- echo '--'.$x['document_and_correlative'].' '.$x['item_name'].'<br>'; --}}
                                {{-- // echo ' '.$a.' <br>'; --}}
                                @endforeach
                                {{-- productos --}}
                            </tbody>
                            <tfoot >
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
                    <li class="list-group-item" style="border: 1px solid #ddd;margin-top:0px;padding-top:0px;">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr style="background-color: lightgray">
                                <tr style="padding-top: 3px;border: 1px solid #ddd;">
                                <td colspan="5" style="text-align:right;font-size:13px">TOTAL {{$key}}:</td>
                                <td style="text-align: right;font-size:13px">@money($total_cliente)</td>
                            </tr>
                        </thead>
                    </table>
                    </li>
                    </ul>
                @endforeach 
                {{-- cliente --}}

                {{-- @foreach ($data as $item=>$value) 
                
                @if($customer!=$value->customer_name)
                <div class="panel-body">
                    <h3>
                        {{$value->customer_name}}
                    </h3>
                </div>
                    <ul class="list-group">
                @endif
                    @if($pago!=$value->pago)                    
                    <li class="list-group-item">
                    <h4>                        
                        {{$value->pago}}
                    </h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td style="width: 10%">Cliente</td>
                                <td style="width: 10%">Forma pago</td>
                                <td style="width: 10%">Fecha</td>
                                <td style="width: 10%">Documento</td>
                                <td style="width: 25%">Producto</td>
                                <td style="width: 5%">Cantidad</td>
                                <td style="width: 10%">P.Venta</td>
                                <td style="width: 10%">Total</td>
                            </tr>
                        </thead>
                        <tbody>
                    @endif 
                            <tr>
                                <td>{{$value->customer_name}}</td>
                                <td>{{$value->pago}}</td>
                                <td>{{$value->sale_date}}</td>
                                <td>{{$value->document_and_correlative}}</td>
                                <td>{{$value->item_name}}</td>
                                <td>{{$value->quantity}}</td>
                                <td>{{$value->selling_price}}</td>
                                <td>{{$value->total_selling}}</td>                                
                            </tr>
                    @if($pago!=$value->pago)
                        </tbody>
                    </table>
                    </li>
                    @endif
                    
                    @if($customer!=$value->customer_name)
                    </ul>
                    @endif
                    @endforeach                                    --}}
                
            </div>
        </div>
    </div>
</div>