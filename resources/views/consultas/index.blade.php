@extends('layouts.app2')

@section('title')
	Consulta AMI®
@endsection

@section('header-content')
	Consulta AMI®
@endsection

@section('breadcrumb')
	<li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
	<li class="breadcrumb-item active">Consulta AMI®</li>
@endsection

@section('content')
	<consultas-index>
		<template v-slot:csrf-token>
			@csrf
		</template>
		<template v-slot:tipo-consulta-opciones>
			@if (AMISilverHabilitado())
				<b-form-select-option value="1">AMI®Silver</b-form-select-option>
			@endif
			@if (AMIGoldHabilitado())
				<b-form-select-option value="2">AMI®Gold</b-form-select-option>
			@endif
			@if (AMIDiamondHabilitado())
				<b-form-select-option value="3">AMI®Diamond</b-form-select-option>
			@endif
		</template>
	</consultas-index>
@endsection