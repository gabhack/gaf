@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Parametros</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('parametros')}}">Lista de Parametros</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Parametro</a>
			</li>
			<!--
			<li class="nav-item">
				<a class="nav-link" href="{{url('parametros/crear')}}">Crear Parametros</a>
			</li>			
			-->
		</ul>
		<br />
		<h4>Editar Parametros</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Parametros</div>
			<div class="panel-body">
				<form action="{{url('parametros/update', ['id' => $parametro->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="valor">{{$parametro->llave}}</label>
							<input type="number" class="form-control" name="valor" id="valor" placeholder="Valor" value="{{$parametro->valor}}">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
