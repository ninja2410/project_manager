@extends('layouts/default')

@section('title',trans('credit.list_credits'))
@section('page_parent',trans('credit.credits'))

@section('header_styles')
@stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
						{{trans('credit.list_credits')}}
					</h3>
					<span class="pull-right clickable">
						<i class="glyphicon glyphicon-chevron-up"></i>
					</span>
				</div>
				<div class="panel-body">
					<hr />
					
					{!! Form::open(['url' =>'/credit/', 'method' => 'GET', 'class' => 'nav-form navbar-lef', 'role' => 'search','id'=>'form_estado_requisicion']) !!}
					<div class="row">
						<div class="col-lg-4">
							<div class="form-group">
								<div class="pull-right">
									<strong>Seleccione estado:</strong>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<select name="estado" id="estado" class="form-control">
									<option value="Pendientes" {{ ($stado == 'Pendientes') ?  'selected="selected"' : '' }} >Pendientes</option>
									<option value="Cancelados" {{ ($stado == 'Cancelados') ?  'selected="selected"' : '' }} >Cancelados</option>
									<option value="Anulados" {{ ($stado == 'Anulados') ?  'selected="selected"' : '' }} >Anulados</option>
									<option value="Todos" {{ ($stado == 'Todos') ?  'selected="selected"' : '' }} >Todos</option>
								</select>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<button class="btn btn-primary">Generar</button>
							</div>
						</div>
					</div>
					{!! Form::close() !!}
					<table class="table table-striped table-bordered" id="tableCredits">
						<thead>
							<th></th>
							<th>Id</th>
							<th data-priority="2" style="width: 20%">Nombre cliente</th>
							<th width="10%">Vence 1er Cr??dito</th>
							<th width="10%">No. Facturas</th>
							<th data-priority="3" style="text-align: center; width: 10">Monto</th>
							<th style="text-align: center;">Saldo</th>
							{{-- <th style="text-align: center;">Monto cuota(s)</th> --}}
							<th style="text-align: center;" width="5%">Estado</th>
							<th style="text-align: center;">Operaciones</th>
						</thead>
						<tbody>
							@foreach($datosCredito as $key => $value)
							<tr>
								<td></td>
								<td style="font-size: 10px;width:5%">{{ $key+1 }}</td>
								<td style="font-size: 12px"><a href="{{URL::to('customers/profile/'.$value->id)}}" data-toggle="tooltip" data-original-title="Ir a cliente">{{ strtoupper($value->name) }}</a></td>
								<td style="font-size: 12px; @if($value->vencimiento<1) background-color: #f0b9b6; @endif">{{date('d/m/Y', strtotime(substr($value->min_due_date,0,10)))}}</td>
								<td style="font-size: 12px">{{$value->facturas}}</td>
								<td style="text-align: right;">@money($value->credit_total)</td>
								<td style="text-align:right;">@money($value->credit_total-$value->paid)</td>
								<td>
									@if($value->status_id == 6)
									<label style="color: green; font-weight: bold;font-size: 11px" >Cancelado</label>
									@elseif($value->status_id == 7)
									<label style="color: red; font-weight: bold;font-size: 11px">Pendiente</label>
									@else
									<label style="color: orange; font-weight: bold;font-size: 11px">Anulado</label>
									@endif
								</td>
								<td class="text-rigth" style="width:20%">
									<div>
										@if($value->status_id == '7')
										<a class="btn btn-info" href="{{ URL::to('credit/' . $value->id . '/edit') }}" data-toggle="tooltip" data-original-title="Pagar">
											<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;{{trans('credit.pay')}}
										</a>
										@else
										<a class="btn btn-default" data-toggle="tooltip" data-original-title="Credito pagado totalmente" disabled="disabled">
											<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;{{trans('credit.pay')}}
										</a>
										@endif
										<a class="btn btn-primary" href="{{ URL::to('credit/statement/' . $value->id ) }}" data-toggle="tooltip" data-original-title="Estado de Cuenta">
											<span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;{{trans('credit.statement')}}
										</a>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th ></th>
								<th ></th>
								<th ></th>
								<th ></th>
								<th colspan="1" style="text-align:right">TOTALES:</th>
								<th colspan="1" style="text-align:right"></th>
								<th colspan="1" style="text-align:right"></th>
								<th ></th>
								<th ></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('footer_scripts')
<script type="text/javascript">
	$(document).ready(function() {
		setDataTable("tableCredits", [5,6], "{{asset('assets/json/Spanish.json')}}");
	});
</script>

@stop
