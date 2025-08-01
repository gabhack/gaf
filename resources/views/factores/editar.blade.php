@extends('layouts.app')

@section('js')
	<script src="{{ asset('js/validaciones/Factores.js') }}"></script>
@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Factores</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('factores')}}">Lista de Factores</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Editar Factor</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('factores/crear')}}">Crear Factores</a>
			</li>			
		</ul>
		<br />
		<h4>Editar Factores</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Editar Factores</div>
			<div class="panel-body">
				<form action="{{url('factores/update', ['id' => $factor->id])}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="aliado">Aliado</label>
							<select class="form-control" name="aliado" id="aliado">
								<option value="">-Seleccione-</option>
								@foreach($aliados as $aliado)
									<option value="{{$aliado->id}}" {{$aliado->id == $factor->aliados_id ? 'selected="selected"' : ''}}>{{$aliado->aliado}}</option>
								@endforeach
							</select>
						</div>
						
						<div class="form-group col-md-6">
							<label for="tasa">Tasa</label>
							<input type="number" step=".000001" class="form-control" name="tasa" id="tasa" placeholder="Tasa" value="{{$factor->tasa}}">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="pagaduria">Pagadur&iacute;a</label>
							<select class="form-control" name="pagaduria" id="pagaduria">
								<option value="">-Seleccione-</option>
								@foreach($pagadurias as $pagaduria)
									<option value="{{$pagaduria->id}}" {{$pagaduria->id == $factor->pagadurias_id ? 'selected="selected"' : ''}}>{{$pagaduria->pagaduria}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="edadMin">Edad M&iacute;nima</label>
							<input type="number" class="form-control" name="edadMin" id="edadMin" placeholder="Edad M&iacute;nima" value="{{$factor->edad_min}}">
						</div>
						<div class="form-group col-md-6">
							<label for="edadMax">Edad M&aacute;xima</label>
							<input type="number" class="form-control" name="edadMax" id="edadMax" placeholder="Edad M&aacute;xima" value="{{$factor->edad_max}}">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="plazo">Plazo</label>
							<input type="number" class="form-control" name="plazo" id="plazo" placeholder="Plazo" value="{{$factor->plazo}}">
						</div>
						<div class="form-group col-md-6">
							<label for="factor">Factor</label>
							<input type="number" step=".00000000001" class="form-control" name="factor" id="factor" placeholder="Factor" value="{{$factor->factor}}">
						</div>
					</div>
					
					<input type="hidden" id="hMargen" name="hMargen" value="{{$factor->margen}}">
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
