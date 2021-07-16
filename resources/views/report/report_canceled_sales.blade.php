@extends('layouts/default')

@section('title',trans('report-sale.canceled_sales'))
@section('page_parent',trans('report-receiving.reports'))


@section('header_styles')
    <!--  calendario -->
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@stop

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12 ">
			<div class="panel panel-default">
        {!! Form::open(array('url'=>'cancel_bill/report_canceled_sales')) !!}
				<div class="panel-body">
          <div class="col-md-14">
            <div class="btn-group btn-group-justified">
              <div class="col-md-4">
              <input type="text" name="date1"  id='admited_at'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
              <center><label for=""><b>Fecha inicial</b></label></center>
            </div>
              <div class="col-md-4">
              <input type="text" name="date2"  id='admited_at2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
              <center><label for=""><b>Fecha final</b></label></center>
            </div>
              <div class="col-md-4">
              {!! Form::submit(trans('Generar'), array('class' => 'btn btn-primary ')) !!}
            </div>
            </div>
          </div>
        </div>
          <div class="panel-body">
            {!! Form::close() !!}
              <div class="panel-body table-responsive">
                <table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th></th>
                        <th>No.</th>
                        <th>{{trans('Serie')}}</th>
                        <th>{{trans('report-sale.date')}}</th>
                        <th>{{trans('report-sale.items_purchased')}}</th>
                        <th>{{trans('report-sale.sold_by')}}</th>
                        <th>{{trans('report-sale.sold_to')}}</th>
                        <th>{{trans('report-sale.total')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data_sales as $i=> $value)
                      <tr>
                        <td></td>
                        <td>{{$i+1}}</td>
                        <td>{{DB::table('documents')->join('series','documents.id','=','series.id_document')->where('series.id_document',
            							'=',$value->id_serie)->value('documents.name')}} {{DB::table('series')->where('id','=',$value->id_serie)->value('name')}}-{{$value->correlative}}</td>
                          <td>
                            {!!date_format($value->created_at, 'd/m/Y H:i:s') !!}
                          </td>
                          <td>
                            {{DB::table('sale_items')->where('sale_id', $value->id)->sum('quantity')}}
                          </td>
                          <td>{{ $value->user->name }}</td>
                          <td>{{ $value->customer->name }}</td>
                          <?php $totalMoneda=DB::table('sale_items')->where('sale_id', $value->id)->sum('total_selling'); ?>
                          <td style="text-align: right;">Q<?php echo number_format($totalMoneda,2) ;  ?></td>

                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <div class="panel-body">
                  </div>
              </div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
function printInvoice() {
    window.print();
}
</script>
@endsection
@section('footer_scripts')

<script>
      $(document).ready(function() {
        $('#example').DataTable({
        language: {
          "url":" {{ asset('assets/json/Spanish.json') }}"
        },
        dom: 'Bfrtip',
        responsive: {
          details: {
            type: 'column'
          }
        },
        columnDefs: [ {
          className: 'control',
          orderable: false,
          targets:   0
        } ],
        buttons: [
        {        
          extend: 'collection',
          text: 'Exportar/Imprimir',
          buttons: [
          {
            extend:'copy',
            text: 'Copiar',
            title: document.title,
            exportOptions:{
              columns: ':visible'
            }
          },
          {
            extend:'excel',
            title: document.title,
            exportOptions:{
              columns: ':visible'
            }
          },
          {
            extend:'pdf',
            title: document.title,
            exportOptions:{
              columns: ':visible'
            }
          },
          {
            extend:'print',
            text: 'Imprimir',
            title: document.title,
            exportOptions:{
              columns: ':visible'
            },
          }          
          ]   
        },        
        ],
      }) ;
        
      });
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
