@extends('layouts.app2')

@section('content')
    <refund-component :user="{{Auth::user()}}"></client-data-component>
@endsection

