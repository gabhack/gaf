@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Cargos</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('cargos')}}">Lista de Cargos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Cargo</a>
			</li>
		</ul>
		<br />
		<h4>Crear Cargos</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Cargos</div>
			<div class="panel-body">
				<form action="{{url('cargos/store')}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="estado">Estado</label>
							<select class="form-control" name="estado" id="estado">
								<option value="">-Seleccione-</option>
								@foreach(estados_cargos() as $key => $estado)
									<option value="{{$key}}">{{$estado}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="cargo">Cargo</label>
							<input type="text" class="form-control" name="cargo" id="cargo" placeholder="Cargo">
						</div>
						<div class="form-group col-md-6">
							<label for="asignacion_adicional">Asignaci&oacute;n Adicional</label>
							<input type="number" step=".001" class="form-control" name="asignacion_adicional" id="asignacion_adicional" placeholder="Asignaci&oacute;n Adicional">
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
