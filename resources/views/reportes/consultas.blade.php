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
		<h4>Informes Generales</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Informes Generales</div>
			<div class="panel-body">
				<form method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-4">
							<label for="documento">Documento</label>
							<input type="number" class="form-control" name="documento" id="documento" placeholder="Documento">
						</div>
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
							<label for="consulta">Consulta</label>
							<select class="form-control" name="consulta" id="consulta">
								<option value="">-Seleccione-</option>
								@foreach(consultas() as $key => $consulta)
									<option value="{{$key}}">{{ $consulta }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<button type="button" id="btnConsulta" class="btn btn-primary">Consultar</button>
				</form>
			</div>
		</div>
		
		<div id="reporte"></div>
	</div>

@endsection
