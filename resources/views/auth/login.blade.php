@extends('layouts.applogin')

@section('content')
<div class="row h-100">
  <div class="col-md-6 d-none d-md-flex align-items-end">
    <img src="/img/amipersonas1.svg" class="img-fluid pb-3 w-100" />
  </div>
  <div class="col-xs-10 col-md-6 d-flex align-items-center justify-content-center">
    <div class="text-center">
      <h1 class="title font-weight-bold text-spring-green mb-0">
        AMI
      </h1>
      <p class="text font-weight-bold mb-4">
        <span class="text-spring-green">Analisis </span>
        de Mercado
        <span class="text-spring-green">Inteligente</span>
      </p>
      <div class="w-50 mx-auto text-center">
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

          <button type="submit" class="btn btn-outline-black-pearl btn-submit">
            Ingresar
          </button>

          <div class="mt-3">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" id="remember" name="remember" class="custom-control-input" {{ old('remember') ? 'checked' : '' }}>
              <label class="custom-control-label" for="remember">Recuérdame</label>
            </div>
            <a class="text-black-pearl" href="{{ route('password.request') }}">
              ¿Olvidaste la contraseña?
            </a>
          </div>

          <div class="mt-3">
            <p class="text-spring-green font-weight-light">Powered by <span class="font-weight-bold">GAF Solutions™</span></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
