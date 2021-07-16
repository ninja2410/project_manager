@extends('app')
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" />
@stop
@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/sale.js', array('type' => 'text/javascript')) !!}
<div class="container-fluid">
   <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                <span class="glyphicon glyphicon-inbox" aria-hidden="true">
                </span>
                  {{trans('sale.register_transfer_to_storage_name')}}
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="id_document">Documento</label>
                      <select class="form-control" name="id_document" id="id_document">
                        <option value="0">Seleccione documento</option>
                        @foreach($data_documents as $value)
                        <option value="{{$value->id}}">{{$value->document}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="bodega_origen">Bodega origen:</label>
                      {!! Form::open(array('url'=>'transfer_to_storage','id'=>'id_storage_origins_form')) !!}
                      <select class="form-control" name="id_storage_origins" id="id_storage_origins" required>
                        <option value="">Seleccione una bodega</option>
                        @foreach($data_storage as $value)
                          <option value="{{$value->id}}" {{ ($selected_storage == $value->id) ?  'selected="selected"' : '' }}>
                            {{$value->name}}
                          </option>
                        @endforeach
                      </select>
                      {!! Form::close() !!}
                    </div>
                    <div class="col-lg-6">
                      <label for="bodega_origen">Bodega Destino:</label>
                      <select class="form-control" name="id_storage_destination" id="id_storage_destination">
                        <option value="0">Seleccione una bodega</option>
                        @foreach($data_storage as $value)
                          @if($selected_storage!=$value->id)
                            <option value="{{$value->id}}">{{$value->name}}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <br>
                  <br>
                  <div class="row">
                    <div class="col-md-4">
                      @if($selected_storage!=0)
                        <table class="table" id="table_advanced">
                          <thead>
                            <th>Producto</th>
                            <th>Existencias</th>
                            <th>Agregar</th>
                          </thead>
                          <tbody>
                            @foreach($data_items as $value)
                            <tr>
                              <td>{{$value->item_name}}</td>
                              <td>{{$value->quantity}}</td>
                              <td>
                                @if($value->quantity>0)
                                <button type="button" name="button" class="btn btn-primary btn-xs" id="{{$value->id}}" onclick="add(this)">
                                  <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                                </button>
                                @endif
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        @endif
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-6">
                      {!! Form::open(array('url'=>'transfer_to_storage/save_transfer','id'=>'id_form_save')) !!}
                      <table class="table" id="target">
                        <thead>
                          <th>Producto</th>
                          <th>Cantidad</th>
                          <th>Borrar</th>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                      <div class="col-md-3">
                      </div>
                      <div class="col-md-3">
                      </div>
                      <!--  Elementos para guardar-->
                      <input type="hidden" name="bodega_destino" value="0" id="bodega_destino">
                      <input type="hidden" name="bodega_origen" value="{{$selected_storage}}">
                      <input type="hidden" name="documento" value="0" id="documento">
                      <input type="hidden" name="item_quantity" value="0" id="item_quantity">
                      {!! Form::close() !!}
                      <div class="col-md-3">
                        <input type="submit" name="" value="Guardar" id="btn_save_transfer" class="btn btn-primary">
                      </div>
                    </div>
                  </div>

              </div>
        </div>
    </div>
  </div>
</div>
@stop
@section('footer_scripts')

<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" ></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" ></script>

<script type="text/javascript">
$(document).ready(function() {
      $('#table_advanced').DataTable({
        "bLengthChange": false,
       // "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        language: {
        search: "_INPUT_",
        searchPlaceholder: "Buscar...",
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Next",
              "sPrevious": "Previous"
          },
          "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
    }
    });
} );


var id_storage_origins=document.getElementById('id_storage_origins');

id_storage_origins.addEventListener('change',function(){
  document.getElementById('id_storage_origins_form').submit();
});

</script>
<script type="text/javascript" src="{{ asset('assets/js/transfer.js') }}"></script>

@stop
