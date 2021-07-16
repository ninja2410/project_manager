@extends('layouts/default')

@section('title',Request::is('inventory_adjustment/detail/input/*') ? trans('inventory_adjustment.inventory_entry') : trans('inventory_adjustment.inventory_exit'))
@section('page_parent',Request::is('inventory_adjustment/detail/input/*') ? trans('inventory_adjustment.inventory_entry') : trans('inventory_adjustment.inventory_exit'))


@section('content')
<section class="content">
	<div class="row" id="inventory_adjustment">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="check-circle" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						{!! Request::is('inventory_adjustment/detail/input/*') ? trans('inventory_adjustment.inventory_entry') : trans('inventory_adjustment.inventory_exit') !!}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body" id="dvContents">
                    <input type="hidden" id="name_document" name="name_document" value="{{$reporte->document_and_correlative}}">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								Fecha: {{date('d/m/Y',strtotime($reporte->date))}}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								Documento: {{$reporte->document_and_correlative}} 
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								Usuario: {{$reporte->name}}
							</div>
                        </div>
                        <div class="col-md-4">
							<div class="form-group">
								Bodega: {{$reporte->almacen}}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<center>
									<h3>
										Detalle
									</h3>
								</center>
								<table class="table table-bordered table-striped">
									<thead>
										<th style="text-align: center; width: 5%;">No.</th>
										<th style="text-align: center; width: 15%;">Código</th>
										<th style="width: 40%;">Producto</th>
										<th style="text-align: center;width: 10%;">Precio</th>
                                        <th style="text-align: center;width: 10%;">Exitencia anterior</th>
                                        <th style="text-align: center;width: 10%;">Cantidad</th>
                                        <th style="text-align: center;width: 10%;">Nueva existencia</th>
									</thead>
									<tbody>
										@foreach($productos as $index => $value)
										<tr>
											<td style="text-align: center;">{{$index+1}}</td>
											<td style="text-align: center;">{{$value->upc_ean_isbn}}</td>
											<td>{{$value->item_name}}</td>
											<td style="text-align: right;">@money($value->cost_price)</td>
                                            <td style="text-align: center;">{{$value->previous_quantity}}</td>
                                            <td style="text-align: center;">{{$value->quantity}}</td>
                                            <td style="text-align: center;">{{$value->new_quantity}}</td>                 
										</tr>
										@endforeach
									</tbody>
									<tfoot>
										<tr>
											<td colspan="3" style="text-align:right"><strong>TOTAL:</strong></td>
											<td style="text-align: right;"><strong>@money($reporte->total)</strong></td>
											<td></td>
											<td style="text-align:center"><strong>{{$reporte->cantidad}}</strong></td>
											<td></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Comentario</label>
                            <textarea class="form-control" readonly="">{{$reporte->comments}}</textarea>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<center>
							<a  href="{{URL::previous()}}" class="btn btn-danger">
								Cancelar
							</a>
							<a href="{!! Request::is('inventory_adjustment/detail/input/*') ? url('/inventory_adjustment/input') : url('/inventory_adjustment/output') !!}" class="btn btn-success">
								Nuevo ajuste
							</a>
							<button type="button" onclick="print()"  class="btn btn-primary hidden-print">{{trans('Imprimir')}}</button>
							
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('footer_scripts')
<script>
    function print() {
        
        var name_document = $('#name_document').val();
        var contents = $("#dvContents").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>' + name_document + '</title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.

        frameDoc.document.write('<style>:root { --color_primary: {{$parameters->primary}}; --color_secundary: {{$parameters->second}}; }</style>');            
        frameDoc.document.write('<link href="{{ asset('/css/app1.css')}}" rel="stylesheet" type="text/css" />');
        frameDoc.document.write('<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />');
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    }
</script>
@endsection