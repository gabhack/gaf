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
						<input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellidos" required>
					</div>
					<div class="form-group col-md-4">
						<label for="email">Correo Electrónico</label>
						<input type="email" class="form-control" name="email" id="email" placeholder="Correo Electrónico" required>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="telefono">Teléfono</label>
						<input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="{{ old('telefono') }}" required>
					</div>
					<div class="form-group col-md-4">
						<label for="concepto">Concepto</label>
						<input type="text" class="form-control" name="concepto" id="concepto" placeholder="Concepto" required>
					</div>
					<div class="form-group col-md-4">
						<label for="monto">Total</label>
						<input type="number" min="0.00" max="10000.00" step="0.01" class="form-control" name="monto" id="monto" placeholder="Total" required>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="tarjeta">Numero de Tarjeta</label>
						<input type="number" class="form-control" name="tarjeta" id="tarjeta" placeholder="Numero de tarjeta" required>
					</div>
					<div class="form-group col-md-3">
						<label for="mes">Mes de expiración</label>
						<select class="form-control" name="mes" id="mes">
							<option value="01">Enero</option>
							<option value="02">Febrero</option>
							<option value="03">Marzo</option>
							<option value="04">Abril</option>
							<option value="05">Mayo</option>
							<option value="06">Junio</option>
							<option value="07">Julio</option>
							<option value="08">Agosto</option>
							<option value="09">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="year">Año de expiración</label>
						<select class="form-control" name="year" id="year">
							<option value="22">2022</option>
							<option value="23">2023</option>
							<option value="24">2024</option>
							<option value="25">2025</option>
							<option value="26">2026</option>
							<option value="27">2027</option>
							<option value="28">2028</option>
							<option value="29">2029</option>
							<option value="30">2030</option>
						</select>
					</div>
					<div class="form-group col-md-2">
						<label for="cvv">CVV</label>
						<input type="number" class="form-control" name="cvv" id="cvv" placeholder="Numero de cvv" required>
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