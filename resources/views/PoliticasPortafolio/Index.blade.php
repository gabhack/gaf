@extends('layouts.app2')

@section('content')
    <politicas-portafolio></politicas-portafolio>
@endsection

@section('title')
    Políticas
@endsection

@section('header-content')
    Políticas
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-dashboard mr-2"></i>Inicio</a></li>
    <li class="breadcrumb-item active"><a href="{{ url('politicas-portafolio') }}">Políticas</a></li>
@endsection
