@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary" href="{{url('consultas')}}"><< Atrás</a>
            <h3>Información de: {{$cliente->nombres}}</h3>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Información general</b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="label-consulta" for="pad">Nombres:
                                <p class="pad">{{ $cliente->nombres == '' ? 'No proporcionado' : $cliente->nombres }}</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="label-consulta" for="pad">Apellidos:
                                <p class="pad">{{ $cliente->apellidos == '' ? 'No proporcionado' : $cliente->apellidos }}</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="label-consulta" for="pad">Tipo de Documento:
                                <p class="pad">{{ $cliente->tipodocumento == '' ? 'No proporcionado' : $cliente->tipodocumento }}</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="label-consulta" for="pad">Documento:
                                <p class="pad">{{ $cliente->documento == '' ? 'No proporcionado' : $cliente->documento }}</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="label-consulta" for="pad">Sexo:
                                <p class="pad">{{ $cliente->sexo == 'F' ? 'Femenino' : 'Masculino' }}</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="label-consulta" for="pad">Fecha de nacimiento:
                                <p class="pad">{{ $cliente->fechanto == '' ? 'No proporcionado' : $cliente->fechanto }}</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="label-consulta" for="pad">Ciudad:
                                <p class="pad">{{ $cliente->ciudad == '' ? 'No proporcionado' : $cliente->ciudad->ciudad }}</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="label-consulta" for="pad">Estado Civil:
                                <p class="pad">{{ $cliente->estado_civil == '' ? 'No proporcionado' : $cliente->estado_civil }}</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Información de contacto</b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="label-consulta" for="pad">Teléfono:
                                <p class="pad">{{ $cliente->telefono == '' ? 'No proporcionado' : $cliente->telefono }}</p>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="label-consulta" for="pad">Celular:
                                <p class="pad">{{ $cliente->celular == '' ? 'No proporcionado' : $cliente->celular }}</p>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="label-consulta" for="pad">Dirección:
                                <p class="pad">{{ $cliente->direccion == '' ? 'No proporcionado' : $cliente->direccion }}</p>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="label-consulta" for="pad">Correo electrónico:
                                <p class="pad">{{ $cliente->correo == '' ? 'No proporcionado' : $cliente->correo }}</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $ultimoregistro = $cliente->registrosfinancieros->first();
        @endphp


        @if ($ultimoregistro)   
            <div class="col-md-12">
                <h3>Información de Comprobante pago (Periodo {{$ultimoregistro->periodo}})</h3>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
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
                            </table>
                        @else
                            <p>No hay registros</p>
                        @endif  
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
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
                            </table>
                        @else
                            <p>No hay registros</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="panel panel-default">
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
            <div class="panel panel-default">
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
            <div class="panel panel-default">
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

@endsection
