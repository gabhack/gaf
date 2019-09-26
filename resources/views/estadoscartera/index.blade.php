@extends('layouts.app')

@section('content')
	
	<h2>Estados Cartera</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Estados</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('estadoscartera/crear')}}">Crear Estado</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Estados Cartera</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Estado</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $estado)
				<tr>
					<td>{{$estado->id}}</td>
					<td>{{$estado->estado}}</td>
					<td class="text-center">
						<a href="{{url('estadoscartera/edit', ['id' => $estado->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('estadoscartera/delete', ['id' => $estado->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
