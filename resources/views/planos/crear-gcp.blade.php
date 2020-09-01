@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		
		<h2>Carga de archivos V2</h2>
		
		<div class="panel panel-default">
			<div class="panel-heading">Archivos Planos</div>
			<div class="panel-body">
				<div role="tabpanel">
					<!-- Nav tabs -->
					<ul id="myTab" class="nav nav-tabs" role="tablist">
						<li role="presentation"><a class="active" href="#carga-masiva" aria-controls="carga-masiva" role="tab" data-toggle="tab">Carga masiva</a></li>
						{{-- <li role="presentation"><a href="#carga-cedula" aria-controls="carga-cedula" role="tab" data-toggle="tab">Carga por cédula</a></li> --}}
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="carga-masiva">
							<form action="{{url('planos/store_gcp_masivo')}}" method="post" enctype="multipart/form-data">
								{!! Form::token() !!}
								<div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="label-archivos"></label>
											<input class="input-archivos" type="file" class="form-control-file" id="archivo" name="archivo" required>
										</div>
									</div>
								</div>
								
								<button type="submit" class="btn btn-primary">Guardar</button>
							</form>
						</div>
						<div role="tabpanel" class="tab-pane" id="carga-cedula">
							<form action="{{url('planos/store_gcp_cedula')}}" method="post" enctype="multipart/form-data">
								{!! Form::token() !!}
								<br>
								<input placeholder="Cedula" type="number" name="cedula" id="input-cedula">
								<br>
								<br>
								<input type="button" value="+" onclick="addRow()">
								<div id="content-archivos">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label class="label-archivos"></label>
											<input class="input-archivos" type="file" class="form-control-file" id="archivos_0" name="archivos[0]" required>
										</div>
									</div>
								</div>
								
								<button type="submit" class="btn btn-primary">Guardar</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<h4>Lista de Archivos</h4>
	
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Estado</th>
				<th class="text-center">Clase Detectada</th>
				<th class="text-center">Nombre Archivo</th>
				<th class="text-center">Mensajes de proceso</th>
				<th class="text-center">Fecha</th>
			</tr>
		</thead>
		<tbody>
			@foreach($archivos as $archivo)
				<tr>
					<td>{{$archivo->id}}</td>
					<td title="{{$archivo->cont_procesos < 0 ? $archivo->logs : ''}}">{{$archivo->cont_procesos < 0 ? 'Error' : ($archivo->cont_procesos == 0 ? 'Completado' : 'Procesando' )}}<span class="process-status status-{{$archivo->cont_procesos < 0 ? 'error' : ($archivo->cont_procesos == 0 ? 'ok' : 'processing' )}}"></span></td>
					<td>{{$archivo->tipo ? $archivo->tipo : '--'}}</td>	
					<td>{{$archivo->nombre_archivo ? $archivo->nombre_archivo : '--'}}</td>	
					<td title="{{$archivo->logs ? $archivo->logs : ''}}">{{$archivo->logs ? mb_strimwidth($archivo->logs, 0, 30, "...") : '--'}}</td>
					<td>{{$archivo->created_at}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

@endsection
