<div class="panel panel-default">
	<div class="panel-heading">C&aacute;lculo de Capacidad</div>
	<div class="panel-body">
		<form method="post" name="frm_paso1" id="frm_paso2">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="ingresos">Ingresos</label>
					<input type="number" class="form-control" name="ingresos" id="ingresos" placeholder="Ingresos" value="{{$estudio->capacidad->ingresos}}">
				</div>
				<div class="form-group col-md-4">
					<label for="spanAdicional">Asignaci&oacute;n Adicional AA</label>
					<span class="form-control" id="spanAdicional"></span>
				</div>
				<div class="form-group col-md-4">
					<label for="span_aportes">Valor Aportes</label>
					<span class="form-control" id="span_aportes"></span>
				</div>
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="total_dctos">Total Descuentos Incluidos Aportes</label>
					<input type="number" class="form-control" name="total_dctos" id="total_dctos" placeholder="Total Descuentos Incluidos Aportes" value="{{$estudio->capacidad->descuentos}}">
				</div>
				<div class="form-group col-md-4">
					<label for="libreInversion">Cupo Libre Inversi&oacute;n</label>
					<span class="form-control" id="libreInversion"></span>
				</div>
				<div class="form-group col-md-4">
					<label for="compraCartera">Cupo Compra de Cartera</label>
					<span class="form-control" id="compraCartera"></span>
				</div>			
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="decision_capacidad">Decisi&oacute;n</label>
					<select class="form-control" name="decision_capacidad" id="decision_capacidad">
						<option value="">-Seleccione-</option>
						@foreach(decisiones_capacidades() as $key => $desicion)
							<option value="{{$key}}" {{$key == $estudio->capacidad->decision ? 'selected="selected"' : '' }}>{{$desicion}}</option>
						@endforeach
					</select>
				</div>			
			</div>	
			
			<input type="hidden" name="estudio" id="estudio" value="{{$estudio->id}}">
			
			<input type="hidden" name="libre_inversion" id="libre_inversion">
			<input type="hidden" name="compra_cartera" id="compra_cartera">
			<input type="hidden" name="adicional" id="adicional">
			
			<input type="hidden" name="asiganacion" id="asignacion">
			<input type="hidden" name="asignacion_adicional" id="asignacion_adicional">
			<input type="hidden" name="aportes" id="aportes">
			<input type="hidden" name="valor_aportes" id="valor_aportes">
			
			<button type="button" id="btn_paso2" class="btn btn-primary">Guardar</button>
		</form>
	</div>		
</div>	

