@extends('layouts.app')

@section('content')
	
	<h2>Sectores</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Sectores</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('sectores/crear')}}">Crear Sector</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Sectores</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Sector</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $sector)
				<tr>
					<td>{{$sector->id}}</td>
					<td>{{$sector->sector}}</td>
					<td class="text-center">
						<a href="{{url('sectores/edit', ['id' => $sector->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('sectores/delete', ['id' => $sector->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
