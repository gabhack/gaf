@extends('layouts.app2')

{{-- Título de la pestaña --}}
@section('title', 'Pendientes – Demográfico')

{{-- Encabezado de la página --}}
@section('header-content', 'Pendientes por aprobar')

{{-- Migas de pan --}}
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ url('home') }}">
            <i class="fa fa-dashboard mr-2"></i>Inicio
        </a>
    </li>
    <li class="breadcrumb-item active">
        Pendientes por aprobar
    </li>
@endsection

{{-- Contenido principal --}}
@section('content')
    <div id="app">
        {{-- Le pasamos al componente Vue la bandera can-approve --}}
        <pending-demographic-upload-list
            :can-approve="@json(auth()->user()->can('permission','demografico.pending.list'))"
        >
        </pending-demographic-upload-list>
    </div>
@endsection
