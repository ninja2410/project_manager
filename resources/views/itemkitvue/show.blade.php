@extends('layouts/default')

@section('title',trans('itemkit.kit_detail'))
@section('page_parent',trans('itemkit.kit'))

@section('header_styles')
<link href="{{asset('assets/css/pages/user_profile.css')}}" rel="stylesheet" type="text/css" />
<!-- Add fancyBox main CSS files -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/fancybox/jquery.fancybox.css')}}" media="screen" />
<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.css')}}" />
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
                        <i class="livicon" data-name="align-left" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                        {{trans('itemkit.kit_detail')}}
                    </h3>
                    <span class="pull-right clickable">
                        <i class="glyphicon glyphicon-chevron-up"></i>
                    </span>
                </div>
                
                <div class="row ">
                    <div class="col-md-4">
                        <br>
                        <div class="form-group">
                            <div class="text-center">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <img src="{!! asset('images/items/') . '/' . $data->avatar !!}" class="img-responsive user_image" alt="{{$data->name}}" style="max-width: 200px;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <center>
                                <a class="btn btn-danger" href="{{ URL::previous() }}">
                                    {{trans('button.back')}}
                                </a>                                
                                <a class="btn btn-info" href="{{ URL::to('item-kits-vue/' . $data->id . '/edit') }}"  data-toggle="tooltip" data-original-title="Editar">
                                    <span class="glyphicon glyphicon-edit"></span>&nbsp;Editar
                                </a>
                            </center>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <center>
                            <a class="btn btn-warning" href="{{ url('item-kits-vue') }}" >
                                {{trans('menu.item_kits')}}
                            </a>
                            </center>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav nav-tabs ul-edit responsive">
                            <li>
                                <a href="#tab-change-pwd" data-toggle="tab">
                                    <i class="livicon" data-name="align-left" data-size="16" data-c="#01BC8C" data-hc="#01BC8C" data-loop="true"></i> {{trans('itemkit.data_kit')}}
                                </a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table class="table table-striped" id="users">
                                <tr>
                                    <td>{{ trans('item.upc_ean_isbn') }}</td>
                                    <td>
                                        <strong>{{$data->upc_ean_isbn}}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ trans('item.item_name') }}</td>
                                    <td>
                                        <strong>{{$data->item_name}}</strong>
                                    </td>
                                </tr>
                                <td>{{ trans('item.description') }}</td>
                                <td>
                                    <strong>{{$data->description}}</strong>
                                </td>
                            </tr>
                            <td>{{ trans('item.category') }}</td>
                            <td>
                                <strong>{{$data->itemCategory->name or 'N/A' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ trans('item.item_type') }}</td>
                            <td>
                                <strong>{{$data->itemType->name or 'N/A' }}</strong>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>{{ trans('item.size') }}</td>
                            <td>
                                <strong>{{$data->size}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ trans('itemkit.stock_kit') }}
                            </td>
                            <td>
                                <strong>{{$existencia}}</strong>
                            </td>
                        </tr>
                        @if(Session::get('administrador',false))
                        <tr>
                            <td>
                                {{ trans('item.cost_price') }}</strong>
                            </td>
                            <td>
                                <strong>@money($data->cost_price)</strong>
                            </td>
                        </tr>
                        @endif
                        <tr colspan="2">
                            <table style="width:100%" class="table table-striped">
                                <caption class="text-center"><strong>Productos del kit<strong></caption>
                                <thead class="thead-dark">
                                    <tr>
                                        <td><strong>Codigo</strong></td>
                                        <td>
                                            <strong>Producto</strong>
                                        </td>
                                        <td>
                                            <strong>Cantidad</strong> 
                                        </td>
                                        @if(Session::get('administrador',false))
                                        <td>
                                            <strong>Costo</strong>
                                        </td>
                                        @endif
                                        <td>
                                            <strong>Existencia</strong>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr style="font-size:11px;">
                                        <td>
                                            {{$item->upc_ean_isbn}}
                                        </td>
                                        <td>
                                            {{$item->item_name}}
                                        </td>
                                        <td>
                                            {{$item->quantity}}
                                        </td>
                                        @if(Session::get('administrador',false))
                                        <td>
                                            {{$item->cost_price}}
                                        </td>
                                        @endif
                                        <td>
                                            {{$item->existencia}}
                                        </td>                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table style="width:100%" class="table table-striped">
                                    <caption class="text-center"><strong>Precios<strong></caption>
                                    @foreach($prices as $key)
                                    <tr>
                                        <td>{{ $key->name }}</td>
                                        <td style="border-right: 1px solid #ddd;"><strong>@if($key->selling_price==null) @money($data->cost_price+(($key->pct/100)*$data->cost_price)) @else @money($key->selling_price) @endif</strong></td>
                                        <td>Mínimo</td>
                                        <td style="border-right: 1px solid #ddd;"><strong>@if($key->low_price==null) @money($data->cost_price+(($key->pct_min/100)*$data->cost_price)) @else @money($key->low_price) @endif</strong></td>
                                        <td>{{ trans('item.pagos')}}</td>
                                        <td>
                                            @foreach ($key->pagos as $pago)
                                            <strong>{{ $pago->name.', ' }}</strong>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endforeach
                                </table> 
                            </td>
                        </tr>                        
                        <tr>
                            <td>{{ trans('item.item_last_tx') }}</td>
                            <td>
                                <strong>{{ $last_tx or 'Ninguna' }}</strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</section>
@endsection
@section('footer_scripts')
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="{{asset('assets/vendors/fancybox/jquery.fancybox.pack.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/fancybox/helpers/jquery.fancybox-thumbs.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop
