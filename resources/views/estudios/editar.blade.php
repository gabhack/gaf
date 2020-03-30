@extends('layouts.app')

@section('css')
    <link href="{{asset('css/gijgo-combined-1.9.13/css/gijgo.min.css')}}" rel="stylesheet">
@endsection

@section('content')
    <script>
        //data
        var carteras = @json($carteras);
        var dataDB = [...carteras];
        
        //params
        var tiposcliente = @json($tiposcliente);
        var cupos = @json($cupos);

        var extraprima = @json($extraprima);
        var p_x_millon = @json($p_x_millon);
        var iva = @json($iva);

        var sectores = @json($sectores);
        var data_cifin_opts = [...sectores];

        var estado = @json($estadoscartera);
        var estado_opts = [...estado];

        var aliados = @json($aliados);
        var compra_ck_o_aliado_opts = ["NO", ...aliados];

        var calificacion_wab_opts = ["A","B","C","D","E","F","G","H","I","J","K"];
    </script>

    @if (isset($error))
        <div class="modal fade" id="modal-default" style="display: block;">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Información de carteras incompleta</h4>
                </div>
                <div class="modal-body">
                    <p>{{$error}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endif

    <form id="form_guardar" action="/estudios/actualizar" method="POST" enctype="multipart/form-data">
        {!! Form::token() !!}
        <input type="hidden" class="form-control" name="cliente_id" id="cliente_id" value="{{$cliente->id}}">
        <input type="hidden" class="form-control" name="registro_id" id="registro_id" value="{{$registro->id}}">
        <input type="hidden" class="form-control" name="estudio_id" id="estudio_id" value="{{$estudio->id}}">
        <input type="hidden" class="form-control" name="json_carteras" id="json_carteras" value=''>
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
                                <select name="asesor_id" id="asesor" class="custom-select form-control" required>
                                    <option disabled value="">Seleccione uno...</option>
                                    @foreach ($asesores as $asesorgen)
                                        <option value="{{$asesorgen->id}}"<?php echo ($asesorgen->id == $asesor->id ? ' selected' : '') ?>>{{$asesorgen->nombres}}</option>
                                    @endforeach
                                    <option value="nuevo">--Nuevo Asesor</option>
                                </select>
                                <input id="asesor_custom" class="hidden form-control" type="text" name="nuevo_asesor">
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
                                            <th scope="row">Ingresos Base {{ $asignacionadicional > 0 ? '(+' . mneyformat($asignacionadicional) . ' de AA)' : '' }}</th>
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
                                            <th scope="row">Cupo Libre inversión potencial</th>
                                            <td><input class="text-center input-{{$cupos['libreInversion']['color']}}" type="text" disabled name="cupo_inversion" id="cupo_inversion" value="<?php echo mneyformat($cupos['libreInversion']['valor']) ?>"></td>
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
            
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>Carteras por comprar</b></div>
                    <div class="panel-body">
                        <button type="button" id="btnAgregarFila" class="btn btn-primary">Agregar cartera</button><br><br>
                        <table id="grid" class="table table-hover table-condensed table-bordered">
                        </table>
                    </div>
                </div>
            </div>

            {{-- Seccion Condiciones Te Recuperamos --}}
            <div id="panel_tr" class="col-md-12 hidden">
                <div class="panel panel-primary">
                <div class="panel-heading">Condiciones Te Recuperamos <span class="tipo-cliente pull-right">Tipo de cliente: <span id="valor-tipo-cliente"></span></span> </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <div class="row">
                                    <div class="col-md-7 text-right">
                                        <label class="label-consulta" for="pad">Costo certificados:</label>
                                    </div>
                                    <div class="col-md">
                                        <input class="form-control w-100 text-center" type="text" name="costo_certificados" id="costo_certificados" required value="{{$estudio->condicion->costocertificados}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="row">
                                    <div class="col-md-7 text-right">
                                        <label class="label-consulta" for="pad">Total Carteras a comprar:</label>
                                    </div>
                                    <div class="col-md">
                                        <input class="form-control w-100 text-center" disabled type="text" name="carteras_comprar" id="carteras_comprar" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="row">
                                    <div class="col-md-7 text-right">
                                        <label class="label-consulta" for="pad">Total Servicio(<span id="costo-servicio-tr"></span>%):</label>
                                    </div>
                                    <div class="col-md">
                                        <input class="form-control w-100 text-center" disabled type="text" name="total_servicio" id="total_servicio" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="row">
                                    <div class="col-md-7 text-right">
                                        <label class="label-consulta" for="pad">Total Servicio + Impuestos(19%):</label>
                                    </div>
                                    <div class="col-md">
                                        <input class="form-control w-100 text-center" disabled type="text" name="servicio_impuestos" id="servicio_impuestos" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seccion Condiciones Aliados --}}
            <div id="panel-aliados" class="col-md-12 panel-aliados hidden">
                <div class="panel panel-primary">
                    <div class="panel-heading">Condiciones Aliados Financieros</div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="panel-AF1" class="col-md text-center hidden">
                                <h4><b>Aliado 1</b></h4>
                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5 text-right">
                                                <label class="label-consulta" for="pad">Aliado</label>
                                            </div>
                                            <div class="col-md">
                                                <select class="form-control" name="AF1[id]" id="aliadof1">
                                                    <option disabled value="">Seleccione uno...</option>
                                                    @foreach ($aliadosCompleto as $aliadoCompleto)
                                                        @if ($aliadoCompleto->tipo_aliado == 1)
                                                            <option value="{{$aliadoCompleto->id}}"{{ isset($aliadosusados[1]) ? ($aliadoCompleto->id == $aliadosusados[1]['id'] ? ' selected' : '') : '' }}>{{$aliadoCompleto->aliado}}</option>                                               
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5 text-right">
                                                <label class="label-consulta" for="pad">Carteras a comprar</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control text-right" type="text" name="carteras_a_comprar_af1" id="carteras_a_comprar_af1" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5 text-right">
                                                <label class="label-consulta" for="pad">Tasa %</label>
                                            </div>
                                            <div class="col-md">
                                                <select class="form-control" name="AF1[tasa]" id="AF1_tasa">
                                                    <option value="" >Seleccione uno...</option>
                                                    @for ($i = 1; $i <= 3; $i+=.1)
                                                        <option value="{{ number_format($i, 3) }}"{{ isset($aliadosusados[1]) ? (number_format($i, 3) == $aliadosusados[1]['condiciones']->tasa ? ' selected' : '') : '' }}>{{ number_format($i, 3) }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5 text-right">
                                                <label class="label-consulta" for="pad">Plazo</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control" type="text" name="AF1[plazo]" id="AF1_plazo" value="{{ isset($aliadosusados[1]) ? $aliadosusados[1]['condiciones']->plazo : '60' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5 text-right">
                                                <label class="label-consulta" for="pad">Costos (%)</label>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" name="AF1[costos]" id="AF1_costos">
                                                    <option value="" >Seleccione uno...</option>
                                                    @for ($i = 5; $i <= 9; $i++)
                                                        <option value="{{ $i }}"{{ isset($aliadosusados[1]) ? (number_format($i, 3) == $aliadosusados[1]['condiciones']->costo ? ' selected' : '') : '' }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control text-right" type="text" name="AF1[costos_valor]" id="AF1_costos_valor" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5 text-right">
                                                <label class="label-consulta" for="pad">Seguro</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control text-right" type="text" name="AF1_seguro" id="AF1_seguro" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5 text-right">
                                                <label class="label-consulta" for="pad">GMF</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control text-right" type="text" name="AF1_GMF" id="AF1_GMF" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-5 text-right">
                                                <label class="label-consulta" for="pad">IVA</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control text-right" type="text" name="AF1_iva" id="AF1_iva" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <label class="label-consulta" for="pad">Total Crédito</label>
                                            </div>
                                            <div class="col-md-12">
                                                <input class="form-control text-center font-weight-bold" type="text" name="AF1_valor_credito" id="AF1_valor_credito" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <label class="label-consulta" for="pad">Cupo máx.</label>
                                            </div>
                                            <div class="col-md-12">
                                                <input class="form-control text-center font-weight-bold" type="text" name="AF1_cupo_max" id="AF1_cupo_max" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <label class="label-consulta" for="pad">Cuota + Seguro</label>
                                            </div>
                                            <div class="col-md-12">
                                                <input class="form-control text-center font-weight-bold" type="text" name="AF1_cuota" id="AF1_cuota" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="panel-AF2" class="col-md text-center hidden">
                                <h4><b>Aliado 2</b></h4>
                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md text-right">
                                                <label class="label-consulta" for="pad">Aliado</label>
                                            </div>
                                            <div class="col-md">
                                                <select class="form-control" name="AF2[id]" id="aliadof2">
                                                    <option disabled value="">Seleccione uno...</option>
                                                    @foreach ($aliadosCompleto as $aliadoCompleto)
                                                        @if ($aliadoCompleto->tipo_aliado == 2)
                                                            <option value="{{$aliadoCompleto->id}}"{{ isset($aliadosusados[2]) ? ($aliadoCompleto->id == $aliadosusados[2]['id'] ? ' selected' : '') : '' }}>{{$aliadoCompleto->aliado}}</option>                                               
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md text-right">
                                                <label class="label-consulta" for="pad">Carteras a comprar</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control" type="text" name="carteras_a_comprar_af2" id="carteras_a_comprar_af2" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md text-right">
                                                <label class="label-consulta" for="pad">Factor x millón</label>
                                            </div>
                                            <div class="col-md">
                                                <select class="form-control" name="AF2[factor_x_millon]" id="AF2_factor_x_millon">
                                                    <option value="" >Seleccione uno...</option>
                                                    @for ($i = 0.017; $i <= 0.027; $i+=.0005)
                                                        <option value="{{ number_format($i, 4) }}"{{ isset($aliadosusados[2]) ? (number_format($i, 4) == $aliadosusados[2]['condiciones']->factor ? ' selected' : '') : '' }}>{{ number_format($i, 4) }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md text-right">
                                                <label class="label-consulta" for="pad">Plazo</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control" type="text" name="AF2[plazo]" id="AF2_plazo" value="{{ isset($aliadosusados[2]) ? $aliadosusados[2]['condiciones']->plazo : '120' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md text-right">
                                                <label class="label-consulta" for="pad">Cupo Máximo</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control" type="text" name="AF2_cupo_max" id="AF2_cupo_max" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md text-right">
                                                <label class="label-consulta" for="pad">Cuota</label>
                                            </div>
                                            <div class="col-md">
                                                <input class="form-control" type="text" name="AF2[cuota]" id="AF2_cuota" value="{{ isset($aliadosusados[1]) ? $aliadosusados[2]['condiciones']->cuota : '0' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <label class="label-consulta" for="pad">Monto a prestar</label>
                                            </div>
                                            <div class="col-md-12">
                                                <input class="form-control text-center font-weight-bold" type="text" name="AF2_monto_prestar" id="AF2_monto_prestar" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <label class="label-consulta" for="pad">Monto Máx.</label>
                                            </div>
                                            <div class="col-md-12">
                                                <input class="form-control text-center font-weight-bold" type="text" name="AF2_monto_max" id="AF2_monto_max" value="" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <label class="label-consulta" for="pad">Saldo al cliente</label>
                                            </div>
                                            <div class="col-md-12">
                                                <input class="form-control text-center font-weight-bold" type="text" name="AF2_saldo" id="AF2_saldo" value="" disabled>
                                            </div>
                                        </div>
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

@section('js')
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('css/gijgo-combined-1.9.13/js/gijgo.min.js')}}"></script>
    <script src="{{asset('js/TablaCarteras.js')}}"></script>
@endsection