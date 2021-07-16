@extends('layouts/default')

@section('title',trans('supplier.list_suppliers'))
@section('page_parent',trans('supplier.suppliers'))

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop
@section('content')
<?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?> 
<section class="content">
  <!-- <div class="container"> -->
    <div class="row">
      <div class="col-md-12">

        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
              {{trans('supplier.list_suppliers')}}
            </h4>
            <div class="pull-right">
                @if(in_array('suppliers/create',$array_p))
              <a href="{{ URL::to('suppliers/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('supplier.new_supplier')}} </a>
              @endif
            </div>
          </div>
          <div class="panel-body">
            

            <table class="table table-striped table-bordered" id="table1">
              <thead>
                <tr>
                  <th></th>
                  <th data-priority="1001" style="width: 5%;"> <b>No.</b> </th>
                  <th data-priority="6" style="width: 12%;">{{trans('supplier.nit_supplier')}}</th>
                  <th data-priority="2">{{trans('supplier.nit_supplier').' / '.trans('supplier.company_name')}}</th>
                  <th data-priority="5" >{{trans('supplier.name')}}</th>
                  <th data-priority="4" data-priority="3">{{trans('supplier.phone_number')}}</th>
                  <th data-priority="7" >{{trans('supplier.email')}}</th>
                  <th data-priority="6">{{trans('supplier.has_credit')}}</th>
                  <th data-priority="6">{{trans('supplier.balance')}}</th>
                  <th style="width: 15%;">{{trans('supplier.actions')}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($supplier as $i=>$value)
                <tr>
                  <td></td>
                  <td>{{$i+1}}</td>
                  <td>{{$value->nit_supplier}}</td>
                  <td>{{ $value->company_name }}</td>
                  <td>{{ $value->name }}</td>
                  <td>{{ $value->phone_number }}</td>
                  <td>{{ $value->email }}</td>
                  <td style="text-align:right">@if($value->max_credit_amount==0)
                    <strong>No</strong>
                    @else @money($value->max_credit_amount)@endif
                  </td>
                  <td style="text-align:right"><a  @if(in_array('customers/statement',$array_p)) href="{{ URL::to('credit_suppliers/statement/' . $value->id ) }}" @endif data-toggle="tooltip" data-original-title="Estado de cuenta">@money($value->balance)</a></td>
                  <td>
                    <a class="btn btn-success" style="width: 40px" data-toggle="tooltip" data-original-title="Ver perfil" href="{{URL::to('suppliers/'.$value->id)}}">
                      <span class="glyphicon glyphicon-user"></span>
                    </a>
                    <a class="btn btn-info" style="width: 40px" title="{{trans('supplier.edit')}}" @if(in_array('suppliers/edit',$array_p)) href="{{ URL::to('suppliers/' . $value->id . '/edit') }}" @else disabled="disabled" @endif data-toggle="tooltip" data-original-title="trans('supplier.edit')" >
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    {!! Form::open(array('url' => 'suppliers/' . $value->id, 'class' => 'pull-right')) !!}
                    {!! Form::hidden('_method', 'DELETE') !!}
                    <button type="submit" class="btn btn-primary btn-danger"  @if(in_array('customers/delete',$array_p)) type="submit" @else disabled="disabled" @endif  data-toggle="tooltip" data-original-title="Borrar"  >
                      <span class="glyphicon glyphicon-remove-circle"></span>
                    </button>
                    {!! Form::close() !!}
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- </div> -->
  </section>
  @endsection
  @section('footer_scripts')


  <script type="text/javascript">
    $(document).ready(function(){
      setDataTable("table1", [], "{{asset('assets/json/Spanish.json')}}");
      
    });


    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
  @stop
