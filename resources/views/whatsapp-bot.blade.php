@extends('layouts.app2')

@section('title')
WhatsApp Bot
@endsection

@section('header-content')
WhatsApp Bot
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
  <a href="{{ url('home') }}">
    <i class="fa fa-dashboard mr-2"></i>Inicio
  </a>
</li>
<li class="breadcrumb-item active">WhatsApp Bot</li>
@endsection

@section('content')
<whatsapp-bot></whatsapp-bot>
@endsection