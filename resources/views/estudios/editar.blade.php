@extends('layouts.app')

@section('content')
    <form id="form_guardar" action="/estudios/actualizar" method="POST" enctype="multipart/form-data">
        {!! Form::token() !!}
        <input type="hidden" class="form-control" name="cliente_id" id="cliente_id" value="<?php echo $cliente->id ?>">
        <input type="hidden" class="form-control" name="registro_id" id="registro_id" value="<?php echo $registro->id ?>">
        <input type="hidden" class="form-control" name="estudio_id" id="estudio_id" value="<?php echo $estudio->id ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group mr-2 float-right" role="group">
                    <a type="button" class="btn btn-secondary" href="{{url('estudios')}}"><i class="fa fa-arrow-left"></i> Atrás</a>
                    <input class="btn btn-success" type="submit" value="Actualizar">
                </div>
                <h3>Cliente: {{$cliente->nombres}}</h3>
            </div>
            <div class="col-md-7">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Información básica</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Nombres:
                                    <p class="pad">{{ $cliente->nombres == '' ? 'No proporcionado' : $cliente->nombres }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Apellidos:
                                    <p class="pad">{{ $cliente->apellidos == '' ? 'No proporcionado' : $cliente->apellidos }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Tipo de Documento:
                                    <p class="pad">{{ $cliente->tipodocumento == '' ? 'No proporcionado' : $cliente->tipodocumento }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Documento:
                                    <p class="pad">{{ $cliente->documento == '' ? 'No proporcionado' : $cliente->documento }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Sexo:
                                    <p class="pad">{{ $cliente->sexo == 'F' ? 'Femenino' : 'Masculino' }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Fecha de nacimiento:
                                    <p class="pad">{{ $cliente->fechanto == '' ? 'No proporcionado' : $cliente->fechanto }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Ciudad:
                                    <p class="pad">{{ $cliente->ciudad == '' ? 'No proporcionado' : $cliente->ciudad->ciudad }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Estado Civil:
                                    <p class="pad">{{ $cliente->estado_civil == '' ? 'No proporcionado' : $cliente->estado_civil }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Centro de Costo:
                                    <p class="pad">{{ $cliente->centro_costo == '' ? 'No proporcionado' : $cliente->centro_costo }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Cargo:
                                    <p class="pad">{{ $cliente->cargo == '' ? 'No proporcionado' : $cliente->cargo }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Tipo de Contratación:
                                    <p class="pad">{{ $cliente->tipo_contratacion == '' ? 'No proporcionado' : $cliente->tipo_contratacion }}</p>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="label-consulta" for="pad">Grado:
                                    <p class="pad">{{ $cliente->grado == '' ? 'No proporcionado' : $cliente->grado }}</p>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Te Recuperamos</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="label-consulta">Asesor asignado</label>
                            </div>
                            <div class="col-md-7">
                                <select name="asesor_id" class="custom-select form-control" required>
                                    <option disabled value="">Seleccione uno...</option>
                                    @foreach ($asesores as $asesorgen)
                                        <option value="{{$asesorgen->id}}"<?php echo ($asesorgen->id == $asesor->id ? ' selected' : '') ?>>{{$asesorgen->nombres}}</option>
                                    @endforeach
                                    <option value="nuevo">--Nuevo Asesor</option>
                                </select>
                                <input id="asesor_custom" class="hidden form-control" type="text" name="nuevo_asesor" id="nuevo_asesor">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label class="label-consulta">Desición TR</label>
                            </div>
                            <div class="col-md-7">
                                <select name="desiciones" class="custom-select form-control" required>
                                    <option disabled value="">Seleccione uno...</option>
                                    @foreach(decisiones_estudios() as $key => $decision)
                                        <option value="{{$key}}" {{$estudio->decision == $key ? 'selected="selected"' : '' }} >{{$decision}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label class="label-consulta">Observaciones</label>
                            </div>
                            <div class="col-md-7">
                                <textarea class="form-control" type="text" name="observaciones" id="observaciones" maxlength="100">{{$estudio->observaciones}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Capacidad de pago</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Ingresos</th>
                                            <td><input class="text-center" type="text" disabled name="ingresos_base" id="ingresos_base" value="<?php echo mneyformat($sueldocompleto) ?>"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Aportes</th>
                                            <td><input class="text-center" type="text" disabled name="aportes" id="aportes" value="<?php echo mneyformat($aportes) ?>"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Total descuentos</th>
                                            <td><input class="text-center" type="text" disabled name="descuentos" id="descuentos" value="<?php echo mneyformat($totaldescuentos) ?>"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Cupo Libre inversión</th>
                                            <td><input class="text-center input-{{$cupos['libreInversion']['color']}}" type="text" disabled name="cupo_inversion" id="cupo_inversion" value="<?php echo mneyformat($cupos['libreInversion']['valor']) ?>"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Cupo compra de cartera</th>
                                            <td><input class="text-center input-{{$cupos['compraCartera']['color']}}" type="text" disabled name="cupo_compra_cartera" id="cupo_compra_cartera" value="<?php echo mneyformat($cupos['compraCartera']['valor']) ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Información de contacto</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="label-consulta" for="pad">Teléfono:
                                    <p class="pad">{{ $cliente->telefono == '' ? 'No proporcionado' : $cliente->telefono }}</p>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="label-consulta" for="pad">Celular:
                                    <p class="pad">{{ $cliente->celular == '' ? 'No proporcionado' : $cliente->celular }}</p>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="label-consulta" for="pad">Dirección:
                                    <p class="pad">{{ $cliente->direccion == '' ? 'No proporcionado' : $cliente->direccion }}</p>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label class="label-consulta" for="pad">Correo electrónico:
                                    <p class="pad">{{ $cliente->correo == '' ? 'No proporcionado' : $cliente->correo }}</p>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Centrales de riesgo</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <label class="label-consulta" for="pad">Calificación/WAB:
                                    <select name="calif_wab" class="custom-select form-control" required>
                                        <option selected disabled value="">Seleccione uno...</option>
                                        <option disabled value="">Seleccione uno...</option>
                                        @foreach(calificaciones() as $key => $calificacion)
                                            <option value="{{$key}}" {{$estudio->central->calificacion_data == $key ? 'selected="selected"' : '' }} >{{$calificacion}}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="label-consulta" for="pad">Puntaje Datacredito:
                                    <input type="text" name="puntaje_datacredito" id="puntaje_datacredito" required value="{{$estudio->central->puntaje_data}}">
                                </label>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="label-consulta" for="pad">Procesos en contra:
                                    <input type="number" name="proc_en_contra" id="proc_en_contra" placeholder="Opcional" value="{{$estudio->central->proc_en_contra}}" min="1" max="99">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($registro)
                <div class="col-md-12">
                    <h3>Información de Comprobante de pago (Periodo {{$registro->periodo}})</h3>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Ingresos aplicados</b></div>
                        <div class="panel-body">
                            @if (ingresos_por_registro($registro->id))
                                <table class="table table-hover table-striped table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Concepto</th>
                                            <th class="text-center">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (ingresos_por_registro($registro->id) as $ingreso)
                                            <tr>
                                                <td>{{$ingreso->concepto}}</td>
                                                <td class="text-center">{{mneyformat($ingreso->valor)}}</td>                                          
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td><b>TOTAL</b></td>
                                            <td style="text-align: center;"><b>{{mneyformat(totalizar_concepto(ingresos_por_registro($registro->id)))}}</b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            @else
                                <p>No hay registros</p>
                            @endif  
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Descuentos aplicados</b></div>
                        <div class="panel-body">
                            @if ($registro)
                                <table class="table table-hover table-striped table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Concepto</th>
                                            <th class="text-center">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (descuentos_por_registro($registro->id) as $descuento)
                                            <tr>
                                                <td>{{$descuento->concepto}}</td>
                                                <td class="text-center">{{mneyformat($descuento->valor)}}</td>                                          
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td><b>TOTAL</b></td>
                                            <td style="text-align: center;"><b>{{mneyformat(totalizar_concepto(descuentos_por_registro($registro->id)))}}</b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            @else
                                <p>No hay registros</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">Descuentos no aplicados</div>
                    <div class="panel-body">
                            @if ($cliente->descuentosnoaplicados->first())
                            <table class="table table-hover table-striped table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Periodo</th>
                                        <th class="text-center">Valor cuota</th>
                                        <th class="text-center">Inconsistencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->descuentosnoaplicados as $descuento)
                                        <tr>
                                            <td class="text-center">{{$descuento->periodo}}</td>
                                            <td class="text-center">{{mneyformat($descuento->valor_fijo)}}</td>
                                            <td class="text-center">{{$descuento->inconsistencia}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No hay registros</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">Embargos</div>
                    <div class="panel-body">
                            @if ($cliente->embargos->first())
                            <table class="table table-hover table-striped table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Fecha Embargo</th>
                                        <th class="text-center">Doc. Demandante</th>
                                        <th class="text-center">Demandante</th>
                                        <th class="text-center">Motivo</th>
                                        <th class="text-center">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->embargos as $embargo)
                                        <tr>
                                            <td class="text-center">{{$embargo->fecha_embargo}}</td>
                                            <td class="text-center">{{$embargo->documento_demandante}}</td>
                                            <td class="text-center">{{$embargo->nombres_demandante}}</td>
                                            <td class="text-center">{{$embargo->motivo_embargo->motivo}}</td>
                                            <td class="text-center">{{mneyformat($embargo->valor)}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No hay embargos</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">Mensajes de liquidación</div>
                    <div class="panel-body">
                            @if ($cliente->mensajesprecaucion->first())
                            <table class="table table-hover table-striped table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Periodo</th>
                                        <th class="text-center">Tipo de mensaje</th>
                                        <th class="text-center">Mensaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->mensajesprecaucion as $mensaje)
                                        <tr>
                                            <td class="text-center">{{$mensaje->periodo}}</td>
                                            <td class="text-center">{{$mensaje->tipo_mensaje}}</td>
                                            <td class="text-center">{{$mensaje->mensaje}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No hay mensajes de liquidación</p>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $total_carteras = 5000000;
                $total_servicio = $total_carteras*$estudio->condicion->costoservicios/100;
                $total_total = $total_carteras+$total_servicio;
            @endphp

            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Condiciones Te Recuperamos</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="row">
                                    <div class="col-md-7 text-right">
                                        <label class="label-consulta" for="pad">Total Carteras a comprar:</label>
                                    </div>
                                    <div class="col-md">
                                        <input class="w-100 text-center" disabled type="text" name="carteras_comprar" id="carteras_comprar" required value="{{mneyformat($total_carteras)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="row">
                                    <div class="col-md-7 text-right">
                                        <label class="label-consulta" for="pad">Total Servicio({{number_format($estudio->condicion->costoservicios, 0, ',', ' ')}}%):</label>
                                    </div>
                                    <div class="col-md">
                                        <input class="w-100 text-center" disabled type="text" name="carteras_comprar" id="carteras_comprar" required value="{{mneyformat($total_servicio)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="row">
                                    <div class="col-md-7 text-right">
                                        <label class="label-consulta" for="pad">Total Servicio + Impuestos:</label>
                                    </div>
                                    <div class="col-md">
                                        <input class="w-100 text-center" disabled type="text" name="carteras_comprar" id="carteras_comprar" required value="{{mneyformat($total_total)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
