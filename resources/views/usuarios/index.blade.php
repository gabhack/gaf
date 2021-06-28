@extends('layouts.app')

@section('content')	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Usuarios</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('usuarios/crear')}}">Crear Usuario</a>
		</li>
	</ul>
	<br/>
	@if ($lista)
		<table class="table table-hover table-striped table-condensed table-bordered">
			<thead>
				<tr>
					@if (IsSuperAdmin())
						<th class="text-center">Rol</th>
					@endif
					<th class="text-center">Nombre</th>
					<th class="text-center">Email</th>
					{{-- <th class="text-center"># Consultas</th> --}}
					<th class="text-center"></th>
				</tr>
			</thead>
			<tbody>
				@foreach($lista as $usuario)
				<tr>
					@if (IsSuperAdmin())
						<td>{{$usuario->rol->rol}}</td>
					@endif	
					<td>{{$usuario->name}}</td>
					<td>{{$usuario->email}}</td>
					{{-- <td>{{getConsultasXUsuario($usuario)}}</td> --}}
					<td class="text-center">
						<div class="btn-group btn-group-sm">
							<a href="{{url('usuarios/edit', ['id' => $usuario->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
							<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('usuarios/delete', ['id' => $usuario->id])}}" title="Eliminar" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	@else
		<div class="col-md-12">
			<h4>Aún no hay usuarios para mostrar.</h4>
		</div>	
	@endif
	
@endsection

@section('title')
    Usuarios
@endsection

@section('header-content')
    Usuarios
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li class="active">Usuarios</li>
@endsection