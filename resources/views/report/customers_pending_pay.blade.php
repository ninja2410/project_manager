@extends('layouts/default')

@section('title','Reporte de clientes morosos')
@section('page_parent','Clientes morosos')

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/buttons.bootstrap.min.css')}}">
      <!-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/print/css/bootstrap.min.css')}}"> -->
    <!--  calendario -->
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@stop

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12 ">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">{{trans('report-receiving.customers-pending-to-pay')}}</div> -->
        {{-- {!! Form::open(array('url'=>'reports/customers_pending_to_pay')) !!} --}}
				<div class="panel-body">
          {!! Form::open(['url' => url('defailter'),
          'method' => 'GET', 'class' => 'nav-form navbar-lef', 'role' => 'search','id'=>'liquidity_index']) !!}
          <div class="row">
              <div class="col-md-4">
              </div>
              <div class="col-md-4">
                  <center><label for=""><b>Seleccione ruta</b></label></center>
                  <select class="form-control" name="route" id="route">
                    <option value="0">General</option>
                    @foreach ($rutas as $key => $value)
                      <option value="{{$value->id}}"
                        @if ($value->id==$ruta)
                          selected
                        @endif>
                        {{$value->name}}
                      </option>
                    @endforeach
                  </select>
              </div>
              <div class="col-md-4">
                  <br>
                  <img id="submitLoading" name="submitLoading" src="{{asset('img/200.gif')}}" style="display: none;" width="10%">
                  {!! Form::submit(trans('accounts.generate'), array('class' => 'btn btn-primary','id'=>'generar_grafica'))
                  !!}
              </div>
          </div>

          {!! Form::close() !!}
        </div>
              <div class="panel-body">
                <div class="panel-body table-responsive">
                  <table id="example" class="table table-striped table-bordered " cellspacing="0" width="100%">
                    <thead>
                      <th>No.</th>
                      <th>Cliente</th>
                      <th>Tarjeta activa</th>
                      <th>Cuotas atrasadas</th>
                    </thead>
                    <tbody>
                      <?php
                      $acumulador=0;
                      $atrasos=0;
                      $cuotas=0;
                      $promedio=0;?>
                       @foreach($data as $i=> $value)
                       <tr>
                         <td >{{$i+1}}</td>
                         <td> <a data-toggle="tooltip" title="Ver perfil del cliente" href="{{url('customers/profile/'.$value->id)}}">{{$value->name}}</a></td>
                         <td>{{$value->number_card}}</td>
                         <td>{{$value->ATRASOS}}</td>
                        <?php
                        $acumulador++;
                        $atrasos+=$value->ATRASOS;
                          ?>
                       </tr>
                       @endforeach
                       <tr>
                         <td></td>
                         <td>TOTAL</td>
                         <td></td>
                         <td style="text-align:right;background-color:#012814;color:white;">{{$atrasos}}</td>
                       </tr>
                    </tbody>
                  </table>
                </div>
                  <div class="panel-body">
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

<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery-1.12.4.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/assets/vendors/print/js/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/metisMenu.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

<script src="{{ asset('assets/vendors/print/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/dataTables.bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/buttons.bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/jszip.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/pdfmake.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/vfs_fonts.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/buttons.html5.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/buttons.print.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/print/js/buttons.colVis.min.js')}}" type="text/javascript"></script>


<script>
      $(document).ready(function() {
        var table = $('#example').DataTable({
          "language":{ "url": "{{ asset('assets/json/Spanish.json') }}" },
          dom: 'Bfrtip',
          lengthChange: false,
          "order": [],
          // buttons: ['copy', 'excel', 'pdf', 'colvis','print']
          buttons: [
            {
              extend:'copy',
              text:'COPIAR',
              @if ($ruta==0)
                title:'Reporte indice de liquidez clientes: General'
              @else
                title:'Reporte indice de liquidez clientes: {{$rutas->find($ruta)->name}}'
              @endif

            },
            {
              extend:'csv',
              text:'CSV',
              @if ($ruta==0)
                title:'Reporte indice de liquidez clientes: General'
              @else
                title:'Reporte indice de liquidez clientes: {{$rutas->find($ruta)->name}}'
              @endif
            },
            {
              extend:'excel',
              text:'EXCEL',
              @if ($ruta==0)
                title:'Reporte indice de liquidez clientes: General'
              @else
                title:'Reporte indice de liquidez clientes: {{$rutas->find($ruta)->name}}'
              @endif
            },
            {
              extend:'pdf',
              text:'PDF',
              @if ($ruta==0)
                title:'Reporte indice de liquidez clientes: General'
              @else
                title:'Reporte indice de liquidez clientes: {{$rutas->find($ruta)->name}}'
              @endif

            },
            {
              extend:'print',
              text:'IMPRIMIR',
              @if ($ruta==0)
                title:'Reporte indice de liquidez clientes: General'
              @else
                title:'Reporte indice de liquidez clientes: {{$rutas->find($ruta)->name}}'
              @endif
            }
          ]
        });
        table.buttons().container()
          .appendTo('#example_wrapper .col-sm-6:eq(0)');
      });
</script>
<!--Canlendario  -->
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script>
$("#admited_at").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY'}).parent().css("position :relative");
</script>
@stop
