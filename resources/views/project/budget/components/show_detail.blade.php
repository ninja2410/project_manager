<div class="col-lg-12 col-xs-12 col-md-12">
  <div class="row">
    <center>
      <h2>{{trans('budget.broken_down_budget')}}</h2>
    </center>
  </div>
  <div class="row">
  <?php
  $total_items = 0;
  $total_services = 0;
  $counter_lines = 1;
  ?>
  @foreach($budget->details as $header)
    <!-- BEGIN CONDENSED TABLE PORTLET-->
      <div class="panel panel-primary">
        <div class="panel-heading">
          <div class="panel-title">
            <div class="caption">
              <center>{{$header->name}}</center>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="table-scrollable">
            <table class="table table-condensed table_details">
              <thead>
              </thead>
              <tbody>
              @foreach($header->getDetails as $line_template)
                <tr class="header_details">
                  <th class="no_column">
                    {{trans('line-template.no')}}
                  </th>
                  <th style="width: 45%">
                    {{trans('line-template.description')}}
                  </th>
                  <th class="centrar">
                    {{trans('line-template.quantity')}}
                  </th>
                  <th>
                    {{trans('line-template.size')}}
                  </th>
                  <th class="price"  style="width: 10%">
                    {{trans('line-template.p_u')}}
                  </th>
                  <th class="price">
                    {{trans('line-template.sub_total')}}
                  </th>
                  <th style="text-align: right">
                    {{trans('budget.total')}}
                  </th>
                </tr>
                <tr class="header_line_template">
                  <td class="_no_column" rowspan="{{count($line_template->items) + 5}}">{{$counter_lines}}</td>
                  <td>{{$line_template->line_template->name}}</td>
                  <td class="centrar">{{$line_template->quantity}}</td>
                  <td>{{$line_template->line_template->size}}</td>
                  <td class="price">@money($line_template->unit_cost)</td>
                  <td></td>
                  <td class="price">@money($line_template->total_cost)</td>
                </tr>
                <?php
                $counter_lines++;
                ?>
                @foreach($config as $conf)
                  <tr class="group_detail_linetemplate">
                    <td colspan="6" style="background-color: {{$conf->color}};"><strong><center>{{$conf->custom_text}}</center></strong></td>
                    <td></td>
                  </tr>
                  <?php
                  $counter = 0;
                  $_subtotal = 0;
                  ?>
                  @foreach ($line_template->items as $element)
                      <?php
                      if ($conf->name=='Material y equipo'){
                          $_type = 1;
                      }
                      else{
                          $_type = 2;
                      }
                      ?>
                      @if($element->item->type_id == $_type)
                        <tr class="detail_line_template">
{{--                          <td></td>--}}
                          <td>{{$element->item->item_name}}</td>
                          <td class="centrar">{{$element->quantity}}</td>
                          <td>{{$element->item->size}}</td>
                          <td class="price">@money($element->unit_cost)</td>
                          <td class="price">@money(($element->unit_cost * $element->quantity))</td>
                          <td></td>
                        </tr>
                        <?php
                        $counter += $element->quantity;
                        $_subtotal += ($element->quantity * $element->unit_cost);
                        ?>
                      @endif

                  @endforeach
                  <tr class="group_detail_linetemplate">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"><strong>{{trans('line-template.total_items')}} {{$conf->custom_text}}</strong></td>
{{--                    <td  class="centrar"><strong>{{$counter}}</strong></td>--}}
                    <td style="border-top: 2px solid;" class="price"><strong>@money($_subtotal)</strong></td>

                  </tr>
                  <?php
                  if($_type == 1){
                      $total_items += $_subtotal;
                  }
                  else{
                      $total_services += $_subtotal;
                  }
                  ?>
                @endforeach
                <tr>
                  <td></td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- END CONDENSED TABLE PORTLET-->
    @endforeach
  </div>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading border-light summary_header">
        <center><h4 class="panel-title" class="text_summary">{{trans('budget.summary')}}</h4></center>
      </div>
      <div class="panel-body droppable" id="summaryBody">
        <div class="row">
          <table class="table table-hover">
            <tbody>
            @foreach($config as $conf)
              <tr>
                <td>{{trans('line-template.total_items').' '.$conf->custom_text}}</td>
                <td style="text-align: right" id="{{($conf->id == 1 ? "bdITotal" : "bdSTotal")}}">@if ($conf->id == 1)
                    @money($total_items)
                  @else
                    @money($total_services)
                  @endif</td>
              </tr>
            @endforeach
            <tr>
              <td><h4><strong>{{trans('budget.total_project')}}</strong></h4></td>
              <td style="text-align: right" id="bdTotal"><h4><strong>@money($budget->amount)</strong></h4></td>
            </tr>
            <tr>
              <td colspan="2"><center><h4><strong>{{$precio_letras}}</strong></h4></center></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
