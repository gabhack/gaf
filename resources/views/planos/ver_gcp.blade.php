@extends('layouts.app')

@section('content')

    <div class="col-md-12">
        <div class="btn-group mr-2 float-right" role="group">
            <a type="button" class="btn btn-secondary" href="{{url('planos/crear_gcp')}}"><i class="fa fa-arrow-left"></i> Atrás</a>
        </div>
        <h3>Carga de archivo #{{ $archivo->id }}</h3>
    </div>

	<div class="col-md-12 col-md-offset-0">
		<div class="panel panel-default">
			<div class="panel-heading"></div>
			<div class="panel-body">
				<h4>Lista de Archivos</h4>
	
                <table class="table table-hover table-striped table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Nombre de archivo</th>
                            <th class="text-center">Ruta Google Storage</th>
                            <th class="text-center">Clase Detectada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalle_archivos as $archivo_gcp)
                            <tr>
                                <td>{{$archivo_gcp->nombre_archivo}}</td>
                                <td>{{$archivo_gcp->url_gcs}}</td>
                                <td class="text-center"><b>{{strtoupper($archivo_gcp->tipo_documento)}}</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
			</div>
		</div>
	</div>

@endsection
