@extends('layouts.applogin')

@section('content')
<div class="row">
    <div class="col-md-6 d-none d-md-block d-lg-block .d-xl-block">
        <div class="bg-image"></div>
    </div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-0 col-md-6 col-md-offset-0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xs-12 col-md-8 col-lg-6 col-lg-offset-3">
                    <div class="panel">
                        <div class="panel-body">
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
        
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <p class="font-varela title-login text-center">
                                            AMI
                                        </p>
                                    </div>
                                </div>
        
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <input id="email" type="email" class="form-control" name="email" placeholder="Correo" value="{{ old('email') }}" required autofocus>
        
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" required>
        
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recuérdame
                                            </label>
                                        </div>
                                    </div>
        
                                    <div class="col-md-6 text-right">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            Olvidaste la contraseña?
                                        </a>
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-submit">
                                            Entrar
                                        </button>
                                    </div>
                                </div>
        
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <p class="gaf-login text-center">Powered by <span>GAF Solutions™</span></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
