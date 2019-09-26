@extends('layouts.app')

@section('content')
	
	<h2>Roles</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Roles</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('roles/crear')}}">Crear Rol</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Roles</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Rol</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $rol)
				<tr>
					<td>{{$rol->id}}</td>
					<td>{{$rol->rol}}</td>
					<td class="text-center">
						<a href="{{url('roles/edit', ['id' => $rol->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('roles/delete', ['id' => $rol->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
