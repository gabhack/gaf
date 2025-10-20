@extends('layouts.app2')

@section('content')
    <demographic-data-avanzado></demographic-data-avanzado>
@endsection

@section('title')
    Datos Demográficos Avanzado
@endsection

@section('header-content')
    Datos Demográficos Avanzado
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-dashboard mr-2"></i>Inicio</a></li>
    <li class="breadcrumb-item active"><a href="{{ url('analisis-de-cartera-avanzado') }}">Datos Demográficos Avanzado</a></li>
@endsection
