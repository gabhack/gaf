@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Ciudades</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('ciudades')}}">Lista de Ciudades</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Ciudad</a>
			</li>
		</ul>
		<br />
		<h4>Crear Ciudades</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Ciudades</div>
			<div class="panel-body">
				<form action="{{url('ciudades/store')}}" method="post">
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
							<label for="codigo">C&oacute;digo</label>
							<input type="number" class="form-control" name="codigo" id="codigo" placeholder="C&oacute;digo">
						</div>						
					</div>
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="ciudad">Ciudad</label>
							<input type="text" class="form-control" name="ciudad" id="ciudad" placeholder="Ciudad">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
