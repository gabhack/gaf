<div class="panel panel-default">
	<div class="panel-heading">Condiciones de Servicio Te-Recuperamos</div>
	<div class="panel-body">
		<form method="post" name="frm_paso5" id="frm_paso5">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="total_carteras">Total Carteras a Comprar</label>
					<span class="form-control" id="total_carteras">{{format($totalCarteras)}}</span>
				</div>
				<div class="form-group col-md-4">
					<label for="costos">Costos</label>
					<input type="number" class="form-control" name="costos" id="costos" placeholder="Costos" value="{{$estudio->condicion->costoservicios}}">
				</div>
				<div class="form-group col-md-4">
					<label for="valor_costos">Valor Costos</label>
					<span class="form-control" id="valor_costos" placeholder="Valor Costos">
						@php($costos = $estudio->condicion->costoservicios * $totalCarteras / 100)
						{{ format($costos) }}
					</span>
				</div>			
			</div>	
			
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="costo_juridico">Costo Jur&iacute;dico</label>
					<select class="form-control" name="costo_juridico" id="costo_juridico">
						<option value="">-Seleccione-</option>
						<option value="S" {{$estudio->condicion->costojuridico == 'S' ? 'selected="selected"' : ''}} >SI</option>
						<option value="N" {{$estudio->condicion->costojuridico == 'N' ? 'selected="selected"' : ''}} >NO</option>
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="span_juridico">Valor Costos Jur&iacute;dico</label>
					<span class="form-control" id="span_juridico">
					{{ $estudio->condicion->costojuridico == 'S' ? format($valor_juridico->valor) : '' }}
					</span>
				</div>
				<div class="form-group col-md-4">
					<label for="impuestos">Impuestos</label>
					<span class="form-control" id="impuestos">
						@if( $estudio->condicion->costojuridico == 'S' )
							@php( $imptos = ($costos + $valor_juridico->valor) * $iva->valor / 100 )
						@else
							@php( $imptos = $costos * $iva->valor / 100 )
						@endif
						{{ format(intval($imptos)) }}
					</span>
				</div>
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="valor_certificado">Costo Certificado</label>
					<input type="number" class="form-control" name="valor_certificado" id="valor_certificado" placeholder="Costo Certificado" value="{{$estudio->condicion->costocertificados}}">
				</div>			
				<div class="form-group col-md-4">
					<label for="gmf">GMF</label>
					<input type="number" class="form-control" name="gmf" id="gmf" placeholder="GMF" value="{{$estudio->condicion->gmf}}">
				</div>			
				<div class="form-group col-md-4">
					<label for="span_terecuperamos">Total Servicio</label>
					<span class="form-control" id="span_terecuperamos">
						{{ format( intval( $totalCarteras + ($estudio->condicion->costojuridico == 'S' ? $valor_juridico->valor : 0) + $costos + $imptos + $estudio->condicion->costocertificados + $estudio->condicion->gmf ) ) }}
					</span>
				</div>			
			</div>
			
			<input type="hidden" name="estudio" id="estudio" value="{{$estudio->id}}">	
			<input type="hidden" name="valor_carteras" id="valor_carteras" value="{{$totalCarteras}}">
			<input type="hidden" name="iva" id="iva" value="{{$iva->valor}}">
			<input type="hidden" name="porc_gmf" id="porc_gmf" value="{{$gmf->valor}}">
			<input type="hidden" name="valor_juridico" id="valor_juridico" value="{{$valor_juridico->valor}}">
			
			<input type="hidden" name="imptostr" id="imptostr" value="{{intval($imptos)}}">
			<input type="hidden" name="costostr" id="costostr" value="{{intval($costos)}}">
			<input type="hidden" name="totaltr" id="totaltr" value="{{$totalCarteras}}">
			
			<button type="button" id="btn_paso5" class="btn btn-primary">Guardar</button>
			
		</form>

	</div>		
</div>	