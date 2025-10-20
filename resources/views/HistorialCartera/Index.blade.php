@extends('layouts.app2')

@section('content')
    <historial-cartera></historial-cartera>
@endsection

@section('title')
    Historial
@endsection

@section('header-content')
    Historial de Análisis de Cartera
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-dashboard mr-2"></i>Inicio</a></li>
    <li class="breadcrumb-item active"><a href="{{ url('historial-cartera') }}">Historial</a></li>
@endsection
