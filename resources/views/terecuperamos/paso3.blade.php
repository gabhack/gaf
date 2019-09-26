<div class="panel panel-default">
	<div class="panel-heading">Carteras a Comprar</div>
	<div class="panel-body">
		<form method="post" name="frm_paso3" id="frm_paso3">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="entidad">Entidad</label>
					<input type="number" class="paso3 form-control" name="entidad" id="entidad" placeholder="Entidad">
				</div>
				<div class="form-group col-md-4">
					<label for="sector">Sector</label>
					<select class="paso3 form-control" name="sector" id="sector">
						<option value="">-Seleccione-</option>
						@foreach($sectores as $sector)
							<option value="{{$sector->id}}">{{$sector->sector}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="estado_cartera">Estado</label>
					<select class="paso3 form-control" name="estado_cartera" id="estado_cartera">
						<option value="">-Seleccione-</option>
						@foreach($estados_cartera as $estado)
							<option value="{{$estado->id}}">{{$estado->estado}}</option>
						@endforeach
					</select>
				</div>
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="comprado_por">Comprado por</label>
					<select class="paso3 form-control" name="comprado_por" id="comprado_por">
						<option value="">-Seleccione-</option>
						@foreach(compradores() as $key => $comprador)
							<option value="{{$key}}">{{$comprador}}</option>
						@endforeach						
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="cuota">Cuota</label>
					<input type="number" class="paso3 form-control" name="cuota" id="cuota" placeholder="Cuota">
				</div>
				<div class="form-group col-md-4">
					<label for="saldoCartera">Saldo Cartera Centrales</label>
					<input type="number" class="paso3 form-control" name="saldoCartera" id="saldoCartera" placeholder="Saldo Cartera Centrales">
				</div>			
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="valor_inicial">Valor Inicial</label>
					<input type="number" class="paso3 form-control" name="valor_inicial" id="valor_inicial" placeholder="Valor Inicial">
				</div>
				<div class="form-group col-md-4">
					<label for="dcto_logrado">Descuento Logrado</label>
					<input type="text" class="paso3 form-control" name="dcto_logrado" id="dcto_logrado" placeholder="Descuento Logrado">
				</div>
				<div class="form-group col-md-4">
					<label for="porc_negociado">Porcentaje Negociado</label>
					<input type="number" step=".001" class="paso3 form-control" name="porc_negociado" id="porc_negociado" placeholder="Porcentaje Negociado">
				</div>				
			</div>	
			
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="spanSaldoNegociado">Saldo Cartera Negociada</label>
					<span class="form-control" id="spanSaldoNegociado"></span>
					
				</div>
				<div class="form-group col-md-4">
					<label for="fecha_vence">Fecha de Vencimiento</label>
					<input type="date" class="paso3 form-control" name="fecha_vence" id="fecha_vence" placeholder="Fecha de Vencimiento">
				</div>			
			</div>	
			
			<input type="hidden" name="estudio" id="estudio" value="{{$estudio->id}}">	
			<input type="hidden" name="cartera" id="cartera">
			<input type="hidden" class="paso3" name="saldoNegociado" id="saldoNegociado">
			<button type="button" id="btn_paso3" class="btn btn-primary">Guardar</button>
		</form>
		
		<br />
		
		<div id="carteras">
			@include('terecuperamos/carteras')
		</div>
	</div>		
</div>	