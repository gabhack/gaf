@extends('layouts.app')

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
		<div class="row justify-content-md-center mt-5">
			<div class="col-md-6">
				<form action="{{url('consultas/consultar')}}" method="post" enctype="multipart/form-data">
					{!! Form::token() !!}
					<div class="form-row" id="panel-pagaduria">
						<div class="form-group col-md-6">
							<input type="number" class="form-control" name="documento" id="documento" placeholder="Documento" required>
						</div>
						<div class="form-group-col-md-3">
							<select class="form-control" name="tipo_consulta" id="tipo_consulta" required>
								<option value="" selected disabled hidden>Tipo Consulta...</option>
								@if (AMISilverHabilitado())
									<option value="1">AMI®Silver</option>
								@endif
								@if (AMIGoldHabilitado())
									<option value="2">AMI®Gold</option>
								@endif
								@if (AMIDiamondHabilitado())
									<option value="3">AMI®Diamond</option>
								@endif
							</select>
						</div>
						<div class="form-group-col-md-3">
							<button type="submit" class="btn btn-primary">Consultar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

@section('title')
	Consulta AMI®
@endsection

@section('header-content')
	Consulta AMI®
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li class="active">Consulta AMI®</li>
@endsection