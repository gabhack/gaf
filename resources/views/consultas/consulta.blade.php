@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h3>Información de: {{$cliente->nombres}}</h3>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Información general</div>
                <div class="panel-body">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Descuentos aplicados</div>
                <div class="panel-body">
                    @if ($cliente->descuentosaplicados->first())
                    <table class="table table-hover table-striped table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Entidad</th>
                                    <th class="text-center">Cuota mensual</th>
                                    <th class="text-center">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cliente->descuentosaplicados as $descuento)
                                    <tr>
                                        {{-- <td>{{$descuento->tercero()->entidad}}</td> --}}
                                        <td>{{getentidad($descuento->entidades_id)}}</td>
                                        <td class="text-center">{{format($descuento->valor)}}</td>
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
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Descuentos no aplicados</div>
                <div class="panel-body">
                        @if ($cliente->descuentosnoaplicados->first())
                        <table class="table table-hover table-striped table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Entidad</th>
                                        <th class="text-center">Cuota mensual</th>
                                        <th class="text-center">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->descuentosnoaplicados as $descuento)
                                        <tr>
                                            {{-- <td>{{$descuento->tercero()->entidad}}</td> --}}
                                            <td>{{getentidad($descuento->entidades_id)}}</td>
                                            <td class="text-center">{{format($descuento->valor)}}</td>
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
    
    <pre>
        {{-- {{print_r($cliente)}} --}}
        {{-- {{print_r($cliente->descuentosaplicados())}} --}}
        {{-- {{print_r($cliente->descuentosnoaplicados())}} --}}
    </pre>

@endsection
