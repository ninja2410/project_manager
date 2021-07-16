@extends('layouts/default')

@section('title',trans('itemkit.kit_list'))
@section('page_parent',trans('itemkit.kit'))

@section('header_styles')
@stop
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-primary">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true"
            data-c="#fff" data-hc="white"></i>
          {{trans('itemkit.item_kits')}}
        </h4>
        <div class="pull-right">
          <a href="{{ URL::to('item-kits-vue/create') }}" class="btn btn-sm btn-default"><span
              class="glyphicon glyphicon-plus"></span> {{trans('itemkit.new_item_kit')}} </a>
        </div>
      </div>
      <div class="panel-body table-responsive">

        

        <table class="table table-striped table-bordered display" id="table1">
          <thead>
            <tr>
              <th></th>
              <th>{{trans('itemkit.item_kit_no')}}</th>
              <th>{{trans('itemkit.code')}}</th>
              <th>{{trans('itemkit.item_kit_name')}}</th>
              {{-- <th>{{trans('itemkit.size')}}</th> --}}
              <th>{{trans('itemkit.categorie')}}</th>
              <th>{{trans('itemkit.type')}}</th>
              <th>{{ trans('itemkit.associated') }}</th>
              <th width="16%">{{trans('item.actions')}}</th>
            </tr>
          </thead>
        </table>
        {{--Begin modal--}}
        <div class="modal fade modal-fade-in-scale-up in" tabindex="-1" id="modalDelete" role="dialog"
          aria-labelledby="modalLabelfade" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-danger">
                <h4 class="modal-title">Confirmación Eliminar</h4>
              </div>
              <div class="modal-body">
                <div class="text-center">
                  <p id="name_item"></p>
                  <br>
                  ¿Desea eliminar este kit?
                </div>
              </div>
              <div class="modal-footer">
                <div class="row">
                  <div class="col-lg-6" style="text-align: right;">
                    {!! Form::open(array('id' => 'frm_delete')) !!}
                    {!! Form::hidden('_method', 'DELETE') !!}
                    <button type="submit" class="btn btn-info" type="submit">
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
@endsection
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/datatables.min.js') }}"></script>

<script type="text/javascript">
  // function setFilter(){
  //   $('#btnFilter').prop("href", APP_URL+"/items/filter/"+document.getElementById('type').value);
  // }
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    var table = $('#table1').DataTable({
      "ajax": {
        "method": "POST",
        "url": "item-kits-vue/index_ajax",
        "dataSrc": "",
        "headers": {
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        }
      },
      "columns": [
        { "data": null },
        {
          "data": null, "sortable": false,
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        { "data": "upc_ean_isbn" },
        { "data": "item_name" },
        // { "data": "size" },
        { "data": "categorie" },
        { "data": "type" },
        {"data":"products"},
        // { "data": "cost_price",
        // render: function ( data, type, row ) {
        //   return 'Q '+ data;
        // } },
        // { "data": "selling_price",
        // render: function ( data, type, row ) {
        //   return 'Q '+ data;
        // } } ,
        {
          "data": "id", render: function (dataField) {
            return '<a class="btn btn-success" href="item-kits-vue/' + dataField + '" data-toggle="tooltip" title=\"Detalles\"><span class="glyphicon glyphicon-eye-open\"></span></a>' +
              '<a class="btn btn-warning" href="item-kits-vue/' + dataField + '/duplicate" data-toggle="tooltip" title=\"Duplicar\"><span class="glyphicon glyphicon-duplicate\"></a>' +
              '<a class="btn btn-info" href="item-kits-vue/' + dataField + '/edit" data-toggle="tooltip" title=\"Editar\"><span class="glyphicon glyphicon-edit\"></a>' +
              "<button type='button' title=\"Eliminar\" data-toggle=\"tooltip\" data-original-title=\"Eliminar\" class='delete form btn btn-danger'><span class=\"glyphicon glyphicon-remove-circle\"></span></button>";
          }
        }

      ],
      language: {
        "url": " {{ asset('assets/json/Spanish.json') }}"
      },
      dom: 'Bflrtip',
      responsive: {
        details: {
          type: 'column'
        }
      },
      columnDefs: [{
        className: 'control',
        orderable: false,
        targets: 0
      }],
      buttons: [
        {
          extend: 'collection',
          text: 'Exportar/Imprimir',
          buttons: [
            {
              extend: 'copy',
              text: 'Copiar',
              title: document.title,
              exportOptions: {
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend: 'excel',
              title: document.title,
              exportOptions: {
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend: 'pdf',
              title: document.title,
              exportOptions: {
                columns: 'th:not(:last-child)'
              }
            },
            {
              extend: 'print',
              text: 'Imprimir',
              title: document.title,
              exportOptions: {
                columns: 'th:not(:last-child)'
              }
            }

          ]
        }
      ],
    });
    // get_data_details("#table1 tbody", table);
    // get_data_profile("#table1 tbody", table);
    get_data_delete("#table1 tbody", table);
  });

  

  var get_data_delete = function (tbody, table) {
    $(tbody).on('click', 'button.delete', function () {
      var data = table.row($(this).parents('tr')).data();
      $('#frm_delete').attr("action", APP_URL + "/item-kits-vue/" + data.id);
      $('#name_item').html(data.item_name);
      console.log(data.item_name);
      $('#modalDelete').modal('show');
    });
  }

          // $(document).ready(function(){

            //   setFilter();
            // });
</script>
@stop