@extends('layouts/default')

@section('title',trans('customer.list_customers'))
@section('page_parent',trans('customer.customers'))

@section('header_styles')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css') }}" /> --}}

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop
@section('content')
<?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?>
<section class="content">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
          {{trans('customer.list_customers')}}
        </h4>
        <div class="pull-right">
          @if(in_array('customers/create',$array_p))
          <a href="{{ URL::to('customers/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('customer.new_customer')}} </a>
          @endif
        </div>
      </div>

      <div class="panel-body table-responsive">
        {{--  --}}
        <div class="table-responsive-lg table-responsive-sm table-responsive-md">
        <table class="table table-striped table-bordered" id="table1">
          <thead>
            <tr>
              <th></th>
              <th data-priority="1001" style="width: 5%;">{{trans('No.')}}</th>
              <th data-priority="1" style="width: 10%;">{{trans('customer.nit_customer')}}</th>
              <th data-priority="2" style="width: 10%;">{{trans('customer.customer_code')}}</th>
              <th data-priority="3" style="width: 20%;">{{trans('customer.name')}}</th>
              <th data-priority="5">{{trans('customer.email')}}</th>
              <th data-priority="4">{{trans('customer.phone_number')}}</th>
              <th data-priority="6" style="width: 8%;">{{trans('customer.has_credit')}}</th>
              <th data-priority="6">{{trans('customer.positive_balance')}}</th>
              <th data-priority="6">{{trans('customer.balance')}}</th>
              <th style="width: 19%;">{{trans('customer.actions')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($customer as $i=> $value)
            <tr>
              <td></td>
              <td>{{ $i+1 }}</td>
              <td style="font-size:12px;">{{$value->nit_customer}}</td>
              <td style="font-size:12px;">{{$value->customer_code}}</td>
              <td>{{ strtoupper($value->name) }}</td>
              <td>{{ $value->email }}</td>
              <td>{{ $value->phone_number }}</td>
              <td style="text-align:right;font-size:12px">@if($value->max_credit_amount==0)
                <strong>No</strong>
                @else @money($value->max_credit_amount)@endif
              </td>
              <td>@money($value->positive_balance)</td>
              <td style="text-align:right;font-size:12px"><a  @if(in_array('customers/statement',$array_p)) href="{{ URL::to('credit/statement/' . $value->id ) }}" @endif data-toggle="tooltip" data-original-title="Estado de cuenta">@money($value->balance)</a></td>
              <td>
                <a class="btn btn-success" style="width: 40px" data-toggle="tooltip" data-original-title="Ver perfil" href="{{URL::to('customers/profile/'.$value->id)}}">
                  <span class="glyphicon glyphicon-user"></span>
                </a>
                <a class="btn btn-info" style="width: 40px" @if(in_array('customers/edit',$array_p)) href="{{ URL::to('customers/' . $value->id . '/edit') }}" @else disabled="disabled" @endif data-toggle="tooltip" data-original-title="Editar">
                  <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a class="btn btn-primary" style="width: 40px" @if(in_array('customers/statement',$array_p)) href="{{ URL::to('credit/statement/' . $value->id ) }}" @else disabled="disabled" @endif  data-toggle="tooltip" data-original-title="Estado de cuenta">
                  <span class="glyphicon glyphicon-list"></span>
                </a>
                <button type="button" style="width: 40px" class="btn btn-danger" data-toggle="modal" @if(in_array('customers/delete',$array_p)) data-target="#modal{!! $value->id !!}" @else disabled="disabled" @endif>
                  <span class="glyphicon glyphicon-remove-circle"></span>
                </button>
                {{--Begin modal--}}
                <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal{!! $value->id !!}" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-danger">
                        <h4 class="modal-title">Confirmación Eliminar</h4>
                      </div>
                      <div class="modal-body">
                        <div class="text-center">
                          {!! $value->name !!}
                          <br>
                          ¿Desea eliminar cliente?
                        </div>
                      </div>
                      <div class="modal-footer" style="text-align:center;">
                        {!! Form::open(array('url' => 'customers/' . $value->id, 'class' => 'pull-right')) !!}
                        {!! Form::hidden('_method', 'DELETE') !!}
                        <button type="submit" class="btn  btn-info">Aceptar</button>
                        <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                        {!! Form::close() !!}
                      </div>
                    </div>
                  </div>
                </div>
                {{--End modal--}}
              </td>

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
@endsection
@section('footer_scripts')

{{-- <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script> --}}


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
          extend: 'copy',
          text: 'Copiar',
          title: document.title,
          exportOptions: {
            columns: 'th:not(:last-child)'
          }
        },
        {
          extend: 'excel',
          title: document.title,
          exportOptions: {
            columns: 'th:not(:last-child)'
          }
        },
        {
          extend: 'pdf',
          title: document.title,
          exportOptions: {
            columns: 'th:not(:last-child)'
          },
          customize: function(doc) {
                doc.styles.tableHeader.fontSize = 8;
                doc.defaultStyle.fontSize = 6;
                doc.styles.tableFooter.fontSize = 8;
                }
        },
        {
          extend: 'print',
          text: 'Imprimir',
          title: document.title,
          exportOptions: {
            columns: 'th:not(:last-child)'
          }
        }

        ]
      }
      ],

    });
  });

  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@stop
