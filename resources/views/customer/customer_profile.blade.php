@extends('layouts/default')

@section('title',trans('customer.profile'))
@section('page_parent',trans('customer.customers'))

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
            {{trans('customer.profile')}}
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
                    <img src="{!! asset('images/customers/') . '/' . $data->avatar !!}" class="img-responsive user_image" alt="{{$data->name}}" style="max-width: 250px;" />
                  </div>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <center>
                <a class="btn btn-danger" href="{{ URL::previous() }}">
                  {{trans('button.back')}}
                </a>                
                <a class="btn btn-info" @if(in_array('customers/edit',$array_p)) href="{{ URL::to('customers/' . $data->id . '/edit') }}"  @else disabled="disabled" @endif data-toggle="tooltip" data-original-title="Editar">
                  <span class="glyphicon glyphicon-edit"></span>&nbsp;Editar
                </a>
              </center>
            </div>            
          </div>
          <div class="col-md-8">
            <ul class="nav nav-tabs ul-edit responsive">            
              <li>
                <a href="#tab-change-pwd" data-toggle="tab">
                  <i class="livicon" data-name="user" data-size="16" data-c="#01BC8C" data-hc="#01BC8C" data-loop="true"></i> Datos del cliente
                </a>
              </li>              
            </ul>
            <div class="table-responsive">
              <table class="table  table-striped" id="users">
                <tr>
                  <td>
                    NIT
                  </td>
                  <td>
                    {{$data->nit_customer}}
                  </td>
                </tr>
                <tr>
                  <td>Nombre</td>
                  <td>
                    {{strtoupper($data->name)}}
                  </td>
                </tr>
                <tr>
                  <td>
                    {{trans('customer.customer_code')}}
                  </td>
                  <td>
                    {{$data->customer_code}}
                  </td>
                </tr>
                
                <tr>
                  <td>Email</td>
                  <td>
                    {{$data->email}}
                  </td>
                </tr>
                <tr>
                  <td>
                    Teléfono
                  </td>
                  <td>
                    {{$data->phone_number}}
                  </td>
                </tr>
                <tr>
                  <td>Tiene crédito?</td>
                  <td>
                    <p class="user_name_max">@money( $data->max_credit_amount)</p>
                  </td>                    
                </tr>
                <tr>
                  <td>Días de crédito</td>
                  <td>
                    <p class="user_name_max">{{$data->days_credit}}</p>
                  </td>                    
                </tr>
                <tr>
                  <td>Saldo</td>
                  <td>
                    <table style="width:100%">
                    <tr>
                      <td>@money($data->balance) &nbsp;&nbsp;</td>
                      <td style="text-align:right">
                          @if(in_array('customers/statement',$array_p))
                          <a class="btn btn-primary" href="{{ URL::to('credit/statement/' . $data->id ) }}" data-toggle="tooltip" data-original-title="Estado de Cuenta">
                            <span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;{{trans('credit.statement')}}
                          </a>
                          @endif
                      </td>
                    </tr>
                  </table>             
                  </td>                    
                </tr>
                <tr>
                  <td>{{trans('customer.positive_balance')}}</td>
                  <td>@money($data->positive_balance)</td>
                </tr>
                <tr>
                  <td>Ruta</td>
                  <td>
                    @if(isset($data->routes[0]->name))
                    <p>{{$data->routes[0]->name}}</p>
                    @else
                    <p>No esta asignado a una ruta</p>
                    @endif
                  </td>                    
                </tr>
                <tr>
                  <td>
                    Información adicional
                  </td>
                  <td>
                    {{$data->comment}}
                  </td>
                </tr>
                <tr>
                  <td>DPI</td>
                  <td>
                    {{$data->dpi}}
                  </td>
                </tr>
                
                <tr>
                  <tr>
                    <td>
                      Fecha de Nacimiento
                    </td>
                    <td>
                      {{date('d/m/Y', strtotime($data->birthdate))}}
                    </td>
                  </tr>
                  <tr>
                    <td>Estado civil</td>
                    <td>
                      {{$data->marital_status}}
                    </td>
                  </tr>
                  <tr>
                    <td>Dirección</td>
                    <td>
                      {{$data->address}}
                    </td>
                  </tr>
                  <tr>
                    <td>Departamento</td>
                    <td>
                      {{$data->state}}
                    </td>
                  </tr>
                  <tr>
                    <td>Municipio/Ciudad</td>
                    <td>
                      {{$data->city}}
                    </td>
                  </tr>
                  <tr>
                    <td>Fecha de registro</td>
                    <td>
                      {{date('d/m/Y H:i', strtotime($data->created_at))}}
                    </td>
                  </tr>
                  <tr>
                    <td>Ultima modificación</td>
                    <td>
                      {{date('d/m/Y H:i', strtotime($data->updated_at))}}
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
  
  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
  
  
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/jquery.fancybox.pack.js')}}"></script>
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-thumbs.js')}}"></script>
  <!-- Add Media helper (this is optional) -->
  <script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-media.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/pages/gallery.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
  @stop
  