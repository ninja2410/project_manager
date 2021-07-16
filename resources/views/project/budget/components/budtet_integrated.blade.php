<div class="row">
  <h2>{{trans('budget.integrated_budget')}}</h2>
</div>
<hr>
<div class="row">
  <table class="table table-hover" id="tblBudgetIntegrated">
    <thead>
    <tr>
      <th scope="col">{{trans('line-template.name')}}</th>
      <th scope="col">{{trans('line-template.quantity')}}</th>
      <th scope="col">{{trans('line-template.size')}}</th>
      <th scope="col">{{trans('line-template.p_u')}}</th>
      <th scope="col">{{trans('line-template.total_items')}}</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
    <tr>
      <td colspan="4" style="text-align: right"><strong>{{trans('budget.total_project')}}</strong></td>
      <td id="fTotalInt"></td>
    </tr>
    </tfoot>
  </table>
</div>
