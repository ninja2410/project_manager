@extends('layouts/default')

@section('title',trans('Facturación de créditos'))
@section('page_parent',trans('Facturación de créditos'))


@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">

<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12 ">
      <div class="panel panel-default">
        <!-- <div class="panel-heading">{{trans('report-sale.reports')}} - {{trans('report-sale.sales_report')}}</div> -->
        <div class="panel-body">
          {!! Form::open(array('url'=>'/credit-report')) !!}
          <div class="row">
            <div class="col-md-3">
              <center>
                <label for="">
                  <b>
                    Seleccione documento
                  </b>
                </label>
              </center>
              <select class="form-control" id="documentsName" name="document">
                <option value="Todo" {{ ($document == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
                @foreach($dataDocuments as $index => $value)
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
          <hr>
          <div>
          	<div class="col-md-9">
          	</div>
          	<div class="col-md-3">
          		<label for=""><b>Nota: </b>Ordenado por fecha</label>
          	</div>
          </div>
          {!! Form::close() !!}
          <hr>
         <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered" id="table1">
              <thead>
                <th style="text-align: center;">No.</th>
                <th style="text-align: center">Documento</th>
                <th style="text-align: center">Serie</th>
                <th style="text-align: center">Numero</th>
                <th >{{trans('report-sale.date')}}</th>
                <th style="text-align: center">Vendedor</th>
                <th  style="text-align: center">Cliente</th>
                <th style="text-align: right">{{trans('report-sale.total')}}</th>
                <th >&nbsp;</th>
              </thead>
              <tbody>
                <?php $totalSales=0;$totalGancias=0; ?>
                @foreach($saleReport as $index => $value)
                <tr>
                  <td style="text-align: center;">{{$index+1}}</td>
                  <td>{{$value->document}}</td>
                  <td>{{$value->name}}</td>
                  <td>{{$value->number}}</td>
                  <td style="font-size: 12px">
                    {{date('d/m/Y',strtotime($value->date))}}
                  </td>
                  <td >
                    {{$value->user_name}}
                  </td>
                  <td style="font-size: 11px">
                    {{$value->customer_name}}
                  </td>
                  <?php
                  $totalSales+=$value->amount;
                   ?>
                  <td style="text-align: right;">Q<?php echo number_format($value->amount,2) ;?></td>
                  <td>
                    <a  class="btn btn-block btn-info" href="{{route('invoice.reprintInv',$value->id_sales)}}" title="Re-impresion">
                      <span class="glyphicon glyphicon-print"></span>
                    </a>
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalDetails_{{$value->id_sales}}" onclick="getDetail({{$value->id_sales}})"  title="Detalle">
                      <span class="glyphicon glyphicon-list-alt"></span>
                    </button>
                    <div class="modal fade" id="modalDetails_{{$value->id_sales}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header" style="background: #073963; color:white">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true" style="color: white">&times;</span>
                            </button>
                            <h5 class="modal-title" id="exampleModalLabel">Detalle documento: {{$value->document.' '.$value->name.'-'.$value->number}} </h5>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-md-6">
                                <h5><b>Documento:</b> {{$value->document.' '.$value->name.'-'.$value->number}}</h5>
                                <h5><b>Cliente: </b>{{$value->customer_name}}</h5>
                                <h5><b>Total: </b>Q {{number_format($value->amount,2)}}</h5>
                              </div>
                              <div class="col-md-6">
                                <h5><b>Fecha:</b> {{ date('d/m/Y H:i:s',strtotime($value->date))}}</h5>
                                <h5><b>Vendedor: </b>{{$value->user_name}}</h5>
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-md-12">
                                <table class="table table-bordered table-striped" id="tableDetails_{{$value->id_sales}}">
                                  <thead>
                                    <th>No.</th>
                                    <th>Descripcion</th>
                                    <th>Total</th>
                                  </thead>
                                  <tbody>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Modal -->
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="row">
           <div class="col-md-6" style="text-align:right">


           </div>
           <div class="col-md-6" style="text-align:right">
             <div class="well well-sm">{{trans('report-sale.grand_total')}}: Q {{number_format($totalSales)}}
              </div>
           </div>
         </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.buttons.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.colVis.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.html5.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/pdfmake.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/vfs_fonts.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/js/general-function/currency_format.js') }}" ></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#table1').DataTable({
      "language": {
        "url": "{{ asset('assets/json/Spanish.json') }}"
      },
      dom: 'Bfrtip',
      buttons: [
      {
        extend:'copy',
        text:"COPIAR",
        exportOptions:{
          columns:[0,1,2,3,4,5]
        },
        title:'Listado de ventas'
      },
      {
        extend:'csv',
        text:"CSV",
        exportOptions:{
          columns:[0,1,2,3,4,5]
        },
        title:'Listado de ventas'
      },
      {
        extend:'excel',
        text:"EXCEL",
        exportOptions:{
          columns:[0,1,2,3,4,5]
        },
        title:'Listado de ventas'
      },
      {
        extend:'pdf',
        text:"PDF",
        exportOptions:{
          columns:[0,1,2,3,4,5]
        },
        title:'{{ trans('dashboard.empresa') }}  Reporte de ventas '+$('#documentsName option:selected').text() +' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),

      },
      {
        extend:'print',
        text:"IMPRIMIR",
        exportOptions:{
          columns:[0,1,2,3,4,5]
        },
        title:'<center><h1 style="color:#1200D0">{{ trans('dashboard.empresa') }}</h1><h2>Reporte de ventas</h2><h3>'+$('#documentsName option:selected').text()+'</h3><h4> Del '+$('#admited_at').val() +' al: '+$('#admited_at2').val()+' </h4></center>',

      }

      ],
      order:[],
    }) ;
  });

  //obtenemos el detalle de la compra
  function getDetail(idSales){
    $.ajax({
      url:'credit-detail/'+idSales,
      method:"GET",
      success:function(data){
        $('#tableDetails_'+idSales+' tbody > tr').remove();
        $.each(data,function(e,i){
          var row='';
          row+='<td style="text-align:center">'+(e+1)+'</td>';
          row+='<td>'+i.user_description+'</td>';
          row+='<td style="text-align:right">Q '+currency_format(i.amount)+'</td>';
          $('<tr>').html(row).appendTo('#tableDetails_'+idSales+' tbody');
        });
      },
      error:function (error) {
        alert('Ha ocurrido un error intente de nuevo'+error);
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
