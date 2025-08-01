@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Sectores</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('sectores')}}">Lista de Sectores</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Sector</a>
			</li>
		</ul>
		<br />
		<h4>Crear Sectores</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Sectores</div>
			<div class="panel-body">
				<form action="{{url('sectores/store')}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="sector">Sector</label>
							<input type="text" class="form-control" name="sector" id="sector" placeholder="Sector">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
