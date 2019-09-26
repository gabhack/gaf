@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Sectores</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('sectores')}}">Lista de Sectores</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Sector</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('sectores/crear')}}">Crear Sectores</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Sectores</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Sectores</div>
			<div class="panel-body">
				<form action="{{url('sectores/update', ['id' => $sector->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="sector">Sector</label>
							<input type="text" class="form-control" name="sector" id="sector" placeholder="Sector" value="{{$sector->sector}}">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
