@extends('layouts/default')

@section('title',$data->company_name)
@section('page_parent',trans('supplier.supplier_detail'))

@section('header_styles')
<link href="{{asset('assets/css/pages/user_profile.css')}}" rel="stylesheet" type="text/css" />
<!-- Add fancyBox main CSS files -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/fancybox/jquery.fancybox.css')}}" media="screen" />
<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.css')}}" />
<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-thumbs.css')}}" />
<!--page level css end-->
@stop
@section('content')
<?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?> 
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
            {{trans('supplier.supplier_detail')}}
          </h3>
          <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
        </div>

        <div class="row ">
          <div class="col-md-4">
            <div class="form-group">
              <div class="text-center">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                  <div class="fileinput-new thumbnail">
                    <img src="{!! asset('images/suppliers/') . '/' . $data->avatar !!}" class="img-responsive user_image" alt="{{$data->company_name}}" style="max-width: 250px;" />
                  </div>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <center>
                <a class="btn btn-danger" href="{{ url('suppliers') }}">
                  {{trans('button.back')}}
                </a>
                <a class="btn btn-info" @if(in_array('suppliers/edit',$array_p)) href="{{ URL::to('suppliers/' . $data->id . '/edit') }}" @else disabled="disabled" @endif data-toggle="tooltip" data-original-title="Editar">
                  <span class="glyphicon glyphicon-edit"></span>&nbsp;Editar
                </a>
              </center>
            </div>
          </div>
          <div class="col-md-8">
            <ul class="nav nav-tabs ul-edit responsive">
              <li>
                <a href="#tab-change-pwd" data-toggle="tab">
                  <i class="livicon" data-name="user" data-size="16" data-c="#01BC8C" data-hc="#01BC8C" data-loop="true"></i> Datos del proveedor
                </a>
              </li>
            </ul>
            <div class="table-responsive">
              <table class="table  table-striped" id="users">
                <tr>
                <td>{{ trans('supplier.company_name') }}</td>
                  <td>
                    {{$data->company_name}}
                  </td>
                </tr>
                <tr>
                  <td>{{ trans('supplier.nit_supplier') }}</td>
                  <td>
                    {{$data->nit_supplier}}
                  </td>
                </tr>
                <tr>
                  <td>{{ trans('supplier.email') }}</td>
                  <td>
                    {{$data->email}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ trans('supplier.phone_number') }}
                  </td>
                  <td>
                    {{$data->phone_number}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ trans('supplier.name') }}
                  </td>
                  <td>
                    {{$data->name}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{ trans('supplier.address') }}
                  </td>
                  <td>
                    {{$data->address}}
                  </td>
                </tr>
                <tr>
                  <td>{{ trans('supplier.type') }}</td>
                  <td>
                    N/A
                  </td>
                </tr>
                <tr>
                  <td>{{ trans('supplier.bank') }}</td>
                  <td>
                    {{$data->name_bank}}
                  </td>
                </tr>
                <tr>
                  <td>{{ trans('supplier.account_number') }}</td>
                  <td>
                    {{$data->account_number}}
                  </td>
                </tr>
                <tr>
                  <td>{{ trans('supplier.name_on_checks') }}</td>
                  <td>
                    {{$data->name_on_checks}}
                  </td>
                </tr>
                <tr>
                  <td>{{ trans('supplier.credit_amount') }}</td>
                  <td>
                    @money($data->max_credit_amount)
                  </td>
                </tr>
                <tr>
                  <td>{{trans('supplier.balance')}}</td>
                  <td>
                    <table style="width: 100%">
                      <tr>
                        <td>@money($data->balance)</td>
                        <td style="text-align:right">
                            @if(in_array('suppliers/statement',$array_p))
                          <a class="btn btn-primary" href="{{ URL::to('credit_suppliers/statement/' . $data->id ) }}" data-toggle="tooltip" data-original-title="Estado de Cuenta">
                            <span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;{{trans('credit.statement')}}
                          </a>
                          @endif
                        </td>
                      </tr>
                    </table>
                  </td>

                </tr>
                <tr>
                  <td>{{ trans('supplier.days_credit') }}</td>
                  <td>
                    {{$data->days_credit}}
                  </td>
                </tr>
                <tr>
                  <td>{{ trans('supplier.created_at') }}</td>
                  <td>
                    {{date('d/m/Y', strtotime($data->created_at))}}
                  </td>
                </tr>
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
  <!-- Add mousewheel plugin (this is optional) -->
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/jquery.fancybox.pack.js')}}"></script>
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-thumbs.js')}}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
  @stop
