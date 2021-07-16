@extends('layouts/default')
@section('title',trans('expenses.expense_detail'))
@section('page_parent',trans('expenses.banks'))

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
                            {{trans('expenses.expense_detail')}}
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
                                        {{-- <a href="{{ url('/banks/expenses') }}" class="btn btn-danger btn-large button-block"> --}}

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
                                                <td>{{trans('bank_expenses.date')}}</td>
                                                <td>
                                                    <p class="user_name_max">{{ $expense->paid_at }}</p>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Monto</td>
                                                <td>
                                                    <p class="user_name_max">@money($expense->amount)</p>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>{{trans('bank_expenses.account')}}</td>
                                                <td>
                                                    {{ $expense->account->account_name }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{trans('bank_expenses.account_number')}}</td>
                                                <td>
                                                    {{ $expense->account->account_number }}
                                                </td>
                                            </tr>

                                            {{-- <tr>
                                                <td>{{trans('bank_expenses.expense_type')}}</td>
                                                <td>
                                                    {{ $expense->category->transaction_name }}
                                                </td>
                                            </tr> --}}
                                            <tr>
                                                <td>{{trans('bank_expenses.payment_method')}}</td>
                                                <td>
                                                    <strong>{{ $expense->pago->name }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{trans('bank_expenses.supplier')}}
                                                </td>
                                                <td>
                                                    {{!empty($expense->supplier->company_name) ? $expense->supplier->company_name : trans('general.na')}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{trans('bank_expenses.description')}}</td>
                                                <td>
                                                    {{ $expense->description }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{trans('bank_expenses.reference')}}</td>
                                                <td>
                                                    {{ $expense->reference }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{trans('bank_expenses.category')}}</td>
                                                <td>
                                                    {{ $expense->category->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{trans('bank_expenses.recipient')}}</td>
                                                <td>
                                                    {{ $expense->recipient}}
                                                </td>
                                            </tr>
                                            @if (isset($expense->stage_id))
                                                <tr>
                                                    <td>{{trans('project.stage')}}</td>
                                                    <td>
                                                        {{$expense->stage->name}}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if(isset($expense->bill_id))
                                                <tr>
                                                    <td>{{trans('expenses.document')}}</td>
                                                    <td><a href="{{url('receivings/complete/'.$expense->bill_id)}}" target="_blank"><span class="label label-sm label-success label-mini">Ver Documento</span></a></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td>{{trans('bank_expenses.status')}}</td>
                                                <td>
                                                    @if ($expense->status==2)
                                                        <span class="label label-danger">{{ trans('revenues.canceled') }}</span>
                                                    @else
                                                        @if ($expense->reconcilied==1)
                                                            <span class="label label-success">{{ trans('bank_expenses.active_status') }}</span> @else
                                                            <span class="label label-danger">{{ trans('bank_expenses.inactive_status') }}</span> @endif
                                                    @endif
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
