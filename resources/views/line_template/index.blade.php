@extends('layouts/default')

@section('title',trans('line-template.line-template'))
@section('page_parent',trans('project.projects'))
@section('header_styles')
@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
              <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                {{trans('line-template.line-templates')}}
              </h4>
              <div class="pull-right">
                <a href="{{ URL::to('line-template/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('line-template.create')}} </a>
              </div>
            </div>
        <div class="panel-body">

          <table class="table table-striped table-bordered" id="table1">
            <thead>
              <tr>
                <th>No.</th>
                <th>{{trans('line-template.name')}}</th>
                <th>{{trans('line-template.description')}}</th>
                <th>{{trans('line-template.price')}}</th>
                <th>{{trans('line-template.items_quantity')}}</th>
                <th>{{trans('line-template.size')}}</th>
                <th style="width: 250px">{{trans('line-template.actions')}}</th>
              </tr>
            </thead>
            <tbody>
            @foreach($templates as $key => $value)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$value->name}}</td>
                    <td>{{$value->description}}</td>
                    <td>Q {{number_format($value->getPrice(), 2)}}</td>
                    <td>{{number_format($value->items_quantity, 2)}}</td>
                    <td>{{$value->size}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ URL::to('line-template/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Ver detalles">
                            <span class="glyphicon glyphicon-eye-open"></span>&nbsp;&nbsp;{{trans('line-template.show')}}
                        </a>
                        <a class="btn btn-info" href="{{ URL::to('line-template/' . $value->id.'/edit' ) }}" data-toggle="tooltip" data-original-title="Editar">
                            <span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;{{trans('line-template.edit')}}
                        </a>
                        <span class="table-remove">
                    <button type="button" class="btn btn-primary btn-danger" data-toggle="modal" data-target="#modal{!! $value->id !!}">
                      <span class="glyphicon glyphicon-remove"></span>&nbsp;&nbsp;
                      {{trans('line-template.delete')}}
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
                                      ¿Desea eliminar renglon?
                                  </div>
                              </div>
                              <div class="modal-footer" style="text-align:center;">
                                {{-- test --}}
                                  {!! Form::open(array('url' => 'line-template/' . $value->id, 'class' => 'pull-right')) !!} {!! Form::hidden('_method',
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
  $(document).ready(function() {
      setDataTable("table1", [], "{{ asset('assets/json/Spanish.json') }}");
  });

</script>
@stop
