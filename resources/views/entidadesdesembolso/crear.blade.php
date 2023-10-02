@extends('layouts.app2')

@section('js')

@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Entidad Desembolso</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('entidadesdesembolso')}}">Lista de Entidad Desembolso</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Entidad Desembolso</a>
			</li>
		</ul>
		<br />
		<h4>Crear Entidad Desembolso</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Cuenta Bancaria</div>
			<div class="panel-body">
				<form action="{{url('entidadesdesembolso/store')}}" method="post">
					{!! Form::token() !!}						
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="nit">NIT</label>
							<input type="text" class="form-control" name="nit" required>
						</div>
						<div class="form-group col-md-6">
							<label for="nombre">Nombre</label>
							<input type="text" class="form-control" name="nombre" id="nombre" required >
						</div>
					</div>	
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
