@extends('layouts/default')

@section('title',trans('item.item_categories'))
@section('page_parent',trans('item.items'))

@section('content')
<section class="content">
    <!-- <div class="container"> -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            {{trans('item.item_categories')}}
                        </h4>
                        <div class="pull-right">
                            <a href="{{ URL::to('categorie_product/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{trans('item.new_item_category')}} </a>
                        </div>
                    </div>  
                    
                    <div class="panel-body">                        
                        
                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="width: 5%;">Id</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th style="width: 10%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categorie_product as $value)
                                <tr>
                                    <td></td>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->description }}</td>
                                    <td>{{ $value->tipo->name }}</td>
                                    <td>
                                        <a class="btn btn-info" style="width: 40px"  href="{{ URL::to('categorie_product/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Editar">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                        {!! Form::open(array('url' => 'categorie_product/' . $value->id, 'class' => 'pull-right')) !!}
                                        {!! Form::hidden('_method', 'DELETE') !!}
                                        <!-- {!! Form::submit(trans('Eliminar'), array('class' => 'btn btn-danger')) !!} -->
                                        <button type="submit" style="width: 40px"  class="btn btn-primary btn-danger" type="submit"  data-toggle="tooltip" data-original-title="trans('customer.delete')">
                                            <span class="glyphicon glyphicon-remove-circle"></span>
                                        </button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
    </section>
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
