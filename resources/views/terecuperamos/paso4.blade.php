<div class="panel panel-default">
	<div class="panel-heading">An&aacute;lisis Centrales de Riesgo</div>
	<div class="panel-body">								
		<form method="post" name="frm_paso1" id="frm_paso4">
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="calificacion_data">Calificaci&oacute;n DATACREDITO</label>
					<select class="form-control" name="calificacion_data" id="calificacion_data">
						<option value="">-Seleccione-</option>
						@foreach(calificaciones() as $key => $calif)
							<option value="{{$key}}" {{$key == $estudio->central->calificacion_data ? 'selected="selected"' : ''}} >{{$calif}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="puntaje_data">Puntaje DATACREDITO</label>
					<input type="number" class="form-control" name="puntaje_data" id="puntaje_data" placeholder="Puntaje DATACREDITO" value="{{$estudio->central->puntaje_data}}">
				</div>
			</div>	
			
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="calificacion_sifin">Calificaci&oacute;n SIFIN</label>
					<select class="form-control" name="calificacion_sifin" id="calificacion_sifin">
						<option value="">-Seleccione-</option>
						@foreach(calificaciones() as $key => $calif)
							<option value="{{$key}}" {{$key == $estudio->central->calificacion_sifin ? 'selected="selected"' : ''}} >{{$calif}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="puntaje_sifin">Puntaje SIFIN</label>
					<input type="number" class="form-control" name="puntaje_sifin" id="puntaje_sifin" placeholder="Puntaje SIFIN" value="{{$estudio->central->puntaje_sifin}}">
				</div>
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="aliados">Libranza Firmada Por</label>
					<select class="form-control" name="aliados" id="aliados">
						<option value="">-Seleccione-</option>
						@foreach($aliados as $aliado)
							<option value="{{$aliado->id}}" {{$aliado->id == $estudio->central->aliados_id ? 'selected="selected"' : ''}} >{{$aliado->aliado}}</option>
						@endforeach
					</select>
				</div>			
			</div>	
			
			<input type="hidden" name="estudio" id="estudio" value="{{$estudio->id}}">
			
			<button type="button" id="btn_paso4" class="btn btn-primary">Guardar</button>
		</form>
	</div>		
</div>	