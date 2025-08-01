<div class="panel panel-default">
	<div class="panel-heading">Condiciones de Cr&eacute;dito Aliado Financiero</div>
	<div class="panel-body">	
		<form method="post" name="frm_paso7" id="frm_paso7">		
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="decisionAF">Decisi&oacute;n</label>
					<select class="form-control" name="decisionAF" id="decisionAF">
						<option value="">-Seleccione-</option>
						@foreach(decisiones_aliados() as $key => $decision)
							<option value="{{$key}}" {{$estudio->condicionaf != null && $estudio->condicionaf->decision == $key ? 'selected="selected"' : '' }} >{{$decision}}</option>						
						@endforeach
					</select>					
				</div>
				<div class="form-group col-md-4">
					<label for="aliado_af">Aliado Financiero</label>
					<select class="form-control" name="aliado_af" id="aliado_af">
						<option value="">-Seleccione-</option>
						@foreach($aliados as $aliado)
							<option value="{{$aliado->id}}" {{$estudio->condicionaf != null && $estudio->condicionaf->aliados_id == $aliado->id ? 'selected="selected"' : '' }} >{{$aliado->aliado}}</option>						
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="plazo_af">Plazo</label>
					<input type="number" class="form-control" name="plazo_af" id="plazo_af" placeholder="Plazo" readonly="readonly" value="{{ $estudio->condicionaf !=null ? $estudio->condicionaf->plazo : '' }}">
				</div>				
			</div>	
			
			<div class="form-row">				
				<div class="form-group col-md-4">
					<label for="tasa_af">Tasa</label>
					<input type="number" class="form-control" name="tasa_af" id="tasa_af" readonly="readonly" placeholder="Tasa" value="{{$tasaAf->valor}}">						
				</div>
				<div class="form-group col-md-4">
					<label for="max_cupo_afi">Cupo M&aacute;ximo</label>
					<span class="form-control" name="max_cupo_afi" id="max_cupo_afi">
						{{ format($maxCupo) }}
					</span>
				</div>
				<div class="form-group col-md-4">
					<label for="cuotaAliado">Cuota</label>
					<input type="number" class="form-control" name="cuotaAliado" id="cuotaAliado" placeholder="Cuota" value="{{ $estudio->condicionaf != null ? $estudio->condicionaf->cuota : '' }}">
				</div>							
			</div>	

			<div class="form-row">								
				<div class="form-group col-md-4">
					<label for="spanMontoAF">Monto</label>
					<span class="form-control" name="spanMontoAF" id="spanMontoAF">
						@php( $montoAF = $estudio->condicionaf != null ? intval($estudio->condicionaf->cuota / $estudio->condicionaf->factor) : 0 )
						{{ format($montoAF) }}
					</span>
				</div>
				<div class="form-group col-md-4">
					<label for="valorCarteraAF">Valor Carteras a Comprar</label>
					<span class="form-control" name="valorCarteraAF" id="valorCarteraAF">
						@php( $valorCarteraAF = $estudio->condicionck != null ? intval($estudio->condicionck->total + $saldoNegociado) : 0 )
						{{ format($valorCarteraAF) }}
					</span>
				</div>
				<div class="form-group col-md-4">
					<label for="spanSaldoAlCliente">Saldo al Cliente</label>
					<span class="form-control" id="spanSaldoAlCliente">
						@php($saldoAlCliente = $montoAF - $valorCarteraAF)
						{{ format($saldoAlCliente) }}
					</span>
				</div>					
			</div>	

			<div class="form-row">														
				<div class="form-group col-md-4">
					<label for="valorTitulos">Valor en T&iacute;tulos</label>
					<input type="number" class="form-control" name="valorTitulos" id="valorTitulos" placeholder="Valor en T&iacute;tulos" value="{{ $estudio->condicionaf !=null ? $estudio->condicionaf->valor_titulos : '' }}">
				</div>
				<div class="form-group col-md-4">
					<label for="saldoClienteTotal">Saldo Cliente + T&iacute;tulos</label>
					<span class="form-control" id="saldoClienteTotal">
						{{ format($saldoAlCliente + ( $estudio->condicionaf !=null ? $estudio->condicionaf->valor_titulos : 0 ) ) }}
					</span>
				</div>				
			</div>
			
			<input type="hidden" name="estudio" id="estudio" value="{{$estudio->id}}">	
			<input type="hidden" name="maxCupo" id="maxCupo" value="{{$maxCupo}}">	
			<input type="hidden" name="maxPlazo" id="maxPlazo">
			
			<input type="hidden" name="valorCompra" id="valorCompra" value="{{$valorCarteraAF}}">
			<input type="hidden" name="saldoAlCliente" id="saldoAlCliente" value="{{$saldoAlCliente}}">
			<input type="hidden" name="factor" id="factor" value="{{ $estudio->condicionaf !=null ? $estudio->condicionaf->factor : 0}}">
			
			<button type="button" id="btn_paso7" class="btn btn-primary">Guardar</button>
			
		</form>
		
	</div>		
</div>	