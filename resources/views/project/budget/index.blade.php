@extends('layouts/default')

@section('title', trans('budget.budget_list'))
@section('page_parent', trans('project.projects'))
@section('header_styles')
  {{-- date time picker --}}
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16" data-loop="true"
                                                 data-c="#fff" data-hc="white"></i>
              {{trans('budget.budget_list')}} {{$project->name}}
            </h4>
            <div class="pull-right">
              <a href="{{ URL::to('project/stages_project/'.$project->id) }}" class="btn btn-sm btn-danger"><span
                        class="glyphicon glyphicon-arrow-left"></span> {{trans('project.return')}} </a>
              <a href="{{ URL::to('project/'.$project->id.'/budget/create') }}" class="btn btn-sm btn-default"><span
                        class="glyphicon glyphicon-plus"></span> {{trans('budget.create')}} </a>
            </div>
          </div>
          <div class="panel-body">
            @include('partials.filter_list_general')
            <hr>
            <div class="row">
              <table class="table table-striped table-bordered" id="table1">
                <thead>
                <tr>
                  <th></th>
                  <th>{{trans('budget.correlative')}}</th>
                  <th>{{trans('budget.date')}}</th>
                  <th>{{trans('budget.created_by')}}</th>
                  <th>{{trans('budget.status')}}</th>
                  <th>{{trans('budget.total_project')}}</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($budgets as $budget)
                  <tr>
                    <td></td>
                    <td>{{$budget->correlative}}</td>
                    <td>{{$budget->date}}</td>
                    <td>{{$budget->createdBy->name.' '.$budget->createdBy->last_name}}</td>
                    <td>{{$budget->status->name}}</td>
                    <td>@money($budget->amount)</td>
                    <td>
                      <a class="btn btn-primary" data-toggle="tooltip" data-original-title="Ver detalles"
                         href="{{ URL::to('project/'.$project->id.'/budget/'.$budget->id.'/show' ) }}">
                        <span class="glyphicon glyphicon-info-sign"></span>
                      </a>
                      @if ($budget->status_id == 1)
                        <a class="btn btn-warning" data-toggle="tooltip" data-original-title="Editar"
                           href="{{ URL::to('project/'.$project->id.'/budget/'.$budget->id.'/edit' ) }}">
                          <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a class="btn btn-success" data-toggle="tooltip" data-original-title="Clonar"
                           href="{{ URL::to('project/budget/clone/'.$budget->id ) }}">
                          <span class="glyphicon glyphicon-duplicate"></span>
                        </a>
                        <span class="table-remove">
                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#modal{!! $budget->id !!}">
                          <span class="glyphicon glyphicon-remove"></span>
                        </button>
                        {{--Begin modal--}}
                        <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal{!! $budget->id !!}"
                             role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h4 class="modal-title">Confirmación Eliminar</h4>
                              </div>
                              <div class="modal-body">
                                <div class="text-center">
                                  {!! $budget->name !!}
                                  <br>
                                  ¿Desea eliminar el presupuesto?
                                </div>
                              </div>
                              <div class="modal-footer" style="text-align:center;">
                                {!! Form::open(array('url' => 'project/'.$project->id.'/budget/'.$budget->id)) !!}
                                {!! Form::hidden('_method','DELETE') !!}
                                  <button type="submit" class="btn  btn-info">{{trans('button.accept')}}</button>
                                  <button class="btn  btn-danger" data-dismiss="modal">{{trans('button.cancel')}}</button>
                                {!! Form::close() !!}
                              </div>
                            </div>
                          </div>
                        </div>
                        {{--End modal--}}
                      @endif
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
  </section>
@endsection
@section('footer_scripts')
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
  <script type="text/javascript">
      $(document).ready(function () {
          setDataTable("table1", [], "{{asset('assets/json/Spanish.json')}}");
          $("#start_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:new Date()}).parent().css("position :relative");
          $("#end_date").datetimepicker({ sideBySide: true, locale:'es',format:'DD/MM/YYYY', defaultDate:new Date()}).parent().css("position :relative");
      });
  </script>
@endsection
