@extends('layouts/default')

@section('title',trans('inventory_closing.inventory_closing'))
@section('page_parent',trans('menu.inventory_closing'))
@section('header_styles')
    {{-- date time picker --}}
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<section class="content">
    <!-- <div class="container"> -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            {{trans('inventory_closing.inventory_closing')}}
                        </h4>
                        <div class="pull-right">
                            <a href="{{ URL::to('inventory_closing/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('inventory_closing.create')}} </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-md-1"></div>
                            {!! Form::open(array('url'=>$url,'method'=>'get')) !!}
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
                                <input type="text" name="date1"  id='start_date'class="form-control" value="{{date('m/Y', strtotime($fecha1))}}">
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
                                <input type="text" name="date2"  id='end_date'class="form-control" value="{{date('m/Y', strtotime($fecha2))}}" >
                            </div>
                            <div class="col-md-3">
                                <center><label for=""><b>Bodega</b></label></center>
                                <select name="cellar" id="cellar" class="form-control">
                                    <option value="" @if(count($cellar)>1) selected @endif>Todos</option>
                                    @foreach($all_cellars as $value)
                                        <option value="{{$value->id}}"
                                                @if(count($cellar)==1 && $value->id == $cellar[0])
                                                selected
                                                @endif
                                        >{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <br>
                                {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn button-block btn-primary button-large boton-ancho')) !!}
                            </div>
                        </div>
                        <hr>
                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="width: 5%;">No.</th>
                                    <th>{{trans('inventory_closing.date')}}</th>
                                    <th>{{trans('inventory_closing.correlative')}}</th>
                                    <th>{{trans('inventory_closing.user')}}</th>
                                    <th>{{trans('inventory_closing.cellar')}}</th>
                                    <th>{{trans('inventory_closing.month')}}</th>
                                    <th>{{trans('inventory_closing.amount')}}</th>
                                    <th style="width: 18%;">{{trans('inventory_closing.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventory_closing_list as $value)
                                <tr>
                                    <td></td>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ date('d/m/Y', strtotime($value->date)) }}</td>
                                    <td>{{ $value->correlative }}</td>
                                    <td>{{ $value->user->name. $value->user->last_name }}</td>
                                    <td>{{ $value->almacen->name }}</td>
                                    <td>De {{trans('months.'.$value->l_month).'/'.$value->l_year}} a {{ trans('months.'.$value->month).'/'.$value->year }}</td>
                                    <td>@money($value->amount)</td>
                                    <td>
                                        <a class="btn btn-warning" href="{{ URL::to('inventory_closing/' . $value->id) }}" data-toggle="tooltip" data-original-title="Ver">
                                            <span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;Detalle
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th colspan="6" style="text-align:right">Total:</th>
                                <th colspan="2" style="text-align: left;"></th>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </section>
    @endsection
    @section('footer_scripts')
        <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
      var dateNow = new Date();
      $("#start_date").datetimepicker({ sideBySide: true, locale:'es',format:'MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
      $("#end_date").datetimepicker({ sideBySide: true, locale:'es',format:'MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
    $('#table1').DataTable({
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
                'Q  '+number_format(pageTotal,2) +'/(Q '+ number_format(total,2)+')'
            );
        },
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
@endsection
