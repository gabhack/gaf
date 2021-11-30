@extends('layouts.app2')

@section('content')
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
                <div>
                    <form action="{{url('consultas/list')}}" method="get">
                        <div class="form-row" id="panel-pagaduria">
                            <div class="form-group col-md-.5 text-center">
                                <p class="pt-1 mb-0 font-weight-bold">Fechas:</p>
                            </div>
                            <div class="form-group col-md-1">
                                <input type="text" class="form-control datepicker" name="filtro[fecha_desde]" id="fecha_desde" placeholder="Desde" value="{{ isset($filtro['fecha_desde']) ? $filtro['fecha_desde'] : '' }}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <p class="pt-1 mb-0 font-weight-bold"> - </p>
                            </div>
                            <div class="form-group col-md-1">
                                <input type="text" class="form-control datepicker" name="filtro[fecha_hasta]" id="fecha_hasta" placeholder="Hasta" value="{{ isset($filtro['fecha_hasta']) ? $filtro['fecha_hasta'] : '' }}" autocomplete="off">
                            </div>
                            <div class="form-group col-md-2">
                                <input type="text" class="form-control" name="documento" id="documento" placeholder="Documento" value="{{ isset($documento) ? $documento : '' }}" autocomplete="off">
                            </div>
                            <div class="form-group col-md-1">
                                <button type="submit" class="btn btn-info"><i class="fa fa-filter" aria-hidden="true"></i> Filtrar</button>
                            </div>
                            @if (isset($documento) || isset($filtro))
                                <div class="form-group col-md-1">
                                    <a href="{{url('consultas/list')}}" class="btn btn-secondary"><i class="fa fa-eraser" aria-hidden="true"></i> Limpiar todo</a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <table class="table table-hover table-striped table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th style="width:10%;" class="text-center">Fecha</th>
                            <th style="width:10%;" class="text-center">Tipo Consulta</th>
                            <th style="width:10%;" class="text-center">Pagaduría</th>
                            <th style="width:10%;" class="text-center">Documento</th>
                            <th style="width:20%;" class="text-center">Nombre Consultante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lista as $consulta)
                            <tr>
                                <td>{{$consulta->created_at}}</td>
                                <th class="text-center">{{LabelTipoConsulta($consulta->tipo_consulta)}}</th>
                                <th class="text-center">{{$consulta->registros_financieros_id ? $consulta->registrofinanciero->pagaduria->pagaduria : ''}}</th>
                                <td>{{$consulta->documento}}</td>
                                <td>{{$consulta->usuario->name}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $links }}
			</div>
		</div>
	</div>
@endsection

@section('title')
    Consultas AMI® 
@endsection

@section('header-content')
    Consultas AMI®
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Inicio</a></li>
    <li class="active">Consultas AMI®</li>
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
@endsection