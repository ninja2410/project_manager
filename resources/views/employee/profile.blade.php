@extends('layouts/default')

@section('title',trans('users.profile').' '.$data->name)
@section('page_parent',trans('users.access'))

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
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                        {{trans('users.profile')}}
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>
                
                <div class="row ">
                    <div class="col-md-4">
                        <br>
                        <div class="form-group">
                            <div class="text-center">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <img src="{!! asset('images/users/') . '/' . $data->avatar !!}" class="img-responsive user_image" alt="{{$data->name}}" style="max-width: 250px;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <center>
                                <a class="btn btn-danger" href="{{ url('employees') }}">
                                    {{trans('button.back')}}
                                </a>
                            </center>
                        </div>            
                    </div>
                    <div class="col-md-8">
                        <br>
                        <ul class="nav nav-tabs ul-edit responsive">            
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">
                                    <i class="livicon" data-name="user" data-size="16" data-c="#01BC8C" data-hc="#01BC8C" data-loop="true"></i>
                                    Datos básicos
                                </a>
                            </li>
                            <li>
                                <a href="#tab2" data-toggle="tab">
                                    <i class="livicon" data-name="barchart" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                                    Datos personales</a>
                                </li>
                                <li>
                                    <a href="#tab3" data-toggle="tab">
                                        <i class="livicon" data-name="thermo-down" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                                        Datos laborales</a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content mar-top">
                                    <div id="tab1" class="tab-pane fade active in">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">
                                                            
                                                        </h3>
                                                        
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table  table-striped" id="users">
                                                            <tr>
                                                                <td>
                                                                    {{trans('employee.number')}}
                                                                </td>
                                                                <td>
                                                                    {{$data->number}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Nombre</td>
                                                                <td>
                                                                    {{$data->name.' '.$data->last_name}}
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>{{trans('employee.email')}}</td>
                                                                <td>
                                                                    {{$data->email}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    {{trans('employee.mobile')}}
                                                                </td>
                                                                <td>
                                                                    {{$data->mobile}}
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                            <tr>
                                                                <td>
                                                                    {{trans('employee.comments')}}
                                                                </td>
                                                                <td>
                                                                    {{$data->comments}}
                                                                </td>
                                                            </tr>                                                
                                                            <tr>
                                                                <td>Estado</td>
                                                                <td>
                                                                    {{DB::table('state_cellars')->where('id', $data->user_state)->value('state_cellars.name')}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Fecha de creación</td>
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
                                    <div id="tab2" class="tab-pane fade">
                                        <div class="row">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                    <!-- Condición medica -->
                                                </h3>
                                                
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table  table-striped" id="users">
                                                    <tr>
                                                        <td>
                                                            {{trans('employee.birthdate')}}
                                                        </td>
                                                        <td>
                                                            {{date('d/m/Y', strtotime($data->birthdate))}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.dpi')}}</td>
                                                        <td>
                                                            {{$data->DPI}}
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td>{{trans('employee.address')}}</td>
                                                        <td>
                                                            {{$data->address}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.alt_address')}}</td>
                                                        <td>
                                                            {{$data->alternative_address}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.nationality')}}</td>
                                                        <td>
                                                            {{$data->nationality}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.phone')}}</td>
                                                        <td>
                                                            {{$data->phone}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.em_name')}}</td>
                                                        <td>
                                                            {{$data->emergency_name}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.em_phone')}}</td>
                                                        <td>
                                                            {{$data->emergency_phone}}
                                                        </td>
                                                    </tr>
                                                    
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab3" class="tab-pane fade">
                                        <div class="row">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                    <!-- Condición medica -->
                                                </h3>
                                                
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table  table-striped" id="users">
                                                    <tr>
                                                        <td>{{trans('employee.date_hire')}}</td>
                                                        <td>
                                                            {{$data->date_hire}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.date_dimissal')}}</td>
                                                        <td>
                                                            {{$data->date_dimissal}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.igss')}}</td>
                                                        <td>
                                                            {{$data->no_IGSS}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.shoes')}}</td>
                                                        <td>
                                                            {{$data->shoe_size}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.trouser')}}</td>
                                                        <td>
                                                            {{$data->trouser_size}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.shirt')}}</td>
                                                        <td>
                                                            {{$data->shirt_size}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.sales_goal')}}</td>
                                                        <td>
                                                            @money($data->sales_goal)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.collection_goal')}}</td>
                                                        <td>
                                                            @money($data->collection_goal)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{trans('employee.expenses_max')}}</td>
                                                        <td>
                                                            @money($data->expenses_max)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{trans('employee.roles')}}</td>
                                                        <td>
                                                            @foreach($data->roles as $key => $item)
                                                            <span class="badge badge-info">{{ $item->role }}</span>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    
                                                </table>
                                            </div>
                                        </div>
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
            