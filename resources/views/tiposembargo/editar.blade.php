@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Tipos de Embargo</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('tiposembargo')}}">Lista de Tipos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Tipos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('tiposembargo/crear')}}">Crear Tipos</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Tipos</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Tipos</div>
			<div class="panel-body">
				<form action="{{url('tiposembargo/update', ['id' => $tipo->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="tipo">Tipo de Embargo</label>
							<input type="text" class="form-control" name="tipo" id="tipo" placeholder="Tipo de Embargo" value="{{$tipo->tipo}}">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
