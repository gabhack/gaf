<div class="panel panel-default">
	<div class="panel-heading">Amortizaci&oacute;n</div>
	<div class="panel-body">
		<table class="table table-hover table-striped table-condensed table-bordered">
			<thead>
				<tr>
					<th class="text-center">Per&iacute;odo</th>
					<th class="text-center">Capital</th>
					<th class="text-center">Servicio</th>
					<th class="text-center">Seguro</th>
					<th class="text-center">Inter&eacute;s</th>
					<th class="text-center">Cuota</th>
					<th class="text-center">Saldo</th>
				</tr>
			</thead>
			<tbody id="amortizacion">
				@php($i = 1)
				@foreach($estudio->condicionck->amortizaciones as $amortizacion)
					<tr>
						<td>{{$i}}</td>
						<td>{{format($amortizacion->capital)}}</td>
						<td>0</td>
						<td>{{format($amortizacion->seguros)}}</td>
						<td>{{format($amortizacion->interes)}}</td>
						<td>{{format($amortizacion->cuota)}}</td>
						<td>{{format($amortizacion->saldo)}}</td>
					</tr>
					@php($i++)
				@endforeach
			</tbody>
		</table>
	</div>		
</div>
