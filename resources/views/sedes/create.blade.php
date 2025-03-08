@extends('layouts.app2')

@section('content')
    <crear-sedes :empresas="{{ $empresas }}"></crear-sedes>
@endsection
