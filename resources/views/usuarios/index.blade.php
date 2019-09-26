@extends('layouts.app')

@section('content')
	
	<h2>Usuarios</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Usuarios</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('usuarios/crear')}}">Crear Usuario</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Usuarios</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Rol</th>
				<th class="text-center">Nombre</th>
				<th class="text-center">Email</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $usuario)
				<tr>
					<td>{{$usuario->id}}</td>
					<td>{{$usuario->rol->rol}}</td>
					<td>{{$usuario->name}}</td>
					<td>{{$usuario->email}}</td>
					<td class="text-center">
						<a href="{{url('usuarios/edit', ['id' => $usuario->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('usuarios/delete', ['id' => $usuario->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
