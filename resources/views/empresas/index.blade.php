@extends('layouts.app2')

@section('content')
  <empresas :empresas="{{ $empresas }}"></empresas>
@endsection
