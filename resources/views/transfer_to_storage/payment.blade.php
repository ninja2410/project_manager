<div class="row">
  <div class="col-lg-6">
    <label for="bodega_origen">{{trans('product_transfer.account_credit')}}</label>
    <select class="form-control" name="account_origin" id="account_origin" form="addCustomerPayment"
            style="width: 100%" onchange="changeCreditAccount(this)">
      <option value="">Seleccione una cuenta</option>
      @foreach($accounts as $value)
        <option id="account1_{{$value->id}}" value="{{$value->id}}">{{$value->account_name}} | @money($value->pct_interes)</option>
      @endforeach
    </select>
  </div>
  <div class="col-lg-6">
    <div class="form-group">
      <label for="bodega_origen">{{trans('product_transfer.total_amount')}}</label>
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw  fa-money"></i></span>
        <input class="form-control" type="text" readonly id="total_amount" value="0"
               name="total_amount">
      </div>
    </div>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-lg-6">
    <label for="bodega_origen">{{trans('product_transfer.account_debit')}}</label>
    <select class="form-control" id="debit_accounts" form="addCustomerPayment"
            style="width: 100%">
      <option value="">Seleccione una cuenta</option>
      @foreach($accounts as $value)
        <option id="account2_{{$value->id}}" balance="{{$value->pct_interes}}" value="{{$value->id}}">{{$value->account_name}} | @money($value->pct_interes)</option>
      @endforeach
    </select>
  </div>
  <div class="col-lg-4">
    <div class="form-group">
      <label for="bodega_origen">{{trans('product_transfer.total_amount')}}</label>
      <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-fw  fa-money"></i></span>
        <input class="form-control" type="text" id="payment_amount" value="0">
      </div>
    </div>
  </div>
  <div class="col-lg-2">
    <br>
    <button type="button" id="btnAddPayment">{{trans('product_transfer.add')}}</button>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="table-scrollable">
      <table class="table table-striped table-bordered table-advance table-hover" id="tblPayments">
        <thead>
        <tr>
          <th style="width: 60%;">
            <i class="livicon" data-name="briefcase" data-size="16" data-c="#666666" data-hc="#666666"
               data-loop="true"></i>
            {{trans('product_transfer.account')}}
          </th>
          <th style="width: 30%;">
            <i class="fa fa-bookAiri Satou"></i> {{trans('product_transfer.debit_amount')}}
          </th>
          <th style="width: 10%;"></th>
        </tr>
        </thead>
        <tbody id="payments_container">

        </tbody>
        <tfoot>
          <tr>
            <td style="text-align: right;">{{trans('product_transfer.total_debit')}}</td>
            <td>
              <label id="total_debit">@money(0)</label>
              <input type="hidden" id="_total_debit" value="0">
            </td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>