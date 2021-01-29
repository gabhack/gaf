@extends('layouts.app')

@section('content')
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
    <form action="{{url('clientes/crear')}}" method="POST" enctype="multipart/form-data">
        {!! Form::token() !!}
        <div class="col-md-12">
            <div class="btn-group mb-3 mr-3" role="group">
                <a type="button" class="btn btn-secondary" href="{{url('estudios/nuevoestudio')}}"><i class="fa fa-arrow-left"></i> Atrás</a>
                <input class="btn btn-success" type="submit" value="Guardar">
            </div>
        </div>
	    <div class="col-md-12 col-md-offset-0">
		    <div class="panel panel-primary">
			    <div class="panel-heading">Crear persona en sistema</div>
			    <div class="panel-body">
					<div class="form-row col-md-6" id="panel-pagaduria">
                        <div class="form-group col-md-12">
                            <h3 class="text-center"><b>Datos básicos</b></h3>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Tipo de documento:
                                <select class="form-control" name="tipo_doc" id="tipo_doc">
                                    @foreach (tipos_documento() as $item => $value)
                                        <option value="{{ $value }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Documento:
                                <input type="number" class="form-control" name="documento" id="documento" placeholder="Documento" value="{{ $documento }}" required>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Nombres:
                                <input type="text" class="form-control" name="nombres" id="nombres" required>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Apellidos:
                                <input type="text" class="form-control" name="apellidos" id="apellidos" required>
                            </label>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Género:
                                <select class="form-control" name="genero" id="genero" required>
                                    <option value="" selected disabled hidden>Seleccione una</option>
                                    @foreach (sexos() as $item => $value)
                                        <option value="{{ $item }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
						<div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Estado civil:
                                <select class="form-control" name="estado_civil" id="estado_civil" required>
                                    <option value="" selected disabled hidden>Seleccione una</option>
                                    @foreach (estados_civiles() as $item => $value)
                                        <option value="{{ $item }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-consulta w-100" for="pad">Fecha de nacimiento:
                                <input type="text" class="form-control datepicker" name="fecha_nto" id="fecha_nto" placeholder="Ej: 2020-01-01" required>
                            </label>
                        </div>
                        <div class="form-group col-md-8">
                            <label class="label-consulta w-100" for="pad">Dirección:
                                <input type="text" class="form-control" name="direccion" id="direccion">
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-consulta w-100" for="pad">Celular de contacto:
                                <input type="number" class="form-control" name="celular" id="celular">
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-consulta w-100" for="pad">Teléfono de contacto:
                                <input type="number" class="form-control" name="telefono" id="telefono">
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="label-consulta w-100" for="pad">Correo electrónico:
                                <input type="text" class="form-control" name="correo" id="correo">
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
                                        <option value="{{ $value->ciudad }}">{{ $value->ciudad }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Centro de costos (Si aplica):
                                <input type="text" class="form-control" name="centro_costos" id="centro_costos" placeholder="Opcional">
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Grado (Si aplica):
                                <input type="text" class="form-control" name="grado" id="grado" placeholder="Opcional">
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Tipo de Cargo (Si aplica):
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="docente_administrativo" id="docente" value="1">
                                    <label class="form-check-label" for="docente">
                                        Docente
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="docente_administrativo" id="administrativo" value="0">
                                    <label class="form-check-label" for="administrativo">
                                        Administrativo
                                    </label>
                                </div>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Tipo de Contratación (Si aplica):
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_contratacion" id="terminoindefinido" value="Indefinido">
                                    <label class="form-check-label" for="terminoindefinido">
                                        Término indefinido
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_contratacion" id="terminofijo" value="Fijo">
                                    <label class="form-check-label" for="terminofijo">
                                        Término fijo
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_contratacion" id="prestacionservicios" value="Prestación de servicios">
                                    <label class="form-check-label" for="prestacionservicios">
                                        Prestación de servicios
                                    </label>
                                </div>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Cargo:
                                <input type="text" class="form-control" name="cargo" id="cargo" placeholder="Opcional">
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="label-consulta w-100" for="pad">Ingresos base:
                                <input type="number" class="form-control" name="ingresos" id="ingresos" required>
                            </label>
                        </div>
					</div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md">
                <h3 class="font-weight-bold">Registros financieros por periodo</h3>
            </div>
            {{-- <div class="col-md-2 mb-3">
                <input class="form-control btn-primary" type="button" value="+ Agregar registro" onclick="addRowRegistros()">
            </div> --}}

            <div class="col-md-12 col-md-offset-0">
                <div id="content-registros">
                    <div class="form-row d-inline m-0">
                        <div class="panel panel-primary">
                            <div class="panel-heading font-weight-bold">
							    <span class="text-panel-heading">Último Registro</span>
                            </div>
                            <div class="panel-body">
                                <div class="form-group col-md-12">
                                    <div class="col-md-3 col-md-offset-3">
                                        <label class="label-consulta w-100" for="pad">Periodo:
                                            <input class="form-control" required placeholder="Ej. 202001" type="number" id="input_periodo_0" name="periodo[0]">
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="label-consulta w-100" for="pad">Pagaduría:
                                            <select class="form-control" name="pagaduria[0]" id="pagaduria_0" required>
                                                <option value="" selected disabled hidden>Seleccione una</option>
                                                @foreach (pagadurias() as $item => $value)
                                                    <option value="{{ $value->pagaduria }}">{{ $value->pagaduria }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Ingresos Aplicados</h4>
                                    <div class="col-md-3">
                                        <input class="form-control btn-primary" type="button" value="+ Ingreso" onclick="addRowIngresos()">
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
                                    <h4>Descuentos Aplicados</h4>
                                    <div class="col-md-3">
                                        <input class="form-control btn-primary" type="button" value="+ Descuento" onclick="addRowDescuentos()">
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