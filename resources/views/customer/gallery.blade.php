@extends('layouts/default')

@section('title','Galería de imágenes')
@section('page_parent','Galería de imágenes')

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/tables.css') }}" />
@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">

        <div class="panel-body">
          <a class="btn btn-small btn-success" href="{{ URL::to('images/create/'.$customer) }}">Añadir imágenes</a>
          <hr />
          @if (Session::has('message'))
          <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
          @endif

          <table class="table table-striped table-bordered" id="table1">
            <thead>
              <tr>
                <td>{{trans('No.')}}</td>
                <td>Nombre</td>
                <td width="280px">Fecha de creación</td>
                <td>Vista previa</td>
                <td width="60px">{{trans('customer.actions')}}</td>
              </tr>
            </thead>
            <tbody>
              @foreach($images as $i=> $value)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{$value->path}}</td>
                <td>{{ date('d/m/Y', strtotime($value->created_at)) }}</td>
                <td>
                  @if($value->path != '')
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="{{'#modalImage'.$value->id}}">Ver Imagen</button>
                    {{--Begin modal--}}
                    <div class="modal fade modal-fade-in-scale-up" tabindex="-1" id="{{'modalImage'.$value->id}}" role="dialog" aria-labelledby="modalLabelfade" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h4 class="modal-title">{!! $value->path!!}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <img class="img_modal" src="{!! asset('images/customers/uploads/') . '/' . $value->path !!}" style="width:100%;height:auto;">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn  btn-danger" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--End modal--}}
                  @endif
                </td>
                <td>
                  <a class="btn btn-danger" style="width: 40px" href="{{ URL::to('images/destroy/' . $value->id . '/'.$value->customer_id) }}" data-toggle="tooltip" data-original-title="Eliminar">
                    <span class="glyphicon glyphicon-remove-circle"></span>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <center>
            <a class="btn btn-danger" href="{{ url('customers') }}">
                {{trans('button.cancel')}}
            </a>
          </center>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('footer_scripts')

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/pdfmake.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/vfs_fonts.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#table1').DataTable({
      dom: 'Bfrtip',
      buttons: [{
          extend: 'copy',
          title: 'Listado de clientes',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        },
        {
          extend: 'csv',
          title: 'Listado de clientes',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        },
        {
          extend: 'excel',
          title: 'Listado de clientes',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        },
        {
          extend: 'pdf',
          title: 'Listado de clientes',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        },
        {
          extend: 'print',
          title: 'Listado de clientes',
          exportOptions: {
            columns: [0, 1, 2, 3, 4]
          }
        }
      ],
    });
  });

  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@stop
