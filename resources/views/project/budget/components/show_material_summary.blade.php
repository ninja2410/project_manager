<div class="col-lg-12 col-xs-12 col-md-12">
  <div class="row">
    <center>
      <h2>{{trans('budget.material_summary')}}</h2>
    </center>
  </div>
  <div class="row">
    <table class="table table_details" id="tblBudgetIntegrated">
      <thead>
      <tr>
        <th scope="col">{{trans('line-template.name')}}</th>
        <th scope="col" class="centrar">{{trans('line-template.quantity')}}</th>
        <th scope="col">{{trans('line-template.size')}}</th>
        <th scope="col" class="price">{{trans('line-template.p_u')}}</th>
        <th scope="col" class="price">{{trans('line-template.total_items')}}</th>
      </tr>
      </thead>
      <tbody>
      @foreach($summary_items as $item)
        <tr class="group_detail_linetemplate">
          <td>{{$item->item->item_name}}</td>
          <td class="centrar">{{$item->quantity_total}}</td>
          <td>{{$item->item->size}}</td>
          <td class="price">@money($item->unit_cost)</td>
          <td class="price">@money(($item->unit_cost * $item->quantity_total))</td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td colspan="4" class="price"><h5><strong>{{trans('budget.total_price')}}</strong></h5></td>
        <td id="fTotalInt" class="price"><h5><strong>@money($budget->amount)</strong></h5></td>
      </tr>
      <tr>
        <td colspan="5"><center><h4><strong>{{$precio_letras}}</strong></h4></center></td>
      </tr>
      </tfoot>
    </table>
  </div>

</div>