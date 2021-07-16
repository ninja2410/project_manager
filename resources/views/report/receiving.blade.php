    @extends('layouts/default')

    @section('title',trans('report-receiving.receivings_report'))
    @section('page_parent',trans('report-receiving.reports'))

    <!--  calendario -->

    @section('header_styles')

    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    @stop
    @section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            {{trans('report-receiving.receivings_report')}}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    {!! Form::open(array('url'=>'reports/receivings')) !!}
                    <div class="panel-body">
                        <div class="col-md-14">
                            <div class="btn-group btn-group-justified">
                                <div class="row">
                                    <div class="col-md-3">
                                        <center>
                                            <label>
                                                <b>Seleccione documento</b>
                                            </label>
                                        </center>
                                        <select name="document" id="document" class="form-control">
                                            <option value="Todo" {{ ($document == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
                                            @foreach($dataDocuments as $value)
                                            <option value="{{$value->id_serie}}" {{ ($document == $value->id_serie) ?  'selected="selected"' : '' }}>{{$value->document.' '.$value->serie}}</option>
                                            @endForeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <center><label for=""><b>Fecha inicial</b></label></center>
                                        <input type="text" name="date1"  id='admited_at'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
                                    </div>
                                    <div class="col-md-3">
                                        <center><label for=""><b>Fecha final</b></label></center>
                                        <input type="text" name="date2"  id='admited_at2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
                                    </div>
                                    <div class="col-md-3">
                                        <br>
                                        {!! Form::submit(trans('Generar'), array('class' => 'btn btn-primary ')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php $costoTotal=DB::table('receiving_items')->join('receivings','receiving_items.receiving_id' ,'=' ,'receivings.id')
                            ->join('series','receivings.id_serie', '=' ,'series.id')
                            ->join('documents','series.id_document', '=' ,'documents.id')
                            ->whereBetween('receivings.created_at',[$fecha1,$fecha2])
                            ->where('receivings.cancel_bill','=',0)->whereNull('receivings.storage_destination')
                            ->where('documents.sign','=','+')->sum('receiving_items.total_cost') ?>
                            <div class="well well-sm ">{{trans('report-receiving.grand_total')}}: Q <?php echo number_format($totalSales[0]->totalCompras,2); ?></div>
                            <table class="table table-striped table-bordered" id="table1">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">No.</th>
                                        <th style="text-align: center">{{trans('Serie')}}</th>
                                        <th style="text-align: center">{{trans('report-receiving.date')}}</th>
                                        <th>Qt</th>
                                        <th>{{trans('report-receiving.received_by')}}</th>
                                        <th>{{trans('report-receiving.supplied_by')}}</th>
                                        <th>{{trans('report-receiving.expenses')}}</th>
                                        <th style="text-align: right">{{trans('report-receiving.total')}}</th>
                                        <th style="text-align: center">{{trans('report-receiving.payment_type')}}</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receivingReport as $index=> $value)
                                    <tr>
                                        <td style="text-align: center;">{{ $index+1 }}</td>
                                        <!-- SELECT d.name
                                            From series  s left JOIN  documents d on s.id_document=d.id where s.id=2; -->
                                            <?php $nameSerie= DB::table('series')->leftJoin('documents','series.id_document','=','documents.id')->where('series.id',$value->id_serie)->select('series.id as id_serie','documents.id as id_document','series.name as serie','documents.name as document')->get();
                                            $serieAndCorrelative=$nameSerie[0]->document.' '.$nameSerie[0]->serie.'-'.$value->correlative;?>
                                            <td style="font-size: 12px">{{$serieAndCorrelative}}</td>
                                            <!-- <td>{{ $value->created_at }}</td> -->
                                            <td style="font-size: 12px">{{ date('d/m/Y H:i:s',strtotime($value->created_at))}}</td>
                                            <td style="text-align:center;">{{DB::table('receiving_items')->where('receiving_id', $value->id)->sum('quantity')}}</td>
                                            <td>{{ $value->nameUser}}</td>
                                            <td style="font-size: 12px">{{ $value->company_name }}</td>
                                            <td>Q{{number_format($value->expenses, 2)}}</td>
                                            <?php $valorTotal=DB::table('receiving_items')->where('receiving_id', $value->id)->sum('total_cost'); ?>
                                            <td style="text-align: right;">Q<?php echo number_format($valorTotal,2); ?></td>
                                            <?php $typePayment=DB::table('pagos')->where('id','=',$value->id_pago)->value('pagos.name'); ?>
                                            <td style="text-align: center">{{$typePayment }}</td>
                                            <td>
                                                <!-- <div class="btn-group"> -->
                                                    <a href="{{route('completereceivings',$value->id.'?details=true')}}" class="btn btn-info btn-block" title="Re-imprimir">
                                                        <span class="glyphicon glyphicon-print"></span>
                                                    </a>
                                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalDetails_{{$value->id}}" onclick="getDetail({{$value->id}})"  title="Detalle">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </button>
                                                    <!-- </div> -->
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalDetails_{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background: #073963; color:white">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true" style="color: white">&times;</span>
                                                                    </button>
                                                                    <h5 class="modal-title" id="exampleModalLabel">Detalle documento: {{$serieAndCorrelative}} </h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <h5><b>Documento:</b> {{$serieAndCorrelative}}</h5>
                                                                            <h5><b>Proveedor:</b> {{ $value->company_name }}</h5>
                                                                            <h5><b>Referencia:</b> {{ $value->reference }}</h5>
                                                                            <h5><b>Total:</b> Q  {{number_format($value->total_cost,2)}}</h5>

                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <h5><b>Fecha:</b> {{ date('d/m/Y H:i:s',strtotime($value->created_at))}}</h5>
                                                                            <h5><b>Recibido por:</b> {{ $value->nameUser }}</h5>
                                                                            <h5><b>Tipo de pago: </b>{{$typePayment}}</h5>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <table class="table table-bordered table-striped" id="tableDetails_{{$value->id}}">
                                                                                <thead>
                                                                                    <th>No.</th>
                                                                                    <th>Producto</th>
                                                                                    <th>Precio</th>
                                                                                    <th>Cantidad</th>
                                                                                    <th>Total</th>
                                                                                </thead>
                                                                                <tbody>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row" id="table_outlay_{{$value->id}}">
                                                                        <h4>Gastos de compra</h4>
                                                                        <hr>
                                                                        <div class="col-md-12">
                                                                            <table class="table table-bordered table-striped" id="tableOutlays_{{$value->id}}">
                                                                                <thead>
                                                                                    <th>No.</th>
                                                                                    <th>Descripción</th>
                                                                                    <th>Monto</th>
                                                                                </thead>
                                                                                <tbody>

                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                                    <!-- <button type="button" class="btn btn-success">Imprimir</button> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal  -->
                                                </td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>TOTAL:</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <!-- Button trigger modal -->


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endsection
            @section('footer_scripts')

            <script type="text/javascript" src="{{ asset('assets/js/general-function/currency_format.js') }}" ></script>
            <script type="text/javascript">
                $(document).ready(function(){
                    $('#table1').DataTable({
                        "language": {
                            "url": "{{ asset('assets/json/Spanish.json') }}"
                        },
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,Q,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column( 7 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            // Total over this page
                            pageTotal = api
                                .column( 7, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            // Update footer
                            $( api.column( 7 ).footer() ).html(
                                'Q  '+number_format(total,2)
                            );
                        },
                        xscrollable:true,
                        dom: 'Bfrtip',
                        buttons: [
                        {
                            extend: 'collection',
                            text: 'Exportar/Imprimir',
                            buttons: [
                            {
                                extend:'copy',
                                text: 'Copiar',
                                footer: true,
                                title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
                                exportOptions:{
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend:'excel',
                                footer: true,
                                title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
                                exportOptions:{
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend:'pdf',
                                footer: true,
                                title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
                                exportOptions:{
                                    columns: 'th:not(:last-child)'
                                }
                            },
                            {
                                extend:'print',
                                text: 'Imprimir',
                                footer: true,
                                title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
                                exportOptions:{
                                    columns: 'th:not(:last-child)'
                                },
                            }
                            ]
                        },
                        ],
                        // order:[],
                    }) ;


                });
                //obteniendo el detalle
                function getDetail(idReceiving){
                    $.ajax({
                        url:'../reports/get-details/'+idReceiving,
                        method:"GET",
                        success:function(data){
                            $('#tableDetails_'+idReceiving+' tbody > tr').remove();

                            $.each(data,function(e,i){
                                var row='';
                                row+='<td style="text-align:center">'+(e+1)+'</td>';
                                row+='<td>'+i.item_name+'</td>';
                                row+='<td style="text-align:right">Q '+currency_format(i.cost_price)+'</td>';
                                row+='<td style="text-align:center">'+i.quantity+'</td>';
                                row+='<td style="text-align:right">Q '+currency_format(i.total_cost)+'</td>';
                                $('<tr>').html(row).appendTo('#tableDetails_'+idReceiving+' tbody');
                                });
                            },
                            error:function (error) {
                                alert('Ha ocurrido un error intente de nuevo');
                            }
                        });

                        $.ajax({
                            url:'../reports/get-outlays/'+idReceiving,
                            method:"GET",
                            success:function(data){
                                if (data.length>0) {
                                    document.getElementById('table_outlay_'+idReceiving).style.display = "inline";
                                }
                                else{
                                    document.getElementById('table_outlay_'+idReceiving).style.display = "none";
                                }
                                $('#tableOutlays_'+idReceiving+' tbody > tr').remove();
                                $.each(data,function(e,i){
                                    var row='';
                                    row+='<td style="text-align:center">'+(e+1)+'</td>';
                                    row+='<td>'+i.description+'</td>';
                                    row+='<td style="text-align:right">Q '+currency_format(i.amount)+'</td>';
                                    $('<tr>').html(row).appendTo('#tableOutlays_'+idReceiving+' tbody');
                                    });
                                },
                                error:function (error) {
                                    alert('Ha ocurrido un error intente de nuevo');
                                }
                            });
                        }
                    </script>
                    <!--Canlendario  -->
                    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
                    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
                    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
                    <script>
                        $("#admited_at").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
                    </script>
                    <script>
                        $("#admited_at2").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
                    </script>
                    @stop
