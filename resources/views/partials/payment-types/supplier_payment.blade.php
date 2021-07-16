<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="date">{{trans('general.date')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="glyphicon glyphicon-calendar"></li> </span>
                <input type="text" class="form-control" id="paid_at" name="paid_at" readonly>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::label('payment_method', trans('revenues.payment_method')) !!}
            <select class="form-control"
                    title="trans('revenues.payment_method')" name="payment_method"
                    id="id_pago" onchange="cambiopago(this.value);">
                <option value="">--Seleccione--</option>
                @foreach($pagos as $item)
                    <option value="{!! $item->id !!}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::label('account_id', trans('revenues.account')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                <select class="form-control validation-cacao" name="account_id" id="account_id">
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::label('amount', trans('revenues.amount')) !!}
            <div class="input-group">
                <span class="input-group-addon"><strong>Q</strong> </span>
                {!! Form::text('amount', Input::old('amount'), array('class' => 'form-control money_efectivo2','placeholder'=>'Monto','readonly'=>'readonly')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6" style="display: none;">
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
        {!! Form::label('lbl_recipient', trans('bank_expenses.recipient')) !!}
        {!! Form::text('recipient',$cliente->name_on_checks, ['class' => 'form-control recipient', 'id'=>'recipient']) !!}
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group referencia">
        {!! Form::label('reference', trans('revenues.reference'), array('id' => 'reference_id' )) !!}
        {!! Form::text('reference', Input::old('reference'), array('class'=> 'form-control referencia', 'placeholder'=>'No. de documento u otra referencia', 'id'=>'reference')) !!}
    </div>
</div>


{{-- </div> --}}
<div class="row">

</div>
{{-- <input type="hidden" name="balance" id="balance"> --}}
{{--<input type="hidden" name="currency" id="currency" value="Q">--}}
{{--<input type="hidden" name="currency_rate" id="currency_rate" value="1">--}}
{{--<input type="hidden" name="payment_method" id="payment_method" value="1">--}}
{{--<input type="hidden" name="status" id="status" value="5"> --}}{{-- No conciliado --}}
{{--<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">--}}
<br>

