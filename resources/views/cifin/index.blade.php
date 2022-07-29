@extends('layouts.app2')

@section('content') 

  @if (isset($message))
    <div id="toast-message" class="col-md-12">
      <div class="row">
        <div class="col-md-4">
          <div class="alert alert-{{ $message['tipo'] }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-exclamation"></i> {{ $message['titulo'] }}</h4>
            {{ $message['mensaje'] }}
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="container-fluid">
     <div tabindex="0" aria-label="Loading" class="vld-overlay is-active is-full-page" style="display: none;">
        <div class="vld-background"></div>
        <div class="vld-icon">
           <svg viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" width="64" height="64" stroke="#0CEDB0">
              <g fill="none" fill-rule="evenodd">
                 <g transform="translate(1 1)" stroke-width="2">
                    <circle stroke-opacity=".25" cx="18" cy="18" r="18"></circle>
                    <path d="M36 18c0-9.94-8.06-18-18-18">
                       <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.8s" repeatCount="indefinite"></animateTransform>
                    </path>
                 </g>
              </g>
           </svg>
        </div>
     </div>
     <div>
        <!----> 
        <div id="consulta-container" class="row">
          <div class="panel mb-3 col-md-12">
            <div class="panel-heading">
              <b>REALIZAR CONSULTA</b>
            </div>
              <div class="panel-body">
                 <div class="row">
                  <form action="{{ url('cifin/consultar') }}" method="post" class="form-group col-md-12">
                    {{ csrf_field() }}
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <b class="panel-label">CEDULA:</b>
                        <input required="required" name="cedula" type="number" class="form-control text-center">
                      </div>
                      <div class="form-group col-md-6">
                        <b class="panel-label">APELLIDO PATERNO:</b> 
                        <input required="required" name="apellido" type="text" class="form-control text-center" onkeyup="mayus(this);">
                      </div>
                    </div>
                    <div class="form-group col-md-12">
                      <button type="submit" class="btn btn-primary float-right">Consultar</button>
                    </div>
                  </form>
                 </div>
              </div>
           </div>
        </div>
     </div>
  </div>
@endsection

@section('title')
    Consulta Cifin
@endsection

@section('header-content')
    Consulta Cifin
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li class="active">Cifin</li>
@endsection

@section('js')
  <script>
    function mayus(e) {
      e.value = e.value.toUpperCase();
    }
  </script>
@endsection