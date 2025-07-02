@extends('layouts.app2')

@section('content')
    <div id="app">
        <pending-demographic-upload-form></pending-demographic-upload-form>
    </div>
@endsection

@section('title') Cargar cédulas – Demográfico @endsection
@section('header-content') Cargar cédulas @endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-dashboard mr-2"></i>Inicio</a></li>
    <li class="breadcrumb-item active">Cargar cédulas</li>
@endsection
