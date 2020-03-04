@extends('layouts.app')

@section('content')
    <form id="form_guardar" action="/estudios/guardar" method="POST" enctype="multipart/form-data">
        {!! Form::token() !!}
        <input type="hidden" class="form-control" name="cliente_id" id="cliente_id" value="<?php echo $cliente->id ?>">
        <input type="hidden" class="form-control" name="registro_id" id="registro_id" value="<?php echo $ultimoregistro->id ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group mr-2 float-right" role="group">
                    <a type="button" class="btn btn-secondary" href="{{url()->previous()}}"><i class="fa fa-arrow-left"></i> Atrás</a>
                    <input class="btn btn-success" type="submit" value="Guardar">
                </div>
                <h3>Cliente: {{$cliente->nombres}}</h3>
            </div>
            <div class="col-md-8">
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
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Asesor asignado</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="asesor_id" id="asesor" class="custom-select form-control" required>
                                    <option selected disabled value="">Seleccione uno...</option>
                                    @foreach ($asesores as $asesor)
                                        <option value="{{$asesor->id}}">{{$asesor->nombres}}</option>
                                    @endforeach
                                    <option value="nuevo">--Nuevo Asesor</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <input id="asesor_custom" class="hidden form-control" type="text" name="nuevo_asesor" id="nuevo_asesor">
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

            @if ($ultimoregistro)
                <div class="col-md-12">
                    <h3>Información de Comprobante de pago (Periodo {{$ultimoregistro->periodo}})</h3>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>Ingresos aplicados</b></div>
                        <div class="panel-body">
                            @if (ingresos_por_registro($ultimoregistro->id))
                                <table class="table table-hover table-striped table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Concepto</th>
                                            <th class="text-center">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (ingresos_por_registro($ultimoregistro->id) as $ingreso)
                                            <tr>
                                                <td>{{$ingreso->concepto}}</td>
                                                <td class="text-center">{{mneyformat($ingreso->valor)}}</td>                                          
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td><b>TOTAL</b></td>
                                            <td style="text-align: center;"><b>{{mneyformat(totalizar_concepto(ingresos_por_registro($ultimoregistro->id)))}}</b></td>
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
                            @if ($ultimoregistro)
                                <table class="table table-hover table-striped table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Concepto</th>
                                            <th class="text-center">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (descuentos_por_registro($ultimoregistro->id) as $descuento)
                                            <tr>
                                                <td>{{$descuento->concepto}}</td>
                                                <td class="text-center">{{mneyformat($descuento->valor)}}</td>                                          
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td><b>TOTAL</b></td>
                                            <td style="text-align: center;"><b>{{mneyformat(totalizar_concepto(descuentos_por_registro($ultimoregistro->id)))}}</b></td>
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
            <div class="col-md-12">
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
            <div class="col-md-12">
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
                            <p>No hay mensajes de liquidación</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12">
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
        </div>
    </form>

@endsection
