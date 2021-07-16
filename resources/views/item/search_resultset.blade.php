<table class="table table-striped table-bordered display" id="table1">
    <thead>
    <tr>
        <th>{{trans('item.upc_ean_isbn')}}</th>
        <th>{{trans('item.item_name')}}</th>
        <th>{{trans('Categoria')}}</th>
        {{-- <th>{{trans('Tipo')}}</th>
        <th>{{trans('item.item_cellar')}}</th> --}}
        <th>{{trans('item.minimal_existence_long')}}</th>
        @if($type == 'precio')
            <th>{{trans('item.price_type')}}</th>
            <th>{{trans('item.selling_price')}}</th>
        @else
            <th>{{trans('item.existence')}}</th>
            <th>{{trans('item.item_cellar')}}</th>
        @endif
        <th>{{trans('item.actions')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $value)
        <tr>
            <td style="font-size:12px;">
                {{ $value->upc_ean_isbn }}
            </td>
            <td style="font-size:12px;">{{ $value->item_name }}</td>
            <td style="font-size:12px;">{{$value->category}}</td>
            <td style="font-size:12px;">
                {{$value->minimal_existence}}
            </td>
            @if($type == 'precio')
                <td style="font-size:12px;">
                    {{$value->price_name}}
                </td>
                <td style="font-size:12px;">
                    @money($value->selling_price)
                </td>
            @else
                <td style="font-size:12px;">
                    {{$value->quantity}}
                </td>
                <td style="font-size:12px;">
                    {{$value->almacen_name}}
                </td>
            @endif
            <td>
                @if($type == 'existencia')
                    @if($value->id_bodega!='')
                        <a class="btn btn-small btn-success btn-xs"
                           href="{{ URL::to('inventory/' . $value->id . '/'.$value->id_bodega) }}" data-toggle="tooltip"
                           data-original-title="Kardex">
                            <span class="glyphicon glyphicon-indent-left"> Kardex
                        </a>
                    @endif
                @endif
                @if ($value->is_kit)
                    <a class="btn btn-small btn-warning btn-xs" href="{{ URL::to('item-kits-vue/' . $value->id ) }}"
                       data-toggle="tooltip" data-original-title="Detalles"><span class="glyphicon glyphicon-eye-open"> Detalles</span></a>
                @else
                    <a class="btn btn-small btn-warning btn-xs" href="{{ URL::to('items/detail/' . $value->id ) }}"
                       data-toggle="tooltip" data-original-title="Detalles"><span class="glyphicon glyphicon-eye-open"> Detalles</span></a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
