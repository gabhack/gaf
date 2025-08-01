@extends('layouts.app')

@section('content')
	
	<h2>Aliados</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Aliados</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('aliados/crear')}}">Crear Aliado</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{url('aliados/parametrizar')}}">Parametrizar Aliados</a>
		</li>
	</ul>
	<br />
	<h4>Lista de Aliados</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Aliado</th>
				<th class="text-center">Plazo M&aacute;ximo</th>
				<th class="text-center">Estado</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $aliado)
				<tr>
					<td>{{$aliado->id}}</td>
					<td>{{$aliado->aliado}}</td>
					<td>{{$aliado->max_plazo}}</td>
					<td>{{estados_aliados()[$aliado->estado]}}</td>
					<td class="text-center">
						<a href="{{url('aliados/edit', ['id' => $aliado->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('aliados/delete', ['id' => $aliado->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
