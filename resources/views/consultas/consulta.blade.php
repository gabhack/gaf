@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
        <a class="btn btn-primary" href="{{url('consultas')}}"><< Atrás</a><h3>Información de: {{$cliente->nombres}}</h3>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Información general</div>
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
                                <p class="pad">{{ $cliente->ciudad->ciudad == '' ? 'No proporcionado' : $cliente->ciudad->ciudad }}</p>
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
                <div class="panel-heading">Información de contacto</div>
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
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Descuentos aplicados</div>
                <div class="panel-body">
                    @if ($cliente->descuentosaplicados->first())
                    <table class="table table-hover table-striped table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Entidad</th>
                                    <th class="text-center">Cuota mensual</th>
                                    <th class="text-center">Valor pagado</th>
                                    <th class="text-center">Valor total</th>
                                    <th class="text-center">Saldo pendiente</th>
                                    <th class="text-center">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cliente->descuentosaplicados as $descuento)
                                    <tr>
                                        <td>{{getentidad($descuento->entidades_id)}}</td>
                                        <td class="text-center">{{mneyformat($descuento->valor)}}</td>
                                        <td class="text-center">{{mneyformat($descuento->valor_pagado)}}</td>
                                        <td class="text-center">{{mneyformat($descuento->valor_total)}}</td>
                                        <td class="text-center">{{mneyformat($descuento->saldo)}}</td>
                                        <td class="text-center">{{$descuento->fecha}}</td>                                            
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
                <div class="panel-heading">Descuentos no aplicados</div>
                <div class="panel-body">
                        @if ($cliente->descuentosnoaplicados->first())
                        <table class="table table-hover table-striped table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Entidad</th>
                                    <th class="text-center">Cuota mensual</th>
                                    <th class="text-center">Valor pagado</th>
                                    <th class="text-center">Valor total</th>
                                    <th class="text-center">Saldo pendiente</th>
                                    <th class="text-center">Inconsistencia</th>
                                    <th class="text-center">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cliente->descuentosnoaplicados as $descuento)
                                    <tr>
                                        <td>{{getentidad($descuento->entidades_id)}}</td>
                                        <td class="text-center">{{mneyformat($descuento->valor_fijo)}}</td>
                                        <td class="text-center">{{mneyformat($descuento->valor_pagado)}}</td>
                                        <td class="text-center">{{mneyformat($descuento->valor_total)}}</td>
                                        <td class="text-center">{{mneyformat($descuento->saldo)}}</td>
                                        <td class="text-center">{{$descuento->inconsistencia}}</td>
                                        <td class="text-center">{{$descuento->fecha}}</td>
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
    </div>

@endsection
