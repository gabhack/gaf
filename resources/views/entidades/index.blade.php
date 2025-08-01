@extends('layouts.app')

@section('content')
	
	<h2>Entidades</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Entidades</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('entidades/crear')}}">Crear Entidad</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Entidades</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Entidad</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $entidad)
				<tr>
					<td>{{$entidad->id}}</td>
					<td>{{$entidad->entidad}}</td>
					<td class="text-center">
						<a href="{{url('entidades/edit', ['id' => $entidad->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('entidades/delete', ['id' => $entidad->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
