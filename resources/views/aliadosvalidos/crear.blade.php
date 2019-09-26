@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Aliados por Pagadur&iacute;a</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('aliados')}}">Lista de Aliados Validos</a>
			</li>			
			<li class="nav-item">
				<a class="nav-link" href="{{url('aliadosvalidos')}}">Parametrizar Aliados</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Aliado por Pagadur&iacute;a</a>
			</li>
		</ul>
		<br />
		<h4>Crear Aliado por Pagadur&iacute;a</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Crear Aliado por Pagadur&iacute;a</div>
			<div class="panel-body">
				<form action="{{url('aliadosvalidos/store')}}" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="pagaduria">Pagadur&iacute;a</label>
							<select class="form-control" name="pagaduria" id="pagaduria">
								<option value="">-Seleccione-</option>
								@foreach($pagadurias as $pagad)
									<option value="{{$pagad->id}}">{{$pagad->pagaduria}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="tasa">Tipo de Embargo</label>
							<select class="form-control" name="tipoembargo" id="tipoembargo">
								<option value="">-Seleccione-</option>
								@foreach($tiposembargos as $tipo)
									<option value="{{$tipo->id}}">{{$tipo->tipo}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="pagaduria">Aliado(s)</label>
							<table class="table table-hover table-striped table-condensed table-bordered">
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="text-center">Aliado</th>
										<th class="text-center">Acciones</th>
									</tr>
								</thead>
								<tbody>
									@foreach($aliados as $aliado)
										<tr>
											<td>{{$aliado->id}}</td>
											<td>{{$aliado->aliado}}</td>					
											<td class="text-center">
												<input type="checkbox" name="aliado[]" value="{{$aliado->id}}">
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>					
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
