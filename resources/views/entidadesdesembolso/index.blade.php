@extends('layouts.app2')

@section('content')
	
	<h2>Entidad Desembolso</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Entidades Desembolso</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('entidadesdesembolso/crear')}}">Crear Entidad Desembolso</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Entidades Desembolso</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">NIT</th>
				<th class="text-center">Nombre</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $entidad)
				<tr>
					<td>{{$entidad->id}}</td>
					<td>{{$entidad->nit}}</td>
					<td>{{$entidad->nombre}}</td>
					<td class="text-center">
						<a href="{{url('entidadesdesembolso/edit', ['id' => $entidad->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('entidadesdesembolso/delete', ['id' => $entidad->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
