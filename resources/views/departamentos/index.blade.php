@extends('layouts.app')

@section('content')
	
	<h2>Departamentos</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Departamentos</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('departamentos/crear')}}">Crear Departamento</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Departamentos</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">C&oacute;digo</th>
				<th class="text-center">Departamento</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $dpto)
				<tr>
					<td>{{$dpto->id}}</td>
					<td>{{$dpto->codigo}}</td>
					<td>{{$dpto->departamento}}</td>
					<td class="text-center">
						<a href="{{url('departamentos/edit', ['id' => $dpto->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('departamentos/delete', ['id' => $dpto->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
