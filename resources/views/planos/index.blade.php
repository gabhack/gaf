@extends('layouts.app')

@section('content')
	
	<h2>Archivos</h2>
	
	
	<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active">Lista de Archivos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('planos/crear')}}">Cargar Archivos Planos</a>
			</li>
		</ul>
	<br />
	<h4>Lista de Archivos</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Estado</th>
				<th class="text-center">Fecha</th>
				<th class="text-center">Pagadur&iacute;a</th>
				<th class="text-center">Archivo</th>
			</tr>
		</thead>
		<tbody>
			@foreach($planos as $plano)
				<tr>
					<td>{{$plano->id}}</td>
					<td title="{{$plano->cont_procesos < 0 ? $plano->errors : ''}}">{{$plano->cont_procesos < 0 ? 'Error' : ($plano->cont_procesos == 0 ? 'Completado' : 'Procesando' )}}<span class="process-status status-{{$plano->cont_procesos < 0 ? 'error' : ($plano->cont_procesos == 0 ? 'ok' : 'processing' )}}"></span></td>
					<td>{{$plano->created_at}}</td>					
					<td>{{$plano->pagaduria->pagaduria}}</td>
					<td>{{planos()[$plano->tipo]}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	
@endsection
