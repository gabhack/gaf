@extends('layouts.app2')

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
    <section class="container-fluid">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('hego.estudios') ? 'active' : '' }}"
                           href="{{ route('hego.estudios') }}">
                            <b>Todas las simulaciones</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('hego.tesoreria') ? 'active' : '' }}"
                           href="{{ route('hego.tesoreria') }}">
                            <b>Tesorería</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('hego.cartera') ? 'active' : '' }}"
                           href="{{ route('hego.cartera') }}">
                            <b>Cartera</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('hego.venta-cartera') ? 'active' : '' }}"
                           href="{{ route('hego.venta-cartera') }}">
                            <b>Venta de Cartera</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('hego.nuevo-estudio') ? 'active' : '' }}"
                           href="{{ route('hego.nuevo-estudio') }}">
                            Nueva
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @yield('panel')
            </div>
        </div>
    </section>
@endsection

@section('css')
    <link href="{{ asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
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
