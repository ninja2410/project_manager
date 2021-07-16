@extends('layouts/default')

@section('title',trans('desk_closing.list'))
@section('page_parent',trans('desk_closing.desk'))


@section('header_styles')

<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12 ">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
              {{trans('desk_closing.list')}}
          </h4>
          <div class="pull-right">
              <a href="{{ URL::to('desk_closing/create/'.$idCaja) }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('desk_closing.new')}} </a>
          </div>
      </div>
        <div class="panel-body">
          {!! Form::open(array('url'=>'desk_closing/index/'.$idCaja)) !!}
          <div class="row">
            <div class="col-md-4">
              <center><label for=""><b>Fecha inicial</b></label></center>
              <input type="text" name="date1"  id='admited_at'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
            </div>
            <div class="col-md-4">
              <center><label for=""><b>Fecha final</b></label></center>
              <input type="text" name="date2"  id='admited_at2'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}">
            </div>
            <div class="col-md-4">
              <br>
              {!! Form::submit(trans('Buscar'), array('class' => 'btn btn-primary ')) !!}
            </div>
          </div>
          {!! Form::close() !!}
          <hr>
          <div class="row">
            <div class="col-md-12">
              <table class="table table-striped table-bordered" id="table1">
                <thead>
                  <th style="width: 5%;">No.</th>
                  <th style="text-align: center">Cuenta</th>
                  <th style="text-align: center">Fecha inicial</th>
                  <th style="text-align: center">Fecha final</th>
                  <th style="text-align: center">Efectivo</th>
                  <th style="text-align: center">Depósito</th>
                  <th style="text-align: center">Cheque</th>
                  <th style="text-align: center">Tarjeta</th>
                  <th style="text-align: center">Tranferencia</th>
                  <th style="text-align: center">Total</th>
                  <th style="text-align: center">Acciones</th>
                </thead>
                <tbody>
                  @foreach ($desk as $key=>$value)
                  <tr>
                    <td style="text-align: center;">{{$key+1}}</td>
                    <td>{{$value->account_name}}</td>
                    <td>{{date('d/m/Y H:m:01', strtotime($value->startDate))}}</td>
                    <td>{{date('d/m/Y H:m:00', strtotime($value->finalDate))}}</td>
                    <td class="text-right">@money($value->cash_amount)</td>
                    <td class="text-right">@money($value->deposit_amount)</td>
                    <td class="text-right">@money($value->check_amount)</td>
                    <td class="text-right">@money($value->card_amount)</td>
                    <td class="text-right">@money($value->transfer_amount)</td>
                    <td class="text-right">@money($value->total)</td>
                    <td><a class="btn btn-success" data-toggle="tooltip" data-original-title="Imprimir" href="{{ URL::to('desk_closing/show/' . $value->id ) }}">
                          <span class="glyphicon glyphicon-print"></span>
                        </a>
                  </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                
                  </tr>
              </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/general-function/currency_format.js') }}" ></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#table1').DataTable({
      "language": {
        "url": "{{ asset('assets/json/Spanish.json') }}"
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
              title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'excel',
              title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'pdf',
              title: document.title+' Del '+$('#admited_at').val()+' al: '+$('#admited_at2').val(),
              exportOptions:{
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend:'print',
              text: 'Imprimir',
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
