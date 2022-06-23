@extends('layouts.app2')

@section('content')
	<div class="col-md-12">
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('pagos')}}">Lista de Pagos</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Realizar Pago</a>
			</li>
		</ul>
		<br/>
		<div class="panel-body">
			<form method="POST" action="{{url('pagos/pay')}}">
				{{ csrf_field() }}
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="nombre">Nombre</label>
						<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="{{ old('nombre') }}" required>
						<input type="hidden" value="{{$source_id}}" name="" id="">
						<input type="hidden" value="" name="device_session_id" id="device_session_id">
					</div>
					<div class="form-group col-md-4">
						<label for="apellido">Apellidos</label>
						<input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellidos" value="{{ old('apellido') }}" required>
					</div>
					<div class="form-group col-md-4">
						<label for="email">Correo Electrónico</label>
						<input type="email" class="form-control" name="email" id="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="telefono">Teléfono</label>
						<input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="{{ old('telefono') }}" required>
					</div>
					<div class="form-group col-md-4">
						<label for="concepto">Concepto</label>
						<input type="text" class="form-control" name="concepto" id="concepto" placeholder="Concepto" value="{{ old('concepto') }}" required>
					</div>
					<div class="form-group col-md-4">
						<label for="monto">Total</label>
						<input type="number" min="0.00" max="10000.00" step="0.01" class="form-control" name="monto" id="monto" value="{{ old('monto') }}" placeholder="Total" required>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="tarjeta">Numero de Tarjeta</label>
						<input type="number" class="form-control" name="tarjeta" id="tarjeta" placeholder="Numero de tarjeta" value="{{ old('tarjeta') }}" required>
						@if ($errors->has('tarjeta'))
						    <p class="error-message">{{ $errors->first('tarjeta') }}</p>
						@endif
					</div>
					<div class="form-group col-md-3">
						<label for="mes">Mes de expiración</label>
						<select class="form-control" name="mes" id="mes">
							<option value="01" {{ old('mes') == '01' ? 'selected' : '' }}>Enero</option>
							<option value="02" {{ old('mes') == '02' ? 'selected' : '' }}>Febrero</option>
							<option value="03" {{ old('mes') == '03' ? 'selected' : '' }}>Marzo</option>
							<option value="04" {{ old('mes') == '04' ? 'selected' : '' }}>Abril</option>
							<option value="05" {{ old('mes') == '05' ? 'selected' : '' }}>Mayo</option>
							<option value="06" {{ old('mes') == '06' ? 'selected' : '' }}>Junio</option>
							<option value="07" {{ old('mes') == '07' ? 'selected' : '' }}>Julio</option>
							<option value="08" {{ old('mes') == '08' ? 'selected' : '' }}>Agosto</option>
							<option value="09" {{ old('mes') == '09' ? 'selected' : '' }}>Septiembre</option>
							<option value="10" {{ old('mes') == '10' ? 'selected' : '' }}>Octubre</option>
							<option value="11" {{ old('mes') == '11' ? 'selected' : '' }}>Noviembre</option>
							<option value="12" {{ old('mes') == '12' ? 'selected' : '' }}>Diciembre</option>
						</select>
						@if ($errors->has('mes'))
						    <p class="error-message">{{ $errors->first('mes') }}</p>
						@endif
					</div>
					<div class="form-group col-md-3">
						<label for="year">Año de expiración</label>
						<select class="form-control" name="year" id="year">
							<option value="22" {{ old('year') == 22 ? 'selected' : '' }}>2022</option>
							<option value="23" {{ old('year') == 23 ? 'selected' : '' }}>2023</option>
							<option value="24" {{ old('year') == 24 ? 'selected' : '' }}>2024</option>
							<option value="25" {{ old('year') == 25 ? 'selected' : '' }}>2025</option>
							<option value="26" {{ old('year') == 26 ? 'selected' : '' }}>2026</option>
							<option value="27" {{ old('year') == 27 ? 'selected' : '' }}>2027</option>
							<option value="28" {{ old('year') == 28 ? 'selected' : '' }}>2028</option>
							<option value="29" {{ old('year') == 29 ? 'selected' : '' }}>2029</option>
							<option value="30" {{ old('year') == 30 ? 'selected' : '' }}>2030</option>
						</select>
					</div>
					<div class="form-group col-md-2">
						<label for="cvv">CVV</label>
						<input type="number" class="form-control" name="cvv" id="cvv" placeholder="Numero de cvv" value="{{ old('cvv') }}" required>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">Pagar</button>
			</form>
		</div>
	</div>
    <script type='text/javascript' src="https://openpay.s3.amazonaws.com/openpay.v1.js"></script>
	<script type='text/javascript' src="https://openpay.s3.amazonaws.com/openpay-data.v1.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			OpenPay.setSandboxMode(true);
			var deviceDataId = OpenPay.deviceData.setup();
			$('#device_session_id').val(deviceDataId);
		});
	</script>

@endsection

@section('title')
    Usuario / Crear
@endsection

@section('header-content')
    Realizar Pago
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li><a href="{{url('usuarios')}}">Pagos</a></li>
    <li class="active">Realizar Pago</li>
@endsection