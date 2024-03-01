@extends('layouts.app2')

@section('title')
Inicio
@endsection

@section('header-content')

@endsection

@section('breadcrumb')
<li class="breadcrumb-item active"></li>
@endsection

<link href="{{ asset('css/ami.css') }}" rel="stylesheet">

@section('content')
<div class="row">
  <div class="col-12 d-flex justify-content-center align-items-center pt-0" style=" background: #021b1e;">
    <h1 style="font-weight: bold; color: #10dd8e;">Análisis de Mercado Inteligente</h1>
  </div>
</div>
<div class="container-fluid row">
  <div class="col-12 col-md-6 pt-5">
    <div class="box-rounded bg-gainsboro text-black-pearl text-left">
      <p class="box-label text-left"><span>VISADO</span></p>
      <ul>
        <li>
          <a href="{{ url('dataClientDraft') }}" style="color: black;">Nueva Consulta</a>
        </li>
        <li>
          <a href="{{ url('historyClient' )}}" style="color: black;">Listados de Consultas</a>
        </li>
        <b-collapse id="collapse-1">
           {{-- <li>
              <a href="" style="color: black;"></a>
              </li>
              <li>
              <a href="" style="color: black;"></a>
              </li> --}}
        </b-collapse>
        <div class="d-flex justify-content-end"><a v-b-toggle.collapse-1 class="text-right" style="cursor: pointer; color: #10dd8e;">Ver más <i class="fas fa-angle-right"></i></a></div>
      </ul>
    </div>
  </div>
  <div class="col-12 col-md-6 pt-5">
    <div class="box-rounded bg-gainsboro text-black-pearl text-left">
      <p class="box-label text-left"><span>PROSPECCIÓN DE MERCADO</span></p>
      <ul>
        <li>
          <a href="" style="color: black;">Consulta Gold</a>
        </li>
        <li>
          <a href="{{ url('refundCartera') }}" style="color: black;">Consulta Diamond</a>
        </li>

        <b-collapse id="collapse-2">
           {{-- <li>
              <a href="{{ url('historyClient' )}}" style="color: black;"></a>
              </li>
              <li>
              <a href="{{ url('historyClient' )}}" style="color: black;"></a>
              </li>  --}}
        </b-collapse>
        <div class="d-flex justify-content-end"><a v-b-toggle.collapse-2 class="text-right" style="cursor: pointer; color: #10dd8e;">Ver más <i class="fas fa-angle-right"></i></a></div>
        </ul>
    </div>
  </div>
  <div class="col-12 col-md-6 pt-5">
    <div class="box-rounded bg-gainsboro text-black-pearl text-left">
      <p class="box-label text-left"><span>RECUPERACIÓN DE CARTERA</span></p>
      <ul>
        <li>
          <a href="" style="color: black;">Consulta Gold</a>
        </li>
        <li>
          <a href="{{ url('refundCartera') }}" style="color: black;">Consulta Diamond</a>
        </li>

        <b-collapse id="collapse-3">
           {{-- <li>
              <a href="" style="color: black;"></a>
              </li>
              <li>
              <a href="" style="color: black;"></a>
              </li>  --}}
        </b-collapse>
        <div class="d-flex justify-content-end"><a v-b-toggle.collapse-3 class="text-right" style="cursor: pointer; color: #10dd8e;">Ver más <i class="fas fa-angle-right"></i></a></div>
      </ul>
    </div>
  </div>
  <div class="col-12 col-md-6 pt-5">
    <div class="box-rounded bg-gainsboro text-black-pearl text-left">
      <p class="box-label text-left"><span>INVESTIGACIÓN</span></p>
      <ul>
        <li>
          <a href="" style="color: black;">Investigción de Bienes Comercial y Localización</a>
        </li>
        <li>
          <a href="{{ url('certificados' )}}" style="color: black;">Certificados de Nacimiento - Defunción</a>
        </li>

        <b-collapse id="collapse-4">
          <li>
            <a href="{{ url('historyClient' )}}" style="color: black;">Datos Personales - Laborales</a>
          </li>
          <li>
            <a href="{{ url('historyClient' )}}" style="color: black;">Información Financiera</a>
          </li>
        </b-collapse>
        <div class="d-flex justify-content-end"><a v-b-toggle.collapse-4 class="text-right" style="cursor: pointer; color: #10dd8e;">Ver más <i class="fas fa-angle-right"></i></a></div>
      </ul>
    </div>
  </div>
</div>

@endsection