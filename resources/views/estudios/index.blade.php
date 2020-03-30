@extends('layouts.app')

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
                <table class="table table-hover table-striped table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Decisión</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lista as $estudio)
                            <tr>
                                <th>{{$estudio->id}}</th>
                                <td>{{$estudio->fecha}}</td>
                                <td>{{$estudio->cliente->nombres}} {{$estudio->cliente->apellidos}}</td>
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
			</div>
		</div>
	</div>
@endsection
