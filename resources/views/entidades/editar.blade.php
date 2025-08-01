@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Entidades</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('entidades')}}">Lista de Entidades</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Entidad</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('entidades/crear')}}">Crear Entidades</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Entidades</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Entidades</div>
			<div class="panel-body">
				<form action="{{url('entidades/update', ['id' => $entidad->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="entidad">Entidad</label>
							<input type="text" class="form-control" name="entidad" id="entidad" placeholder="Entidad" value="{{$entidad->entidad}}">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
