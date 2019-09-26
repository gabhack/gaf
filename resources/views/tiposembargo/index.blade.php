@extends('layouts.app')

@section('content')
	
	<h2>Tipos de Embargo</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Tiposs</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('tiposembargo/crear')}}">Crear Tipos</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Tipos de Embargo</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Tipo</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $tipoembargo)
				<tr>
					<td>{{$tipoembargo->id}}</td>
					<td>{{$tipoembargo->tipo}}</td>
					<td class="text-center">
						<a href="{{url('tiposembargo/edit', ['id' => $tipoembargo->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('tiposembargo/delete', ['id' => $tipoembargo->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
