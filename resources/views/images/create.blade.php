@extends('layouts/default')

@section('title','Crear serie')
@section('page_parent',"Documentos de inventario")
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" /> 
<!-- Toast -->
<link href="{{ asset('assets/css/toast/toastr.min.css') }}" rel="stylesheet" type="text/css" />

@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">Nueva serie</div> -->
				<div class="panel-body">

				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('footer_scripts')
	<script type="text/javascript " src="{{ asset('assets/js/toast/toastr.min.js')}} "></script>
	<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
@endsection
