@extends('layouts.app2')

@section('content')
    <ver-area-comerciales
        :comercial="{{ $comercial }}"
        :usuario-comercial="{{ $usuarioComercial }}"
    ></ver-area-comerciales>
@endsection
