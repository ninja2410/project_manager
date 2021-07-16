<!-- Modal new customer -->
    <div class="modal fade in" id="pago-1" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg" role="document">            
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title" id="modalLabelsuccess">Pago Efectivo</h4>
                    </div> {{-- modal-header --}}
                    <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
                        
							{!! Form::open(array('url' => 'banks/revenues', 'files' => true, 'id'=>'frmSup')) !!}												
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
                                        <label for="date">{{trans('general.date')}}</label>
										<div class="input-group">
											<span class="input-group-addon"><li class="glyphicon glyphicon-calendar"></li> </span>
											<input type="text" class="form-control" id="paid_at" name="paid_at" required>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('payment_method', trans('revenues.payment_method')) !!}
										<div class="input-group select2-bootstrap-prepend">
                                            <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                                            <input type="text" class="form-control" id="payment_method" name="payment_method" value="">
										</div>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">										
										{!! Form::label('account_id', trans('revenues.account')) !!}
										<div class="input-group select2-bootstrap-prepend">
											<div class="input-group-addon"><i class="fa fa-money"></i></div>
											<select class="form-control" name="account_id" id="account_id">												
											</select>
										</div>										
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('amount', trans('revenues.amount')) !!} 
										<div class="input-group">
												<span class="input-group-addon"><li class="glyphicon glyphicon-usd"></li> </span>
										{!! Form::text('amount', Input::old('amount'), array('class' => 'form-control
										money_efectivo2','placeholder'=>'Monto', 'onKeyup'=>'applyRetentions(this)')) !!}
										</div>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										{!! Form::label('description', trans('revenues.description').' ') !!} 
										{!! Form::textarea('description', Input::old('description'), array('class' => 'form-control','size' => '30x2','placeholder'=>'Motivo del ingreso')) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group">
										{!! Form::label('bank_name', trans('revenues.bank_name_check')) !!}
										<input type="text" class="form-control" id="bank_name" name="bank_name">
									</div>
								</div>
								<div class="col-lg-2">
									<div class="form-group">
										{!! Form::label('same_bank', trans('revenues.same_bank')) !!}
										<select class="form-control" title="trans('revenues.same_bank')" name="same_bank" id="same_bank">
											<option value="1">-Si-</option>
											<option value="0">-No-</option>
										</select>
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
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('card_name', trans('revenues.card_name')) !!}
										<input type="text" class="form-control" id="card_name" name="card_name">										
									</div>
								</div>								
								<div class="col-lg-6">
									<div class="form-group">
										{!! Form::label('card_number', trans('revenues.card_number')) !!} 
										{!! Form::text('card_number', Input::old('card_number'), array('class'=> 'form-control', 'placeholder'=>'XXXX')) !!}
									</div>
								</div>
								
							</div>
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
								<input type="hidden" name="balance" id="balance">
								<input type="hidden" name="currency" id="currency" value="Q">
                                <input type="hidden" name="currency_rate" id="currency_rate" value="1">
                                <input type="hidden" name="payment_method" id="payment_method" value="1">
								<input type="hidden" name="status" id="status" value="5"> {{-- No conciliado --}}								
								<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">								
								<br>
								<div class="col-lg-12">
									@include('partials.buttons',['cancel_url'=>"/banks/revenues"])
								</div>
								{!! Form::close() !!}
                    </div> {{-- modal-body --}}
                    
                </div> {{-- modal-content --}}
        </div> {{-- modal-dialog  --}}
        
    </div> {{-- modal fade in  --}}
    {{-- </div> --}}
    <!--  Fin del modal new customer-->