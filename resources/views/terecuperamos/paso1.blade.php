<div class="panel panel-default">
	<div class="panel-heading">Datos Personales</div>
	<div class="panel-body">								
		<form method="post" name="frm_paso1" id="frm_paso1">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="fecha">Fecha Diligenciamiento</label>
					<span class="form-control">{{$estudio->fecha}}</span>
				</div>
				<div class="form-group col-md-4">
					<label for="tipo_doc">Tipo de Documento</label>
					<select class="form-control" name="tipo_doc" id="tipo_doc">
						<option value="">-Seleccione-</option>
						@foreach(tipos_documento() as $key => $estado)
							<option value="{{$key}}" {{$key == $estudio->cliente->tipodocumento ? 'selected="selected"' : ''}}>{{$estado}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="documento">Documento</label>
					<span class="form-control" id="documento">{{format($estudio->cliente->documento)}}</span>
				</div>
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="nombres">Nombres</label>
					<input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombres" value="{{$estudio->cliente->nombres}}">
				</div>
				<div class="form-group col-md-4">
					<label for="apellidos">Apellidos</label>
					<input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos" value="{{$estudio->cliente->apellidos}}">
				</div>
				<div class="form-group col-md-4">
					<label for="sexo">Sexo</label>
					<select class="form-control" name="sexo" id="sexo">
						<option value="">-Seleccione-</option>
						@foreach(sexos() as $key => $sexo)
							<option value="{{$key}}" {{$key == $estudio->cliente->sexo ? 'selected="selected"' : ''}}>{{$sexo}}</option>
						@endforeach
					</select>
				</div>
				
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="fecha_nto">Fecha de Nacimiento</label>
					<input type="date" class="form-control" name="fecha_nto" id="fecha_nto" placeholder="Fecha de Nacimimento" value="{{$estudio->cliente->fechanto}}">
				</div>
				<div class="form-group col-md-4">
					<label for="edad">Edad</label>
					<span class="form-control">
						@php( $cumpleanos = new DateTime($estudio->cliente->fechanto . '00:00:00') )
						@php( $hoy = new DateTime() )
						@php( $annos = $hoy->diff($cumpleanos) )
						{{ $annos->y }}
					</span>
				</div>
				<div class="form-group col-md-4">
					<label for="direccion">Direcci&oacute;n</label>
					<input type="text" class="form-control" name="direccion" id="direccion" placeholder="Direcci&oacute;n" value="{{$estudio->cliente->direccion}}">
				</div>
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="telefono">Tel&eacute;fono</label>
					<input type="number" class="form-control" name="telefono" id="telefono" placeholder="Tel&eacute;fono" value="{{$estudio->cliente->telefono}}">
				</div>
				<div class="form-group col-md-4">
					<label for="celular">Celular</label>
					<input type="text" class="form-control" name="celular" id="celular" placeholder="Celular" value="{{$estudio->cliente->celular}}">
				</div>
				<div class="form-group col-md-4">
					<label for="email">Correo</label>
					<input type="email" class="form-control" name="email" id="email" placeholder="Correo" value="{{$estudio->cliente->correo}}">
				</div>
			</div>	
		</div>	
		
		<div class="panel-heading">Datos de Pagadur&iacute;a</div>
		<div class="panel-body">								
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="pagaduria">Pagadur&iacute;a</label>
					<span class="form-control">{{$estudio->base->pagaduria->pagaduria}}</span>
				</div>
				<div class="form-group col-md-4">
					<label for="fecha_ingreso">Fecha de Ingreso a Pagadur&iacute;a</label>
					<input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" placeholder="Fecha de Ingreso a Pagadur&iacute;a" value="{{$estudio->base->fecha_ingreso}}">
				</div>
				<div class="form-group col-md-4">
					<label for="gaf">Consultado en GAF</label>
					<select class="form-control" name="gaf" id="gaf">
						<option value="">-Seleccione-</option>
						<option value="S" {{$estudio->adicional->gaf == 'S' ? 'selected="selected"' : ''}}>SI</option>
						<option value="N" {{$estudio->adicional->gaf == 'N' ? 'selected="selected"' : ''}}>NO</option>
					</select>
				</div>			
			</div>
			
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="vinculacion">Vinculaci&oacute;n</label>
					<select class="form-control" name="vinculacion" id="vinculacion">
						<option value="">-Seleccione-</option>
						@foreach(estados_cargos() as $key => $estado)
							<option value="{{$key}}" {{$estudio->adicional->cargo->estado == $key ? 'selected="selected"' : ''}}>{{$estado}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="cargo">Cargo</label>
					<select class="form-control" name="cargo" id="cargo">
						<option value="">-Seleccione-</option>					
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="tipo_nombramiento">Tipo de Nombramiento</label>
					<input type="text" class="form-control" name="tipo_nombramiento" id="tipo_nombramiento" placeholder="Tipo de Nombramiento">
				</div>
			</div>
			
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="mes_desprendible">&Uacute;ltimo desprendible</label>
					<select class="form-control" name="mes_desprendible" id="mes_desprendible">
						<option value="">-Seleccione-</option>
						@foreach(meses() as $key => $mes)
							<option value="{{$key}}" {{$estudio->mes_desprendible == $key ? 'selected="selected"' : ''}}>{{$mes}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="ano_desprendible">A&ntilde;o desprendible</label>
					<select class="form-control" name="ano_desprendible" id="ano_desprendible">
						<option value="">-Seleccione-</option>
						@for($i = date('Y') - 4; $i <= date('Y'); $i++)
							<option value="{{$i}}" {{$estudio->ano_desprendible == $i ? 'selected="selected"' : ''}}>{{$i}}</option>
						@endfor
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="historial_emabrgos">Historial de Embargos Desprendible</label>
					<input type="text" class="form-control" name="historial_emabrgos" id="historial_emabrgos" placeholder="Historial de Embargos Desprendible">
				</div>
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="tipo_embargo">Tipo de Embargo Activo</label>
					<select class="form-control" name="tipo_embargo" id="tipo_embargo">
						<option value="">-Seleccione-</option>
						@foreach($tipo_embargos as $tipo)
							<option value="{{$tipo->id}}" {{$estudio->tiposembargos_id == $tipo->id ? 'selected="selected"' : ''}}>{{$tipo->tipo}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<label for="clave">Clave</label>
					<input type="text" class="form-control" name="clave" id="clave" placeholder="Clave" value="{{$estudio->adicional->clave}}">
				</div>
				<div class="form-group col-md-4">
					<label for="foncolpuertos">Foncolpuertos</label>
					<select class="form-control" name="foncolpuertos" id="foncolpuertos">
						<option value="">-Seleccione-</option>
						<option value="S" {{$estudio->adicional->foncolpuertos == 'S' ? 'selected="selected"' : ''}}>SI</option>
						<option value="N" {{$estudio->adicional->foncolpuertos == 'N' ? 'selected="selected"' : ''}}>NO</option>
					</select>
				</div>			
			</div>	

			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="sector">Descuentos en Cola</label>
					<span class="form-control">{{ $dctos_aplicados == 0 ? 'NO' : 'SI' }}</span>
				</div>
				<div class="form-group col-md-4">
					<label for="otros_ingresos">Otros Ingresos</label>
					<span class="form-control">{{$otros_ingresos != null ? $otros_ingresos->pagaduria->pagaduria : 'N/A'}}</span>
				</div>
			</div>	
			
			<input type="hidden" name="position" id="position" value="{{$estudio->adicional->cargos_id}}">
			<input type="hidden" name="estudio" id="estudio" value="{{$estudio->id}}">
			<input type="hidden" name="cliente" id="cliente" value="{{$estudio->cliente->id}}">
			<input type="hidden" name="base" id="base" value="{{$estudio->base->id}}">
			<input type="hidden" name="pagaduria" id="pagaduria" value="{{$estudio->base->pagaduria->id}}">
			
			<button type="button" id="btn_paso1" class="btn btn-primary">Guardar</button>
		</form>
	</div>	
</div>	