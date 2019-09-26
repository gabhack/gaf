@extends('layouts.app')

@section('js')
	<script src="{{ asset('js/validaciones/Reportes.js') }}"></script>	
@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Reportes</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('reportes')}}">Reportes</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Consultas</a>
			</li>
		</ul>
		<br />
		<h4>Informes Personalizados</h4>
		
		<form method="post">
			{!! Form::token() !!}
				<div class="panel panel-default">
					<div class="panel-heading">Informes Personalizados</div>
					<div class="panel-body">
						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="pagaduria">Pagadur&iacute;a</label>
								<select class="form-control" name="pagaduria" id="pagaduria">
									<option value="">-Seleccione-</option>
									@foreach($pagadurias as $pagad)
										<option value="{{$pagad->id}}">{{ $pagad->pagaduria }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="pagaduria">Fecha de Nacimiento desde</label>
								<input type="date" class="form-control" name="" id="" placeholder="">
							</div>
							<div class="form-group col-md-4">
								<label for="pagaduria">Fecha de Nacimiento hasta</label>
								<input type="date" class="form-control" name="" id="" placeholder="">
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="pagaduria">Departamento</label>
								<select class="form-control" name="pagaduria" id="pagaduria">
									<option value="">-Seleccione-</option>
									@foreach($departamentos as $depto)
										<option value="{{$depto->id}}">{{ $depto->departamento }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="pagaduria">Municipio</label>
								<select class="form-control" name="pagaduria" id="pagaduria">
									<option value="">-Seleccione-</option>
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="pagaduria">Valor Mesada</label>
								<input type="number" class="form-control" name="" id="" placeholder="Valor Mesada">
							</div>
						</div>							
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading">Campos a Mostrar</div>
					<div class="panel-body">
						<div class="form-row">
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Documento</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Nombre</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Fecha de Ingreso</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Fecha de Retiro</label>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="form-row">
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Fecha de Nacimiento</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Mesada</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Cargo</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Grado</label>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="form-row">
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Escalaf&oacute;n</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Fecha de Nombramiento</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Situaci&oacute;n Laboral</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Centro Costo</label>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="form-row">
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Profesi&oacute;n</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Sexo</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Tel&eacute;fono</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Direcci&oacute;n</label>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="form-row">
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Correo</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Municipio</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Nivel CDB</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Nivel CPP</label>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<div class="form-row">
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Area</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Instituci&oacute;n Educativa</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Ubicaci&oacute;n</label>
							</div>
							<div class="form-check col-md-3">
								<input type="checkbox" name="pagaduria" id="pagaduria">
								<label for="pagaduria" class="form-check-label">Capacidad de Cartera</label>
							</div>
						</div>
					</div>
				</div>
				
			<button type="button" class="btn btn-primary">Consultar</button>
		</form>
	</div>

@endsection
