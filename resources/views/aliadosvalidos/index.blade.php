@extends('layouts.app')

@section('content')
	
	<h2>Aliados por Pagadur&iacute;a</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link" href="{{url('aliados')}}">Lista de Aliados</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active">Parametrizar Aliados</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('aliadosvalidos/crear')}}">Nuevo Aliado por Pagadur&iacute;a</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Aliados por Pagadur&iacute;a</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Pagadur&iacute;a</th>
				<th class="text-center">Tipo de Embargo</th>
				<th class="text-center">Aliado</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($validos as $valido)
				<tr>
					<td>{{$valido->id}}</td>
					<td>{{$valido->pagaduria->pagaduria}}</td>
					<td>{{$valido->tiposembargos_id != "" ? $valido->tipoembargo->tipo : '' }}</td>
					<td>{{$valido->aliado->aliado}}</td>					
					<td class="text-center">
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('aliadosvalidos/delete', ['id' => $valido->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
