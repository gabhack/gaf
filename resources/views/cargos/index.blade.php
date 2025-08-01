@extends('layouts.app')

@section('content')
	
	<h2>Cargos</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Cargos</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('cargos/crear')}}">Crear Cargo</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Cargos</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Estado</th>
				<th class="text-center">Cargo</th>
				<th class="text-center">Asignaci&oacute;n Adicional</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $cargo)
				<tr>
					<td>{{$cargo->id}}</td>
					<td>{{estados_cargos()[$cargo->estado]}}</td>
					<td>{{$cargo->cargo}}</td>
					<td>{{$cargo->asignacion_adicional}}</td>
					<td class="text-center">
						<a href="{{url('cargos/edit', ['id' => $cargo->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('cargos/delete', ['id' => $cargo->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
