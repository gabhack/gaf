@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">
		<ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{url('estudios')}}">Lista de Estudios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active"><b>Crear Estudio</b></a>
            </li>
        </ul>
		<div class="panel panel-default">
			<div class="panel-heading">Seleccione persona para estudio</div>
			<div class="panel-body">
				<form action="{{url('estudios/iniciar')}}" method="post" enctype="multipart/form-data">
					{!! Form::token() !!}
					<div class="form-row" id="panel-pagaduria">
						<div class="form-group col-md-4">
							<input type="number" class="form-control" name="documento" id="documento" placeholder="Documento">
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
