<style>
    .boton-ancho {
        width: 100%;
    }
</style>
<div class="row">
    <div class="col-md-1">
{{--        <center>--}}
{{--            <label for="">--}}
{{--                <b>--}}
{{--                    Seleccione forma de pago--}}
{{--                </b>--}}
{{--            </label>--}}
{{--        </center>--}}
{{--        <select class="form-control" id="forma_pagoName" name="forma_pago">--}}
{{--            <option value="Todo" {{ ($forma_pago == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>--}}
{{--            @foreach($dataPagos as $index => $value)--}}
{{--                <option value="{{$value->id}}" {{ ($forma_pago == $value->id) ?  'selected="selected"' : '' }}>{{$value->name}}</option>--}}
{{--            @endForeach--}}
{{--        </select>--}}
    </div>
    <div class="col-md-3">
        <center><label for=""><b>{{trans('project.type_retention')}}</b></label></center>
        <select class="form-control" id="retention" name="retention">
            <option value="Todo" {{ ($retention == 'Todo') ?  'selected="selected"' : '' }}>Todos</option>
            @foreach($retention_type as $index => $value)
                <option value="{{$value->id}}" {{ ($retention == $value->id) ?  'selected="selected"' : '' }}>{{$value->name}}</option>
            @endForeach
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