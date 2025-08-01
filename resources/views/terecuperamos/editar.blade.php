@extends('layouts.app')

@section('js')
	<script src="{{ asset('js/validaciones/Cargos.js') }}"></script>
	<script src="{{ asset('js/validaciones/Terecuperamos.js') }}"></script>
@endsection

@section('content')
	
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Te Recuperamos</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('terecuperamos')}}">Lista de Estudios</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Estudio de Solicitud</a>
			</li>
		</ul>
		<br />
		<h4>Estudio de Solicitud</h4>
		
		<ul class="nav nav-tabs flex-column flex-sm-row" id="myTab" role="tablist">
			<li class="nav-item active">
				<a class="nav-link" id="paso1-tab" data-toggle="tab" href="#paso1" role="tab" aria-controls="paso1" aria-selected="true">CLIENTE</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="paso2-tab" data-toggle="tab" href="#paso2" role="tab" aria-controls="paso2" aria-selected="false">CAPACIDAD</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="paso3-tab" data-toggle="tab" href="#paso3" role="tab" aria-controls="paso3" aria-selected="false">CARTERAS A COMPRA</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="paso4-tab" data-toggle="tab" href="#paso4" role="tab" aria-controls="paso4" aria-selected="false">CENTRALES</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="paso5-tab" data-toggle="tab" href="#paso5" role="tab" aria-controls="paso5" aria-selected="false">TE-RECUPERAMOS</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="paso6-tab" data-toggle="tab" href="#paso6" role="tab" aria-controls="paso6" aria-selected="false">CK COMERCIALIZADORA</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="paso7-tab" data-toggle="tab" href="#paso7" role="tab" aria-controls="paso7" aria-selected="false">ALIADO</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="paso7-tab" data-toggle="tab" href="#paso8" role="tab" aria-controls="paso8" aria-selected="false">AMORTIZACION</a>
			</li>
		</ul>

			
		<div class="tab-content">
			<div class="tab-pane active" id="paso1" role="tabpanel" aria-labelledby="paso1-tab">
				@include('terecuperamos/paso1')
			</div>
			<div class="tab-pane" id="paso2" role="tabpanel" aria-labelledby="paso2-tab">
				@include('terecuperamos/paso2')
			</div>
			<div class="tab-pane" id="paso3" role="tabpanel" aria-labelledby="paso3-tab">
				@include('terecuperamos/paso3')
			</div>
			<div class="tab-pane" id="paso4" role="tabpanel" aria-labelledby="paso4-tab">
				@include('terecuperamos/paso4')
			</div>
			<div class="tab-pane" id="paso5" role="tabpanel" aria-labelledby="paso5-tab">
				@include('terecuperamos/paso5')
			</div>
			<div class="tab-pane" id="paso6" role="tabpanel" aria-labelledby="paso6-tab">
				@include('terecuperamos/paso6')
			</div>
			<div class="tab-pane" id="paso7" role="tabpanel" aria-labelledby="paso7-tab">
				@include('terecuperamos/paso7')
			</div>
			<div class="tab-pane" id="paso8" role="tabpanel" aria-labelledby="paso8-tab">
				@include('terecuperamos/paso8')
			</div>
		</div>					
		
		<div class="panel panel-default">
			<div class="panel-heading">DECISI&Oacute;N COMIT&Eacute;</div>
			<div class="panel-body">	
				<form method="post" action="{{url('terecuperamos/update', ['id' => $estudio->id])}}">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="decisiontr">Decisi&oacute;n</label>
							<select class="form-control" name="decisiontr" id="decisiontr">
								<option value="">-Seleccione-</option>
								@foreach(decisiones_estudios() as $key => $decision)
									<option value="{{$key}}" {{$estudio->decision == $key ? 'selected="selected"' : '' }} >{{$decision}}</option>
								@endforeach
							</select>					
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">OBSERVACIONES</div>
			<div class="panel-body">	
				<form method="post" action="{{url('terecuperamos/saveObservaciones', ['id' => $estudio->id])}}">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="observacion">Observaci&oacute;n</label>
							<textarea class="form-control" name="observacion" id="observacion"></textarea>
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
				
				<br />
				
				<table class="table table-hover table-striped table-condensed table-bordered">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Fecha</th>
							<th class="text-center">Usuario</th>
							<th class="text-center">Observaci&oacute;n</th>
							<!-- <th class="text-center">Acciones</th> -->
						</tr>
					</thead>
					<tbody>
						@foreach($observaciones as $observ)
							<tr>
								<td>{{$observ->id}}</td>
								<td>{{$observ->created_at}}</td>
								<td>{{$observ->user->name}}</td>
								<td>{{$observ->observacion}}</td>
								<!-- 
								<td class="text-center">
									<a href="{{url('terecuperamos/edit', ['id' => $observ->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('terecuperamos/delete', ['id' => $observ->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
								</td>
								-->
							</tr>
						@endforeach
					</tbody>
				</table>
				
			</div>
		</div>
		
	</div>

@endsection
