@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Carga de Archivos</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('planos')}}">Lista de Archivos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Cargar Archivos Planos</a>
			</li>
		</ul>
		<br />
		<h4>Archivos Planos</h4>
		
		<div class="panel panel-{{ $response['cod'] == 200 ? 'success' : ($response['cod'] == 400 ? 'danger' : ($response['cod'] == 300 ? 'warning' : 'default')) }}">
			<div class="panel-heading">{{$response['mensaje']}}</div>
			<div class="panel-body">
				<a href="{{url('planos' . ($response['redirect'] ? '/' . $response['redirect'] : ''))}}" class="btn btn-primary">Volver</a>
			</div>
		</div>
	</div>

@endsection
