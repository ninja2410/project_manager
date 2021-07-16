@foreach($_list as $line)
    <tr class="rowItem _item_row" item_id="{{$line->id}}">
        <td class="hidden-xs item_name_filter itm_name">{{$line->item_name}}</td>
        <td class="hidden itm_size">{{$line->size}}</td>
        <td class="hidden">
            @if($line->verifyExpire())
                <span class="label label-sm label-info label-mini">
            @else
                <span class="label label-sm label-danger label-mini">
            @endif
                    Q
                    <a href="#"
                       data-pk="{{$line->id}}"
                       class="unit_cost item_{{$line->id}}"
                       item_id="{{$line->id}}">{{number_format($line->budget_cost, 2)}}</a>
                </span>
        </td>
        <td class="hidden">
            <input type="hidden" class="refer_quantity_item" value="1">
            <span class="label label-sm label-success label-mini">
                <a href="#" class="quantity_item" approach_type="{{$line->approach_type}}" item_id="{{$line->id}}">1.00</a>
            </span>
        </td>
        <td class="hidden">
            <span class="label label-sm label-default label-mini sut_total">@money($line->budget_cost)</span>
        </td>
        <td class="hidden">
            <button type="button" class="btn btn-sm btn-danger" onclick="deleteElement('_item_row', this)">X</button>
        </td>
    </tr>
@endforeach
