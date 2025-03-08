@extends('layouts.app2')

@section('content')
    <editar-sedes :sede="{{ $sede }}" :empresas="{{ $empresas }}"></editar-sedes>
@endsection
