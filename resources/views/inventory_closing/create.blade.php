@extends('layouts/default')

@section('title',trans('inventory_closing.create'))
@section('page_parent',trans('inventory_closing.inventory_closing'))

<!--  Para agregar el calendario-->
@section('header_styles')
    <link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>
    <!-- ALERTS -->
    <link href="{{ asset('assets/css/pages/alerts.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <section class="content">
        <!-- <div class="container"> -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('inventory_closing.create')}}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        
                        {!! Form::open(array('url' => url('inventory_closing'),'id'=>'frmSave', 'files' => true)) !!}
                        <input type="hidden" name="stock_action" value="+" id="stock_action">
                        <div class="row">
                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet box default">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="livicon" data-name="pen" data-size="16" data-loop="true" data-c="#fff"
                                           data-hc="white"></i> {{trans('inventory_closing.history')}}
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <thead>
                                            <tr>
                                                <th>
                                                    <i class="livicon" data-name="truck" data-size="16"
                                                       data-c="#666666" data-hc="#666666" data-loop="true"></i>
                                                    {{trans('inventory_closing.cellar')}}
                                                </th>
                                                <th>
                                                    <i class="livicon" data-name="calendar" data-size="16"
                                                       data-c="#666666" data-hc="#666666" data-loop="true"></i>
                                                    {{trans('inventory_closing.date')}}
                                                </th>
                                                <th class="hidden-xs">
                                                    <i class="livicon" data-name="money" data-size="16"
                                                       data-c="#666666" data-hc="#666666" data-loop="true"></i>
                                                    {{trans('inventory_closing.amount')}}
                                                </th>
                                                <th class="hidden-xs">
                                                    <i class="livicon" data-name="number" data-size="16"
                                                       data-c="#666666" data-hc="#666666" data-loop="true"></i>
                                                    {{trans('inventory_closing.total_quantity')}}
                                                </th>
                                                <th class="hidden-xs">
                                                    <i class="livicon" data-name="comment" data-size="16"
                                                       data-c="#666666" data-hc="#666666" data-loop="true"></i>
                                                    {{trans('inventory_closing.comment')}}
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                    $total_quantity = 0;
                                                    $total_cost = 0;
                                            ?>
                                            @foreach($history as $value)
                                                <tr>
                                                    <td>{{$value->almacen->name}}</td>
                                                    <td>{{$value->date}}</td>
                                                    <td>@money($value->amount)</td>
                                                    <td>{{$value->total_quantity}}</td>
                                                    <td>{{$value->comment}}</td>
                                                </tr>
                                                <?php
                                                    $total_quantity += $value->total_quantity;
                                                    $total_cost += $value->amount;
                                                ?>
                                            @endforeach
                                            <tr>
                                                <td colspan="2" style="text-align: right;"><strong>TOTAL:</strong></td>
                                                <td><strong>@money($total_cost)</strong></td>
                                                <td><strong>{{$total_quantity}}</strong></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE TABLE PORTLET-->
                        </div>
                        <div class="row">
                            <div class="alert-message alert-message-success">
                                <h4>{{trans('inventory_closing.months_to_close').$meses}}</h4> <strong>{{$mMeses}}</strong>
                                <p>
                                    Después de cerrado el mes no se podrá realizar ningún movimiento de inventario en dichas fechas.
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Form::label('upc_ean_isbn', trans('inventory_closing.date')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="calendar" data-size="16" data-c="#555555"
                                               data-hc="#555555"
                                               data-loop="true"></i>
                                        </div>
                                        <input type="text" name="date" value="{{date('d/m/Y')}}" readonly id="date"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Form::label('item_name', trans('inventory_closing.comment')) !!}
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="livicon" data-name="responsive-menu" data-size="16"
                                               data-c="#555555" data-hc="#555555"
                                               data-loop="true"></i>
                                        </div>
                                        <input type="text" name="comment" id="comment" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-4"></div>
                                    <div class="col-lg-4" style="text-align: center;">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" id="btn_save"  >
                                                {{trans('button.save')}}
                                            </button>
                                            <a class="btn btn-danger" href="{{ url('inventory_closing') }}">
                                                {{trans('button.cancel')}}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4"></div>
                                </div>

                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        {{--Begin modal--}}
        <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="confirmSave" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Confirmación guardar</h4>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            Datos correctos
                            <br>
                            ¿Seguro que desea guardar?
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align:center;">
                        <a class="btn  btn-info" id="btn_save_confirm" onclick="sendForm()">Aceptar</a>
                        <a class="btn  btn-danger" data-dismiss="modal" >Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    {{--End modal--}}
        <!-- </div> -->
    </section>
@endsection
<!--  para agregar el calendario de bootstrap-->
@section('footer_scripts')
    <script language="javascript" type="text/javascript"
            src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
    <script type="text/javascript">

        function sendForm(){
            showLoading("Generando cierres de inventario...");
            $('#frmSave').submit();
        }
        $(document).ready(function () {

            $('#btn_save').click(function () {
                $('#confirmSave').modal('show');
            });

            $('select').select2({
                allowClear: true,
                theme: "bootstrap",
                placeholder: "Buscar"
            });
        });


    </script>

    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>
@stop
