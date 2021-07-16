@extends('app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Ajustes de inventario ingreso
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="documento">
									Serie
								</label>
								<select name="id_serie" id="id_serie" class="form-control">
									@foreach($listDocuments as $index => $value)
										<option>Seleccione serie</option>
										<option value="{{$value->id_serie}}">{{$value->document.' - '.$value->serie}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-1">
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for='correlative'>Correlativo</label>
								<input type="text" name="correlative" id="correlative" class="form-control" value="0" style="text-align:center;">
							</div>
						</div>
						<div class="col-md-1">
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="date">Fecha</label>
								<input type="text" name="date" id="date" class="form-control" style="text-align: right;">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Bodega destino</label>
								<select class="form-control" name="bodega" id="bodega">
									<option>Seleccione bodega </option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Seleccione producto</label>
								<input type="text" name="search_product" id="search_product" class="form-control">
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label>Detalle </label>
								<table class="table table-bordered table-striped">
									<thead style="">
										<th>No.</th>
										<th>Producto</th>
										<th>Cantidad</th>
									</thead>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="comment">Comentario</label>
								<textarea class="form-control" name="comment" id="comment"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection