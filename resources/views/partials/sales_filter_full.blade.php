<style>
  .boton-ancho {
    width: 100%;
  }
</style>
<div class="row">
  <div class="col-md-3">
    <center>
      <label for="">
        <b>
          Documento
        </b>
      </label>
    </center>
    <select class="form-control" id="documentsName" name="document">
      <option value="Todo" {{ ($document == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
      @foreach($dataDocuments as $index => $value)
      <option value="{{$value->id_serie}}" {{ ($document == $value->id_serie) ?  'selected="selected"' : '' }}>{{$value->document.' '.$value->serie}}</option>
      @endForeach
    </select>
  </div>
  <div class="col-md-3">
    <center><label for=""><b>{{trans('sale.status')}}</b></label></center>
    <select class="form-control" id="statusName" name="status">
      <option value="0" {{ ($status == '0') ?  'selected="selected"' : '' }}>Activas</option>
      <option value="1" {{ ($status == '1') ?  'selected="selected"' : '' }}>Anuladas</option>
      <option value="Todo" {{ ($status == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
    </select>
  </div>
  <div class="col-md-3">
    <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
    <input type="text" name="date1"  id='start_date'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
  </div>
  <div class="col-md-3">
    <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
    <input type="text" name="date2"  id='end_date'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
  </div>  
</div>
<hr>
<div class="row">
  <div class="col-md-2" style="text-align:center">
    <h4>Ventas</h4>
  </div>
  <div class="col-md-2" style="text-align:center">
    <input @if($tipo==="lista") {{ "checked='checked'"  }} @endif name="tipo_" id="tipo_" type="radio" class="square" value="lista">
    
  </div>
  <div class="col-md-2" style="text-align:center">
    <h4>Ventas por forma de pago</h4>
  </div>
  <div class="col-md-2" style="text-align:center">
    <input @if($tipo==="forma_pago") {{ "checked='checked'"  }} @endif name="tipo_" id="tipo_" type="radio"  class="square" value="forma_pago">
    
  </div>
  <div class="col-md-3">
    {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn btn-primary button-large boton-ancho')) !!}
  </div>
  <div class="col-md-1"></div>
</div>
<hr>