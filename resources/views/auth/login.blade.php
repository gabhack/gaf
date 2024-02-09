@extends('layouts.applogin')

@section('content')
<div class="row h-100 img-background">
  <div class="col-xl-5 d-none d-xl-flex align-items-center justify-content-end">
    <img src="/img/INGRESO-IMG-GAF2.png" class="img-fluid w-75" />
  </div>
  <div class="col-xs-10 col-xl-7 d-flex align-items-center justify-content-center">
    <div class="text-center">
      <h1 class="title font-weight-bold text-spring-green mb-0">
        GAF
      </h1>
      <p class="text font-weight-bold mb-4">
        <!-- <span class="text-spring-green">AMI</span> -->
        AMI - HEGO
        <!-- <span class="text-spring-green">Inteligente</span> -->
      </p>
      <div>
        <form method="POST" action="{{ route('login') }}">
          {{ csrf_field() }}

          <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <input id="email" type="email" class="form-control" name="email" placeholder="Correo" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
              <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
            @endif
          </div>

          <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" required>

            @if ($errors->has('password'))
              <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
              </span>
            @endif
          </div>
          <div class="mt-3 pb-2">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" id="remember" name="remember" class="custom-control-input" {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label password" for="remember">Recuérdame</label>
              </div>
              <a class="text-black-pearl password" href="{{ route('password.request') }}">
                ¿Olvidaste la contraseña?
              </a>
            </div>
          <div class="align-items-center justify-content-center" style="display: grid;">
            <button type="submit" class="btn-submit">
              Ingresar al Portal Cliente
            </button>
            <!-- <button  class="btn-submit1">
              Crear nuevo usuario
            </button> -->
        </div>
          <div class="mt-3">
            <p class="text-spring-green font-weight-light power">Powered by <span class="font-weight-bold">GAF Solutions™</span></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
