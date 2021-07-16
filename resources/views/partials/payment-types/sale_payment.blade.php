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
        {{-- <div class="form-group">
            {!! Form::label('payment_method', trans('revenues.payment_method')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                <input type="text" class="form-control" id="payment_method" name="payment_method" value="">
            </div>
        </div> --}}
        <div class="form-group">
            {!! Form::label('lblpayment_method', trans('sale.payment_method')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                <select class="form-control" title="trans('revenues.payment_method')" name="id_pago" id="id_pago" >
                    <option value="0">--Seleccione--</option>
                    {{-- @foreach($payments as $item)
                    <option value="{!! $item->id !!}" type="{!! $item->type !!}">{{ $item->name }}</option>
                    @endforeach --}}
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('account_id', trans('revenues.account')) !!}
            <div class="input-group select2-bootstrap-prepend">
                <div class="input-group-addon"><i class="fa fa-money"></i></div>
                <select class="form-control validation-cacao" name="account_id" id="account_id" >
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">
    
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('amount', trans('revenues.amount')) !!}
            <div class="input-group">
                <span class="input-group-addon"><strong>Q</strong> </span>
                {!! Form::text('amount', Input::old('amount'), array('class' => 'form-control money_efectivo2','placeholder'=>'Monto','readonly'=>'readonly')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            {!! Form::label('paid', trans('revenues.paid')) !!}
            <div class="input-group">
                <span class="input-group-addon"><strong>Q</strong> </span>
                {!! Form::text('paid', Input::old('paid'), array('class' => 'form-control money_efectivo2 validation-cacao','placeholder'=>'Monto','step' => '0.1')) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
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
{{-- <div class="row"> --}}
    <div class="col-lg-6">
        <div class="form-group referencia" style="display:none">
            {!! Form::label('reference', trans('revenues.reference'),array('id'=>'reference_id')) !!}
            {!! Form::text('reference', Input::old('reference'), array('class'
            => 'form-control referencia', 'placeholder'=>'No. de documento u otra referencia')) !!}
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group banco" style="display:none">
            {!! Form::label('bank_name', trans('revenues.bank_name_check')) !!}
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
            {{-- <input type="text" class="form-control banco" id="bank_name" name="bank_name"> --}}
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group mismo_banco" style="display:none">
            {!! Form::label('same_bank', trans('revenues.same_bank')) !!}
            <select class="form-control mismo_banco" title="trans('revenues.same_bank')" name="same_bank" id="same_bank">
                <option value="0">-No-</option>
                <option value="">-Seleccione-</option>
                <option value="1">-Si-</option>                
            </select>
        </div>
    </div>
    
    
    
    {{-- </div> --}}
    {{-- <div class="row" > --}}
        <div class="col-lg-6">
            <div class="form-group tarjeta" style="display:none">
                {!! Form::label('card_name', trans('revenues.card_name')) !!}
                <input type="text" class="form-control tarjeta" id="card_name" name="card_name">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group tarjeta" style="display:none"> 
                {!! Form::label('card_number', trans('revenues.card_number')) !!}
                {!! Form::text('card_number', Input::old('card_number'), array('class'=> 'form-control tarjeta', 'placeholder'=>'XXXX')) !!}
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="form-group credito" style="display:none">
                {!! Form::label('customer_credit', trans('sale.customer_credit')) !!}
                <div class="input-group">
                    <span class="input-group-addon"><strong>Q</strong> </span>
                    {!! Form::text('customer_credit', Input::old('customer_credit'), array('class' => 'form-control money_efectivo2 credito','placeholder'=>'Credito autorizado','readonly'=>'readonly')) !!}
                </div>
                
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group credito" style="display:none">
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
            
            
