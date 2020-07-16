@extends('layouts.app')

@section('content')
	
	<h4>Archivos Planos</h4>
		
	<div class="panel panel-default">
		<div class="panel-heading">Archivos Planos</div>
		<div class="panel-body">
			<form action="{{url('planos/store')}}" method="post" enctype="multipart/form-data">
				{!! Form::token() !!}
				<div class="form-row" id="panel-pagaduria">
					<div class="form-group col-md-12">
						<label for="pagaduria">Pagaduría</label>
						<select class="form-control" name="pagaduria" id="pagaduria" required>
							<option value="">-- Seleccione --</option>
							@foreach($pagadurias as $pagad)
								<option value="{{$pagad->id}}">{{$pagad->pagaduria}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-row hidden" id="panel-tipo-archivo">
					<div class="form-group col-md-12">
						<label for="tipo_archivo">Tipo de archivo</label>
						<select class="form-control" name="tipo-archivo" id="tipo-archivo" required>
							<option value="">-- Seleccione --</option>
							<option value="basicos">Datos Básicos</option>
							<option value="aplicados">Descuentos Aplicados</option>
							<option value="no_aplicados">Descuentos No Aplicados</option>
							<option value="embargos">Embargos</option>
							<option value="comppago">Comprobantes de pago</option>
							<option value="mens_liquidacion">Mensajes de liquidación</option>
							{{-- <option value="concep_liquid">Conceptos liquidados</option> --}}
						</select>
					</div>
				</div>
				<div class="form-row hidden" id="archivos">
					<div class="form-group col-md-6">
						<label class="label-archivos"></label>
						<input class="input-archivos" type="file" class="form-control-file" id="input-archivos" name="" required>
					</div>
				</div>
				
				<button type="submit" class="btn btn-primary">Guardar</button>
			</form>
		</div>
	</div>

	<h4>Lista de Archivos</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Estado</th>
				<th class="text-center">Fecha</th>
				<th class="text-center">Pagadur&iacute;a</th>
				<th class="text-center">Archivo</th>
			</tr>
		</thead>
		<tbody>
			@foreach($planos as $plano)
				<tr>
					<td>{{$plano->id}}</td>
					<td title="{{$plano->cont_procesos < 0 ? $plano->errors : ''}}">{{$plano->cont_procesos < 0 ? 'Error' : ($plano->cont_procesos == 0 ? 'Completado' : 'Procesando' )}}<span class="process-status status-{{$plano->cont_procesos < 0 ? 'error' : ($plano->cont_procesos == 0 ? 'ok' : 'processing' )}}"></span></td>
					<td>{{$plano->created_at}}</td>					
					<td>{{$plano->pagaduria->pagaduria}}</td>
					<td>{{planos()[$plano->tipo]}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
