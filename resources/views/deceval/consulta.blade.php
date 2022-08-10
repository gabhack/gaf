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
        <div id="consulta-container" class="row">
            <div data-v-1fb519a4="" class="col-6">
                <div data-v-1fb519a4="" class="panel mb-3">
                    <div data-v-1fb519a4="" class="panel-heading"><b data-v-1fb519a4="">RESULTADO DE LA CONSULTA</b></div>
                    <div data-v-1fb519a4="" class="panel-body">
                        <div data-v-1fb519a4="" class="row">
                            <a href="{{ asset('pagare.pdf') }}">abrir Pagare</a>
                        </div>
                    </div>
                </div>
                <iframe scrolling="auto" height="1100" width="850" src="{{ asset('pagare.pdf') }}" frameborder="0"></iframe>
            </div>
        </div>
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