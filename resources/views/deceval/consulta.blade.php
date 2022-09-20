@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div tabindex="0" aria-label="Loading" class="vld-overlay is-active is-full-page" style="display: none;">
        <div class="vld-background"></div>
        <div class="vld-icon">
            <svg viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" width="64" height="64" stroke="#0CEDB0">
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(1 1)" stroke-width="2">
                        <circle stroke-opacity=".25" cx="18" cy="18" r="18"></circle>
                        <path d="M36 18c0-9.94-8.06-18-18-18">
                            <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.8s" repeatCount="indefinite"></animateTransform>
                        </path>
                    </g>
                </g>
            </svg>
        </div>
    </div>
    <div>
        @if ($resultado)
        <form action="{{ url('deceval/firmar') }}" method="post" class="form-group col-md-12">
            <input type="hidden" id="step" name="step" value="{{$step}}" />
            <input type="hidden" id="phoneListStr" name="phoneListStr" value="{{$phoneListStr}}" />
            <input type="hidden" id="application" name="application" value="{{$application}}" />
            <input type="hidden" id="idDocumentoPagare" name="idDocumentoPagare" value="{{$idDocumentoPagare}}" />
            <input type="hidden" id="otorganteTipoId" name="otorganteTipoId" value="{{$otorganteTipoId}}" />
            <input type="hidden" id="otorganteNumId" name="otorganteNumId" value="{{$otorganteNumId}}" />
            <input type="hidden" id="numPagareEntidad" name="numPagareEntidad" value="{{$numPagareEntidad}}" />
            <input type="hidden" id="codigoDepositante" name="codigoDepositante" value="{{$codigoDepositante}}" />
            <input type="hidden" id="fecha" name="fecha" value="{{$fecha}}" />
            <input type="hidden" id="hora" name="hora" value="{{$hora}}" />
            {{ csrf_field() }}

            <div id="consulta-container" class="row">
                <div data-v-1fb519a4="" class="col-6">
                    <div data-v-1fb519a4="" class="panel mb-3">

                        <div data-v-1fb519a4="" class="panel-heading">
                            <b data-v-1fb519a4="">RESULTADO DE LA CONSULTA</b>
                        </div>

                        <div data-v-1fb519a4="" class="panel-body">
                            @if ($errorFirma)
                                <div data-v-1fb519a4="" class="row" style="font-size:60%;color:red;">
                                    {{$errorFirma}}
                                </div>
                            @else
                                <div data-v-1fb519a4="" class="row"></div>
                            @endif
                            @if ($step==='confirmCode' || $step==='initial')
                                <div data-v-1fb519a4="" class="row">
                                    <div class="form-group col-md-4">
                                        <b class="panel-label">TELEFONO DE CONFIRMACION:</b>
                                        <select name="phone" required="required" id="phone" class="form-control text-center">
                                            @foreach ($phoneList as $phone)
                                            <option value="{{$phone}}">{{$phone}}</option> 
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        @if ($confirmCode==='confirmCode')
                                        <b class="panel-label">CODIGO DE CONFIRMACION:</b>
                                        <input type="text" id="code" required="required" name="code" value="" />
                                            @if($ambiente===0)
                                                <span>Codigo Generado Para UAT:{{$code}}</span>
                                            @else
                                            @endif
                                        @else

                                        @endif
                                    </div>
                                </div>
                            @else
                            @endif
                            <div data-v-1fb519a4="" class="row">
                                <div data-v-1fb519a4=""  class="form-group col-md-6">
                                    @if ($step==='confirmCode')
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fa fa-pencil"></i> Confirmar Codigo
                                        </button>
                                    @elseif ($step ==='initial')
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fa fa-pencil"></i> Generar Codigo
                                        </button>    
                                    @elseif($step==='failCreateFlow')
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fa fa-pencil"></i> Regenerar Flujo
                                        </button>
                                        @elseif($step==='confirmCode2')
                                    @else
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fa fa-pencil"></i> Firmar Documento
                                        </button>
                                    @endif
                                </div>

                                <div data-v-1fb519a4=""  class="form-group col-md-4">
                                    <a href="{{ asset('pagare.pdf') }}" class="btn btn-primary"><i class="fa fa-envelope-open"></i> Abrir Pagare</a>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <iframe scrolling="auto" height="1100" width="850" src="{{ asset('pagare.pdf') }}?alternate={{$step}}" frameborder="0"></iframe>
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