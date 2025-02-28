@extends('layouts.app2')

@section('content')
    <ver-empresas
        :empresa="{{ $empresa }}"
        :representante-legal="{{ $representanteLegal }}"
        :documento-empresa="{{ $documentoEmpresa }}"
        :usuario-empresa="{{ $usuarioEmpresa }}"
    ></ver-empresas>
@endsection
