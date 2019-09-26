@extends('layouts.app')

@section('js')
	<script src="{{asset('js/validaciones/Comerciales.js')}}"></script>	
@endsection

@section('content')
	
	<h2>Comerciales</h2>
	
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active">Actualizaci&oacute;n de Datos</a>
		</li>
	</ul>
	<br />
	<h4>Actualizaci&oacute;n de Datos</h4>
	<form action="{{url('comerciales/store')}}" id="comercial" method="post">
		{!! Form::token() !!}
		<div class="panel panel-default">
			<div class="panel-heading">Consulta</div>
			<div class="panel-body">				
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="pagad">Pagadur&iacute;a</label>
						<select class="form-control" name="pagad" id="pagad">
							<option value="">-Seleccione-</option>
							@foreach($pagadurias as $pagad)
								<option value="{{$pagad->id}}">{{$pagad->pagaduria}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-6">
						<label for="documento">Documento</label>
						<input type="number" class="form-control" name="documento" id="documento" placeholder="Documento">
					</div>						
				</div>				
				<button type="button" id="consultar" class="btn btn-primary">Consultar</button>				
			</div>
		</div>
		
		<div id="result"></div>
	</form>
@endsection
