@extends('layouts.app2')

@section('content')
    <client-data-component-draft :user="{{ Auth::user() }}"></client-data-component-draft>
@endsection
