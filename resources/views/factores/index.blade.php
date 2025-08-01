@extends('layouts.app')

@section('content')
	
	<h2>Factores</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Factores</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('factores/crear')}}">Crear Factor</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Factores</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Aliado</th>
				<th class="text-center">Pagadur&iacute;a</th>
				<th class="text-center">Edad M&iacute;nima</th>
				<th class="text-center">Edad M&aacute;xima</th>
				<th class="text-center">Tasa</th>
				<th class="text-center">Plazo</th>
				<th class="text-center">Factor</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $factor)
				<tr>
					<td>{{$factor->id}}</td>
					<td>{{$factor->aliado->aliado}}</td>
					<td>{{$factor->pagaduria != '' ? $factor->pagaduria->pagaduria : '-'}}</td>
					<td>{{$factor->edad_min == 0 ? '-' : $factor->edad_min}}</td>
					<td>{{$factor->edad_max == 0 ? '-' : $factor->edad_max}}</td>
					<td>{{$factor->tasa}}</td>
					<td>{{$factor->plazo}}</td>
					<td>{{$factor->factor}}</td>
					<td class="text-center">
						<a href="{{url('factores/edit', ['id' => $factor->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('factores/delete', ['id' => $factor->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
