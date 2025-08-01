@extends('layouts.hego')

@section('title')
HEGO | Cartera
@endsection

@section('header-content')
Cartera
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Cartera</li>
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
                <tr class="thead-dark">
                    <th>C&eacute;dula</th>
                    <th>F. Desemb</th>
                    <th>Días rest.</th>
                    <th>Mes Prod</th>
                    <th>Nombre</th>
                    <th>No. Libranza</th>
                    <th>Tasa</th>
                    <th>Cuota</th>
                    <th>Vr Cr&eacute;dito</th>
                    <th>Pagadur&iacute;a</th>
                    <th>Plazo</th>
                    <th>Estado</th>
                    <th>Total Recaud.</th>
                    <th>Cuotas<br>Pag.</th>
                    <th>Cuotas<br>Causad.</th>
                    <th>Cuotas<br>Mora</th>
                    <th>Plan de Pagos</th>
                </tr>
                <tbody>
                    @foreach ($lista as $cartera)
                    <tr>
                        <td><a href="{{ url('/estudios/detalle-cartera/'.$cartera->id ) }}">{{ $cartera->estudio->datacotizer ?  $cartera->estudio->datacotizer->idNumber : "sin Data" }}</a></td>
                        <td>{{ $cartera->created_at }}</td>
                        <th>20</th>
                        <th>6</th>
                        <td>{{ $cartera->estudio->datacotizer ?  $cartera->estudio->datacotizer->firstName : "sin Data" }} {{ $cartera->estudio->datacotizer ?  $cartera->estudio->datacotizer->firstLastname : "sin Data" }}</td>
                        <td>FIAN 62112</td>
                        <td>2.00</td>
                        <td>$1.600.000</td>
                        <td>$12.600.000</td>
                        <td>SED CAUCA</td>
                        <td>12</td>
                        <td>Vigente</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td class="text-center">
                            <div class="row">
                                <a class="btn btn-sm" style="padding:3px" href="#" title="Modificar"><img src="{{ asset('img/EditIcon.png') }}"/></a>
                                <a class="btn btn-sm" style="padding:3px" onclick="return confirm('Seguro que desea eliminar este registro y su informacion relacionada?')" href="#" title="Eliminar"><img src="{{ asset('img/DeleteIcon.png') }}"/></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $lista->links() }}
    </div>
</div>
@endsection