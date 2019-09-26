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
				<a class="nav-link active">Crear Oficina</a>
			</li>
		</ul>
		<br />
		<h4>Crear Oficinas</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Oficinas</div>
			<div class="panel-body">
				<form action="{{url('oficinas/store')}}" method="post">
					{!! Form::token() !!}
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="depto">Departamento</label>
							<select class="form-control" name="depto" id="depto">
								<option value="">-Seleccione-</option>
								@foreach($dptos as $dpto)
									<option value="{{$dpto->id}}">{{$dpto->departamento}}</option>
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
							<input type="text" class="form-control" name="oficina" id="oficina" placeholder="Oficina">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
