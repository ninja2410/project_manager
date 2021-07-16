@extends('layouts/default') 
@section('title',trans('transfers.transfers')) 
@section('page_parent',trans('transfers.banks'))

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            {{trans('transfers.transfers')}}
          </h4>
          <div class="pull-right">
            <a href="{{ URL::to('banks/transfers/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('transfers.new_transfer')}} </a>
          </div>
        </div>
        <div class="panel-body">
            
          <hr /> 
          <table class="table table-striped table-bordered" id="table1">
            <thead>
              <tr>
                <th></th>
                <th style="width:5%;">No</th>
                <th>Fecha</th>
                <th>{{trans('transfers.from_account')}}</th>
                <th>{{trans('transfers.to_account')}}</th>
                <th>{{trans('transfers.description')}}</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            @foreach($transfers as $i => $value)
            <tr>
              <td></td>
              <td>{{$i+1}}</td>
              <td style="font-size: 12px;">{{$value->payment->paid_at}}</td>
              <td style="font-size: 12px;">{{$value->payment->account->account_name}}</td>
              <td style="font-size: 12px;">{{$value->revenue->account->account_name}}</td>
              <td style="font-size: 12px;">{{$value->payment->description}}</td>
              <td>@money($value->payment->amount)</td>
              <td style="font-size: 12px;">
                @if ($value->status==1)
                <span class="label label-success">{{ trans('Activo') }}</span> @else
                <span class="label label-danger">{{ trans('Inactivo') }}</span> @endif
              </td>
              <td>
                <a class="btn btn-warning" href="{{ URL::to('banks/transfers/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Detalles">
                  <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Detalles
                </a>
              </td>
            </tr>
            @endforeach
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('footer_scripts')

<script type="text/javascript">
  $(document).ready(function() {
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
























































































@stop