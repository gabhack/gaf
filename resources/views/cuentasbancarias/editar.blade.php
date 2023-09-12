@extends('layouts.app2')

@section('js')

@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Cuenta Bancaria</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('cuentasbancarias')}}">Lista de Cuenta Bancaria</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Cuenta Bancaria</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('cuentasbancarias/crear')}}">Crear Cuenta Bancaria</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Cuenta Bancaria</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Cuenta Bancaria</div>
			<div class="panel-body">
				<form action="{{url('cuentasbancarias/update', ['id' => $cuenta->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="aliado">Entidad</label>
							<select class="form-control" name="id_entidad" id="id_entidad">
								<option value="">-Seleccione-</option>
								@foreach($entidades as $entidad)
									<option value="{{$entidad->id}}" {{$entidad->id == $cuenta->id_entidad ? 'selected="selected"' : ''}}>{{$entidad->entidad}}</option>
								@endforeach
							</select>
						</div>
						
						<div class="form-group col-md-6">
							<label for="pagaduria">Tipo de Cuenta</label>
							<select class="form-control" name="tipo_cuenta" id="tipo_cuenta">
								<option value="">-Seleccione-</option>
								<option value="0" {{$cuenta->tipo_cuenta == 0 ? 'selected="selected"' : ''}}>Ahorro</option>
								<option value="1" {{$entidad->tipo_cuenta == 1 ? 'selected="selected"' : ''}}>Corriente</option>
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="nro_cuenta">Número de Cuenta</label>
							<input type="text" class="form-control" name="nro_cuenta" value="{{ $cuenta->nro_cuenta }}" required>
						</div>
						<div class="form-group col-md-6">
							<label for="nombre">Nombre</label>
							<input type="text" class="form-control" name="nombre" id="nombre" value="{{ $cuenta->nombre }}" required >
						</div>
					</div>	
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
