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
            {{-- @foreach($dataStatus as $index => $value) --}}
            {{-- <option value="{{$value->id}}" {{ ($status == $value->id) ?  'selected="selected"' : '' }}>{{$value->name}}</option> --}}
            {{-- @endForeach --}}
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
        {!! Form::submit(trans('report-sale.generate'), array('class' => 'btn button-block btn-primary button-large boton-ancho')) !!}
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
