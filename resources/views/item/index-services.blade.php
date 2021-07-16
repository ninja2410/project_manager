@extends('layouts/default')

@section('title',trans('item.services_catalog'))
@section('page_parent',trans('item.items'))

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/datatables.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop
<?php $permisos=Session::get('permisions'); $array_p =array_column(json_decode(json_encode($permisos), True),'ruta');  ?> 
@section('content')
<section class="content">
  <!-- <div class="container"> -->
    <div class="row">
      <div class="col-md-12 ">
        <div class="panel panel-primary">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
              {{trans('item.services_catalog')}}
            </h4>
            <div class="pull-right">
              @if(in_array('ítems/create',$array_p))
              <a href="{{ URL::to('services/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('item.new_sevice')}} </a>
              @endif
            </div>
          </div>            
          <div class="panel-body">
                                   
            <div class="row">
              <div class="col-md-4">   
              </div>
              <div class="col-md-3">
                <label>
                  <b>{{trans('item.price_types')}}</b>
                </label>
                <select class="form-control" name="price_id" id="price_id">
                  @foreach ($prices as $key => $value)
                  <option @if ($selected==$value->id) selected @endif value="{{$value->id}}">{{$value->name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-1">
                <br>
                <a class="btn btn-small btn-primary" href="#" id="btnFilter" >Filtrar</a>
              </div>
              <div class="col-md-4">
                
              </div>
            </div>
            <div class="panel-body table-responsive">
              <table class="table table-striped table-bordered display" id="table1">
                <thead>
                  <tr>
                    <th></th>
                    <th data-priority="1001" style=" width:5%">{{trans('No.')}}</th>
                    <th style=" width:10%">{{trans('item.upc_ean_isbn')}}</th>
                    <th style=" width:25%">{{trans('item.item_name')}}</th>
                    <th style=" width:12%">{{trans('item.category')}}</th>
                    <th style=" width:12%">{{trans('item.item_type')}}</th>
                    <th style=" width:8%">{{trans('item.selling_p')}}</th>
                    <th width="12%">{{trans('item.actions')}}</th>
                  </tr>
                </thead>
                
              </table>
              {{--Begin modal--}}
              <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="modalDelete" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header bg-danger">
                      <h4 class="modal-title">Confirmación Eliminar</h4>
                    </div>
                    <div class="modal-body">
                      <div class="text-center">
                        <p id="name_item"></p>
                        <br>
                        ¿Desea eliminar servicio?
                      </div>
                    </div>
                    <div class="modal-footer" >
                      <div class="row">
                        <div class="col-lg-6" style="text-align: right;">
                          {!! Form::open(array('id' => 'frm_delete')) !!}
                          {!! Form::hidden('_method', 'DELETE') !!}
                          @if(in_array('ítems/delete',$array_p))
                          <button type="submit" class="btn btn-info" type="submit">
                            @else
                            <button disabled="disabled" class="btn btn-gray">
                              @endif
                              Eliminar
                            </button>
                            {!! Form::close() !!}
                          </div>
                          <div class="col-lg-6" style="text-align: left;">
                            <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                {{--End modal--}}
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- </div> -->
    </section>
    <style type="text/css">
      .code {
        height: 40px !important;
        
      }
    </style>
    @endsection
    @section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script>
    
    <script type="text/javascript">
      
      $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        var table = $('#table1').DataTable({
          "ajax":{
            "method":"GET",
            "url":"items/index_services_ajax_by_price",
            "data": function ( d ) {
              d.price_id = cleanNumber($('#price_id').val());
            },
            "dataSrc": "",
            "headers": {
              "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
          },
          "columns": [
          { "data": "blanco"},
          { "data": "id" },
          { "data": "upc_ean_isbn" },
          { "data": "item_name" },
          { "data": "category" },
          { "data": "tipo" },        
          { "data": "selling_price",
          render: function ( data, type, row ) {
            return formato_moneda(data);
          } } ,
          { "data": "id", render: function (dataField) { 
            return '<a class="btn btn-small btn-success btn-xs" href="items/detail/' + dataField + '" data-toggle="tooltip" data-original-title="Detalles"><span class="glyphicon glyphicon-eye-open\"></span></a>' +
            '<a class="btn btn-small btn-info btn-xs" href="items/' + dataField + '/edit" data-toggle="tooltip" data-original-title="Editar"><span class="glyphicon glyphicon-edit\"></a>' +
              "<button type='button' title=\"Eliminar\" data-toggle=\"tooltip\" data-original-title=\"Eliminar\" class='delete form btn btn-danger btn-xs '><span class=\"glyphicon glyphicon-remove-circle\"></span></button>"; } }
              
              ],
              "language": {
                "url":" {{ asset('assets/json/Spanish.json') }}"
              },
              "dom": 'Bfrtip',
              responsive: {
                details: {
                  type: 'column'
                }
              },
              columnDefs: [ {
                className: 'control',
                orderable: false,
                searchable: false,
                targets:   0
              } ],
              buttons: [
              {
                extend: 'collection',
                text: 'Exportar/Imprimir',
                buttons: [
                {
                  extend:'copy',
                  text: 'Copiar',
                  title: document.title,
                  filename: function () { return document.title;},
                  footer: true,
                  messageBottom: function () {           
                    return 'Cacao-ERP '+getFechaHoraActual();
                  },
                  exportOptions:{
                    columns: 'th:not(:last-child)'
                  }
                },
                {
                  extend:'excel',
                  title: document.title,
                  filename: function () { return document.title;},
                  footer: true,
                  messageBottom: function () { 
                    return 'Cacao-ERP '+getFechaHoraActual();
                  },
                  exportOptions:{
                    columns: 'th:not(:last-child)'
                  }
                },
                {
                  extend:'pdf',
                  title: document.title,
                  filename: function () { return document.title;},
                  footer: true,
                  messageBottom: function () { 
                    return 'Cacao-ERP '+getFechaHoraActual();
                  },
                  exportOptions:{
                    columns: 'th:not(:last-child)'
                  }
                },
                {
                  extend:'print',
                  text: 'Imprimir',
                  title: document.title,
                  filename: function () { return document.title;},
                  footer: true,
                  messageBottom: function () { 
                    return 'Cacao-ERP '+getFechaHoraActual();
                  },
                  exportOptions:{
                    columns: 'th:not(:last-child)'
                  },
                }
                ]
              },
              ],
              
              "drawCallback": function( settings ) {
                $('body').loadingModal('hide');
              }
            });
            get_data_delete("#table1 tbody", table);
            $('#price_id').change(function (){
              var pago_texto = $( "#price_id option:selected" ).text();
              var titulo = document.title;
              document.title = titulo + ' - Precio: '+ pago_texto;
            })
            $('#btnFilter').click( function (){
              $('body').loadingModal({
                text: 'Actualizando productos...'
              });
              $('body').loadingModal('show');
              table.ajax.reload();                            
              $('body').loadingModal('hide');
            });
          });/*Document ready*/
          
          
          var get_data_delete = function(tbody, table){
            $(tbody).on('click', 'button.delete', function(){
              var data = table.row($(this).parents('tr')).data();
              $('#frm_delete').attr("action", APP_URL+"/items/"+data.id);
              $('#name_item').html(data.item_name);
              console.log(data.item_name);
              $('#modalDelete').modal('show');
            });
          }
          
        </script>
        @stop
        
        