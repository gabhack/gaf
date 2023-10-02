@extends('layouts.app2')

@section('content')
	
	<h2>Cuentas Bancarias</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Cuentas Bancarias</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('cuentasbancarias/crear')}}">Crear Cuenta Bancaria</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Cuentas Bancarias</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Entidad</th>
				<th class="text-center">Tipo de Cuenta</th>
				<th class="text-center">Nro de Cuenta</th>
				<th class="text-center">Nombre</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $cuenta)
				<tr>
					<td>{{$cuenta->id}}</td>
					<td>{{$cuenta->entidad->entidad}}</td>
					<td>{{$cuenta->tipo_cuenta == 0 ? 'Ahorro' : 'Corriente' }}</td>
					<td>{{$cuenta->nro_cuenta}}</td>
					<td>{{$cuenta->nombre}}</td>
					<td class="text-center">
						<a href="{{url('cuentasbancarias/edit', ['id' => $cuenta->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('cuentasbancarias/delete', ['id' => $cuenta->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
