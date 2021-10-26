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
							<input type="number" class="form-control" name="documento" id="documento" placeholder="Documento" onchange="checkSubmit(this)" onkeyup="checkSubmit(this)" required>
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
							<button type="button" id="btn-consultar" onclick="consultarPagadurias();" class="btn btn-primary" disabled>Consultar</button>
						</div>
						<div class="form-group col-md-12 text-center">
							<label for="autorizacion_file">Autorización Política de datos</label>
							<input class="m-auto mb-4" type="file" id="autorizacion_file" name="autorizacion_file" accept="application/pdf" required>
						</div>
						<div class="form-group col-md-12 text-center">
							<label for="desprendible_file">Último Desprendible</label>
							<input class="m-auto mb-4" type="file" id="desprendible_file" name="desprendible_file" accept="application/pdf" required>
						</div>
					</div>
					<div class="form-row" id="panel-pagaduria">
						<div class="form-group-col-md-3">
							<select class="form-control" onchange="checkSubmitTotal();" name="registro_pagaduria" id="registro_pagaduria" required hidden>
								<option value="" selected disabled hidden>Pagaduría...</option>
							</select>
						</div>
						<div class="form-group-col-md-3">
							<button id="btn-submit-consulta" type="submit" class="btn btn-primary" disabled hidden>Generar Reporte</button>
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

@section('js')
	<script>
		function consultarPagadurias() {
			var documento = document.getElementById('documento').value;
			var select_pagaduria = document.getElementById("registro_pagaduria");
			var btn_submit_consulta = document.getElementById("btn-submit-consulta");

			$.post('api/ami/getDesprendiblesXDocumento', {'documento' : documento}, function(data){
				if (data.error) {
					alert(data.error);
					select_pagaduria.hidden = true;
					btn_submit_consulta.hidden = true;
					reinitPagadurias();
				} else {
					select_pagaduria.hidden = false;
					btn_submit_consulta.hidden = false;
					reinitPagadurias();
					//Agregar las opciones de pagadurías
					data.desprendibles.forEach(registro => {
						const option = document.createElement('option');
						option.className = 'option_registro_pagaduria';
						option.innerHTML = registro.periodo + ' - ' + data.adicionales[registro.id];
						option.value = registro.id;
						select_pagaduria.appendChild(option);
					});
				}
			});
		}

		function checkSubmit() {
			var val = document.getElementById('documento').value;
			reinitPagadurias();
			if (val !== '') {
				document.getElementById('btn-consultar').disabled = false;
			} else {
				document.getElementById('btn-consultar').disabled = true;
			}
		}

		function checkSubmitTotal() {
			var val = document.getElementById('registro_pagaduria').value;
			if (val !== '') {
				document.getElementById('btn-submit-consulta').disabled = false;
			} else {
				document.getElementById('btn-submit-consulta').disabled = true;
			}
		}

		function reinitPagadurias () {
			var select_pagaduria = document.getElementById("registro_pagaduria");
			document.getElementById('btn-submit-consulta').disabled = true;
			//Limpiar opciones
			select_pagaduria.innerHTML = "";
			//Agregar primera opción
			const optioninicial = document.createElement('option');
			optioninicial.className = 'option_registro_pagaduria';
			optioninicial.innerHTML = 'Pagaduría...';
			optioninicial.disabled = true;
			optioninicial.hidden = true;
			optioninicial.selected = true;
			select_pagaduria.appendChild(optioninicial);
		}
	</script>
@endsection

@section('header-content')
	Consulta AMI®
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li class="active">Consulta AMI®</li>
@endsection