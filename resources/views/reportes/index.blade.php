@extends('layouts.app')

@section('content')
	
	<h2>Reportes</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Reportes</a>
		</li>
	</ul>
	<br />
	<h4>Reportes</h4>
	
	<div class="panel panel-default">
		<div class="panel-heading">Reportes</div>
		<div class="panel-body">
			<div class="form-row px-2 mb-3 mt-3">
				<div class="form-group col-md-3">
					<a href="{{url('reportes/consultas')}}">
						<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
						<strong class="text-center">Consultas</strong>
						<br />
						<span>Informes Generales</span>
					</a>
				</div>
				<div class="form-group col-md-3">
					<a href="{{url('reportes/personalizados')}}">
						<img src="{{asset('img/custom.png')}}" alt="Search" class="rounded float-left">
						<strong class="text-center">Personalizados</strong>
						<br />
						<span>Informes Personalizados</span>
					</a>
				</div>
				<!--
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
				-->
			</div>
			<!--
			<div class="form-row px-2  mb-3 mt-3">
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
			</div>
			<div class="form-row px-2  mb-3 mt-3">
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
				<div class="form-group col-md-3">
					<img src="{{asset('img/search.png')}}" alt="Search" class="rounded float-left">
					<strong class="text-center">Consultas</strong>
					<br />
					<span>Consulta de Clientes</span>
				</div>
			</div>
			-->
		</div>
	</div>
	
@endsection
