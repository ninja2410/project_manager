@extends('layouts/default')

@section('title',trans('sale.list_sales'))
@section('page_parent',trans('sale.sales'))

@section('header_styles')
  {{-- date time picker --}}
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

  <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet">
@stop
@section('content')
    <?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?>
    <section class="content">
      <div class="row">
        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
              {{trans('sale.list_sales')}}
            </h4>
            <div class="pull-right">
              <a href="{{ URL::to('sales/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('sale.new_sale')}} </a>
            </div>
          </div>

          <div class="panel-body table-responsive">

            {!! Form::open(array('url'=>'sales','method'=>'get')) !!}
            @include('partials.sales_filter_full')
            {!! Form::close() !!}

            <table class="table table-striped table-bordered compact" id="table1">
              @include('partials.sales_resultset_full')
            </table>
            <input type="hidden" name="tipo" value="{{$tipo}}" id="tipo">
          </div>
        </div>
        {{--Begin modal anulación--}}
        <div class="modal fade in" id="ajax-modal" tabindex="-1" role="dialog" aria-hidden="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-info">
                <h4 class="modal-title">Anulación</h4>
              </div>
              <div class="modal-body">
                <div class="text-center">
                  <h3>¿Seguro que desea anular la factura?</h3>
                </div>
              </div>
              <div class="modal-footer">
                <!-- <input type="button"  id="btnPrint" value="Si" class="btn btn-danger" /> -->
                {!! Form::open(array('url' => 'cancel_bill/anular','method' => 'get', 'id'=>'id_form_bodega')) !!}
                <input type="hidden" name="id_elemento" value="0" id="elemento_a_borrar">
                <input type="button" data-dismiss="modal" value ="No" class="btn btn-danger">
                <!-- <a href="" id="anular_factura"class="btn btn-danger">Si</a> -->
                <input type="submit" name="" value="Si" id="anular_factura" class="btn btn-success">
                {!! Form::close() !!}

              </div>
            </div>
          </div>
        </div>
        {{-- Fin modal anulación --}}
      </div>
      {{-- </div> --}}
    </section>
@endsection
@section('footer_scripts')
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>


  <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>

  <script type="text/javascript">
      /*Se inicializan los checkboxes*/
      $('input[type="checkbox"].square, input[type="radio"].square').iCheck({
          checkboxClass: 'icheckbox_square-green',
          radioClass: 'iradio_square-green',
          increaseArea: '20%'
      });
      $(document).ready(function() {
          $('[data-toggle="tooltip"]').tooltip();
          var dateNow = new Date();
          $("#start_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
          $("#end_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:dateNow}).parent().css("position :relative");
          var tipo= document.getElementById('tipo').value;
          if(tipo=='lista') {
              setDataTable("table1", [7,8], "{{ asset('assets/json/Spanish.json') }}");
          }else {
              var table = $('#table1').DataTable({
                  language: {
                      "url":" {{ asset('assets/json/Spanish.json') }}"
                  },
                  "pageLength": 30,
                  xscrollable:true,
                  dom: 'Bfrtip',
                  "ordering": false,

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
                          .column( 5 , {filter:'applied'})
                          .data()
                          .reduce( function (a, b) {
                              return intVal(a) + intVal(b);
                          }, 0 );

                      // Update footer
                      $( api.column( 6 ).footer() ).html(
                          formato_moneda(total,2)
                      );
                  },
                  buttons: [
                      {
                          extend: 'collection',
                          text: 'Exportar/Imprimir',
                          grouped_array_index: [ 4 ] ,
                          buttons: [
                              {
                                  extend:'copy',
                                  text: 'Copiar',
                                  title: 'Ventas por tipo de pago - Del '+$('#start_date').val()+' al: '+$('#end_date').val(),
                                  footer:true,
                                  exportOptions:{
                                      columns: ':visible'
                                  }
                              },
                              {
                                  extend:'excel',
                                  title: 'Ventas por tipo de pago - Del '+$('#start_date').val()+' al: '+$('#end_date').val(),
                                  footer:true,
                                  exportOptions:{
                                      columns: ':visible'
                                  }
                              },
                              {
                                  extend:'pdf',
                                  title: 'Ventas por tipo de pago - Del '+$('#start_date').val()+' al: '+$('#end_date').val(),
                                  footer:true,
                                  exportOptions:{
                                      columns: ':visible'
                                  },
                                  customize: function(doc) {
                                      doc.styles.tableHeader.fontSize = 8;
                                      doc.defaultStyle.fontSize = 6;
                                      doc.styles.tableFooter.fontSize = 8;
                                  }
                              },
                              {
                                  extend:'print',
                                  text: 'Imprimir',
                                  title: 'Ventas por tipo de pago - Del '+$('#start_date').val()+' al: '+$('#end_date').val(),
                                  footer:true,
                                  exportOptions:{
                                      columns: ':visible'
                                  }
                              }
                          ]
                      },
                  ],
              });
              table.buttons().container()
                  .appendTo('#example_wrapper .col-sm-6:eq(0)');
          }
      });

      function clickBtn(button)
      {
          var elemento_a_borrar=document.getElementById('elemento_a_borrar');
          elemento_a_borrar.value=button.id;
      }


  </script>
@stop
