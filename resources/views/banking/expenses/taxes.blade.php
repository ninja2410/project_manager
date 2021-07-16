<div class="div" id="taxes_div" style="display: none;">
    <center><h3>Calculo de impuestos</h3></center>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('taxe_category', trans('bank_expenses.taxes')) !!}
                <div class="input-group select2-bootstrap-prepend">
                    <div class="input-group-addon"><i class="fa fa-calculator"></i></div>
                    <select class="form-control" name="taxe_category" style="width: 100%;" id="taxe_category">
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('units', trans('bank_expenses.units')) !!}
                <div class="input-group">
                    <span class="input-group-addon"><strong>#</strong></span>
                    {!! Form::text('units', Input::old('units'), array('class' => 'form-control tax_ money_efectivo2 money_manual','id'=>'units')) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('unit_cost', trans('bank_expenses.unit_cost')) !!}
                <div class="input-group">
                    <span class="input-group-addon"><strong>Q</strong></span>
                    {!! Form::text('unit_cost', Input::old('unit_cost'), array('class' => 'form-control money_manual tax_ money_efectivo2','id'=>'unit_cost')) !!}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('total_cost', trans('bank_expenses.total_cost')) !!}
                <div class="input-group">
                    <span class="input-group-addon"><strong>Q</strong></span>
                    {!! Form::text('total_cost', Input::old('total_cost'), array('class' => 'money_manual form-control tax_ money_efectivo2','id'=>'total_cost')) !!}
                </div>
            </div>
        </div>
    </div>
</div>
