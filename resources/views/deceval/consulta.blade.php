@extends('layouts.app2')

@section('content')
    <div class="container-fluid">
        <div
            class="vld-overlay is-active is-full-page"
            aria-label="Loading"
            tabindex="0"
            style="display: none;"
        >
            <div class="vld-background"></div>
            <div class="vld-icon">
                <svg
                    viewBox="0 0 38 38"
                    xmlns="http://www.w3.org/2000/svg"
                    width="64"
                    height="64"
                    stroke="#0CEDB0"
                >
                    <g
                        fill="none"
                        fill-rule="evenodd"
                    >
                        <g
                            transform="translate(1 1)"
                            stroke-width="2"
                        >
                            <circle
                                stroke-opacity=".25"
                                cx="18"
                                cy="18"
                                r="18"
                            ></circle>
                            <path d="M36 18c0-9.94-8.06-18-18-18">
                                <animateTransform
                                    type="rotate"
                                    attributeName="transform"
                                    from="0 18 18"
                                    to="360 18 18"
                                    dur="0.8s"
                                    repeatCount="indefinite"
                                ></animateTransform>
                            </path>
                        </g>
                    </g>
                </svg>
            </div>
        </div>
        <div>
            @if ($resultado)
                <form
                    class="form-group col-md-12"
                    action="{{ url('deceval/firmar') }}"
                    method="post"
                >
                    <input
                        id="step"
                        name="step"
                        type="hidden"
                        value="{{ $step }}"
                    />
                    <input
                        id="phoneListStr"
                        name="phoneListStr"
                        type="hidden"
                        value="{{ $phoneListStr }}"
                    />
                    <input
                        id="application"
                        name="application"
                        type="hidden"
                        value="{{ $application }}"
                    />
                    <input
                        id="idDocumentoPagare"
                        name="idDocumentoPagare"
                        type="hidden"
                        value="{{ $idDocumentoPagare }}"
                    />
                    <input
                        id="otorganteTipoId"
                        name="otorganteTipoId"
                        type="hidden"
                        value="{{ $otorganteTipoId }}"
                    />
                    <input
                        id="otorganteNumId"
                        name="otorganteNumId"
                        type="hidden"
                        value="{{ $otorganteNumId }}"
                    />
                    <input
                        id="numPagareEntidad"
                        name="numPagareEntidad"
                        type="hidden"
                        value="{{ $numPagareEntidad }}"
                    />
                    <input
                        id="codigoDepositante"
                        name="codigoDepositante"
                        type="hidden"
                        value="{{ $codigoDepositante }}"
                    />
                    <input
                        id="fecha"
                        name="fecha"
                        type="hidden"
                        value="{{ $fecha }}"
                    />
                    <input
                        id="hora"
                        name="hora"
                        type="hidden"
                        value="{{ $hora }}"
                    />
                    {{ csrf_field() }}

                    <div
                        class="row"
                        id="consulta-container"
                    >
                        <div class="col-6">
                            <div class="panel mb-3">

                                <div class="panel-heading">
                                    <b>RESULTADO DE LA CONSULTA</b>
                                </div>

                                <div class="panel-body">
                                    @if ($errorFirma)
                                        <div
                                            class="row"
                                            style="font-size:60%;color:red;"
                                        >
                                            {{ $errorFirma }}
                                        </div>
                                    @else
                                        <div class="row"></div>
                                    @endif
                                    @if ($step === 'confirmCode' || $step === 'initial')
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <b class="panel-label">TELEFÓNO DE CONFIRMACIÓN:</b>
                                                <select
                                                    class="form-control text-center"
                                                    id="phone"
                                                    name="phone"
                                                    required="required"
                                                >
                                                    @foreach ($phoneList as $phone)
                                                        <option value="{{ $phone }}">{{ $phone }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                @if ($confirmCode === 'confirmCode')
                                                    <b class="panel-label">CODIGO DE CONFIRMACION:</b>
                                                    <input
                                                        id="code"
                                                        name="code"
                                                        type="text"
                                                        value=""
                                                        required="required"
                                                    />
                                                    @if ($ambiente === 0)
                                                        <span>Codigo Generado Para UAT:{{ $code }}</span>
                                                    @else
                                                    @endif
                                                @else
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                    @endif
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            @if ($step === 'confirmCode')
                                                <button
                                                    class="btn btn-secondary"
                                                    type="submit"
                                                >
                                                    <i class="fa fa-pencil"></i> Confirmar Codigo
                                                </button>
                                            @elseif ($step === 'initial')
                                                <button
                                                    class="btn btn-secondary"
                                                    type="submit"
                                                >
                                                    <i class="fa fa-pencil"></i> Generar Codigo
                                                </button>
                                            @elseif($step === 'failCreateFlow')
                                                <button
                                                    class="btn btn-secondary"
                                                    type="submit"
                                                >
                                                    <i class="fa fa-pencil"></i> Regenerar Flujo
                                                </button>
                                            @elseif($step === 'firmado')
                                            @else
                                                <button
                                                    class="btn btn-secondary"
                                                    type="submit"
                                                >
                                                    <i class="fa fa-pencil"></i> Firmar Documento
                                                </button>
                                            @endif
                                        </div>

                                        <div class="form-group col-md-4">
                                            <a
                                                class="btn btn-primary"
                                                href="{{ asset('pagare.pdf') }}"
                                            ><i class="fa fa-envelope-open"></i> Abrir Pagare</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <iframe
                                src="{{ asset('pagare.pdf') }}?alternate={{ $step }}"
                                scrolling="auto"
                                height="1100"
                                width="850"
                                frameborder="0"
                            ></iframe>
                        </div>
                    </div>
                </form>
            @else
                <div class="col-md-12">
                    <h4>Ocurrio un error al generar el pagare firmado.</h4>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('title')
    Consulta Deceval
@endsection

@section('header-content')
    Consulta Deceval
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li><a href="{{ url('deceval') }}">Deceval</a></li>
    <li class="active">Consulta</li>
@endsection
