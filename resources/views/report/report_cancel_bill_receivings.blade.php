@extends('layouts/default')

@section('title',trans('report-receiving.canceled_bill'))
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
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('report-receiving.canceled_bill')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        {!! Form::open(array('url'=>'cancel_bill_receivings/report_cancel_bill_receivings')) !!}
        <div class="panel-body">
          <div class="col-md-14">
            <div class="btn-group btn-group-justified">
              <div class="col-md-4">
                <center><label for=""><b>Fecha inicial</b></label></center>
                <input type="text" name="date1"  id='admited_at'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
              </div>
              <div class="col-md-4">
                <center><label for=""><b>Fecha final</b></label></center>
                <input type="text" name="date2"  id='admited_at2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
              </div>
              <div class="col-md-4">
                <br>
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
                  <th>{{trans('report-receiving.date')}}</th>
                  <th>{{trans('report-receiving.items_received')}}</th>
                  <th>{{trans('report-receiving.received_by')}}</th>
                  <th>{{trans('report-receiving.supplied_by')}}</th>
                  <th>{{trans('report-receiving.total')}}</th>
                  <th>{{trans('report-receiving.payment_type')}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data_receivings as $i=> $value)
                <tr>
                  <td></td>
                  <td>{{ $value->id }}</td>
                  <td>{{ $value->serie->document->name.' '.$value->serie->name.'-'.$value->correlative}}</td>
                    <td>
                      {!!date_format($value->created_at, 'd/m/Y H:i:s') !!}

                    </td>
                    <td style="text-align:center;">{{DB::table('receiving_items')->where('receiving_id', $value->id)->sum('quantity')}}</td>
                    <td>{{ $value->user->name }}</td>
                    <td>{{ $value->supplier->company_name }}</td>
                    <td style="text-align: right;">@money($value->total_cost)</td>
                    <td>{{$value->pago->name}}</td>
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
    $("#admited_at2").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
  </script>
  @stop
