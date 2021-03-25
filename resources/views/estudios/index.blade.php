@extends('layouts.app')

@section('title')
    HEGO
@endsection

@section('content')
    @if (isset($message))
        <div id="toast-message" class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                        <div class="alert alert-{{ $message['tipo'] }} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> {{ $message['titulo'] }}</h4>
                            {{ $message['mensaje'] }}
                        </div>
                </div>
            </div>
        </div>
    @endif
	<div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active"><b>Lista de Estudios</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url('estudios/nuevoestudio')}}">Crear Estudio</a>
            </li>
        </ul>
		<div class="panel panel-default">
			<div class="panel-heading">Listado de Estudios</div>
			<div class="panel-body">
                <div>
                    <form action="{{url('estudios')}}" method="get">
                        <div class="form-row" id="panel-pagaduria">
                            <div class="form-group col-md-4">
                                <input type="text" class="form-control" name="busq" id="busq" placeholder="Buscar por: Nombres - Documento" value="{{ isset($busq) ? $busq : '' }}">
                            </div>
                            <div class="form-group-col-md-2">
                                <button type="submit" class="btn btn-info"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                            @if (isset($busq))
                                <div class="form-group-col-md-2">
                                    <a href="{{url('estudios')}}" class="btn btn-secondary"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <table class="table table-hover table-striped table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th style="width:5%;" class="text-center">#</th>
                            <th style="width:10%;" class="text-center">Fecha</th>
                            <th style="width:10%;" class="text-center">Periodo Estudio</th>
                            <th style="width:10%;" class="text-center">Documento</th>
                            <th style="width:20%;" class="text-center">Nombres Cliente</th>
                            <th style="width:15%;" class="text-center">Asesor</th>
                            <th style="width:10%;" class="text-center">Decisión</th>
                            <th style="width:10%;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lista as $estudio)
                            <tr>
                                <th>{{$estudio->id}}</th>
                                <td>{{$estudio->fecha}}</td>
                                <td>{{$estudio->periodo_estudio}}</td>
                                <td>{{$estudio->cliente->documento}}</td>
                                <td>{{$estudio->cliente->nombres}} {{$estudio->cliente->apellidos}}</td>
                                <td>{{$estudio->asesor->nombres}}</td>
                                @if ($estudio->decision)
                                    <td>{{decisiones_estudios()[$estudio->decision]}}</td>
                                @else
                                    <td>N/A</td>
                                @endif
                                <td class="text-center">
                                    <a href="{{url('estudios/editar', ['id' => $estudio->id])}}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <a onclick="return confirm('Seguro que desea eliminar este registro y su informacion relacionada?')" href="{{url('estudios/borrar', ['id' => $estudio->id])}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $links }}
			</div>
		</div>
	</div>
@endsection
