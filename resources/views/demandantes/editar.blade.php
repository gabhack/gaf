@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Demandantes</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('demandantes')}}">Lista de Demandantes</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Demandante</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('demandantes/crear')}}">Crear Demandantes</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Demandantes</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Demandantes</div>
			<div class="panel-body">
				<form action="{{url('demandantes/update', ['id' => $demandante->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="documento">Documento</label>
							<input type="number" class="form-control" name="documento" id="documento" placeholder="Documento" value="{{$demandante->documento}}">
						</div>
						<div class="form-group col-md-6">
							<label for="demandante">Demandante</label>
							<input type="text" class="form-control" name="demandante" id="demandante" placeholder="Demandante" value="{{$demandante->demandante}}">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
