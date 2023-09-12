@extends('layouts.app2')

@section('content')
	
	<h2>Forma de Pago</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Forma de Pago</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('formapago/crear')}}">Crear Forma de Pago</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Forma de Pago</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Nombre</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $forma)
				<tr>
					<td>{{$forma->id}}</td>
					<td>{{$forma->nombre}}</td>
					<td class="text-center">
						<a href="{{url('formapago/edit', ['id' => $forma->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('formapago/delete', ['id' => $forma->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
