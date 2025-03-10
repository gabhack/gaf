@extends('layouts.app2')

@section('content')
    <editar-sedes :sede="{{ $sede }}" :empresas="{{ $empresas }}" :user="{{ $user }}"></editar-sedes>
@endsection
