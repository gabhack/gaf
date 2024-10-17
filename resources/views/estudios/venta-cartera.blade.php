@extends('layouts.hego')

@section('title')
HEGO | Venta de Cartera
@endsection

@section('header-content')
Venta de Cartera
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Venta de Cartera</li>
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
                        <input type="text" class="form-control2 datepicker" name="filtro[fecha_desde]" id="fecha_desde" placeholder="Desde" value="{{ isset($filtro['fecha_desde']) ? $filtro['fecha_desde'] : '' }}">
                    </div>
                    <div class="form-group">
                        <p class="pt-1 mb-0 font-weight-bold"> - </p>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="text" class="form-control2 datepicker" name="filtro[fecha_hasta]" id="fecha_hasta" placeholder="Hasta" value="{{ isset($filtro['fecha_hasta']) ? $filtro['fecha_hasta'] : '' }}">
                    </div>
                    <div class="form-group col-md-2 text-center">
                        <select class="form-control2" name="filtro[asesor]" id="asesor">
                            <option value="" <?php echo isset($filtro['asesor']) ? '' : ' selected'; ?>>Todos los asesores...</option>
                            @foreach (asesores() as $asesorgen)
                            <option value="{{ $asesorgen->id }}" <?php echo isset($filtro['asesor']) ? ($asesorgen->id == $filtro['asesor'] ? ' selected' : '') : ''; ?>>{{ $asesorgen->nombres }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 text-center">
                        <select class="form-control2" name="filtro[decision]" id="decision">
                            <option value="" <?php echo isset($filtro['decision']) ? '' : ' selected'; ?>>Todas las decisiones...</option>
                            @foreach (decisiones_estudios() as $key => $decision)
                            <option value="{{ $key }}" <?php echo isset($filtro['decision']) ? ($key == $filtro['decision'] ? ' selected' : '') : ''; ?>>{{ $decision }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="number" class="form-control2 datepicker-periodo" name="filtro[periodo]" id="periodo" placeholder="Periodo" value="{{ isset($filtro['periodo']) ? $filtro['periodo'] : '' }}">
                    </div>
                    <div class="form-group col-md-2">
                        <input type="text" class="form-control2" name="busq" id="busq" placeholder="Buscar por: Nombres - Documento" value="{{ isset($busq) ? $busq : '' }}">
                    </div>
                    <div class="form-group col-md-1">
                        <button style="padding: 8px 16px; border-radius: 8px; border-color: none; font-size: 14px; font-weight: 500; line-height: 18.23px; min-height: 40px; background-color: #0E866C; border-color: #0E866C; color: white;" type="submit" class="btn btn-info"><i class="fa fa-filter" aria-hidden="true"></i>
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
        <div class="table-responsive">
            <table class="table table-hover table-striped table-condensed table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>F. Venta</th>
                        <th>Comprador</th>
                        <th>Tasa</th>
                        <!--<th>Modalidad</th>-->
                        <th>C&eacute;dula</th>
                        <th>Nombre</th>
                        <th>Pagadur&iacute;a</th>
                        <th>Vr Cr&eacute;dito</th>
                        <th>Vr Capital</th>
                        <th>Vr Cr&eacute;dito<br>Comprador</th>
                        <th>Subestado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lista as $estudio)
                    <tr>
                        <th>{{ $estudio->id }}</th>
                        <td>{{ $estudio->fecha }}</td>
                        <td>{{ $estudio->periodo_estudio }}</td>
                        <td>{{ $estudio->cliente ?  $estudio->cliente->documento : "Sin Data" }}</td>
                        <td>{{ $estudio->cliente ? $estudio->cliente->documento . " " .  $estudio->cliente->apellidos : "Sin Data" }}</td>
                        <td>{{ $estudio->asesor ? $estudio->asesor->nombres : "Sin Data" }}</td>
                        @if ($estudio->decision)
                        <td>{{ decisiones_estudios()[$estudio->decision] }}</td>
                        @else
                        <td>N/A</td>

                        @endif
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>

                        <td class="text-center">
                            <div class="row">
                                <a class="btn btn-sm" style="padding:3px" href="{{ url('estudios/editar', ['id' => $estudio->id]) }}" title="Modificar"><img src="{{ asset('img/EditIcon.png') }}"/></a>
                                <a class="btn btn-sm" style="padding:3px" onclick="return confirm('Seguro que desea eliminar este registro y su informacion relacionada?')" href="{{ url('estudios/borrar', ['id' => $estudio->id]) }}" title="Eliminar"><img src="{{ asset('img/DeleteIcon.png') }}"/></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $links }}
    </div>
</div>
@endsection