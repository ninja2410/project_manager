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
                                                <hr>
                                                @if ($expense->photo!='')
                                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                                            data-target="#modalPhoto">
                                                        <span class="livicon" data-name="box" data-size="14" data-loop="true" data-c="#fff" data-hc="white">&nbsp;&nbsp;</span>
                                                        Ver comprobante
                                                    </button>

                                                    {{--                                                COMPBORANTE MODAL--}}
                                                <!-- Modal -->
                                                    <div class="modal fade" id="modalPhoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background-color:rgb(46, 144, 95); color:white">
                                                                    <h4 class="modal-title">Comprobante de gasto</h4>
                                                                </div>
                                                                <div class="modal-body" style="align-content: center; text-align: center;">
                                                                    <img src="<?php echo asset('images/expenses/'.$expense->photo); ?>" style="max-width: 750px;" />
                                                                </div>
                                                                <div class="modal-footer" style="text-align:center;">
                                                                    <button class="btn  btn-danger"  data-dismiss="modal" >Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{--                                                FINAL COSA DE COMRPBANTE--}}
                                                @endif


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
                                                                @if (isset($expense->account->account_name))
                                                                    {{ $expense->account->account_name }}
                                                                @else
                                                                    <p>{{trans('general.na')}}</p>
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>{{trans('bank_expenses.account_number')}}</td>
                                                            <td>
                                                                @if (isset($expense->account->account_number))
                                                                    {{ $expense->account->account_number }}
                                                                @else
                                                                    <p>{{trans('general.na')}}</p>
                                                                @endif
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
                                                            <td>{{trans('bank_expenses.status')}}</td>
                                                            <td>
                                                                <strong>{{ $expense->state->name}}</strong>
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
                                                        @if (isset($expense->user_assigned))
                                                            <tr>
                                                                <td>{{trans('expenses.user')}}</td>
                                                                <td>
                                                                    {{ $expense->user_assigned->name.' '.$expense->user_assigned->last_name }}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @if (isset($expense->credit_note_id))
                                                            <tr>
                                                                <td>{{trans('credit_notes.credit_note_singular')}}</td>
                                                                <td>
                                                                    <a target="_blank" href="{{url('credit_note/'.$expense->credit_note_id)}}">{{ $expense->creditNote->serie->name.'-'.$expense->creditNote->correlative }}</a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td>{{trans('bank_expenses.category')}}</td>
                                                            <td>
                                                                {{ $expense->category->name }}
                                                            </td>
                                                        </tr>

                                                        @if ($expense->route_id!='0')
                                                            <tr>
                                                                <td>{{trans('expenses.route')}}</td>
                                                                <td>
                                                                    {{!empty($expense->route->name) ? $expense->route->name : trans('general.na')}}
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if ($expense->recipient!='')
                                                            <tr>
                                                                <td>{{trans('bank_expenses.recipient')}}</td>
                                                                <td>
                                                                    {{$expense->recipient}}
                                                                </td>
                                                            </tr>
                                                        @endif

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
