<div class="row">
    <div class="col-md-1"></div>
    {!! Form::open(array('url'=>url('project/projects'),'method'=>'get')) !!}
    <div class="col-md-2">
        <center><label for=""><b>{{trans('report-sale.start_date')}}</b></label></center>
        <div class="input-group">
            <span class="input-group-addon"><li
                        class="glyphicon glyphicon-calendar"></li></span>
            <input type="text" name="date1"  id='start_date' class="form-control" value="{{date('d/m/Y', strtotime($fecha1))}}">
        </div>
    </div>
    <div class="col-md-2">
        <center><label for=""><b>{{trans('report-sale.end_date')}}</b></label></center>
        <div class="input-group">
            <span class="input-group-addon"><li
                        class="glyphicon glyphicon-calendar"></li></span>
            <input type="text" name="date2"  id='end_date' class="form-control" value="{{date('d/m/Y', strtotime($fecha2))}}" >
        </div>
    </div>
    <div class="col-md-3">
        <center><label for=""><b>Estado</b></label></center>
        <div class="input-group">
            <span class="input-group-addon"><li
                        class="glyphicon glyphicon-list-alt"></li></span>
            <select name="status" id="status" class="form-control">
                <option value="0" @if(count($status)>1) selected @endif>Todos</option>
                @foreach($all_status as $value)
                    <option value="{{$value->id}}"
                            @if(count($status)==1 && $value->id == $status[0])
                            selected
                            @endif
                    >{{$value->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <center><label for=""><b>Tipo</b></label></center>
        <div class="input-group">
            <span class="input-group-addon"><li
                        class="glyphicon glyphicon-tag"></li></span>
            <select name="type" id="type" class="form-control">
                <option value="" @if(count($type)>1) selected @endif>Todos</option>
                @foreach($types as $value)
                    <option value="{{$value->id}}"
                            @if(count($type)==1 && $value->id == $type[0])
                            selected
                            @endif
                    >{{$value->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <br>
        {!! Form::submit(trans('report-sale.filter'), array('class' => 'btn button-block btn-primary button-large boton-ancho')) !!}
    </div>
</div>
