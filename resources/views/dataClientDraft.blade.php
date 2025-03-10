@extends('layouts.app2')

@php
    $user = Auth::user();
    $user->role = $user->role;
@endphp

@section('content')
    <client-data-component-draft :user="{{ $user }}"></client-data-component-draft>
@endsection
