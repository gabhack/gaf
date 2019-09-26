@extends('layouts.app')

@section('js')
	<script src="{{asset('js/validaciones/Ciudades.js')}}"></script>
	<script src="{{asset('js/validaciones/Oficinas.js')}}"></script>
	<script src="{{asset('js/validaciones/Usuarios.js')}}"></script>
@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Usuarios</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('usuarios')}}">Lista de Usuarios</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Usuario</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('usuarios/crear')}}">Crear Usuarios</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Usuarios</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Usuarios</div>
			<div class="panel-body">
				<form action="{{url('usuarios/update', ['id' => $usuario->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="name">Nombre</label>
							<input type="text" class="form-control" name="name" id="name" placeholder="Nombre" value="{{$usuario->name}}">
						</div>
						<div class="form-group col-md-6">
							<label for="email">Email</label>
							<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{$usuario->email}}">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="passwd">Password</label>
							<input type="password" class="form-control" name="passwd" id="passwd" placeholder="Password">
						</div>
						<div class="form-group col-md-6">
							<label for="clave">Repetir Password</label>
							<input type="password" class="form-control" name="clave" id="clave" placeholder="Repetir Password">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="rol">Rol</label>
							<select class="form-control" name="rol" id="rol">
								<option value="">-Seleccione-</option>
								@foreach($roles as $rol)
					 				<option value="{{$rol->id}}" {{$rol->id == $usuario->roles_id ? 'selected="selected"' : ''}} >{{$rol->rol}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="rol">Departamento</label>
							<select class="form-control" name="depto" id="depto">
								<option value="">-Seleccione-</option>
								@foreach($dptos as $depto)
					 				<option value="{{$depto->id}}" {{$depto->id == $usuario->oficina->ciudad->departamento->id ? 'selected="selected"' : ''}} >{{$depto->departamento}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="ciudad">Ciudad</label>
							<select class="form-control" name="ciudad" id="ciudad">
								<option value="">-Seleccione-</option>								
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="oficina">Oficina</label>
							<select class="form-control" name="oficina" id="oficina">
								<option value="">-Seleccione-</option>								
							</select>
						</div>
					</div>
					
					<input type="hidden" name="city" id="city" value="{{$usuario->oficina->ciudades_id}}">
					<input type="hidden" name="office" id="office" value="{{$usuario->oficina->id}}">
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
