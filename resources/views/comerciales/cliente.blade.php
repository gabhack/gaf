	<script src="{{asset('js/validaciones/Ciudades.js')}}"></script>

	<div class="panel panel-default">
		<div class="panel-heading">Informaci&oacute;n del Cliente</div>
		<div class="panel-body">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="pagad">Documento</label>
						<span class="form-control">{{$documento}}</span>
						<input type="hidden" name="documento" id="documento" value="{{$documento}}">
					</div>
					<div class="form-group col-md-4">
						<label for="nombres">Nombres</label>
						<input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombre" value="{{$info->cliente->nombres}}">
					</div>
					<div class="form-group col-md-4">
						<label for="apellidos">Apellidos</label>
						<input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos" value="{{$info->cliente->apellidos}}">
					</div>						
				</div>
				
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="fecha_nto">Fecha de Nacimiento</label>
						<input type="date" class="form-control" name="fecha_nto" id="fecha_nto" placeholder="Fecha de Nacimiento" value="{{$info->cliente->fechanto}}">
					</div>
					<div class="form-group col-md-4">
						<label for="estado_civil">Estado Civil</label>
						<select class="form-control" name="estado_civil" id="estado_civil">
							<option value="">-Seleccione-</option>
							@foreach(estados_civiles() as $key => $estado)
								<option value="{{$key}}" {{$key == $info->cliente->estado_civil ? 'selected="selected"' : ''}}>{{$estado}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="direccion">Direcci&oacute;n</label>
						<input type="text" class="form-control" name="direccion" id="direccion" placeholder="Direcci&oacute;n" value="{{$info->cliente->direccion}}">
					</div>						
				</div>
				
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="depto">Departamento</label>
						<select class="form-control" name="depto" id="depto">
							<option value="">-Seleccione-</option>
							@foreach($departamentos as $dpto)
								<option value="{{$dpto->id}}"{{$dpto->id == $info->cliente->ciudad->departamentos_id ? 'selected="selected"' : ''}}>{{$dpto->departamento}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="ciudad">Ciudad</label>
						<select class="form-control" name="ciudad" id="ciudad">
							<option value="">-Seleccione-</option>							
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="correo">Correo</label>
						<input type="email" class="form-control" name="correo" id="correo" placeholder="Correo" value="{{$info->cliente->correo}}">
					</div>						
				</div>
				
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="telefono">Tel&eacute;fono</label>
						<input type="number" class="form-control" name="telefono" id="telefono" placeholder="Tel&eacute;fono" value="{{$info->cliente->telefono}}">
					</div>
					<div class="form-group col-md-4">
						<label for="basico">B&aacute;sico</label>
						<input type="text" class="form-control" name="basico" id="basico" placeholder="B&aacute;sico">
					</div>
					<div class="form-group col-md-4">
						<label for="cupo_disponible">Cupo Disponible</label>
						<input type="number" class="form-control" name="cupo_disponible" id="cupo_disponible" placeholder="Cupo Disponible" value="{{$info->cupo_disponible}}">
					</div>						
				</div>
				
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="compra_cartera">Valor Compra Cartera</label>
						<input type="number" class="form-control" name="compra_cartera" id="compra_cartera" placeholder="Valor Compra Cartera">
					</div>
					<div class="form-group col-md-4">
						<label for="sector">Cr&eacute;dito Libre Inversi&oacute;n</label>
						<input type="number" class="form-control" name="libre_inversion" id="libre_inversion" placeholder="libre_inversion">
					</div>
					<div class="form-group col-md-4">
						<label for="observaciones">Observaciones</label>
						<textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones"></textarea>
					</div>						
				</div>
				
				<input type="hidden" name="base" id="base" value="{{$info->id}}">
				<input type="hidden" name="cliente" id="cliente" value="{{$info->cliente->id}}">
				<input type="hidden" name="city" id="city" value="{{$info->cliente->ciudades_id}}">
				<button type="submit" class="btn btn-primary">Guardar</button>			
		</div>
	</div>