<style>
  .boton-ancho {
    width: 100%;
  }
</style>
<div class="row">
  <div class="col-md-2">
    <center>
      <label for="">
        <b>
          Forma de pago
        </b>
      </label>
    </center>
    <select class="form-control" id="forma_pagoName" name="forma_pago">
      <option value="Todo" {{ ($forma_pago == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
      @foreach($dataPagos as $index => $value)
        <option value="{{$value->id}}" {{ ($forma_pago == $value->id) ?  'selected="selected"' : '' }}>{{$value->name}}</option>
      @endForeach
    </select>
  </div>
  <div class="col-md-2">
    <center><label for=""><b>{{trans('sale.status')}}</b></label></center>
    <select class="form-control" id="statusName" name="status">
      <option value="Todo" {{ ($status == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
      @foreach($dataStatus as $index => $value)
        <option value="{{$value->id}}" {{ ($status == $value->id) ?  'selected="selected"' : '' }}>{{$value->name}}</option>
      @endForeach
    </select>
  </div>
  <div class="col-md-2">
    <center><label for=""><b>{{trans('revenues.deposit')}}</b></label></center>
    <select class="form-control" id="filter_deposit" name="deposit">
      <option value="Todo" {{ ($deposit == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
      <option value="1" {{ ($deposit == "1") ?  'selected="selected"' : '' }}>{{trans('revenues.valid')}}</option>
      <option value="0" {{ ($deposit == "0") ?  'selected="selected"' : '' }}>{{trans('revenues.invalid')}}</option>

    </select>
  </div>
  <div class="col-md-2">
    <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
    <input type="text" name="date1"  id='start_date'class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
  </div>
  <div class="col-md-2">
    <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
    <input type="text" name="date2"  id='end_date'class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
  </div>
  <div class="col-md-2">
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