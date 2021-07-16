@if(isset($budget))
  <?php
  $general_counter_details = 0;
  ?>
  <input type="hidden" id="header_count" value="{{count($budget->details)}}">
  @foreach($budget->details as $counter => $header)
    <div class="panel panel-warning panel_header header_container header_container{{$counter}}"
         header_counter="{{$counter}}">
      <div class="panel-heading clearfix">
        <h4 class="panel-title">
          <a href="#" class="header_title editable editable-click" id="h_{{$counter}}" data-original-title=""
             title="">{{$header->name}}</a>
        </h4>
        <span class="pull-right btnDelete" style="display: inline;">
        <button onclick="deleteElement('panel_header', this)" type="button"
                class="btn btn-danger btn-sm">Eliminar</button>
        </span>
      </div>
      <div class="panel-body detail_header ui-droppable" style="height: 40px;">
        @foreach ($header->getDetails as $detail_counter =>$line)
          <?php $general_counter_details++ ?>
          <li class="detail lt_detail" line_template_id="{{$line->line_template_id}}">
            <div class="row">
              <table style="width: 100%; margin-bottom: 5px;"
                     class="table table-striped table-bordered table-advance table-hover table-sm">
                <thead class="btnDelete">
                <tr>
                  <th style="width: 40%">
                    {{trans('line-template.description')}}
                  </th>
                  <th style="width: 15%">
                    {{trans('line-template.size')}}
                  </th>
                  <th style="width: 15%">
                    {{trans('line-template.p_u')}}
                  </th>
                  <th style="width: 15%">
                    {{trans('line-template.quantity')}}
                  </th>
                  <th style="width: 15%">
                    {{trans('line-template.sub_total')}}
                  </th>
                  <th>
                    <button type="button" data-placement="top"  class="btn btn-sm btn-danger example-popover total_error hidden" data-toggle="popover" title="{{trans('budget.error')}}" data-content="And here's some amazing content. It's very engaging. Right?">
                      <i class="livicon" data-n="thumbs-down" data-s="20" data-c="white" data-hc="white"></i>
                    </button>
                    <button type="button" class="btn btn-sm total_success btn-success">
                      <i class="livicon" data-n="thumbs-up" data-s="20" data-c="white" data-hc="white"></i>
                    </button>
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td style="background-color: #1f648b">
                    <p>
                      <a class="draggable_component lt_name collapsed" data-toggle="collapse"
                         href="#h{{$counter}}_d{{$detail_counter}}"
                         role="button" aria-expanded="false" aria-controls="collapseExample"
                         style="text-align: left; color: white" header_counter="{{$counter}}"
                         detail_counter="{{$detail_counter}}">{{$line->line_template->name}}</a>
                    </p>
                  </td>
                  <td class="btnDelete lt_size">{{$line->line_template->size}}</td>
                  <td class="btnDelete">
                    <span class="label label-sm label-info label-mini Total_line">@money($line->unit_cost)</span>
                  </td>
                  <td class="btnDelete">
                            <span class="label label-sm label-success label-mini">
                                <a class="Quantity_line editable editable-click" href="#" style="text-align: left;">
                                    {{$line->quantity}}
                                </a>
                            </span>
                  </td>
                  <td class="btnDelete Subtotal_line">@money($line->total_cost)</td>
                  <td class="btnDelete">
                    <button type="button" onclick="deleteElement('detail', this)" class="btn btn-danger btn-sm">X
                    </button>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <div class="collapse" id="h{{$counter}}_d{{$detail_counter}}" header_counter="{{$counter}}"
                 detail_counter="{{$detail_counter}}" aria-expanded="false"
                 style="height: 0px;">
              <div class="card card-body">
                @foreach($config as $conf)
                  <table class="table table-striped table-bordered table-advance table-hover {{($conf->name=='Material y equipo')? "allowProducts":"allowServices"}}">
                    <thead>
                    <tr style="background-color: {{$conf->color}};">
                      <th colspan="5">
                                <span>
                                    <strong>
                                        <center><i class="{{$conf->icon}}"></i> {{$conf->custom_text}}</center>
                                    </strong>
                                </span>
                      </th>
                    </tr>
                    <tr>
                      <th style="width: 40%">
                        {{--                                <i class="livicon"--}}
                        {{--                                   data-name="briefcase"--}}
                        {{--                                   data-size="16"--}}
                        {{--                                   data-c="#666666"--}}
                        {{--                                   data-hc="#666666"--}}
                        {{--                                   data-loop="true"></i>--}}
                        {{--                                {{trans('line-template.description')}}--}}
                      </th>
                      <th style="width: 15%">
                        {{--                                <i class="fa fa-balance-scale-right"></i> {{trans('line-template.size')}}--}}
                      </th>
                      <th style="width: 15%">
                        {{--                                <i class="fa fa-money-bill-wave"></i> {{trans('line-template.price_u')}}--}}
                      </th>
                      <th style="width: 15%">
                        {{--                                <i class="fa fa-box"></i> {{trans('line-template.quantity')}}--}}
                      </th>
                      <th style="width: 15%">
                        {{--                                <i class="fa fa-money-bill-wave"></i> {{trans('line-template.sub_total')}}--}}
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="comodin"></tr>
                    <?php
                    $_tmptotal = 0;
                    ?>
                    @foreach ($line->items as $element)
                        <?php
                        if ($conf->name == 'Material y equipo') {
                            $_type = 1;
                        } else {
                            $_type = 2;
                        }
                        ?>
                        @if($element->item->type_id ==$_type)
                          <tr class="rowItem _item_row" item_id="{{$element->item_id}}">
                            <td class="hidden-xs itm_name">{{$element->item->item_name}}</td>
                            <td class="hidden-xs itm_size">{{$element->item->size}}</td>
                            <td>
                              @if($element->item->verifyExpire())
                                <span class="label label-sm label-info label-mini">
                                        @else
                                    <span class="label label-sm label-danger label-mini">
                                        @endif
                                            Q
                                            <a href="#"
                                               data-pk="{{$element->item_id}}"
                                               class="unit_cost item_{{$element->item_id}}"
                                               item_id="{{$element->item_id}}">{{number_format($element->unit_cost, 2)}}</a>
                                        </span>
                            </td>
                            <td class="row_qty">
                              <input type="hidden" class="refer_quantity_item" value="{{$element->quantity}}">
                              <span class="label label-sm label-success label-mini">
                                            <a href="#" class="quantity_item">{{$element->quantity}}</a>
                                        </span>
                            </td>
                            <td>
                              <span class="label label-sm label-default label-mini sut_total">@money($element->unit_cost * $element->quantity)</span>
                            </td>
                            <td>
                              <button type="button" class="btn btn-sm btn-danger"
                                      onclick="deleteElement('_item_row', this)">X
                              </button>
                            </td>
                          </tr>
                          <?php
                          $_tmptotal += ($element->quantity * $element->unit_cost)
                          ?>
                        @endif
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr style="border-top: 2px solid;">
                      <td colspan="4"
                          style="text-align: right;">{{trans('line-template.total_items').' '.strtolower($conf->custom_text)}}</td>
                      <td>
                        <span class="label label-sm label-default label-mini subtotal_table" type_item="{{$conf->id}}">@money($_tmptotal)</span>
                      </td>
                    </tr>
                    </tfoot>
                  </table>
                @endforeach
              </div>
            </div>
          </li>
        @endforeach
      </div>
    </div>
  @endforeach
  <input type="hidden" id="details_counter" value="{{$general_counter_details}}">
@endif
