@extends('layouts.app')

@section('content')
	
	<h2>Parametros</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Lista de Parametros</a>
		</li>
		<!--
		<li class="nav-item">
			<a class="nav-link" href="{{url('parametros/crear')}}">Crear Parametro</a>
		</li>
		-->
	</ul>
	<br />
	<h4>Lista de Parametros</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Documento</th>
				<th class="text-center">Parametro</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lista as $param)
				<tr>
					<td>{{$param->id}}</td>
					<td>{{$param->llave}}</td>
					<td>{{$param->valor}}</td>
					<td class="text-center">
						<a href="{{url('parametros/edit', ['id' => $param->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<!--
						<a onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')" href="{{url('parametros/delete', ['id' => $param->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
						-->
					</td>
				</tr>
			@endforeach



		</tbody>
	</table>
	
