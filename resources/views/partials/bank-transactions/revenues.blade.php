<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="date">Fecha</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="glyphicon glyphicon-calendar"></li> </span>
                <input type="text" class="form-control" id="paid_at" name="paid_at" required>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            {!! Form::label('document_id', trans('revenues.document'), array('class'=>'control-label')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-file"></i></div>
                <select class="form-control" title="{{trans('revenues.document')}}" name="serie_id" id="serie_id">
                    @foreach($series as $item)
                        <option value="{!! $item->id !!}" @if(old('serie_id') === $item->id) selected="selected" @endif>{{ $item->document->name.' '.$item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            {!! Form::label('receipt_number', trans('revenues.receipt_number'), array('class'=>'control-label')) !!}
            <div class="input-group">
                <span class="input-group-addon"><strong>#</strong></span>
                {!! Form::text('receipt_number', old('receipt_number',$receipt_number), array('class' => 'form-control','placeholder'=>'No. Recibo', 'id'=> 'receipt_number')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('payment_method', trans('revenues.payment_method'), array('class'=>'control-label')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                <select class="form-control" title="trans('revenues.payment_method')" name="payment_method" id="payment_method" onchange="cambiopago(this.value);">
                    <option value="">--Seleccione--</option>
                    @foreach($payments as $item)
                        <option value="{!! $item->id !!}" @if(old('payment_method') === $item->id) selected="selected" @endif>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('account_id', trans('revenues.account'), array('class'=>'control-label')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                <select class="form-control" name="account_id" id="account_id">
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('amount', trans('revenues.amount'), array('class'=>'control-label')) !!}
            <div class="input-group">
                <span class="input-group-addon"><strong>Q</strong></span>
                {!! Form::text('amount', Input::old('amount'), array('class' => 'form-control','placeholder'=>'Monto', 'onKeyup'=>'applyRetentions(this)')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('customer_id', trans('sale.customer'), array('class'=>'control-label')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-users"></i></div>
                <select class="form-control" name="customer_id" id="customer_id">
                    <option value="0">Seleccione cliente</option>
                    @foreach($customer as $value)
                        <option value="{!! $value->id !!}" max_credit_amount="{!! $value->max_credit_amount !!}">
                            {{ $value->name }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-btn">
                    <a href="#" style="font-size: 14px" id="add_customer_btn" class="btn btn-default btn-icon"
                       data-toggle="modal" data-target="#modal-2"><i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            {!! Form::label('description', trans('revenues.description').' ', array('class'=>'control-label')) !!}
            {!! Form::textarea('description', Input::old('description'), array('class' => 'form-control','size' => '30x2','placeholder'=>'Motivo del ingreso')) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('bank_name', trans('revenues.bank_name_check'), array('class'=>'control-label')) !!}
            <select class="form-control" title="" name="bank_name" id="bank_name" required>
                <option value="">Seleccione banco</option>
                <option value="Banco Agromercantil (BAM)" @if(old('bank_name') === 'Banco Agromercantil (BAM)') selected="selected" @endif>Banco Agromercantil (BAM)</option>
                <option value="Banco industrial" @if(old('bank_name') === 'Banco industrial') selected="selected" @endif>Banco industrial</option>
                <option value="BAC-Credomatic" @if(old('bank_name') === 'BAC-Credomatic') selected="selected" @endif>BAC-Credomatic</option>
                <option value="Banco Promerica" @if(old('bank_name') === 'Banco Promerica') selected="selected" @endif>Banco Promerica</option>
                <option value="Banco Internacional" @if(old('bank_name') === 'Banco Internacional') selected="selected" @endif>Banco Internacional</option>
                <option value="Banco G&T Continental" @if(old('bank_name') === 'Banco G&T Continental') selected="selected" @endif>Banco G&T Continental</option>
                <option value="Banrural" @if(old('bank_name') === 'Banrural') selected="selected" @endif>Banrural</option>
                <option value="Bantrab" @if(old('bank_name') === 'Bantrab') selected="selected" @endif>Bantrab</option>
                <option value="Vivibanco" @if(old('bank_name') === 'Vivibanco') selected="selected" @endif>Vivibanco</option>
                <option value="Banco Ficohsa" @if(old('bank_name') === 'Banco Ficohsa') selected="selected" @endif>Banco Ficohsa</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            {!! Form::label('same_bank', trans('revenues.same_bank'), array('class'=>'control-label')) !!}
            <select class="form-control" title="trans('revenues.same_bank')" name="same_bank" id="same_bank">
                <option value="1">-Si-</option>
                <option value="0">-No-</option>
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::label('reference', trans('revenues.reference'), array('class'=>'control-label')) !!}
            {!! Form::text('reference', Input::old('reference'), array('class'
            => 'form-control', 'placeholder'=>'No. de documento u otra referencia')) !!}
        </div>
    </div>

</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::label('card_name', trans('revenues.card_name'), array('class'=>'control-label')) !!}
            <input type="text" class="form-control" id="card_name" name="card_name">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            {!! Form::label('card_number', trans('revenues.card_number'), array('class'=>'control-label')) !!}
            {!! Form::text('card_number', Input::old('card_number'), array('class'=> 'form-control', 'placeholder'=>'XXXX')) !!}
        </div>
    </div>

</div>
@if (isset($deposit))
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('lblDeposit', trans('revenues.deposit_no'), array('class'=>'control-label')) !!}
                {!! Form::text('deposit', Input::old('deposit'), array('class'=> 'form-control', 'placeholder'=>'XXXX')) !!}
            </div>
        </div>
    </div>
@endif
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
