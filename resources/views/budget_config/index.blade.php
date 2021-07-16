@extends('layouts/default')
@section('title',trans('budget_config.index'))
@section('page_parent',trans('menu.parameters'))

@section('header_styles')
    <!-- Validaciones -->
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/bootstrap/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/fontawesome/css/all.css")}}" rel="stylesheet">
@stop
@section('content')
    <section class="content">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading clearfix">
                <span class="glyphicon glyphicon-list" aria-hidden="true">
                </span>
                    {{trans('budget_config.index')}}

                    <div class="pull-right">
                        <a href="{{ URL::to('budget_config/create') }}" class="btn btn-sm btn-default"><span
                                    class="glyphicon glyphicon-edit"></span> {{trans('budget_config.edit2')}} </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <h3>{{trans('budget_config.line_template')}}</h3>
                        <h4>{{trans('budget_config.groups')}}</h4>
                        <div class="row">
                            @foreach($line_template_config as $config)
                                <div class="col-lg-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading" style="background-color: {{$config->color}} !important;">
                                            <h3 class="panel-title">
                                                <i class="{{$config->icon}}"></i> {{$config->name}}
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="box-body">
                                                <p>{{$config->description}}</p>
                                                <p>
                                                    <label for="">{{trans('budget_config.custom_text')}}: </label> {{$config->custom_text}}
                                                </p>
                                                <p>
                                                    <label for="">{{trans('budget_config.icon')}}: </label> <i class="{{$config->icon}}"></i>
                                                </p>
                                                <p>
                                                    <label for="">{{trans('budget_config.order')}}: </label> {{$config->order}}
                                                </p>
                                                <p style="color: {{$config->color}}">
                                                    <label for="">{{trans('budget_config.color')}}: </label> {{$config->color}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer_scripts')
    {{-- FORMATO DE MONEDAS --}}
    <script src="{{asset('assets/js/vuejs/cleave.min.js')}} "></script>
    <script src="{{asset('assets/js/vuejs/vue-cleave.min.js')}} "></script>
    <!-- Valiadaciones -->
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
@stop