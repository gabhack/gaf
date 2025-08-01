@extends('layouts.app')

@section('content')
			
	<h2>Ciudades</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Ciudades</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('ciudades/crear')}}">Crear Ciudad</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Ciudades</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">C&oacute;digo</th>
				<th class="text-center">Departamento</th>
				<th class="text-center">Ciudad</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $ciudad)
				<tr>
					<td>{{$ciudad->id}}</td>
					<td>{{$ciudad->codigo}}</td>
					<td>{{$ciudad->departamento->departamento}}</td>
					<td>{{$ciudad->ciudad}}</td>
					<td class="text-center">
						<a href="{{url('ciudades/edit', ['id' => $ciudad->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('ciudades/delete', ['id' => $ciudad->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
