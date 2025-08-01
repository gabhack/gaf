@extends('layouts.app')

@section('content')
	
	<h2>Pagadurias</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Pagadurias</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('pagadurias/crear')}}">Crear Pagaduria</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Pagadurias</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Pagaduria</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $pagaduria)
				<tr>
					<td>{{$pagaduria->id}}</td>
					<td>{{$pagaduria->pagaduria}}</td>
					<td class="text-center">
						<a href="{{url('pagadurias/edit', ['id' => $pagaduria->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('pagadurias/delete', ['id' => $pagaduria->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
