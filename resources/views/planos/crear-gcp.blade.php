@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Clasificación de Archivos</h2>
		
		<div class="panel panel-default">
			<div class="panel-heading">Archivos Planos</div>
			<div class="panel-body">
				<form action="{{url('planos/store_gcp')}}" method="post" enctype="multipart/form-data">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label class="label-archivos"></label>
							<input class="input-archivos" type="file" class="form-control-file" id="input-archivos" name="archivo" required>
						</div>
					</div>
					
					<button type="submit" class="btn btn-primary">Guardar</button>
				</form>
			</div>
		</div>
	</div>

@endsection
