@extends('layouts.app2')

@section('content')
    <crear-sedes :empresas="{{ $empresas }}" :user="{{ $user }}"></crear-sedes>
@endsection
