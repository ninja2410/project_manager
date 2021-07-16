@extends('layouts/default')

@section('title',trans('Anulación de facturas de venta'))
@section('page_parent',trans('Anulaciones'))

@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/sale.js', array('type' => 'text/javascript')) !!}
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('sale.list_cancel_bill')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>
        
        <div class="panel-body">
          <hr />
          <input type="hidden" name="path" id="path" value="{{ url('/') }}">
          @if (Session::has('message'))
          <div class="alert alert-success">{{ Session::get('message') }}</div>
          @endif
          {!! Html::ul($errors->all()) !!}
          <table class="table table-striped table-bordered" id="tableIdSales">
            <thead>
              <tr>
                <th></th>
                <th>No.</th>
                <th>{{trans('Serie')}}</th>
                <th>{{trans('report-sale.date')}}</th>
                <th>{{trans('report-sale.items_cant')}}</th>
                <th>{{trans('report-sale.sold_by')}}</th>
                <th>{{trans('report-sale.sold_to')}}</th>
                <th>{{trans('report-sale.total')}}</th>
                <th>{{trans('report-sale.actions')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data_sales as $i=> $value)
              <tr>
                <td></td>
                <td>{{$i+1}}</td>
                <td>
                  {{!empty($value->serie->document->name) ? $value->serie->document->name: trans('general.na')}}
                  {{!empty($value->serie->name) ? $value->serie->name: ''}}
                  {{!empty($value->correlative) ? $value->correlative: ''}}
                </td>
                <td>{!!date_format($value->created_at, 'd/m/Y H:i:s') !!}</td>
                <td style="text-align: center;">{{$value->sale_items->count()}}</td>
                <td>{{ $value->user->name }}</td>
                <td>{{ $value->customer->name or '' }}</td>
                <td style="text-align: right;">Q<?php echo number_format($value->total_cost,2) ;  ?></td>
                <td>
                  <a  data-toggle="modal"  data-href="#ajax-modal" href="#ajax-modal" class="btn btn-danger" onclick="clickBtn(this);" id="{{$value->id}}">
                    <span class="glyphicon glyphicon-minus-sign"></span> {{trans('Anular')}}
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
    <div class="modal fade in" id="ajax-modal" tabindex="-1" role="dialog" aria-hidden="false">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header" >
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <center>
              <h2 class="modal-title">Anulación de documento</h2>
            </center>
            <div id="dvContents">
              
            </div>
          </div>
          <div class="modal-body">
            <center>
              <h3>Esta seguro que desea anular la factura?</h3>
            </center>
          </div>
          <div class="modal-footer">
            <!-- <input type="button"  id="btnPrint" value="Si" class="btn btn-danger" /> -->
            {!! Form::open(array('url' => 'cancel_bill/anular','method' => 'get', 'class' => 'form-horizontal','id'=>'id_form_bodega')) !!}
            <input type="hidden" name="id_elemento" value="0" id="elemento_a_borrar">
            <input type="button" data-dismiss="modal" value ="No" class="btn btn-success">
            <!-- <a href="" id="anular_factura"class="btn btn-danger">Si</a> -->
            <input type="submit" name="" value="Si" id="anular_factura" class="btn btn-danger">
            {!! Form::close() !!}
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript">
  $(document).ready(function(){
    $('#tableIdSales').DataTable({
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
  
  function clickBtn(button)
  {
    var elemento_a_borrar=document.getElementById('elemento_a_borrar');
    elemento_a_borrar.value=button.id;
  }
  
  anular_factura=document.getElementById('anular_factura');
  anular_factura.addEventListener('click',function(){
    var id_delete=document.getElementById('elemento_a_borrar');
    console.log(id_delete.value);
    
  });
  
  
  
</script>
@stop
