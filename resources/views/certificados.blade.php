@extends('layouts.app2')

@section('content')
    <certificados :user="{{Auth::user()}}"></client-data-component>
@endsection

