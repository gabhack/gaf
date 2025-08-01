@extends('layouts.app2')

@section('content')
<div>

    <h2>Portal de gestión de Solicitudes de Crédito</h2>
    <credit-requests-list :user="{{Auth::user()}}"></credit-requests-list>

</div>

@endsection
