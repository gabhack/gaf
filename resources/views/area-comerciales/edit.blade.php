@extends('layouts.app2')

@section('content')
    <editar-area-comerciales
        :comercial="{{ $comercial }}"
        :usuario-comercial="{{ $usuarioComercial }}"
    ></editar-area-comerciales>
@endsection
