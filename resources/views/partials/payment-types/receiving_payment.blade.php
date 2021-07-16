{!! Form::open(array('url' => 'banks/revenues', 'files' => true, 'id'=>'frm_payment')) !!}
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label for="date">{{trans('general.date')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="glyphicon glyphicon-calendar"></li> </span>
                <input type="text" class="form-control" id="paid_at" name="paid_at" readonly>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('payment_method', trans('revenues.payment_method')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                <input type="text" class="form-control" id="payment_method" name="payment_method" value="">
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('account_id', trans('revenues.account')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                <select class="form-control validation-cacao" name="account_id" id="account_id">
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::label('amount', trans('revenues.amount')) !!}
            <div class="input-group">
                <span class="input-group-addon"><strong>Q</strong> </span>
                {!! Form::text('amount', Input::old('amount'), array('class' => 'form-control money_efectivo2','placeholder'=>'Monto','readonly'=>'readonly')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::label('paid', trans('revenues.paid')) !!}
            <div class="input-group">
                <span class="input-group-addon"><strong>Q</strong> </span>
                {!! Form::text('paid', Input::old('paid'), array('class' => 'form-control money_efectivo2 validation-cacao','placeholder'=>'Monto','step' => '0.1')) !!}
            </div>
        </div>
    </div>
    <div style="display: none;">
        <div class="form-group">
            {!! Form::label('change', trans('revenues.change')) !!}
            <div class="input-group">
                <span class="input-group-addon"><strong>Q</strong> </span>
                {!! Form::text('change', Input::old('change'), array('class' => 'form-control money_efectivo2','placeholder'=>'Monto','readonly'=>'readonly')) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            {!! Form::label('description', trans('revenues.description').' ') !!}
            {!! Form::textarea('description', Input::old('description'), array('class' => 'form-control validation-cacao','size' => '30x2','placeholder'=>'Motivo de la transacción')) !!}
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group recipient">
        {!! Form::label('recipient', trans('bank_expenses.recipient')) !!}        
        {!! Form::text('recipient', Input::old('recipient'), ['class' => 'form-control recipient', 'id'=>'recipient']) !!}
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group referencia">
        {!! Form::label('reference', trans('revenues.reference')) !!}
        {!! Form::text('reference', Input::old('reference'), array('class'
        => 'form-control referencia', 'placeholder'=>'No. de documento u otra referencia', 'id'=>'reference')) !!}
    </div>
</div>
{{-- <div class="row"> --}}
{{--<div class="col-lg-6">--}}
{{--    <div class="form-group referencia">--}}
{{--        {!! Form::label('reference', trans('revenues.reference')) !!}--}}
{{--        {!! Form::text('reference', Input::old('reference'), array('class'--}}
{{--        => 'form-control referencia', 'placeholder'=>'No. de documento u otra referencia')) !!}--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="col-lg-4">--}}
{{--    <div class="form-group banco">--}}
{{--        {!! Form::label('bank_name', trans('revenues.bank_name_check')) !!}--}}
{{--        <input type="text" class="form-control banco" id="bank_name" name="bank_name">--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="col-lg-2">--}}
{{--    <div class="form-group mismo_banco">--}}
{{--        {!! Form::label('same_bank', trans('revenues.same_bank')) !!}--}}
{{--        <select class="form-control mismo_banco" title="trans('revenues.same_bank')" name="same_bank" id="same_bank">--}}
{{--            <option value="">-Seleccione-</option>--}}
{{--            <option value="1">-Si-</option>--}}
{{--            <option value="0">-No-</option>--}}
{{--        </select>--}}
{{--    </div>--}}
{{--</div>--}}


{{-- </div> --}}
{{-- <div class="row" > --}}
<div class="col-lg-6">
    <div class="form-group tarjeta">
        {!! Form::label('card_name', trans('revenues.card_name')) !!}
        <input type="text" class="form-control tarjeta" id="card_name" name="card_name">
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group tarjeta">
        {!! Form::label('card_number', trans('revenues.card_number')) !!}
        {!! Form::text('card_number', Input::old('card_number'), array('class'=> 'form-control tarjeta', 'placeholder'=>'XXXX')) !!}
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group credito">
        {!! Form::label('customer_credit', trans('receiving.supplier_credit')) !!}
        <div class="input-group">
            <span class="input-group-addon"><strong>Q</strong> </span>
            {!! Form::text('customer_credit', Input::old('customer_credit'), array('class' => 'form-control money_efectivo2 credito','placeholder'=>'Credito autorizado','readonly'=>'readonly', 'id'=>'supplier_credit_amount')) !!}
        </div>

    </div>
</div>
<div class="col-lg-6">
    <div class="form-group credito">
        {!! Form::label('payment_date', trans('sale.payment_date')) !!}
        <div class="input-group">
            <span class="input-group-addon"><li class="glyphicon glyphicon-calendar"></li> </span>
            {!! Form::text('date_payments', Input::old('date_payments'), array('id' =>'date_payments' ,'class'=> 'form-control credito')) !!}
        </div>

    </div>
</div>

{{-- </div> --}}
<div class="row">
    {{--
        <div class="col-lg-4">
            <div class="form-group">
                {!! Form::label('customer_id', trans('revenues.customer')) !!}
                <select class="form-control" title="trans('revenues.customer')" name="customer_id" id="customer_id">
                    <option value="">-Seleccione cliente-</option>
                    @foreach($customers as $item)
                    <option value="{!! $item->id !!}" >{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}
</div>
{{-- <input type="hidden" name="balance" id="balance"> --}}
<input type="hidden" name="currency" id="currency" value="Q">
<input type="hidden" name="currency_rate" id="currency_rate" value="1">
<input type="hidden" name="payment_method" id="payment_method" value="1">
<input type="hidden" name="status" id="status" value="5"> {{-- No conciliado --}}
<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
<br>
<div class="col-lg-12">
    {{-- @include('partials.buttons',['cancel_url'=>"/banks/revenues"]) --}}
</div>
{!! Form::close() !!}

