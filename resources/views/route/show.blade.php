@extends('layouts/default')

@section('title',trans('route.detail'))
@section('page_parent',trans('route.route')))

@section('header_styles')
<link href="{{asset('assets/css/pages/user_profile.css')}}" rel="stylesheet" type="text/css" />
<!-- Add fancyBox main CSS files -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/fancybox/jquery.fancybox.css')}}" media="screen" />
<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css"
    href="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.css')}}" />
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
                        <i class="livicon" data-name="align-left" data-size="18" data-c="#fff" data-hc="#fff"
                            data-loop="true"></i>
                        {{trans('route.detail')}}
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>

                <div class="row ">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped" id="users">
                                <tr>
                                    <td>{{ trans('route.name') }}</td>
                                    <td>
                                        <strong>{{$route->name}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('route.amount') }}</td>
                                    <td>
                                        <strong>{{$route->goal_amount}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('route.description') }}</td>
                                    <td>
                                        <strong>{{$route->description}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('route.state') }}</td>
                                    <td>
                                        <strong>{{$route->states->name}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('route.created_by') }}</td>
                                    <td>
                                        <strong>{{$route->creador->name}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('route.updated_by') }}</td>
                                    <td>
                                        <strong>{{$route->actualizacion->name }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table style="width:100%" class="table table-striped">
                                            <caption class="text-center"><strong>Encargados<strong></caption>
                                            @foreach($route->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table style="width:100%" class="table table-striped">
                                            <caption class="text-center"><strong>Clientes<strong></caption>
                                            @foreach($route->costumers as $key=>$value)
                                            <tr>
                                                <td>{{ $value->name }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="class=" col-md-12">
                        <center>`
                            <a class="btn btn-danger" href="{{ URL::previous() }}">
                                {{trans('button.back')}}
                            </a>
                            <a class="btn btn-info" href="{{ URL::to('routes/'.$route->id.'/edit') }}"
                                data-toggle="tooltip" data-original-title="Editar">
                                <span class="glyphicon glyphicon-edit"></span>&nbsp;Editar
                            </a>
                        </center>
                    </div>
                <hr>
            </div>
        </div>
</section>
@endsection
@section('footer_scripts')
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="{{asset('assets/vendors/fancybox/jquery.fancybox.pack.js')}}"></script>
<script type="text/javascript"
    src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-thumbs.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop