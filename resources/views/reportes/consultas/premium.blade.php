<div class="panel panel-default">
	<div class="panel-heading">Informaci&oacute;n Personal</div>
	<div class="panel-body">
		<div class="form-row">
			<div class="form-group col-md-3">
				<label for="documento">Documento</label>
				<span class="form-control" id="documento">{{$base->cliente->documento}}</span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Nombre</label>
				<span class="form-control" id="documento">{{$base->cliente->nombres}} {{$base->cliente->apellidos}}</span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Fecha de Nacimiento</label>
				<span class="form-control" id="documento">{{$base->cliente->fechanto}}</span>
			</div>		
			<div class="form-group col-md-3">
				<label for="documento">Edad</label>
				<span class="form-control" id="documento">
					@php( $cumpleanos = new DateTime($base->cliente->fechanto . '00:00:00') )
					@php( $hoy = new DateTime() )
					@php( $annos = $hoy->diff($cumpleanos) )
					{{ $annos->y }}
				</span>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-3">
				<label for="pagaduria">Tipo de Pensi&oacute;n</label>
				<span class="form-control" id="documento"></span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Fecha de Resoluci&oacute;n</label>
				<span class="form-control" id="documento"></span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Direcci&oacute;n</label>
				<span class="form-control" id="documento">{{$base->cliente->direccion}}</span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Departamento</label>
				<span class="form-control" id="documento">{{$base->cliente->ciudad->departamento->departamento}}</span>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-3">
				<label for="pagaduria">Municipio</label>
				<span class="form-control" id="documento">{{$base->cliente->ciudad->ciudad}}</span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Celular</label>
				<span class="form-control" id="documento">{{$base->cliente->celular}}</span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Tel&eacute;fono</label>
				<span class="form-control" id="documento">{{$base->cliente->telefono}}</span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Correo</label>
				<span class="form-control" id="documento">{{$base->cliente->correo}}</span>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-3">
				<label for="pagaduria">B&aacute;sico</label>
				<span class="form-control" id="documento"></span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Salud</label>
				<span class="form-control" id="documento"></span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Descuentos</label>
				<span class="form-control" id="documento"></span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Deducidos</label>
				<span class="form-control" id="documento"></span>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-3">
				<label for="pagaduria">Mesada Compartida</label>
				<span class="form-control" id="documento"></span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Cupo Disponible</label>
				<span class="form-control" id="documento"></span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Capacidad Compra de Cartera</label>
				<span class="form-control" id="documento"></span>
			</div>
			<div class="form-group col-md-3">
				<label for="pagaduria">Embargos</label>
				<span class="form-control" id="documento">
					@if($embargos != 0)
						SI
					@else
						NO
					@endif
				</span>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-3">
				<label for="pagaduria">Cr&eacute;dito Libre Inversi&oacute;n</label>
				<span class="form-control" id="documento"></span>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">Descuentos Aplicados</div>
	<div class="panel-body">
		<table class="table table-hover table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th class="text-center">Tercero</th>
					<th class="text-center">Valor Aplicado</th>
					<th class="text-center">Pagare No.</th>
					<th class="text-center">Per&iacute;odo</th>
					<th class="text-center">Saldo</th>
				</tr>
			</thead>
			<tbody>
				@foreach($descApli as $dcto )
					<tr>
						<td>$dcto->tercero->entidad</td>
						<td>$dcto->valor</td>
						<td>$dcto->pagare</td>
						<td>$dcto->periodo</td>
						<td>$dcto->saldo</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">Descuentos No Aplicados</div>
	<div class="panel-body">
		<table class="table table-hover table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th class="text-center">Tercero</th>
					<th class="text-center">Valor Aplicado</th>
					<th class="text-center">Pagare No.</th>
					<th class="text-center">Per&iacute;odo</th>
					<th class="text-center">Saldo</th>
				</tr>
			</thead>
			<tbody>
				@foreach($descNoApli as $dcto )
					<tr>
						<td>$dcto->tercero->entidad</td>
						<td>$dcto->valor</td>
						<td>$dcto->pagare</td>
						<td>$dcto->periodo</td>
						<td>$dcto->saldo</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
	