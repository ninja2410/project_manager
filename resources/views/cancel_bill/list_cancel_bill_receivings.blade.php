@extends('layouts/default')

@section('title',trans('Anulación de facturas de compra'))
@section('page_parent',trans('Anulaciones'))


@section('content')
    {!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
    {!! Html::script('js/sale.js', array('type' => 'text/javascript')) !!}
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('receiving.list_cancel_bill_receivings')}}
                        </h3>
                        <span class="pull-right clickable">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </span>
                    </div>

                    <div class="panel-body table-responsive">
                        <hr/>
                        <input type="hidden" name="path" id="path" value="{{ url('/') }}">
                        @if (Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                        {!! Html::ul($errors->all()) !!}
                        <table class="table table-striped table-bordered compact" id="tableIdReceiving">
                            <thead>
                            <tr>
                                <th></th>
                                <th>No.</th>
                                <th>{{trans('Serie')}}</th>
                                <th>{{trans('report-receiving.date')}}</th>
                                <th>{{trans('report-receiving.items_received')}}</th>
                                <th>{{trans('report-receiving.received_by')}}</th>
                            <!-- <th>{{trans('report-receiving.supplied_by')}}</th> -->
                                <th>{{trans('report-receiving.total')}}</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data_receivings as $i=> $value)
                                <tr>
                                    <td></td>
                                    <td>{{$i+1}}</td>
                                    <td>{{$value->serie->document->name.' '.$value->serie->name.'-'.$value->correlative}}</td>
                                    <td>
                                        {!!date_format($value->created_at, 'd/m/Y H:i:s') !!}
                                    </td>
                                    <td style="text-align: center;">
                                        {{DB::table('receiving_items')->where('receiving_id', $value->id)->sum('quantity')}}
                                    </td>
                                    <td>{{ $value->user->name }}</td>
                                    <td style="text-align: right;">@money($value->total_cost)</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer_scripts')

    <script type="text/javascript">
        $('#example').DataTable({
            language: {
                "url": " {{ asset('assets/json/Spanish.json') }}"
            },
            dom: 'Bfrtip',
            responsive: {
                details: {
                    type: 'column'
                }
            },
            columnDefs: [{
                className: 'control',
                orderable: false,
                targets: 0
            }],
            buttons: [
                {
                    extend: 'collection',
                    text: 'Exportar/Imprimir',
                    buttons: [
                        {
                            extend: 'copy',
                            text: 'Copiar',
                            title: document.title,
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excel',
                            title: document.title,
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdf',
                            title: document.title,
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: document.title,
                            exportOptions: {
                                columns: ':visible'
                            },
                        }
                    ]
                },
            ],
        });
    </script>
@stop
