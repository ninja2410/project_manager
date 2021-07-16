@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Lista de Bodegas</div>
                
                <div class="panel-body">
                    <a class="btn btn-small btn-success" href="{{ URL::to('almacen/create') }}">Nueva bodega</a>
                    <hr />
                    @if (Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Nombre</td>
                                <td>Teléfono</td>
                                <td>Dirección</td>
                                <td>Comentario</td>
                                <td>Estado</td>
                                <td>&nbsp;</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($almacen as $value)
                            <tr>
                                <td>{{ $value->id }}</td>
                                <td>{{ $value->nombre }}</td>
                                <td>{{ $value->phone }}</td>
                                <td>{{ $value->adress }}</td>
                                <td>{{ $value->comentario }}</td>
                                <td>{{ $value->estado }}</td>
                                <td>
                                    
                                    <a class="btn btn-small btn-primary" style="width:40px" href="{{ URL::to('almacen/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar"></a>
                                    {!! Form::open(array('url' => 'almacen/' . $value->id, 'class' => 'pull-right')) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {{-- {!! Form::submit(trans('Eliminar'), array('class' => 'btn btn-danger','width'=>'40px')) !!} --}}
                                        <button type="submit" style="width: 40px" class="btn btn-primary btn-danger" type="submit"  title="Borrar" data-toggle="tooltip" data-original-title="trans('customer.delete')">
                                        <span class="glyphicon glyphicon-remove-circle"></span>
                                      </button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
