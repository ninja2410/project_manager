@extends('layouts/default')
@section('title',trans('reconciliation.list'))
@section('page_parent',trans('reconciliation.reconciliation'))

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            {{trans('reconciliation.list')}}
          </h4>
        </div>
        <div class="panel-body">
          
          <table class="table table-striped table-bordered" id="table1">
            <thead>
              <tr>
                <th></th>
                <th data-priority="1001" style="width: 5%;">No</th>
                <th data-priority="2">{{trans('reconciliation.account')}}</th>
                <th data-priority="6">{{trans('reconciliation.m_y')}}</th>
                <th data-priority="4">{{trans('reconciliation.transit_revenue')}}</th>
                <th data-priority="4">{{trans('reconciliation.outsanding_payments')}}</th>
                <th style="width: 12%;">Acciones</th>
              </tr>
            </thead>
            @foreach($reconciliations as $i => $value)
            <tr>
              <td></td>
              <td>{{$i+1}}</td>
              <td>{{$value->account->account_name}}</td>
              <td>{{trans('months.'.$value->month)}}/{{$value->year}}</td>
              <td>@money($value->transit_revenue)</td>
              <td>@money($value->outstanding_payments)</td>
              <td>
                <a class="btn btn-info" href="{{ URL::to('bank_reconciliation/header/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Detalles">
                  <span class="glyphicon glyphicon-eye-open"></span>
                </a>
{{--                <a class="btn btn-success" href="{{ URL::to('bank_reconciliation/header/details' . $value->id ) }}" data-toggle="tooltip" data-original-title="Detalle de documentos">--}}
{{--                  <span class="glyphicon glyphicon-list"></span>--}}
{{--                </a>--}}
                </span>
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

</script>



























































@stop
