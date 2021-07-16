<div class="col-lg-12 col-xs-12 col-md-12">
  <div class="row">
    <center>
      <h2>{{trans('budget.integrated_budget')}}</h2>
    </center>
  </div>
  <div class="row">
    <div class="table-scrollable">
      <table class="table table-condensed table_details" id="tblBudgetIntegrated">
        <thead class="header_details">
        <tr>
          <th scope="col" class="no_column">{{trans('line-template.no')}}</th>
          <th scope="col">{{trans('line-template.name')}}</th>
          <th scope="col">{{trans('line-template.quantity')}}</th>
          <th scope="col">{{trans('line-template.size')}}</th>
          <th scope="col" class="price">{{trans('line-template.p_u')}}</th>
          <th scope="col" class="price">{{trans('line-template.total_items')}}</th>
        </tr>
        </thead>
        <tbody>
        <?php
                $key = 1;
        ?>
        @foreach ($budget->details as $header)
{{--          <tr class="header_line_template">--}}
{{--            <td colspan="5"><center><strong>{{$header->name}}</strong></center></td>--}}
{{--          </tr>--}}
          @foreach($header->getDetails as $line)
            <tr class="group_detail_linetemplate">
              <td>{{$key}}</td>
              <td>{{$line->line_template->name}}</td>
              <td>{{$line->quantity}}</td>
              <td>{{$line->line_template->size}}</td>
              <td class="price">@money($line->unit_cost)</td>
              <td class="price">@money($line->total_cost)</td>
            </tr>
            <?php $key++; ?>
          @endforeach
        @endforeach
        </tbody>
        <tfoot>
        <tr>
          <td colspan="5" style="text-align: right"><h5><strong>{{trans('budget.total_project')}}</strong></h5></td>
          <td id="fTotalInt" class="price"><h5><strong>@money($budget->amount)</strong></h5></td>
        </tr>
        <tr>
          <td colspan="6"><h4><strong><center>{{$precio_letras}}</center></strong></h4></td>
        </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>