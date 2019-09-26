<div class="panel panel-default">
	<div class="panel-heading">Condiciones de Cr&eacute;dito CK</div>
	<div class="panel-body">	
		<form method="post" name="frm_paso6" id="frm_paso6">		
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="tasa_ck">Tasa %</label>
					<input type="number" class="form-control" name="tasa_ck" id="tasa_ck" value="{{$estudio->condicionck != null ? $estudio->condicionck->tasa : $tasa_ck->valor}}">
				</div>
				<div class="form-group col-md-4">
					<label for="plazo">Plazo</label>
					<input type="number" class="form-control" name="plazo" id="plazo" placeholder="Plazo" value="{{$estudio->condicionck != null ? $estudio->condicionck->plazo : ''}}">
				</div>
				<div class="form-group col-md-4">
					<label for="cupo_maximo_ck">Cupo M&aacute;ximo</label>
					<span class="form-control" id="cupo_maximo_ck">{{format($cupoMaximo)}}</span>
				</div>			
			</div>	
			
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="span_cuota_ck">Valor Cuota</label>
					<span class="form-control" id="span_cuota_ck">{{$estudio->condicionck != null ? format($estudio->condicionck->cuota) : ''}}</span>
				</div>
				<div class="form-group col-md-4">
					<label for="anticipo">Anticipo Cliente</label>
					<input type="number" class="form-control" name="anticipo" id="anticipo" placeholder="Anticipo Cliente" value="{{$estudio->condicionck != null ? $estudio->condicionck->anticipo : ''}}">
				</div>
				<div class="form-group col-md-4">
					<label for="total_anticipo">Total Anticipo</label>
					<span class="form-control" name="total_anticipo" id="total_anticipo">
						@if($estudio->condicionck != null)
							{{ format( $estudio->condicionck->anticipo + ($estudio->condicionck->anticipo * $anticipo->valor / 100) ) }}
						@endif
					</span>
				</div>
			</div>	

			<div class="form-row">				
				<div class="form-group col-md-4">
					<label for="precio_carteras">Total Carteras a Comprar</label>
					<span class="form-control" id="precio_carteras">
						@php($valCostos = $estudio->condicion->costoservicios * $totalCarteras / 100)
						
						@if( $estudio->condicion->costojuridico == 'S' )
							@php( $imptos = ($valCostos + $valor_juridico->valor) * $iva->valor / 100 )
						@else
							@php( $imptos = $valCostos * $iva->valor / 100 )
						@endif
						
						@php( $totalAcomprar = intval( $totalCarteras + ($estudio->condicion->costojuridico == 'S' ? $valor_juridico->valor : 0) + $valCostos + $imptos + $estudio->condicion->costocertificados + $estudio->condicion->gmf ) )
						{{ format($totalAcomprar) }}
					</span>
				</div>
				<div class="form-group col-md-4">
					<label for="porc_costos">Costos %</label>
					<input type="number" class="form-control" name="porc_costos" id="porc_costos" placeholder="Costos" value="{{$costos->valor}}">
				</div>
				<div class="form-group col-md-4">
					<label for="span_costos">Valor Costos</label>
					<span class="form-control" id="span_costos">
						@if($estudio->condicionck != null)
							@if($totalCarteras == 0)
								@php($costos_ck = ($totalAcomprar + $estudio->condicionck->anticipo + ($estudio->condicionck->anticipo * $anticipo->valor / 100)) * $costos->valor / 100)								
							@else
								@php($costos_ck = ($totalCarteras + $estudio->condicionck->anticipo + ($estudio->condicionck->anticipo * $anticipo->valor / 100)) * $costos->valor / 100)
							@endif						
							{{ format($costos_ck) }}
						@endif
					</span>
				</div>			
			</div>	
			
			<div class="form-row">						
				<div class="form-group col-md-4">
					<label for="imptos">Impuestos</label>
					<span class="form-control" id="imptos">
						@php($imptos = ceil($costos_ck * $iva->valor / 100) )
						{{format( intval($imptos) )}}
					</span>
				</div>	
				<div class="form-group col-md-4">
					<label for="span_seguro_ck">Seguro</label>
					<span class="form-control" id="span_seguro_ck">
						{{$estudio->condicionck != null ? format( intval($estudio->condicionck->seguro * $estudio->condicionck->total) ) : ''}}
					</span>
				</div>
				<div class="form-group col-md-4">
					<label for="span_gmf_ck">GMF</label>
					<span class="form-control" id="span_gmf_ck">
						@php( $gmf =  $estudio->condicionck != null ? intval($estudio->condicionck->gmf) : '0')
						{{ format($gmf) }}
					</span>
				</div>			
			</div>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="span_total_ck">Total Cr&eacute;dito</label>
					<span class="form-control" id="span_total_ck">
						{{ format( $estudio->condicionck != null ? intval($estudio->condicionck->total) : '' )}}
					</span>
				</div>			
			</div>	
		
			<input type="hidden" name="estudio" id="estudio" value="{{$estudio->id}}">	
			<input type="hidden" name="total_a_comprar" id="total_a_comprar" value="{{$totalAcomprar}}">	
			<input type="hidden" name="porc_anticipo" id="porc_anticipo" value="{{$anticipo->valor}}">	
			<input type="hidden" name="porc_seguro" id="porc_seguro" value="{{$seguro->valor}}">	
			<input type="hidden" name="cupo_maximo" id="cupo_maximo" value="{{$cupoMaximo}}">	
			
			<input type="hidden" name="seguro_ck" id="seguro_ck" value="{{$estudio->condicionck != null ? intval($estudio->condicionck->seguro * $estudio->condicionck->total ) : ''}}">
			<input type="hidden" name="gmf_ck" id="gmf_ck" value="{{ $gmf }}">
			<input type="hidden" name="cuota_ck" id="cuota_ck" value="{{ $estudio->condicionck != null ? intval($estudio->condicionck->cuota) : '' }}">
			<input type="hidden" name="total_ck" id="total_ck" value="{{ $estudio->condicionck != null ? intval($estudio->condicionck->total) : '' }}">
			
			<button type="button" id="btn_paso6" class="btn btn-primary">Guardar</button>
			
		</form>
		
	</div>		
</div>	