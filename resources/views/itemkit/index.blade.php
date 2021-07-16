@extends('layouts/default')

@section('title',trans('itemkit.item_kits'))
@section('page_parent',trans('itemkit.item_kits'))

@section('header_styles')
@stop
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-primary">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
          {{trans('itemkit.item_kits')}}
        </h4>
        <div class="pull-right">
          <a href="{{ URL::to('item-kits/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('itemkit.new_item_kit')}} </a>
        </div>
      </div>

      <div class="panel-body">

        

        <table class="table table-striped table-bordered" id="table1">
          <thead>
            <tr>
              <th></th>
              <th>{{trans('itemkit.item_kit_no')}}</th>
              <th>{{trans('itemkit.code')}}</th>
              <th>{{trans('itemkit.item_kit_name')}}</th>
              <th>{{trans('itemkit.cost_price')}}</th>
              <th>{{trans('itemkit.selling_price')}}</th>
              <th>{{trans('itemkit.item_kit_description')}}</th>
              <th>{{trans('itemkit.actions')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($itemkits as $i=>$value)
            <tr>
              <td></td>
              <td style="width:5%;">{{$i+1}}</td>
              <td>{{$value->upc_ean_isbn}}</td>
              <td>{{$value->item_name}}</td>
              <td>{{$value->cost_price}}</td>
              <td>{{$value->selling_price}}</td>
              <td>{{$value->description}}</td>
              <td>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$value->id}}">
                  Ver detalle
                </button>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header" style="background-color:rgb(46, 144, 95); color:white">
                        <h4 class="modal-title">Detalle de combo {{$value->item_name}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <?php
                        $lista=App\ItemKitItem::join('items', 'items.id', '=', 'item_kit_items.item_id')
                        ->where('item_kit_id', $value->id)
                        ->select('items.item_name', 'item_kit_items.quantity')
                        ->get();
                        ?>
                        <table class="table table-striped table-bordered">
                          <tr>
                            <thead>
                              <th>Nombre</th>
                              <th>Cantidad</th>
                            </thead>
                          </tr>
                          <tbody>
                            @foreach ($lista as $key => $item)
                            <tr>
                              <td>{{$item->item_name}}</td>
                              <td>{{$item->quantity}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button class="btn  btn-danger" data-dismiss="modal">Cerrar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
@section('footer_scripts')

<script type="text/javascript">
  $(document).ready(function(){
    $('#table1').DataTable({
      language: {
        "url":" {{ asset('assets/json/Spanish.json') }}"
      },
      dom: 'Bfrtip',
      responsive: {
        details: {
          type: 'column'
        }
      },
      columnDefs: [ {
        className: 'control',
        orderable: false,
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
          exportOptions:{
            columns: 'th:not(:last-child)'
          }
        },
        {
          extend:'excel',
          title: document.title,
          exportOptions:{
            columns: 'th:not(:last-child)'
          }
        },
        {
          extend:'pdf',
          title: document.title,
          exportOptions:{
            columns: 'th:not(:last-child)'
          }
        },
        {
          extend:'print',
          text: 'Imprimir',
          title: document.title,
          exportOptions:{
            columns: 'th:not(:last-child)'
          },
        }
        ]
      },
      ],
    }) ;
  });
</script>
@endsection
