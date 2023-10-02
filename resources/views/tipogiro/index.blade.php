@extends('layouts.app2')

@section('content')
	
	<h2>Tipo de Giro</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Tipo de Giro</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('tipogiro/crear')}}">Crear Tipo de Giro</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Tipo de Giro</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Nombre</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $tipo)
				<tr>
					<td>{{$tipo->id}}</td>
					<td>{{$tipo->nombre}}</td>
					<td class="text-center">
						<a href="{{url('tipogiro/edit', ['id' => $tipo->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('tipogiro/delete', ['id' => $tipo->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
