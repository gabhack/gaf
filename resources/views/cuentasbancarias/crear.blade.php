@extends('layouts.app2')

@section('js')

@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Cuentas Bancarias</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('cuentasbancarias')}}">Lista de Cuentas Bancarias</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Cuenta Bancaria</a>
			</li>
		</ul>
		<br />
		<h4>Crear Cuenta Bancaria</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Cuenta Bancaria</div>
			<div class="panel-body">
				<form action="{{url('cuentasbancarias/store')}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="aliado">Entidad</label>
							<select class="form-control" name="id_entidad" id="id_entidad" required>
								<option value="">-Seleccione-</option>
								@foreach($entidades as $entidad)
									<option value="{{$entidad->id}}">{{$entidad->entidad}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="tasa">Tipo de Cuenta</label>
							<select class="form-control" name="tipo_cuenta" id="tipo_cuenta" required>
								<option value="">-Seleccione-</option>
								<option value="0">Ahorro</option>
								<option value="1">Corriente</option>
							</select>
						</div>
					</div>								
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="nro_cuenta">Número de Cuenta</label>
							<input type="text" class="form-control" name="nro_cuenta" required>
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
