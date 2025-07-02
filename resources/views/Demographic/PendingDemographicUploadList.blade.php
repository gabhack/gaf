@extends('layouts.app2')

@section('content')
    <div id="app">
        <pending-demographic-upload-list></pending-demographic-upload-list>
    </div>
@endsection

@section('title') Pendientes – Demográfico @endsection
@section('header-content') Pendientes por aprobar @endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-dashboard mr-2"></i>Inicio</a></li>
    <li class="breadcrumb-item active">Pendientes por aprobar</li>
@endsection
