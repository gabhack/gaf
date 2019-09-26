@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Carga de Archivos</h2>
		
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('planos')}}">Lista de Archivos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Cargar Archivos Planos</a>
			</li>
		</ul>
		<br />
		<h4>Archivos Planos</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Archivos Planos</div>
			<div class="panel-body">
				<form action="{{url('planos/store')}}" method="post" enctype="multipart/form-data">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="pagaduria">Pagadur&iacute;a</label>
							<select class="form-control" name="pagaduria" id="pagaduria">
								<option value="">-Seleccione-</option>
								@foreach($pagadurias as $pagad)
									<option value="{{$pagad->id}}">{{$pagad->pagaduria}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="basicos">Datos B&aacute;sicos</label>
							<input type="file" class="form-control-file" id="basicos" name="basicos" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .txt">
						</div>
						<div class="form-group col-md-6">
							<label for="aplicados">Descuentos Aplicados</label>
							<input type="file" class="form-control-file" id="aplicados" name="aplicados" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .txt">
						</div>						
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="no_aplicados">Descuentos No Aplicados</label>
							<input type="file" class="form-control-file" id="no_aplicados" name="no_aplicados" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .txt">
						</div>
						<div class="form-group col-md-6">
							<label for="embargos">Embargos</label>
							<input type="file" class="form-control-file" id="embargos" name="embargos" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .txt">
						</div>						
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
