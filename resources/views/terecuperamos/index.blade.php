@extends('layouts.app')

@section('content')
	
	<h2>Te Recuperamos</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Te Recuperamos</a>
		</li>
		<!--
		<li class="nav-item">
			<a class="nav-link" href="{{url('terecuperamos/crear')}}">Crear Estudio</a>
		</li>
		-->
	</ul>
	<br />
	<h4>Lista de Estudios</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Fecha</th>
				<th class="text-center">Cliente</th>
				<th class="text-center">Pagadur&iacute;a</th>
				<th class="text-center">Decisi&oacute;n</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $estudio)
				<tr>
					<td>{{$estudio->id}}</td>
					<td>{{$estudio->fecha}}</td>
					<td>{{$estudio->cliente->nombres}} {{$estudio->cliente->apellidos}}</td>
					<td>{{$estudio->base->pagaduria->pagaduria}}</td>
					<td>{{decisiones_estudios()[$estudio->decision]}}</td>
					<td class="text-center">
						<a href="{{url('terecuperamos/edit', ['id' => $estudio->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('terecuperamos/delete', ['id' => $estudio->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
