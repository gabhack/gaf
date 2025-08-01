@extends('layouts.app2')

@section('title')
	Consulta {{LabelTipoConsulta($consulta->tipo_consulta)}}
@endsection

@section('header-content')
	Consulta {{LabelTipoConsulta($consulta->tipo_consulta)}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('consultas') }}">Consulta AMI®</a></li>
    <li class="breadcrumb-item active">Cédula: {{ $cliente->documento }}</li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <!-- <a class="btn btn-primary" href="{{ url()->previous()}} "><< Atrás</a> -->
                <div class="d-flex align-items-end">
                    <img src="/img/avatar-img.svg" width="90" class="mr-3">
                    <h2 class="h3 text-black-pearl font-weight-exbold d-inline-block mb-0">MARIA EUGENIA</h2>
                </div>
                <button type="button" onclick="print();" class="btn btn-black-pearl px-3">
                    <span>Descargar PDF</span>
                    <download-icon></download-icon>
                </button>
            </div>
        </div>
        <div id="consulta-container" class="row">
            <div class="col-7">
                <div class="panel">
                    <div class="panel-heading">
                        <b>Información básica</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">Nombres:</b>
                                <p class="panel-value">{{ $cliente->nombres == '' ? 'No proporcionado' : $cliente->nombres }}</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Apellidos:</b>
                                <p class="panel-value">{{ $cliente->apellidos == '' ? 'No proporcionado' : $cliente->apellidos }}</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Tipo de Documento:</b>
                                <p class="panel-value">{{ $cliente->tipodocumento == '' ? 'No proporcionado' : tipos_documento()[$cliente->tipodocumento] }}</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Documento:</b>
                                <p class="panel-value">{{ $cliente->documento == '' ? 'No proporcionado' : $cliente->documento }}</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Sexo:</b>
                                <p class="panel-value">{{ $cliente->sexo == '' ? 'No proporcionado' : sexos()[$cliente->sexo] }}</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Fecha de nacimiento:</b>
                                <p class="panel-value">
                                    @if ($cliente->fechanto == '')
                                        No proporcionado
                                    @else
                                        {{ $cliente->fechanto }} (<b>{{ $viabilidad['edad']->y }} años y {{ $viabilidad['edad']->m }} meses</b>)
                                    @endif
                                </p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Ciudad:</b>
                                <p class="panel-value">{{ $cliente->ciudad == '' ? 'No proporcionado' : $cliente->ciudad->ciudad }}</p>
                            </div> <div class="col-6">
                                <b class="panel-label">Estado Civil:</b>
                                <p class="panel-value">{{ $cliente->estado_civil == '' ? 'No proporcionado' : estados_civiles()[$cliente->estado_civil] }}</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Centro de Costo:</b>
                                <p class="panel-value">{{ $cliente->centro_costo == '' ? 'No proporcionado' : $cliente->centro_costo }}</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Cargo:</b>
                                <p class="panel-value">{{ $cliente->cargo == '' ? 'No proporcionado' : $cliente->cargo }}</p>
                            </div>
                            <div class="col-md-6">
                                <b class="panel-label">Tipo de Contratación:</b>
                                <p class="panel-value">{{ $cliente->tipo_contratacion == '' ? 'No proporcionado' : $cliente->tipo_contratacion }}</p>
                            </div>
                            <div class="col-md-6">
                                <b class="panel-label">Vinculación:</b>
                                <p class="panel-value">{{ $cliente->vinculacion == '' ? 'No proporcionado' : vinculaciones()[$cliente->vinculacion] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="panel">
                    <div class="panel-heading">
                        <b>Información de Contacto</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-6">
                                <b class="panel-label">Teléfono:</b>
                                <p class="panel-value">No proporcionado</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Celular:</b>
                                <p class="panel-value">No proporcionado</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Dirección:</b>
                                <p class="panel-value">No proporcionado</p>
                            </div>
                            <div class="col-6">
                                <b class="panel-label">Correo electrónico:</b>
                                <p class="panel-value">No proporcionado</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <b>Capacidad de pago ({{$registro->pagaduria->pagaduria}} - {{$registro->periodo}})</b>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Ingresos Base</th>
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
                                            <th scope="row">Cupo Libre Inversión</th>
                                            <td><input class="text-center input-{{$cupos['libreInversion']['color']}}" type="text" disabled name="cupo_inversion" id="cupo_inversion" value="<?php echo mneyformat($cupos['libreInversion']['valor']) ?>"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Cupo Compras de Cartera</th>
                                            <td><input class="text-center input-{{$cupos['compraCartera']['color']}}" type="text" disabled name="compra_cartera" id="compra_cartera" value="<?php echo mneyformat($cupos['compraCartera']['valor']) ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($consulta->tipo_consulta == 2 || $consulta->tipo_consulta == 3)

                @if ($registro)
                    <div class="col-md-12">
                        <h3>INFORMACIÓN FINANCIERA ({{$registro->pagaduria->pagaduria}} - {{$registro->periodo}})</h3>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>INGRESOS</b></div>
                            <div class="panel-body">
                                @if (ingresos_por_registro($registro->id))
                                    <table class="table table-hover table-striped table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Entidad Pagadora</th>
                                                <th class="text-center">Concepto</th>
                                                <th class="text-center">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (ingresos_por_registro($registro->id) as $ingreso)
                                                <tr>
                                                    <td>{{$registro->pagaduria->pagaduria}}</td>
                                                    <td>{{$ingreso->concepto}}</td>
                                                    <td class="text-center">{{mneyformat($ingreso->valor)}}</td>                                          
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
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
                            <div class="panel-heading"><b>EGRESOS</b></div>
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
                @if ($consulta->tipo_consulta == 3)
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
                    {{-- <div class="col-md-12">
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
                    </div> --}}
                @endif
            @endif
        </div>
    </div>

@endsection



@section('css')
    <link href="{{asset('libs/print/print.min.css')}}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{asset('libs/print/print.min.js')}}"></script>
    <script>
        function print() {
            printJS({
                printable: 'consulta-container',
                type: 'html',
                css: 'css/styles.css',
                scanStyles: false
            });
        }
    </script>

@endsection

