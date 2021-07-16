@extends('layouts/default')

@section('title',trans('report-sale.expiration_report'))
@section('page_parent',trans('report-sale.reports'))


@section('header_styles')
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="linechart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('report-sale.expiration_report')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        <div class="panel-body">
          @if (Session::has('message'))
          <div class="alert alert-info">{{ Session::get('message') }}</div>
          @endif
          <div class="panel-body table-responsive">
            {!! Form::open(array('url'=>'reports/expired_report')) !!}
            <div class="row">
              <div class="col-lg-4"></div>
              <div class="col-lg-4">
                <label for="">
                  Seleccione fecha:
                  <input type="text" name="expiration_date" id="expiration_date" value="{{$date_parameters}}" class="form-control">
                </label>
                <input type="submit" name="" value="Generar" class="btn btn-primary">
              </div>
              <div class="col-lg-4"></div>
            </div>
            {!! Form::close() !!}
            <table id="table1" class="table table-striped table-bordered display" >
              <thead>
                <th>No.</th>
                <th>Producto</th>
                <th>Precio venta</th>
                <th>Costo</th>
                <th>Existencia minima</th>
                <th>Cantidad</th>
                <th>Fecha Expiracion</th>
              </thead>
              <tbody>
                @foreach($data_items as $i=> $value)
                <tr>
                  <td>{{$i+1}}</td>
                  <td>{{$value->item_name}}</td>
                  <td>{{$value->selling_price}}</td>
                  <td>{{$value->cost_price}}</td>
                  <td>{{$value->minimal_existence}}</td>
                  <td>{{$value->quantity}}</td>
                  <td>{{$value->expiration_date}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<style type="text/css">
  .code {
    height: 40px !important;

  }
</style>
@endsection
@section('footer_scripts')

<script type="text/javascript">
  $(document).ready(function(){
    $('#table1').DataTable({
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
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'excel',
              title: document.title,
              exportOptions:{
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'pdf',
              title: document.title,
              exportOptions:{
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'print',
              text: 'Imprimir',
              title: document.title,
              exportOptions:{
                columns: 'th:not(:last-child)'
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
  $("#expiration_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
</script>

@stop
