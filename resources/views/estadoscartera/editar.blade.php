@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Estados Cartera</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('estadoscartera')}}">Lista de Estados</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Estado</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('estadoscartera/crear')}}">Crear Estados</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Estados</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Estados</div>
			<div class="panel-body">
				<form action="{{url('estadoscartera/update', ['id' => $estado->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="estado">Estado Cartera</label>
							<input type="text" class="form-control" name="estado" id="estado" placeholder="Estado Cartera" value="{{$estado->estado}}">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
