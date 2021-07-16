@extends('layouts/default')

@section('title',trans('credit_notes.list'))
@section('page_parent',trans('credit_notes.credit_note'))

@section('header_styles')
  <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet"
        type="text/css"/>
  <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css"/>
@stop
@section('content')
  <section class="content">
    <div class="row">
      <input type="hidden" id="load_sale_url">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"><i class="livicon" data-name="list-ul" data-size="16"
                                               data-loop="true" data-c="#fff" data-hc="white"></i>
            {{trans('credit_notes.list')}}
          </h4>
          <div class="pull-right">
            <a href="{{ URL::to('credit_note/create') }}" class="btn btn-sm btn-default"><span
                      class="glyphicon glyphicon-plus"></span> {{trans('credit_notes.create')}} </a>
          </div>
        </div>

        <div class="panel-body table-responsive">

          @include('partials.filter_list_general')
          <hr>
          <div class="table-responsive-lg table-responsive-sm table-responsive-md">
            <table class="table table-striped table-bordered" id="table1">
              <thead>
              <tr>
                <th></th>
                <th data-priority="4" style="width: 5%;">{{trans('credit_notes.no')}}</th>
                <th data-priority="4">{{trans('credit_notes.credit_note_singular')}}</th>
                {{-- <th data-priority="4">{{trans('credit_notes.correlative')}}</th> --}}
                <th data-priority="2" style="width: 18%;">{{trans('credit_notes.customer')}}</th>
                <th data-priority="3" style="width: 12%;">{{trans('credit_notes.date')}}</th>
                <th data-priority="1">{{trans('credit_notes.status')}}</th>

                <th data-priority="7">{{trans('credit_notes.sale')}}</th>
                <th data-priority="9">{{trans('credit_notes.type')}}</th>
                <th data-priority="6">{{trans('credit_notes.amount')}}</th>
                {{--                                <th data-priority="8">{{trans('credit_notes.reference')}}</th>--}}
                <th style="width: 10%;">{{trans('credit_notes.actions')}}</th>
              </tr>
              </thead>
              <tbody>
              @foreach($credit_notes as $i=> $value)
                <tr>
                  <td></td>
                  <td>{{$i+1}}</td>
                  <td>{{$value->serie->name.' # '.$value->correlative}}</td>
                  <td style="font-size:12px;">{{$value->customer->name}}</td>
                  <td>{{date('d/m/Y', strtotime($value->date))}}</td>
                  <td>
                    @if ($value->status_id!=13)
                      <span class="label label-success">{{$value->status->name}}</span>
                    @else
                      <span class="label label-danger">{{$value->status->name}}</span>
                    @endif
                  </td>
                  <td>
                    @if (isset($value->sale_id))
                      <a data-toggle="tooltip" target="_blank"
                         data-original-title="{{trans('quotation.see_sale')}}"
                         href="{{url('sales/complete/'.$value->sale->id)}}">{{$value->sale->serie->document->name.' '.$value->sale->serie->name.'-'.$value->sale->correlative}}</a>
                    @else
                      {{trans('quotation.n/f')}}
                    @endif
                  </td>
                  <td>
                    <span class="label label-info">{{trans('credit_notes.type'.$value->type)}}</span>
                  </td>
                  <td>@money($value->amount)</td>
                  {{--                                    <td>{{$value->comment}}</td>--}}
                  <td>
                    <a class="btn btn-success" style="width: 40px" data-toggle="tooltip"
                       data-original-title="Ver detalles"
                       href="{{URL::to('credit_note/'.$value->id)}}">
                      <span class="glyphicon glyphicon-file"></span>
                    </a>
                    @if ($value->status_id != 13)
                      <a class="btn btn-danger"  data-toggle="modal"  data-href="#modal{{$value->id}}" href="#modal{{$value->id}}"  id="{{$value->id}}"><span class="glyphicon glyphicon-remove-sign"></span></a>
                      {{--Begin modal anulación--}}
                      <div class="modal fade in" id="modal{{$value->id}}" tabindex="-1" role="dialog" aria-hidden="false">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header bg-info">
                              <h4 class="modal-title">Anulación</h4>
                            </div>
                            <div class="modal-body">
                              <div class="text-center">
                                <h3>¿Seguro que desea anular la nota de crédito {{$value->serie->name.' # '.$value->correlative}}?</h3>
                              </div>
                            </div>
                            <div class="modal-footer" style="text-align:center;">
                              {!! Form::open(array('url' => 'credit_note/' . $value->id, 'name'=>'frmDelete'.$value->id, 'id'=>'frmDelete'.$value->id)) !!}
                              {!! Form::hidden('_method', 'DELETE') !!}
                              <button type="submit" onclick="showLoading('Anulando nota de crédito...');" class="btn  btn-info">Aceptar</button>
                              <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                              {!! Form::close() !!}
                            </div>
                          </div>
                        </div>
                      </div>
                      {{-- Fin modal anulación --}}
                    @endif
                  </td>
                </tr>
              @endforeach
              </tbody>
              <tfoot>
              <tr>
                <td colspan="7" style="text-align: right"><strong>Total:</strong></td>
                <td colspan="3"></td>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('footer_scripts')
  {{-- Calendario --}}
  <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/moment/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

  <script type="text/javascript">
      var load_sale_url;

      $(document).ready(function () {
          $('[data-toggle="tooltip"]').tooltip();
          $("#start_date").datetimepicker({
              sideBySide: true,
              locale: 'es',
              format: 'DD/MM/YYYY'
          }).parent().css("position :relative");
          $("#end_date").datetimepicker({
              sideBySide: true,
              locale: 'es',
              format: 'DD/MM/YYYY'
          }).parent().css("position :relative");
          setDataTable("table1", [8], "{{asset('assets/json/Spanish.json')}}");
      });
  </script>
@stop
