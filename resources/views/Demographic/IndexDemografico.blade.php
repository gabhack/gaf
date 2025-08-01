@extends('layouts.app2')

@section('content')
    <div id="app">
        <demographic-index></demographic-index>
    </div>
@endsection

@section('title')
    Datos Demográficos
@endsection

@section('header-content')
    Datos Demográficos
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-dashboard mr-2"></i>Inicio</a></li>
    <li class="breadcrumb-item active"><a href="{{ url('demographic') }}">Datos Demográficos</a></li>
@endsection
