@extends('layouts.app2')

@section('content')
    @if (isset($message))
        <div id="toast-message" class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                        <div class="alert alert-{{ $message['tipo'] }} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-exclamation"></i> {{ $message['titulo'] }}</h4>
                            {{ $message['mensaje'] }}
                        </div>
                </div>
            </div>
        </div>
    @endif
	<div class="col-md-12 col-md-offset-0">
		<ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{url('estudios')}}">Todas las simulaciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active"><b>Nueva <i class="fa fa-plus" aria-hidden="true"></i></b></a>
            </li>
        </ul>
		<div class="panel panel-default">
			<div class="panel-heading">Seleccione persona para estudio</div>
			<div class="panel-body">
				<form action="{{url('estudios/iniciar')}}" method="post" enctype="multipart/form-data">
					{!! Form::token() !!}
					<div class="form-row" id="panel-pagaduria">
						<div class="form-group col-md-4">
                            <input type="number" class="form-control" name="documento" id="documento" placeholder="Documento" value="{{ isset($documento) ? $documento : '' }}">
                        </div>
                        <div class="form-group-col-md-2">
                            <button type="submit" class="btn btn-primary">Siguiente</button>
                        </div>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection
