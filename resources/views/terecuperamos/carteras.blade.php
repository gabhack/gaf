		
	<table class="table table-hover table-striped table-condensed table-bordered">
		<thead>
			<tr>
				<th class="text-center">Entidad</th>
				<th class="text-center">Sector</th>
				<th class="text-center">Estado</th>
				<th class="text-center">Comprador</th>
				<th class="text-center">Cuota</th>
				<th class="text-center">Saldo Centrales</th>
				<th class="text-center">Valor Inicial</th>
				<th class="text-center">Descuento</th>
				<th class="text-center">Saldo</th>
				<th class="text-center">% Negociacio</th>
				<th class="text-center">Fecha Vencimiento</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($carteras as $cartera)
				<tr>
					<td>{{$cartera->entidad->entidad}}</td>
					<td>{{$cartera->sector->sector}}</td>
					<td>{{$cartera->estado->estado}}</td>
					<td>{{compradores()[$cartera->comprado_por]}}</td>
					<td>{{format($cartera->cuota)}}</td>
					<td>{{format($cartera->saldo)}}</td>
					<td>{{format($cartera->valor_ini)}}</td>
					<td>{{format($cartera->dcto_logrado)}}</td>
					<td>{{format($cartera->saldo_negociado)}}</td>
					<td>{{$cartera->porc_negociado}}</td>
					<td>{{$cartera->fecha_vence}}</td>
					<td class="text-center">
						<a onclick="updateCartera(event, {{$cartera->id}})" href="" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
						<a onclick="deleteCartera(event, this, {{$cartera->id}})" href="" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	