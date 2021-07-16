@extends('layouts/default')

@section('title',trans('item.edit_item_category'))
@section('page_parent',trans('item.items'))

@section('header_styles')
<link href="{{ asset('assets/css/bootstrap/bootstrapValidator.min.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">
							<i class="livicon" data-name="edit" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
							{{trans('item.edit_item_category')}}
						</h3>
						<span class="pull-right clickable">
							<i class="glyphicon glyphicon-chevron-up"></i>
						</span>
					</div>		
					<div class="panel-body">
						
						
						{!! Form::model($categorie_product, array('route' => array('categorie_product.update', $categorie_product->id), 'method' => 'PUT', 'files' => true,'id'=>'itemCategory'  )) !!}
						<div class="col-md-10 col-md-offset-1">
							<div class="form-group">
								{!! Form::label('name', trans('Nombre').' *') !!}
								{!! Form::text('name', null, array('class' => 'form-control')) !!}
							</div>
						</div>
						<div class="col-md-10 col-md-offset-1">
							<div class="form-group">
								{!! Form::label('description', trans('Descripción').' ') !!}
								{!! Form::textarea('description', Input::old('description'), array('class' => 'form-control')) !!}
							</div>
						</div>
						<div class="col-md-10 col-md-offset-1">
							<div class="form-group">
								<div class="form-group">
									<select class="form-control" name="item_type_id" id="item_type_id">
									  <option value="">Seleccione el tipo</option>
									  @foreach($tipos as $id => $name)
									  <option value="{!!$id!!}" @if($categorie_product->item_type_id==$id) selected="selected" @endif>{!!$name!!}</option>
									  @endforeach
									</select>
								  </div>
							</div>
						</div>								
						<div class="row">
							@include('partials.buttons',['cancel_url'=>"/categorie_product"])
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</section>
	@endsection
	
	@section('footer_scripts')
	<script src="{{ asset('assets/js/bootstrap/bootstrapValidator.min.js') }} " type="text/javascript "></script>
	
	<script type="text/javascript">
		$('#itemCategory').bootstrapValidator({
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			message: 'Valor no valido',
			fields:{			
				name:{
					validators:{
						notEmpty:{
							message:'Debe ingresar nombre.'
						},
						stringLength:{
							min:3,
							max:100,
							message:'El nombre debe tener almenos 3 caracteres. '
						}
					}
				},
				item_type_id:{
					validators:{
						notEmpty:{
							message:'Debe seleccionar tipo.'
						}
					}
				}			
			}
		});
		
	</script>
	@endsection
	