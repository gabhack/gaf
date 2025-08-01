@extends('layouts.app2')

@section('title')
    Inicio
@endsection

@section('header-content')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active"></li>
@endsection

@section('content')
    <div class="container-fluid"  style="background-color: #f9fafc;">
        <dashboard></dashboard>
        {{-- @if (IsUser() || (IsCompany() && !IsSuperAdmin()))
            <div class="row mb-5">
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <div class="box-rounded bg-black-pearl text-white text-center">
                        <span class="total-count text-center">{{ $labels['total_consultas'] }}</span>
                        <p class="box-label"><span>TOTAL CONSULTAS</span></p>
                        <div class="text-right">
                            <a href="{{ url('/consultas/list') }}" class="text-white">
                                Ver más <i class="fas fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @if (IsUserCreator() || IsCompany())
                    <div class="col-12 col-md-4">
                        <div class="box-rounded bg-gainsboro text-black-pearl text-center">
                            <span class="total-count text-center">{{ $labels['usuarios_activos'] }}</span>
                            <p class="box-label text-center"><span>USUARIOS ACTIVOS</span></p>
                            <div class="text-right">
                                <a href="{{ url('usuarios') }}" class="text-black-pearl">
                                    Ver más <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="box-rounded bg-gainsboro text-black-pearl text-center">
                            <span class="total-count text-center">{{ $labels['usuarios_activos'] }}</span>
                            <p class="box-label text-center"><span>COMPAÑIAS ACTIVAS</span></p>
                            <div class="text-right">
                                <a href="{{ url('usuarios') }}" class="text-black-pearl">
                                    Ver más <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                      </div>
                @endif
            </div>
            @if (IsUser() || IsUserCreator() || (IsCompany() && !IsSuperAdmin()))
                <div class="row">
                    <div class="col-12 col-lg-12 col-xl-12">
                        <p class="h4 text-uppercase font-weight-exbold mb-3">
                            Uso de Consultas AMI: <span class="font-weight-normal">{{ $labels['mes_actual'] }}</span>
                        </p>
                        <div class="row d-flex" style="justify-content: space-between;">
                            <div class="col-12 col-sm-6 col-lg-4 mb-5 mb-lg-0">
                                <div class="box-square bg-caribbean-green text-white">
                                    <div class="mb-5">
                                        <p class="box-label mb-0">
                                            {{ $labels['consultas']['silver'] }} <span
                                                class="font-weight-bold">Visitas</span>
                                        </p>
                                        <p class="text-uppercase">AMI® Silver</p>
                                    </div>
                                    <p class="box-percentage mb-0">
                                        {{ $labels['total_consultas'] ? ($labels['consultas']['silver'] / $labels['total_consultas']) * 100 : '0' }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4 mb-5 mb-lg-0">
                                <div class="box-square bg-black-pearl text-white">
                                    <div class="mb-5">
                                        <p class="box-label mb-0">
                                            {{ $labels['consultas']['gold'] }} <span
                                                class="font-weight-bold">Visitas</span>
                                        </p>
                                        <p class="text-uppercase">AMI® Gold</p>
                                    </div>
                                    <p class="box-percentage mb-0">
                                        {{ $labels['total_consultas'] ? ($labels['consultas']['gold'] / $labels['total_consultas']) * 100 : '0' }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="box-square bg-gainsboro text-white">
                                    <div class="mb-5">
                                        <p class="box-label mb-0">
                                            {{ $labels['consultas']['diamond'] }} <span
                                                class="font-weight-bold">Visitas</span>
                                        </p>
                                        <p class="text-uppercase">AMI® Diamond</p>
                                    </div>
                                    <p class="box-percentage mb-0">
                                        {{ $labels['total_consultas'] ? ($labels['consultas']['diamond'] / $labels['total_consultas']) * 100 : '0' }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif --}}
    </div>
@endsection
