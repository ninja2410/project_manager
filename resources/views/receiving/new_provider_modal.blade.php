<!-- Modal new provider -->
<div class="modal fade" id="modal-2" name="modal-2" role="dialog" aria-labelledby="modalLabelsuccess">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" id="frmNewProvider">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="modalLabelsuccess">Agregar nuevo proveedor</h4>
                </div>
                <div class="modal-body" style="margin-left: auto;margin-right: auto; display: block;">
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('nit_supplier', trans('supplier.nit_supplier').' *') !!}
                            <input type="text" name="nit_supplier" id="nit_supplier" class="form-control"
                                   value="c/f" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('company_name', trans('supplier.company_name').' *') !!}
                            {!! Form::text('company_name', Input::old('company_name'), array('class' => 'form-control','id'=>'name_supplier', 'required' =>'true')) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('name', trans('supplier.name')) !!}
                            {!! Form::text('name', Input::old('name'), array('class' => 'form-control', 'id'=>'name')) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('address', trans('supplier.address' ))!!}*
                            {!! Form::text('address', 'Ciudad', array('class' => 'form-control', 'id'=>'address')) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('phone_number', trans('supplier.phone_number')) !!}
                            {!! Form::text('phone_number', Input::old('phone_number'), array('class' => 'form-control', 'maxlength'=>8)) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('email', trans('supplier.email')) !!}
                            {!! Form::text('email', Input::old('name'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('credit', trans('supplier.credit')) !!}
                            {!! Form::number('credit', Input::old('credit'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('days_credit', trans('supplier.days_credit')) !!}
                            {!! Form::number('days_credit', Input::old('days_credit'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('name_on_checks', trans('supplier.name_on_checks')) !!}
                            {!! Form::text('name_on_checks', Input::old('name_on_checks'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('bank', trans('supplier.bank')) !!}
                            {!! Form::text('bank', Input::old('bank'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('account_number', trans('supplier.account_number')) !!}
                            {!! Form::text('account_number', Input::old('account_number'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="modal-footer" style="text-align: center;">
                            <button type="button" class="btn  btn-primary" id="btnSaveNewProvider">Guardar</button>
                            <button class="btn  btn-danger" data-dismiss="modal" id="cerrar_">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!--  Fin del modal new provider-->
