@extends('layouts/default') 
@section('title',trans('transfers.transfer_detail')) 
@section('page_parent',trans('transfers.banks'))

@section('header_styles')
<!-- Validaciones -->
{{--
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />  --}} {{--
        <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> --}} 
        @stop 
        @section('content')
        <section class="content">
            <!-- <div class="container"> -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                                    {{trans('transfers.transfer_detail')}}
                                </h3>
                                <span class="pull-right clickable">
                                    <i class="glyphicon glyphicon-chevron-up"></i>
                                </span>
                            </div>
                            
                            <div class="panel-body">
                                
                                
                                <div class="panel-body">
                                    <div class="col-md-2">
                                        <div class="panel-body">
                                            <a href="{{ URL::previous() }}" class="btn btn-danger btn-large button-block">
                                                {{-- <a href="{{ url('/banks/transfers') }}" class="btn btn-danger btn-large button-block"> --}}
                                                    <span class="livicon" data-name="undo" data-size="14" data-loop="true" data-c="#fff" data-hc="white">&nbsp;&nbsp;</span>
                                                    Regresar
                                                </a>
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="col-md-10">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped" id="users">
                                                        <tr>
                                                            <td>Fecha de ingreso</td>
                                                            <td>
                                                                <p class="user_name_max">{{ $payment->paid_at }}</p>
                                                            </td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td>Monto</td>
                                                            <td>
                                                                <p class="user_name_max">@money($payment->amount)</p>
                                                            </td>
                                                            
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>
                                                                {{trans('transfers.from_account')}}
                                                            </td>
                                                            <td>
                                                                {{ $payment->account->account_name }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{trans('transfers.to_account')}}</td>
                                                            <td>
                                                                {{ $revenue->account->account_name }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{trans('transfers.description')}}</td>
                                                            <td>
                                                                {{ $payment->description }}
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>{{trans('transfers.reference')}}</td>
                                                            <td>
                                                                {{ $payment->reference }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{trans('transfers.status')}}</td>
                                                            <td>
                                                                @if ($transfer->status==1)
                                                                <span class="label label-success">{{ trans('Activo') }}</span> @else
                                                                <span class="label label-danger">{{ trans('Inactivo') }}</span> @endif
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
                    <!-- </div> -->
                </section>
                @endsection
                
                @section('footer_scripts')
                
                <!-- Valiadaciones -->
                {{--
                    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script> --}} 
                    @stop