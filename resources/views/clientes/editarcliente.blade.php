@extends('layouts.app')

@section('content')
    <script>
        //data
        var pagadurias = @json(pagadurias());
        var htmlpagadurias = '';
        pagadurias.forEach( function(pagaduria) {
            htmlpagadurias += `<option value="`+pagaduria.id+`">`+pagaduria.pagaduria+`</option>`;
        });
    </script>
    @if (isset($message))
        <div id="toast-message" class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="alert alert-{{ $message['tipo'] }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-exclamation"></i> {{ $message['titulo'] }}</h4>
                        {{ $message['mensaje'] }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    <form action="{{url('clientes/actualizar')}}" method="POST" enctype="multipart/form-data">
        {!! Form::token() !!}
        <div class="col-md-12">
            <div class="btn-group mb-3 mr-3" role="group">
                <a type="button" class="btn btn-secondary" href="{{url('estudios/iniciar/'.$cliente->documento)}}"><i class="fa fa-arrow-left"></i> Atrás</a>
                <input class="btn btn-success" type="submit" value="Guardar">
            </div>
        </div>
	    <div class="col-md-12 col-md-offset-0">
		    <div class="panel panel-primary">
			    <div class="panel-heading">Editar persona en sistema</div>
			    <div class="panel-body">
					<div class="form-row col-md-6" id="panel-pagaduria">
                        <div class="form-group col-md-12">
                            <h3 class="text-center"><b>Datos básicos</b></h3>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Tipo de documento:
                                <select class="form-control" name="tipo_doc" id="tipo_doc" disabled>
                                    @foreach (tipos_documento() as $item => $value)
                                        <option value="{{ $value }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Documento:
                                <input type="number" class="form-control" name="documento" id="documento" placeholder="Documento" value="{{ $cliente->documento }}" readonly>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Nombres:
                                <input type="text" class="form-control" name="nombres" id="nombres" value="{{ $cliente->nombres }}" required>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Apellidos:
                                <input type="text" class="form-control" name="apellidos" id="apellidos" value="{{ $cliente->apellidos }}" required>
                            </label>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Género:
                                <select class="form-control" name="genero" id="genero" required>
                                    <option value="" selected disabled hidden>Seleccione una</option>
                                    @foreach (sexos() as $item => $value)
                                        <option {{ $cliente->sexo == $item ? 'selected' : '' }} value="{{ $item }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Estado civil:
                                <select class="form-control" name="estado_civil" id="estado_civil">
                                    <option value="" selected disabled hidden>Seleccione una</option>
                                    @foreach (estados_civiles() as $item => $value)
                                        <option {{ $cliente->estado_civil == $item ? 'selected' : '' }} value="{{ $item }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-consulta w-100" for="pad">Fecha de nacimiento:
                                <input type="text" class="form-control datepicker" name="fecha_nto" id="fecha_nto" value="{{ $cliente->fechanto }}" placeholder="Ej: 2020-01-01" required>
                            </label>
                        </div>
                        <div class="form-group col-md-8">
                            <label class="label-consulta w-100" for="pad">Dirección:
                                <input type="text" class="form-control" name="direccion" id="direccion" value="{{ $cliente->direccion }}">
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-consulta w-100" for="pad">Celular de contacto:
                                <input type="number" class="form-control" name="celular" id="celular" value="{{ $cliente->celular }}">
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-consulta w-100" for="pad">Teléfono de contacto:
                                <input type="number" class="form-control" name="telefono" id="telefono" value="{{ $cliente->telefono }}">
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-consulta w-100" for="pad">Correo electrónico:
                                <input type="text" class="form-control" name="correo" id="correo" value="{{ $cliente->correo }}">
                            </label>
                        </div>
                    </div>
                    <div class="form-row col-md-6">
                        <div class="form-group col-md-12">
                            <h3 class="text-center"><b>Datos extra</b></h3>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Ciudad:
                                <select class="form-control" name="ciudad" id="ciudad" required>
                                    <option value="" selected disabled hidden>Seleccione una</option>
                                    @foreach (ciudades() as $item => $value)
                                        <option {{ $cliente->ciudad->ciudad == $item ? 'selected' : '' }} value="{{ $value->ciudad }}">{{ $value->ciudad }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Centro de costos (Si aplica):
                                <input type="text" class="form-control" name="centro_costos" id="centro_costos" placeholder="Opcional" value="{{ $cliente->centro_costo }}">
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Grado (Si aplica):
                                <input type="text" class="form-control" name="grado" id="grado" placeholder="Opcional" value="{{ $cliente->grado }}">
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Vinculacion:
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="vinculacion" id="docente" value="d" {{ $cliente->vinculacion == 'd' ? 'checked="checked"' : '' }} required>
                                    <label class="form-check-label" for="docente">
                                        Docente
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="vinculacion" id="administrativo" value="a" {{ $cliente->vinculacion == 'a' ? 'checked="checked"' : '' }} required>
                                    <label class="form-check-label" for="administrativo">
                                        Administrativo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="vinculacion" id="pensionado" value="p" {{ $cliente->vinculacion == 'p' ? 'checked="checked"' : '' }} required>
                                    <label class="form-check-label" for="pensionado">
                                        Pensionado
                                    </label>
                                </div>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Tipo de Contratación (Si aplica):
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_contratacion" id="terminoindefinido" value="Indefinido" {{ ($cliente->tipo_contratacion == 'Indefinido' || $cliente->tipo_contratacion == 'Propiedad') ? 'checked="checked"' : '' }}>
                                    <label class="form-check-label" for="terminoindefinido">
                                        Término indefinido
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_contratacion" id="terminofijo" value="Fijo" {{ ($cliente->tipo_contratacion == 'Fijo' || $cliente->tipo_contratacion == 'Provisional') ? 'checked="checked"' : '' }}>
                                    <label class="form-check-label" for="terminofijo">
                                        Término fijo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_contratacion" id="prestacionservicios" value="Prestación de servicios" {{ ($cliente->tipo_contratacion == 'Prestación de servicios' || $cliente->tipo_contratacion == 'Periodo de Prueba') ? 'checked="checked"' : '' }}>
                                    <label class="form-check-label" for="prestacionservicios">
                                        Prestación de servicios
                                    </label>
                                </div>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Cargo:
                                <input type="text" class="form-control" name="cargo" id="cargo" placeholder="Opcional" value="{{ $cliente->cargo }}">
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Ingresos base:
                                <input type="number" class="form-control" name="ingresos" id="ingresos" required value="{{ $cliente->ingresos }}">
                            </label>
                        </div>
					</div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-md-offset-0">
            <div class="form-row d-inline m-0">
                <div class="panel panel-primary">
                    <div class="panel-heading font-weight-bold">
                        <span class="text-panel-heading">Descuentos No Aplicados</span>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <input id="btn-add-desc-na" class="form-control btn btn-primary" type="button" value="+ Descuento no aplicado" onclick="addRowDescNoAplicado()">
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Cod. Concepto</th>
                                            <th scope="col">Concepto</th>
                                            <th scope="col">Periodo (Año y mes)</th>
                                            <th scope="col">Pagaduría</th>
                                            <th scope="col">Inconsistencia</th>
                                            <th scope="col">Valor Cuota</th>
                                            <th scope="col">Valor total</th>
                                            <th scope="col">Saldo pdte</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="content-desc-no-aplicados">
                                        @if (isset($cliente->descuentosnoaplicados))
                                            @foreach ($cliente->descuentosnoaplicados as $key => $desc)
                                                <tr class="row_desc_no_aplicados">
                                                    <th scope="row">{{$key+1}}</th>
                                                    <td><input class="form-control" type="text" name="desc_na_cod_concepto[0][{{$key+1}}]" id="desc_na_cod_concepto_{{$key+1}}" placeholder="Opcional" value="{{$desc->cod_concepto}}"></td>
                                                    <td><input class="form-control" type="text" name="desc_na_concepto[0][{{$key+1}}]" id="desc_na_concepto_{{$key+1}}" required value="{{$desc->concepto}}"></td>
                                                    <td><input class="form-control" type="number" name="desc_na_periodo[0][{{$key+1}}]" id="desc_na_periodo_{{$key+1}}" required value="{{$desc->periodo}}" placeholder="Ej: 201907"></td>
                                                    <td>
                                                        <select class="form-control" name="desc_na_pagaduria[0][{{$key+1}}]" id="desc_na_pagaduria_{{$key+1}}" required>
                                                            <option value="" selected disabled hidden>Seleccione una</option>
                                                            @foreach (pagadurias() as $item => $value)
                                                                <option {{$desc->pagadurias_id == $value->id ? 'selected ' : ''}}value="{{ $value->id }}">{{ $value->pagaduria }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input class="form-control" type="text" name="desc_na_inconsistencia[0][{{$key+1}}]" id="desc_na_inconsistencia_{{$key+1}}" required value="{{$desc->inconsistencia}}"></td>
                                                    <td style="width: 15%;"><input class="form-control" type="number" name="desc_na_valor_fijo[0][{{$key+1}}]" id="desc_na_valor_fijo_{{$key+1}}" required value="{{$desc->valor_fijo}}"></td>
                                                    <td style="width: 15%;"><input class="form-control" type="number" name="desc_na_valor_total[0][{{$key+1}}]" id="desc_na_valor_total_{{$key+1}}" placeholder="Opcional" value="{{$desc->valor_total}}"></td>
                                                    <td style="width: 15%;"><input class="form-control" type="number" name="desc_na_saldo[0][{{$key+1}}]" id="desc_na_saldo_{{$key+1}}" placeholder="Opcional" value="{{$desc->saldo}}"></td>
                                                    <td style="width: 5%;">
                                                        <a class="btn btn-link eliminar-registro" type="button" value="-" onclick="removeRowDescNoAplicado(this)">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="form-registros" action="{{url('api/clientes/actualizarRegistro')}}" method="POST">
        {!! Form::token() !!}
        <input type="hidden" name="idcliente" value="{{$cliente->id}}">
        <div class="col-md-12">
            <div class="col-md">
                <h3 class="font-weight-bold">Gestión de Registros financieros por periodo</h3>
            </div>

            <div class="col-md-12 col-md-offset-0">
                <div id="content-registros">
                    <div class="form-row d-inline m-0">
                        <div class="panel panel-primary">
                            <div class="panel-heading font-weight-bold">
                                <span class="text-panel-heading">Edición de registros</span>
                            </div>
                            <div class="panel-body">
                                <div class="form-group col-md-12">
                                    <div class="col-md-3 col-md-offset-3">
                                        <label class="label-consulta w-100" for="pad">Periodo:
                                            <select class="form-control" name="periodo" id="input_periodo" onchange="getPagaduriasXPeriodo({{$cliente->id}})">
                                                <option value="" selected disabled hidden>Seleccione una</option>
                                                @foreach ($registros as $item => $value)
                                                    <option value="{{ $value }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="label-consulta w-100" for="pad">Pagaduría:
                                            <select class="form-control" name="pagaduria" id="pagaduria_select" disabled onchange="getRegistrosXPagaduriayPeriodo({{$cliente->id}})">
                                                <option value="" selected disabled hidden>Seleccione una</option>
                                                @foreach (pagadurias() as $item => $value)
                                                    <option id="option_pag_{{ $value->id }}" value="{{ $value->id }}" disabled hidden>{{ $value->pagaduria }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-2 col-md-offset-5">
                                            <input id="btn-submit-registros" class="form-control btn btn-success" type="submit" value="Actualizar" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="font-weight-bold">Ingresos Aplicados</h4>
                                    <div class="col-md-3">
                                        <input id="btn-add-ingr" class="form-control btn btn-primary" type="button" value="+ Ingreso" onclick="addRowIngresos()" disabled>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Cod. Concepto</th>
                                                    <th scope="col">Concepto</th>
                                                    <th scope="col">Valor</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="content-ingr-aplicados">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="font-weight-bold">Descuentos Aplicados</h4>
                                    <div class="col-md-3">
                                        <input id="btn-add-desc" class="form-control btn btn-primary" type="button" value="+ Descuento" onclick="addRowDescuentos()" disabled>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Cod. Concepto</th>
                                                    <th scope="col">Concepto</th>
                                                    <th scope="col">Valor</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="content-desc-aplicados">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('css')
    <link href="{{asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('js/scriptsClientes.js')}}"></script>
@endsection