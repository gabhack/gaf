@extends('layouts.app')

@section('js')
	<script src="{{asset('js/validaciones/Ciudades.js')}}"></script>
@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Oficinas</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('oficinas')}}">Lista de Oficinas</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Modificar Oficina</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('oficinas/crear')}}">Crear Oficinas</a>
			</li>
		</ul>
		<br />
		<h4>Modificar Oficinas</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Modificar Oficinas</div>
			<div class="panel-body">
				<form action="{{url('oficinas/update', ['id' => $oficina->id])}}" method="post">
					{!! Form::token() !!}
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="depto">Departamento</label>
							<select class="form-control" name="depto" id="depto">
								<option value="">-Seleccione-</option>
								@foreach($dptos as $dpto)
									<option value="{{$dpto->id}}" {{$dpto->id == $oficina->ciudad->departamento->id ? 'selected="selected"' : ''}}>{{$dpto->departamento}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="ciudad">Ciudad</label>
							<select class="form-control" name="ciudad" id="ciudad">
								<option value="">-Seleccione-</option>								
							</select>
						</div>						
					</div>
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="oficina">Oficina</label>
							<input type="text" class="form-control" name="oficina" id="oficina" placeholder="Oficina" value="{{$oficina->oficina}}">
						</div>
					</div>
					
					<input type="hidden" name="city" id="city" value="{{$oficina->ciudades_id}}">
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
