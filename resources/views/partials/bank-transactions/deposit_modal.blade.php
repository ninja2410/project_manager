{{--Begin modal--}}
<div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="addDepositModal" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title">{{trans('revenues.add_deposit_no')}}</h4>
      </div>
      <div class="modal-body">
        {!! Form::open(['url' => url('banks/deposit/addnumber'), 'method' => 'post', 'id'=>'frmDeposit']) !!}
        <input type="hidden" name="revenue_id" id="revenue_id">
        <input type="hidden" name="view" value="{{$view}}">
        <div class="row">
          <h4>Nota:</h4>
          <p>Después de guardar, no podrá modificar el número de depósito.</p>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              {!! Form::label('lblDeposit', trans('revenues.deposit_no'), array('class'=>'control-label')) !!}
              {!! Form::text('deposit', Input::old('deposit'), array('class'=> 'form-control', 'placeholder'=>'XXXX', 'required','id'=>'deposit')) !!}
            </div>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
      <div class="modal-footer" style="text-align:center;">
        <a class="btn  btn-info" id="btn_save_confirm" onclick="verifyDeposit()">Aceptar</a>
        <a class="btn  btn-danger" data-dismiss="modal" >Cancelar</a>
      </div>
    </div>
  </div>
</div>
{{--End modal--}}
