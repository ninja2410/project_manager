{{--
FILTRO PARA LISTAS EN GENERAL
PARAMETROS NECESARIOS A ENVIAR DESDE EL CONTROLADOR:
* $url: URL a donde se enviará la búsqueda
* $fecha1 y $fecha2: rango de fechas
* $all_status: lista de estados por los cuales se realizará un filtrado
* $status: id de estado seleccionado

OBSERVACION CON EL STATUS Y SU MANEJO DENTRO DEL CONTROLADOR:
- Para hacer que el partial sea genérico dentro del controlador se debe manejar el status por medio de un array
y en la busqueda de eloquent utilizar funcion whereIn para aplicarla. Ej.

    Si el estado es nullo (no se ha filtrado nada)
    if ($status == null){
        $status = StateCellar::where('type', 'transfer')
            ->lists('id');
    }
    else{
    SI EL ESTADO ESTA SELECCIONADO CONFIGURAR A ARRAY PARA PODER UTILIZAR whereIn en la consulta
        $status = (array)$status;
    }

    //APLICANDO FILTRO
    ->whereIn('receivings.status_transfer_id', $status)

--}}
<div class="row">
    <div class="col-md-1"></div>
    {!! Form::open(array('url'=>$url,'method'=>'get', 'name'=>'frmFilter')) !!}
    <div class="col-md-3">
        <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
        <input type="text" name="date1"  id='start_date' class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
    </div>
    <div class="col-md-3">
        <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
        <input type="text" name="date2"  id='end_date' class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
    </div>
    <div class="col-md-3">
        <center><label for=""><b>Estado</b></label></center>
        <select name="status" id="status" class="form-control">
            <option value="" @if(count($status)>1) selected @endif>Todos</option>
            @foreach($all_status as $value)
                <option value="{{$value->id}}"
                        @if(count($status)==1 && $value->id == $status[0])
                        selected
                        @endif
                >{{$value->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <br>
        {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn button-block btn-primary button-large boton-ancho')) !!}
    </div>
    {!! Form::close() !!}
</div>
