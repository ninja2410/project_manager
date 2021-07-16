<div class="col-lg-6">
  <div class="form-group">
    <label for="date">Fecha</label>
    <div class="input-group">
      <span class="input-group-addon"><li class="glyphicon glyphicon-calendar"></li> </span>
      <input type="text" class="form-control" id="paid_at" name="paid_at" required>
    </div>
  </div>
</div>
<div class="col-lg-6">
  <div class="form-group">
    {!! Form::label('lblpayment_method', trans('bank_expenses.payment_method')) !!}
    <div class="input-group select2-bootstrap-prepend">
      <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
      <select class="form-control" title="trans('bank_expenses.payment_method')" name="payment_method"
              id="payment_method">
        <option value="">--Seleccione--</option>
        @foreach($payments as $item)
          <option value="{!! $item->id !!}"
                  @if(old('payment_method') === $item->id) selected="selected" @endif>{{ $item->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="form-group">
      {!! Form::label('account_id', trans('bank_expenses.account')) !!}
      <div class="input-group select2-bootstrap-prepend">
        <div class="input-group-addon"><i class="fa fa-money"></i></div>
        <select class="form-control" name="account_id" id="account_id">
        </select>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="form-group">
      {!! Form::label('amount', trans('bank_expenses.amount')) !!}
      <div class="input-group">
        <span class="input-group-addon"><strong>Q</strong></span>
        {!! Form::text('amount', Input::old('amount'), array('class' => 'form-control money_efectivo2','placeholder'=>'Monto', 'id'=>'expense_amount')) !!}
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="form-group">
      {!! Form::label('category_id', trans('bank_expenses.category')) !!}
      <div class="input-group select2-bootstrap-prepend">
        <div class="input-group-addon"><i class="fa fa-tag"></i></div>
        <select class="form-control" title="trans('bank_expenses.category')" name="category_id" id="category_id">
          <option value="">--Seleccione--</option>
          @foreach($categories as $item)
            <option value="{!! $item->id !!}"
                    @if(old('category_id') === $item->id) selected="selected" @endif>{{ $item->name }}</option>
          @endforeach
        </select>
      </div>

    </div>
  </div>
  <div class="col-lg-6">
    <div class="form-group">
      {!! Form::label('supplier_id', trans('bank_expenses.supplier')) !!}
      <div class="input-group select2-bootstrap-prepend">
        <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
        <select class="form-control" name="supplier_id" id="supplier_id">
          <option value="">Seleccione proveedor</option>
          @foreach($suppliers as $item)
            <option value="{!! $item->id !!}">{{ $item->company_name }}</option>
          @endforeach
        </select>
        <div class="input-group-btn">
          <a href="#" style="font-size: 14px" id="add_customer_btn"
             class="btn btn-default btn-icon" data-toggle="modal"
             data-target="#modal-2"><i class="fa fa-plus"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="form-group">
      {!! Form::label('user_id', trans('expenses.user')) !!}
      <div class="input-group select2-bootstrap-prepend">
        <div class="input-group-addon"><i class="fa fa-user"></i></div>
        <select class="form-control" title="trans('expenses.user')" name="user_assigned_id" id="user_assigned_id">
          <option value="0">--Seleccione--</option>
          @foreach($users as $item)
            <option value="{!! $item->id !!}"
                    @if(old('user_id') === $item->id) selected="selected" @endif>{{ $item->name.' '.$item->last_name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="form-group">
      {!! Form::label('route_id', trans('expenses.route')) !!}
      <div class="input-group select2-bootstrap-prepend">
        <div class="input-group-addon"><i class="fa fa-random"></i></div>
        <select class="form-control" title="{{trans('expenses.route')}}" name="route_id" id="route_id">
          <option value="">--Seleccione--</option>
          @foreach($routes as $item)
            <option value="{!! $item->id !!}"
                    @if(old('route_id') === $item->id) selected="selected" @endif>{{ $item->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
</div>
@include('banking.expenses.taxes')
<div class="row">
  <div class="col-lg-12">
    <div class="form-group">
      {!! Form::label('description', trans('bank_expenses.description').' ') !!}
      {!! Form::textarea('description', Input::old('description'),
      array('class' => 'form-control','size' => '30x2')) !!}
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="form-group">
      {!! Form::label('recipient', trans('bank_expenses.recipient')) !!}
      {{--									<input type="text" class="form-control" id="recipient" name="recipient">--}}
      {!! Form::text('recipient', Input::old('recipient'), ['class' => 'form-control', 'id'=>'recipient']) !!}
    </div>
  </div>
  <div class="col-lg-6">
    <div class="form-group">
      {!! Form::label('reference', trans('revenues.reference')) !!}
      {!! Form::text('reference', Input::old('reference'), array('class'
      => 'form-control', 'placeholder'=>'No. de documento u otra referencia')) !!}
    </div>
  </div>
</div>
