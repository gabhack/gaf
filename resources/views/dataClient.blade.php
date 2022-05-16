@extends('layouts.app2')

@section('content')
    <client-data-component :user="{{Auth::user()}}"></client-data-component>
@endsection

