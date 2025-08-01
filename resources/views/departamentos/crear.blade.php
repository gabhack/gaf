@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Departamentos</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('departamentos')}}">Lista de Departamentos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Departamento</a>
			</li>
		</ul>
		<br />
		<h4>Crear Departamentos</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Departamentos</div>
			<div class="panel-body">
				<form action="{{url('departamentos/store')}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="codigo">C&oacute;digo</label>
							<input type="number" class="form-control" name="codigo" id="codigo" placeholder="C&oacute;digo">
						</div>
						<div class="form-group col-md-6">
							<label for="departamento">Departamento</label>
							<input type="text" class="form-control" name="departamento" id="departamento" placeholder="Departamento">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
