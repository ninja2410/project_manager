@extends('layouts/default')
@section('title',trans('accounts.edit_cash_register'))
@section('page_parent',trans('accounts.banks'))

@section('header_styles')
    <!-- Validaciones -->
    {{--
        <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
    --}}
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')
    <section class="content">
        <!-- <div class="container"> -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('accounts.edit_cash_register')}}
                        </h3>
                        <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                    </div>
                    <div class="panel-body">
                        {{--  --}}
                        {!! Form::model($account, array('route' => array('banks.cash_register.update', $account->id),
                        'method' => 'PUT', 'files' => true, 'id'=>'frmSup')) !!}
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('account_name', trans('accounts.name').' *') !!}
                                    {!! Form::text('account_name', Input::old('account_name'), array('class' =>
                                    'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('almacen_id', trans('accounts.storage')) !!}
                                    <select class="form-control" title="" name="almacen_id" id="almacen_id">
                                        <option value="">--Seleccione--</option>
                                        @foreach($almacen as $item)
                                            <option value="{!! $item->id !!}" @if($account->almacen_id === $item->id)
                                            selected="selected" @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                {{-- <div class="form-group">
                                    {!! Form::label('account_number', trans('accounts.account_number')) !!} {!!
                                    Form::text('account_number', Input::old('account_number'),
                                    array('class' => 'form-control')) !!}
                                </div> --}}
                                <div class="form-group">
                                    {!! Form::label('account_responsible', trans('accounts.account_responsible').' *') !!}
                                    <select class="form-control" title="trans('accounts.account_responsible')"
                                            name="account_responsible" id="account_responsible" required>
                                        @foreach($users as $item)
                                            <option value="{!! $item->id !!}" @if($account->account_responsible===$item->id)
                                            selected='selected' @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('account_type_id', trans('accounts.account_type').' *') !!}
                                    <select class="form-control" title="" name="account_type_id" id="account_type_id"
                                            required>
                                        @foreach($account_type as $item)
                                            <option value="{!! $item->id !!}" @if(old('account_type_id')===$item->id)
                                            selected="selected" @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            {{--
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                {!! Form::label('account_type', trans('accounts.account_type').' *') !!}
                                                <select class="form-control" title="" name="account_type" id="account_type" required>
                                                    <option value="" @if($account->account_type=='') selected='selected' @endif>Seleccione tipo</option>
                                                    <option value="Efectivo" @if($account->account_type=='Efectivo') selected='selected' @endif>Efectivo</option>
                                                    <option value="Monetarios" @if($account->account_type=='Monetarios') selected='selected' @endif>Monetarios</option>
                                                    <option value="Ahorros" @if($account->account_type=='Ahorros') selected='selected' @endif>Ahorros</option>
                                                    <option value="Préstamo" @if($account->account_type=='Préstamo') selected='selected' @endif>Préstamo</option>
                                                    <option value="Inversión" @if($account->account_type=='Inversión') selected='selected' @endif>Inversión</option>
                                                    <option value="Tarjeta de crédito" @if($account->account_type=='Tarjeta de crédito') selected='selected' @endif>Tarjeta de crédito</option>
                                                    <option value="Otro" @if($account->account_type=='Otro') selected='selected' @endif>Otro</option>
                                                </select>
                                            </div>
                                        </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('status', trans('accounts.status').' *') !!}
                                    <select class="form-control" title="trans('accounts.status')" name="status" id="status"
                                            required>
                                        @foreach($state as $item)
                                            <option value="{!! $item->id !!}" @if($account->status===$item->id) selected='selected'
                                                    @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('fixed_amount',trans('accounts.fixed_amount' ))!!}
                                    {!! Form::text('fixed_amount',Input::old('fixed_amount'),
                                    array('class'=> 'form-control dinero','required'=>'required')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('opening_balance',trans('accounts.opening_balance' ))!!}* {!!
                        Form::text('opening_balance',Input::old('opening_balance'),
                        array('class' => 'form-control','required'=>'required','disabled'=>'disabled')) !!}
                                </div>

                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {!! Form::label('max_amount', trans('accounts.max_amount')) !!} {!!
                        Form::text('max_amount', Input::old('max_amount'), array('class'
                        => 'form-control', 'placeholder'=>'Vacío ó 0.00 para no poner límite')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" name="bank_name" id="bank_name" value="{{$account->bank_name}}"> {{-- <input type="hidden"
                                name="status" id="status" value="1"> --}}
                            <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">

                            <br>
                            <div class="col-lg-12">
                                @include('partials.buttons',['cancel_url'=>"/banks/cash_register"])
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </section>
@endsection

@section('footer_scripts')
    <script>
        // var bank_id = document.getElementById('bank_id');
        // bank_id.addEventListener('change', function () {
        //     $("#bank_name").val($("#bank_id option:selected").text());
        //     console.log($("#bank_id option:selected").text());
        // })

    </script>
    <!-- Valiadaciones -->
    {{--
                    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript ">
    </script> --}}
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#account_name').focus();
            $('#frmSup').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                message: 'Valor no valido',
                fields: {
                    account_name: {
                        validators: {
                            notEmpty: {
                                message: 'Debe ingresar nombre de la cuenta.'
                            },
                            stringLength: {
                                min: 3,
                                message: 'Debe ingrear por lo menos 3 caracteres.'
                            }
                        }
                    },
                    account_number: {
                        validators: {
                            notEmpty: {
                                message: 'Debe ingresar el número de la cuenta.'
                            },
                            stringLength: {
                                min: 1,
                                max: 20,
                                message: 'El número de cuenta debe tener al menos 1 digitos.'
                            },
                            regexp: {
                                regexp: /^[0-9-?]+$/,
                                message: 'Ingrese un número válido.'
                            }
                        }
                    },
                    account_responsible: {
                        validators: {
                            notEmpty: {
                                message: 'Debe seleccionar un usuario responsable de la cuenta'
                            }
                        }
                    },
                    almacen_id: {
                        validators: {
                            notEmpty: {
                                message: 'Debe seleccionar la bodega/sala de ventas'
                            }
                        }
                    }
                }
            });
        });

    </script>
@stop
