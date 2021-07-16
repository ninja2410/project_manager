@extends('layouts/default')

@section('title',trans('item.item_inventory'))
@section('page_parent',trans('item.items'))

@section('header_styles')
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>
@endsection

@section('content')
    <section class="content">
        <!-- <div class="container"> -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="list" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            {{trans('item.item_inventory')}}
                        </h3>
                        <span class="pull-right clickable">
              <i class="glyphicon glyphicon-chevron-up"></i>
            </span>
                    </div>

                    <div class="panel-body">
                        

                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <b>Seleccione tipo de precio</b>
                                </label>
                                <select class="form-control" id="price" name="price_filter"
                                        onchange="setFilter()" >
                                    @foreach ($prices as $key => $value)
                                        <option @if ($price==$value->id)
                                                selected
                                                @endif value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <b>Seleccione bodega</b>
                                </label>
                                <select class="form-control" id="almacen" name="almacen_filter"
                                        onchange="setFilter()">
                                    @foreach ($almacen as $key => $value)
                                        <option @if ($bod==$value->id)
                                                selected
                                                @endif value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <br>
                                <a class="btn btn-small btn-primary" href="{{ URL::to('items/show/0/'.$price) }}"
                                   id="btnFilter">Filtrar</a>
                            </div>
                            <div class="col-md-1">

                            </div>
                        </div>
                        <div class="panel-body table-responsive">
                            <table class="table table-striped table-bordered display" id="table1">
                                <thead>
                                <tr>
                                    <th></th>
{{--                                    <th>{{trans('item.item_id')}}</th>--}}
                                    <th>{{trans('item.upc_ean_isbn')}}</th>
                                    <th>{{trans('item.item_name')}}</th>
                                    <th>{{trans('Categoria')}}</th>
                                    <th>{{trans('Tipo')}}</th>
                                    @if (Session::get('administrador', 'false'))
                                        <th>{{trans('item.cost_price')}}</th>
                                    @endif
                                    <th>{{trans('item.minimal_sale_price')}}</th>
                                    <th>{{trans('item.selling_price')}}</th>
                                    <th>{{trans('item.existence')}}</th>
                                    {{-- <th>{{trans('item.minimal_existence')}}</th> --}}
                                    <th>{{trans('item.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($item as $value)
                                    <tr>
                                        <td></td>
{{--                                        <td style="font-size:12px;">{{ $value->id }}</td>--}}
                                        <td style="font-size:12px;">
                                            {{ $value->upc_ean_isbn }}
                                        </td>
                                        <td style="font-size:12px;">{{ $value->item_name }}</td>
                                        <td style="font-size:12px;">{{$value->name}}</td>
                                        <td style="font-size:12px;">{{$value->tipo}}</td>
                                        @if (Session::get('administrador', false))
                                            <td style="font-size:12px; text-align: center;">{{$value->cost_price}}</td>
                                        @endif
                                        <td style="font-size:12px; text-align: center;">{{$value->low_price}}</td>
                                        <td style="font-size:12px; text-align: center;">{{$value->selling_price}}</td>
                                        @if ($value->stock_action!='1')
                                            @if($value->quantity<$value->minimal_existence)
                                                <td style="background-color:#da5b5b73;color:white; text-align:center;font-size:12px;">
                                                    <a href="#" data-toggle="tooltip"
                                                       title="Hay pocas existencias!">{{$value->quantity}}</a>
                                                </td>
                                            @else
                                                <td style="color:black; text-align:center;font-size:12px;">{{$value->quantity}}</td>
                                            @endif
                                        @else
                                            <td style="color:black; text-align:center;font-size:12px;">N-I</td>
                                        @endif

                                        {{-- <td style="font-size:12px;">
                                            {{$value->minimal_existence}}
                                        </td> --}}
                                        <td>
                                            <a class="btn btn-small btn-success btn-xs"
                                               href="{{ URL::to('inventory/' . $value->id . '/'.$bod) }}"
                                               data-toggle="tooltip" data-original-title="Kardex">
                                                Kardex
                                            </a>
                                            {{-- @if(($value->stock_action!='1'))
                                                @if(($value->quantity==0))
                                                    <button type='button' onclick="modalDelete('{{$value->id}}','{{$value->item_name}}')" title="Eliminar" data-toggle="tooltip" data-original-title="Eliminar" class='delete form btn btn-danger btn-xs'><span class="glyphicon glyphicon-remove-circle"></span></button>
                                                @endif
                                            @endif --}}
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{--Begin modal--}}
            <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modalDelete" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h4 class="modal-title">Confirmación Eliminar</h4>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <h4 id="name_item"></h4>
                                <br>
                                ¿Desea eliminar producto?
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align:center;">
                            {!! Form::open(array('url' => '', 'class' => 'pull-right', 'id'=>'frmDelete')) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            <button type="submit" class="btn  btn-info">Aceptar</button>
                            <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            {{--End modal--}}
        </div>
        <!-- </div> -->
    </section>
    <style type="text/css">
        .code {
            height: 40px !important;

        }
    </style>
@endsection
@section('footer_scripts')
    <script language="javascript" type="text/javascript"
            src="{{ asset('assets/vendors/select2/js/select2.js') }}"></script>
    <script type="text/javascript">
        function setFilter() {
            let price = +$('#price').val();
            let bodega = +$('#almacen').val();
            $('#btnFilter').prop("href", APP_URL + "/items/show/" + bodega+"/"+price);
        }

        // function setFilter(){
        //   $('#btnFilter').prop("href", APP_URL+"/items/"+document.getElementById('type').value+"/"+
        //   document.getElementById('almacen').value);
        // }
        $(document).ready(function () {

            $.fn.select2.defaults.set("width", "100%");
            $('select').select2({
                allowClear: true,
                theme: "bootstrap",
                placeholder: "Buscar"
            });

            setDataTable("table1", [], "{{asset('assets/json/Spanish.json')}}");    
        });
        let modalDelete = function(id,name){
        $('#name_item').text(name);
        $('#frmDelete').attr("url", APP_URL+'/items/'+id);
        $('#frmDelete').attr("action", APP_URL+'/items/'+id);
        $('#modalDelete').modal('show');
        };
    </script>
@stop
