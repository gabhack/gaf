@extends('layouts.app2')

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
            <a class="nav-link active"><b>Todas las simulaciones</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('estudios/nuevoestudio')}}">Nueva <i class="fa fa-plus" aria-hidden="true"></i></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-body">
            <div>
                <form action="{{url('estudios')}}" method="get">
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
                                <option value="" <?php echo (isset($filtro['asesor']) ? '' : ' selected') ?>>Todos los asesores...</option>
                                @foreach (asesores() as $asesorgen)
                                <option value="{{$asesorgen->id}}" <?php echo (isset($filtro['asesor']) ? ($asesorgen->id == $filtro['asesor'] ? ' selected' : '') : '') ?>>{{$asesorgen->nombres}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2 text-center">
                            <select class="form-control" name="filtro[decision]" id="decision">
                                <option value="" <?php echo (isset($filtro['decision']) ? '' : ' selected') ?>>Todas las decisiones...</option>
                                @foreach (decisiones_estudios() as $key => $decision)
                                <option value="{{$key}}" <?php echo (isset($filtro['decision']) ? ($key == $filtro['decision'] ? ' selected' : '') : '') ?>>{{$decision}}</option>
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
                            <button type="submit" class="btn btn-info"><i class="fa fa-filter" aria-hidden="true"></i> Filtrar</button>
                        </div>
                        @if (isset($busq) || isset($filtro))
                        <div class="form-group col-md-1">
                            <a href="{{url('estudios')}}" class="btn btn-secondary"><i class="fa fa-eraser" aria-hidden="true"></i> Limpiar todo</a>
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
                        <th style="width:10%;" class="text-center">Documento</th>
                        <th style="width:20%;" class="text-center">Nombres Cliente</th>
                        <th style="width:15%;" class="text-center">Asesor</th>
                        <th style="width:10%;" class="text-center">Decisión</th>
                        <th style="width:10%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lista as $cotizer)
                    <tr>
                        <th>{{$cotizer->id}}</th>
                        <td>{{$cotizer->created_at}}</td>
                        <td>{{$cotizer->idNumber}}</td>
                        <td>{{$cotizer->firstName}} {{$cotizer->firstLastname}}</td>
                        <td>---</td>
                        @if ($cotizer->status)
                        <td>{{$cotizer->status}}</td>
                        @else
                        <td>N/A</td>
                        @endif
                        <td class="text-center">
                            <a href="{{ route('cotizer-data.show', $cotizer->id) }}" title="Modificar" class="btn btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a onclick="return confirm('Seguro que desea eliminar este registro y su informacion relacionada?')" href="{{ url('cotizer-data/borrar', ['id' => $cotizer->id] )}}" title="Eliminar" class="btn btn-info"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
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

@section('css')
<link href="{{asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection

@section('js')
<script src="{{asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script>
    $('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        toggleActive: true,
        language: "es"
    });
</script>
<script>
    $('.datepicker-periodo').datepicker({
        format: "yyyymm",
        autoclose: true,
        toggleActive: true,
        language: "es",
        startView: "decade",
        showOnFocus: true,
        minViewMode: 'months'
    });
</script>
@endsection