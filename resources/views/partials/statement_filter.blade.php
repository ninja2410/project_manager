<style>
  .boton-ancho {
    width: 100%;
  }
</style>
<div class="row">
  <div class="col-md-3">
    <center><label for=""><b>{{trans('sale.status')}}</b></label></center>
    <select class="form-control" id="statusName" name="status">
      <option value="7" {{ ($status == '7') ?  'selected="selected"' : '' }}>Pendientes de pago</option>      
      <option value="Todo" {{ ($status == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
    </select>
  </div>
  <div class="col-md-2">
    <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
    <input type="text" name="date1"  id='start_date' class="form-control fecha" value="{{date('d/m/Y', strtotime($fecha1))}}" @if($all===0) style="display: none" @endif>
  </div>
  <div class="col-md-2">
    <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
    <input type="text" name="date2"  id='end_date' class="form-control fecha" value="{{date('d/m/Y', strtotime($fecha2))}}" @if($all===0) style="display: none" @endif>
  </div>
  <div class="col-md-1">
    <center><label for=""><b>{{trans('credit.use_dates')}}</b></label></center>
    <input type="checkbox" name="all"  id='all' class="form-control" @if($all===1) checked="checked" @endif >
  </div>
  <div class="col-md-3">
    <br>
    {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn button-block btn-primary button-large boton-ancho')) !!}
  </div>
  
  
</div>
<hr>
{{-- <div class="row">
  <div class="col-md-6"></div>
  <div class="col-md-3"><br><label for=""><b>Nota: </b>Ordenado por fecha</label></div>
  <div class="col-md-3">
    <br>
    {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn btn-primary ')) !!}
  </div>
</div> --}}