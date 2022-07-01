@extends('layouts.app2')

@section('content') 
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active">Lista de Pagos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{url('pagos/pagar')}}">Realizar Pago</a>
    </li>
  </ul>
  <br/>
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
    
  @if ($lista)
    <table class="table table-hover table-striped table-condensed table-bordered">
      <thead>
        <tr>
          @if (IsSuperAdmin())
            <th class="text-left">Id Usuario</th>
          @endif
          <th class="text-left">Nombre</th>
          <th class="text-left">Concepto</th>
          <th class="text-left">Status</th>
          <th class="text-left">Monto</th>
        </tr>
      </thead>
      <tbody>
        @foreach($lista as $usuario)
        <tr>
          @if (IsSuperAdmin())
            <td>{{$usuario->rol->usuarioid}}</td>
          @endif  
          <td>{{$usuario->nombre}}</td>
          <td>{{$usuario->concepto}}</td>
          <td>{{$usuario->status}}</td>
          <td>{{$usuario->monto}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $links }}
  @else
    <div class="col-md-12">
      <h4>Aún no hay usuarios para mostrar.</h4>
    </div>  
  @endif
  
@endsection

@section('title')
    Pagos
@endsection

@section('header-content')
    Pagos
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li class="active">Pagos</li>
@endsection