@extends('layouts.app')

@section('content')
			
	<h2>Oficinas</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Oficinas</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('oficinas/crear')}}">Crear Oficina</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Oficinas</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Departamento</th>
				<th class="text-center">Ciudad</th>
				<th class="text-center">Oficina</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $oficina)
				<tr>
					<td>{{$oficina->id}}</td>
					<td>{{$oficina->ciudad->departamento->departamento}}</td>
					<td>{{$oficina->ciudad->ciudad}}</td>
					<td>{{$oficina->oficina}}</td>
					<td class="text-center">
						<a href="{{url('oficinas/edit', ['id' => $oficina->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('oficinas/delete', ['id' => $oficina->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
