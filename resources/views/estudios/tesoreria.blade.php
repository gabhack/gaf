@extends('layouts.hego')

@section('title')
HEGO | Tesorería
@endsection

@section('header-content')
Tesorería
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Tesorería</li>
@endsection

@section('panel')
<div class="panel panel-default">
    <div class="panel-body">
        <div>
            <form action="{{ url('estudios') }}" method="get">
                <div class="form-row" id="panel-pagaduria">
                    <div class="form-group col-md-.5 text-center">
                        <p class="pt-1 mb-0 font-weight-bold">Fechas:</p>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="text" class="form-control datepicker" name="filtro[fecha_desde]" id="fecha_desde" placeholder="Desde" value="{{ isset($filtro['fecha_desde']) ? $filtro['fecha_desde'] : '' }}">
                    </div>
                    <div class="form-group">
                        <p class="pt-1 mb-0 font-weight-bold"> - </p>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="text" class="form-control datepicker" name="filtro[fecha_hasta]" id="fecha_hasta" placeholder="Hasta" value="{{ isset($filtro['fecha_hasta']) ? $filtro['fecha_hasta'] : '' }}">
                    </div>
                    <div class="form-group col-md-2 text-center">
                        <select class="form-control" name="filtro[asesor]" id="asesor">
                            <option value="" <?php echo isset($filtro['asesor']) ? '' : ' selected'; ?>>Todos los asesores...</option>
                            @foreach (asesores() as $asesorgen)
                            <option value="{{ $asesorgen->id }}" <?php echo isset($filtro['asesor']) ? ($asesorgen->id == $filtro['asesor'] ? ' selected' : '') : ''; ?>>{{ $asesorgen->nombres }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 text-center">
                        <select class="form-control" name="filtro[decision]" id="decision">
                            <option value="" <?php echo isset($filtro['decision']) ? '' : ' selected'; ?>>Todas las decisiones...</option>
                            @foreach (decisiones_estudios() as $key => $decision)
                            <option value="{{ $key }}" <?php echo isset($filtro['decision']) ? ($key == $filtro['decision'] ? ' selected' : '') : ''; ?>>{{ $decision }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="number" class="form-control datepicker-periodo" name="filtro[periodo]" id="periodo" placeholder="Periodo" value="{{ isset($filtro['periodo']) ? $filtro['periodo'] : '' }}">
                    </div>
                    <div class="form-group col-md-2">
                        <input type="text" class="form-control" name="busq" id="busq" placeholder="Buscar por: Nombres - Documento" value="{{ isset($busq) ? $busq : '' }}">
                    </div>
                    <div class="form-group col-md-1">
                        <button type="submit" class="btn btn-info"><i class="fa fa-filter" aria-hidden="true"></i>
                            Filtrar</button>
                    </div>
                    @if (isset($busq) || isset($filtro))
                    <div class="form-group col-md-1">
                        <a href="{{ url('estudios') }}" class="btn btn-secondary"><i class="fa fa-eraser" aria-hidden="true"></i> Limpiar todo</a>
                    </div>
                    @endif
                </div>
            </form>
        </div>
        <table class="table table-hover table-striped table-condensed table-bordered">
            <thead>
                <tr>
                    <th style="width:5%;" class="text-center">#</th>
                    <th style="width:10%;" class="text-center">Cédula</th>
                    <th style="width:10%;" class="text-center">Nombre</th>
                    <th style="width:10%;" class="text-center">Pagaduría</th>
                    <th style="width:20%;" class="text-center">Vr Solicitado</th>
                    <th style="width:15%;" class="text-center">Comp. Cartera</th>
                    <th style="width:10%;" class="text-center">Fecha</th>
                    <th style="width:10%;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lista as $estudio)
                <tr>
                    <th>{{ $estudio->id }}</th>
                    <td><a href="{{ url('estudios/tesoreria/detalle', ['id' => $estudio->id])  }}">{{ $estudio->datacotizer ? $estudio->datacotizer->idNumber : 'Sin identificación'   }}</a></td>
                    <td>{{ $estudio->datacotizer ?  $estudio->datacotizer->firstName : "sin Data" }} {{ $estudio->datacotizer ?  $estudio->datacotizer->firstLastname : "sin Data" }}</td>
                    <td>{{ $estudio->pagaduria ? $estudio->pagaduria->pagaduria  : "Sin pagaduria"}}</td>
                    <td>{{ $estudio->solicitudcredito ? $estudio->solicitudcredito->credito_total  : "Sin valor"}}</td>
                    <td>Si</td>
                    <td>{{ $estudio->fecha }}</td>
                    <td class="text-center">
                        <a href="{{ route('tesoreria.detalle', ['id' => $estudio->id]) }}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="{{ url('estudios/pagos', ['id' => $estudio->id]) }}" title="Pagos" class="btn btn-info"><i class="fa fa-file" aria-hidden="true"></i></a>
                        <a onclick="return confirm('Seguro que desea eliminar este registro y su informacion relacionada?')" href="{{ url('estudios/borrar', ['id' => $estudio->id]) }}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $links }}
    </div>
</div>
@endsection