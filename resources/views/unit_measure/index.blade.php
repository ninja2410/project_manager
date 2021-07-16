@extends('layouts/default')

@section('title',trans('unit_measure.list'))
@section('page_parent',trans('unit_measure.unit_measure'))
@section('header_styles')
@endsection
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            {!! trans('unit_measure.list') !!}
          </h4>
          <div class="pull-right">
            <a href="{{ URL::to('unit_measure/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {!! trans('unit_measure.create') !!} </a>
          </div>
        </div>
        <div class="panel-body">

          <div class="row">
            <table class="table table-striped table-bordered" id="table1">
              <thead>
                <tr>
                  <td>No.</td>
                  <td>{!! trans('unit_measure.name') !!}</td>
                  <td>{!! trans('unit_measure.abbreviation') !!}</td>
                  <td>{!! trans('unit_measure.status') !!}</td>
                  <td style="width: 300px">Acciones</td>
                </tr>
              </thead>
              @foreach($units as $key => $value)
              <tr>
                <td>{{$key+1}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->abbreviation}}</td>
                <td>{{$value->status->name}}</td>
                <td>
                  @if ($value->status_id == 1)
                    <a class="btn btn-info" href="{{ URL::to('unit_measure/' . $value->id.'/edit' ) }}" data-toggle="tooltip" data-original-title="Editar">
                      <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Editar
                    </a>
                    <span class="table-remove">
                      <button type="button" class="btn btn-primary btn-danger" data-toggle="modal" data-target="#modal{!! $value->id !!}">
                        <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;
                        Eliminar
                      </button>
                      {{--Begin modal--}}
                      <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal{!! $value->id !!}" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-danger">
                              <h4 class="modal-title">Confirmación Eliminar</h4>
                            </div>
                            <div class="modal-body">
                              <div class="text-center">
                                {!! $value->name !!}
                                <br>
                                ¿Desea eliminar la unidad de medida?
                              </div>
                            </div>
                            <div class="modal-footer" style="text-align:center;">
                              {{-- test --}}
                              {!! Form::open(array('url' => 'unit_measure/' . $value->id, 'class' => 'pull-right')) !!} {!! Form::hidden('_method',
                              'DELETE') !!}
                              {{-- test --}}
                              <button type="submit" class="btn  btn-info">Aceptar</button>

                              <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button> {!! Form::close() !!}
                            </div>
                          </div>
                        </div>
                      </div>
                      {{--End modal--}}
                    </span>
                  @endif
                </td>
              </tr>
              @endforeach
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript">
  $(document).ready(function() {
    setDataTable("table1", [], "{{asset('assets/json/Spanish.json')}}");
  });
</script>
@endsection
