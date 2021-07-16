@extends('layouts/default')

@section('title',trans('item.price_types'))
@section('page_parent',trans('item.items'))

@section('header_styles')

@stop
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16" data-loop="true"
                                                 data-c="#fff" data-hc="white"></i>
              {{trans('item.price_types')}}
            </h4>
            <div class="pull-right">
              <a href="{{ URL::to('prices/create') }}" class="btn btn-sm btn-default"><span
                        class="glyphicon glyphicon-plus"></span>{{trans('item.new_price')}}</a>
            </div>
          </div>
          <div class="panel-body">

            <table class="table table-striped table-bordered" id="table1">
              <thead>
              <tr>
                <th></th>
                <th style="width:5%" data-priority="1001">No.</th>
                <th style="width: 15%" data-priority="2">{{trans('item.name')}}</th>
                <th data-priority="5">{{trans('item.price_pct')}}</th>
                <th data-priority="4">{{trans('item.price_pct_min')}}</th>
                <th data-priority="6">{{trans('item.order')}}</th>
                <th data-priority="3">{{trans('item.main')}}</th>
                <th data-priority="3">{{trans('item.active')}}</th>
                <th data-priority="3">{{trans('item.pagos')}}</th>
                <th style="width: 10%">{{trans('item.actions')}}</th>
              </tr>
              </thead>
              <tbody>
              @foreach($prices as $i=>$value)
                <tr>
                  <td></td>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $value->name }}</td>
                  <td>{{ $value->pct }}</td>
                  <td>{{ $value->pct_min }}</td>
                  <td>{{ $value->order }}</td>
                  <td>{{ $value->main }}</td>
                  <td>{{ $value->active }}</td>
                  <td>
                    @foreach($value->pagos as $key => $item)
                      <span class="badge badge-info">{{ $item->name }}</span>
                    @endforeach
                  </td>
                  <td>
                    {{-- <a class="btn btn-small btn-primary" href="{{ URL::to('bodega_usuario/' . $value->id . '/edit') }}" >{{trans('Agregar usuarios')}}</a> --}}
                    <a class="btn btn-info" style="width: 40px" href="{{ URL::to('prices/' . $value->id . '/edit') }}"
                       data-toggle="tooltip" data-original-title="Editar">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <button type="button" class="btn btn-primary btn-danger" data-toggle="modal" data-target="#modal_repeat{!! $value->id !!}">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    {{--Begin modal--}}
                    <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal_repeat{!! $value->id !!}" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-success">
                            <h4 class="modal-title">Confirmación eliminar precio</h4>
                          </div>
                          <div class="modal-body">
                            <div class="text-center">
                              {!! $value->name !!}
                              <br>
                              ¿Desea eliminar el precio?
                            </div>
                          </div>
                          <div class="modal-footer" style="text-align:center;">
                            {!! Form::open(array('url' => 'prices/' . $value->id, 'class' => 'pull-right')) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            <button type="submit" class="btn  btn-info">Aceptar</button>
                            <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                            {!! Form::close() !!}
                          </div>
                        </div>
                      </div>
                    </div>
                    {{--End modal--}}
                  </td>
                </tr>

              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('footer_scripts')
  <script type="text/javascript">
      $(document).ready(function () {
          setDataTable("table1", [], "{{ asset('assets/json/Spanish.json') }}");
      });
  </script>
@stop
