@extends('layouts.app2')

@section('content')
    <history-component :user="{{Auth::user()}}"></history-component>
@endsection

