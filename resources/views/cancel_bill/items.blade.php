@extends('layouts/default')

@section('title',trans('receiving.cancel_error'))
@section('page_parent',trans('receiving.cancel_bill'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>
                        Listado de productos con existencia insuficiente
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-danger" role="alert">
                                <b>Error!!</b>
                                No se puede anular debido a que no hay existencias suficientes en los siguientes
                                productos
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <b>Documento:</b>
                            <label for="document" id="nameDocument">
                                {{$dataSeries[0]->document.' '.$dataSeries[0]->name.'-'.$dataSeries[0]->correlative}}
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-striped table-bordered" id='tableDetails'>
                                <thead>
                                <th></th>
                                <th style="text-align:center;">No</th>
                                <th>Nombre del producto</th>
                                <th style="text-align:center;">Cantidad de factura</th>
                                <th style="text-align:center;">Cantidad en bodega</th>
                                </thead>
                                <tbody>
                                <?php $itemsQt = count($arrayItems);?>
                                @for ($i = 0; $i <$itemsQt; $i++)
                                    <tr>
                                        <td></td>
                                        <td style="text-align:center;">{{$i+1}}</td>
                                        <td>
                                            {{$arrayItems[$i]['items_name']}}
                                        </td>
                                        <td style="text-align:center;">
                                            {{$arrayItems[$i]['discount_quantity']}}
                                        </td>
                                        <td style="text-align:center; color: red">
                                            {{$arrayItems[$i]['current_quantity']}}
                                        </td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4">
                            <center>
                                <a href="{{ url('/receivings') }}" class="btn btn-danger">Aceptar</a>
                            </center>
                        </div>
                        <div class="col-lg-4"></div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@section('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#tableDetails').DataTable({
                language: {
                    "url": " {{ asset('assets/json/Spanish.json') }}"
                },
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
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: "Copiar",
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        title: document.title
                    },
                    {
                        extend: 'csv',
                        text: "Csv",
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        title: document.title
                    },
                    {
                        extend: 'excel',
                        text: "Excel",
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        title: document.title
                    },
                    {
                        extend: 'pdf',
                        text: "Pdf",
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        title: document.title
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        },
                        title: document.title
                    }

                ],

            });
        });
    </script>
@stop
