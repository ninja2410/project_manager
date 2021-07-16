@extends('layouts/default')
@section('title',trans($view_name))
@section('page_parent',trans('accounts.banks'))

@section('content')
<?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
            {{trans($view_name)}}
          </h4>
          <div class="pull-right">
            @if(in_array('accounts/create',$array_p))
            <a href="{{ URL::to($create_route) }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Nueva cuenta</a>
            @endif
          </div>
        </div>
        <div class="panel-body">
          
          <div class="row">
            {!! Form::open(['url' => url('banks/accounts'), 'method' => 'get']) !!}
              <div class="col-md-4">
              </div>
              <div class="col-md-3">
                <label>
                  <b>{{trans('general.status')}}</b>
                </label>
                <select class="form-control" name="status_id" id="status_id">
                  {{-- <option value="0">Seleccione</option> --}}
                  @foreach ($status as $key => $value)
                    <option @if ($selected==$value->id) selected @endif value="{{$value->id}}">{{$value->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-1">
                <br>
                <button type="submit" class="btn btn-small btn-primary">Filtrar</button>
              </div>
              <div class="col-md-4">
                <input type="hidden" id="forma_pago" name="forma_pago" value=""/>
              </div>
            {!! Form::close() !!}
          </div>

          <table class="table table-striped table-bordered" id="table1">
            <thead>
              <tr>
                <th></th>
                <th data-priority="1001" style="width: 5%;">No</th>
                <th data-priority="2">Nombre</th>
                <th data-priority="6">Banco</th>
                <th data-priority="4">Número</th>
                <th data-priority="5">Tipo</th>
                <th data-priority="3">Saldo actual</th>
                <th data-priority="3">Saldo conciliado</th>
                <th>Estado</th>
                <th style="width: 28%;">Acciones</th>
              </tr>
            </thead>
            <tbody>
            @foreach($accounts as $i => $value)
              <tr>
                <td></td>
                <td>{{$i+1}}</td>
                <td><a @if(in_array('accounts/statement',$array_p)) href="{{ URL::to('banks/accounts/statement/' . $value->id ) }}" @endif data-toggle="tooltip" data-original-title="Estado de cuenta">{{$value->account_name}}</a></td>
                <td>{{$value->bank_name}}</td>
                <td>{{$value->account_number}}</td>
                <td>{{$value->type->name}}</td>
                <td style="text-align:right">@money($value->balance)</td>
                <td style="text-align:right">@money($value->reconcilied_balance)</td>
                <td style="text-align:center">
                  @if ($value->status==1)
                    <span class="label label-success">{{ trans('Activo') }}</span> @else
                    <span class="label label-danger">{{ trans('Inactivo') }}</span> @endif
                </td>
                <td>
                  <a class="btn btn-primary" data-toggle="tooltip" data-original-title="Estado de cuenta" @if(in_array('accounts/statement',$array_p)) href="{{ URL::to('banks/accounts/statement/' . $value->id ) }}" @endif>
                    <span class="glyphicon glyphicon-info-sign"></span>
                  </a>
                  <a class="btn btn-success" data-toggle="tooltip" data-original-title="{{trans('reconciliation.reconciliation')}}" @if(in_array('accounts/statement',$array_p)) href="{{ URL::to('bank_reconciliation/account/' . $value->id ) }}" @endif>
                    <span class="glyphicon glyphicon-list-alt"></span>
                  </a>
                  <a class="btn btn-info" @if(in_array('accounts/edit',$array_p))  href="{{ URL::to('banks/accounts/' . $value->id.'/edit' ) }}" @endif data-toggle="tooltip" data-original-title="Editar">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <a class="btn btn-warning"  href="{{ URL::to('banks/accounts/' . $value->id ) }}"  data-toggle="tooltip" data-original-title="Detalles">
                    <span class="glyphicon glyphicon-eye-open"></span>
                  </a>
                  <button type="button" class="btn btn-primary btn-danger" @if(in_array('accounts/delete',$array_p)) data-toggle="modal" data-target="#modal{!! $value->id !!}" @endif title="Eliminar">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>

                  {{--Begin modal--}}
                  <span class="table-remove">
                  <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modal{!! $value->id !!}" role="dialog" aria-labelledby="modalLabelfade"
                       aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header bg-danger">
                          <h4 class="modal-title">Confirmación Eliminar</h4>
                        </div>
                        <div class="modal-body">
                          <div class="text-center">
                            {!! $value->account_name !!}
                            <br> ¿Desea eliminar cuenta?
                          </div>
                        </div>
                        <div class="modal-footer" style="text-align:center;">
                          {{-- test --}} {!! Form::open(array('url' => 'banks/accounts/' . $value->id, 'class' => 'pull-right')) !!} {!! Form::hidden('_method',
                          'DELETE') !!} {{-- test --}}
                          <a href="{{ route('banks.accounts.destroy',$value->id) }}">
                            <button type="submit" class="btn  btn-info">Aceptar</button>
                          </a>

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
            <tfoot>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th style="text-align:right">Total</th>
              <th style="text-align:right"></th>
              <th style="text-align:right"></th>
              <th></th>
              <th></th>
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
<script type="text/javascript">
  $(document).ready(function () {
    setDataTable("table1", [6,7], "{{asset('assets/json/Spanish.json')}}");
  });
</script>
@stop
