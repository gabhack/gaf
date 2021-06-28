@extends('layouts.app')

@section('content')
	<div class="col-md-8 col-md-offset-2">		
		<div class="panel panel-default">
			<div class="panel-heading">Informaci&oacute;n Personal</div>
			<div class="panel-body">
				<form action="{{url('profile/store')}}" id="editar" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="nombre">Nombre: </label>								
							<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="{{$user->name}}"{{ IsCompany() || IsUser() ? ' disabled' : '' }}/>
							@if($errors -> first('nombre'))
								<label class="control-label"><img src="img/alert.png" title="{{$errors-> first('nombre')}}" alt="*" /></label>
							@endif
						</div>						
						<div class="form-group col-md-6">
							<label for="email">Email: </label>
							<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{$user->email}}"/>
							@if($errors -> first('email'))			
								<label class="control-label"><img src="img/alert.png" title="{{$errors-> first('email')}}" alt="*" /></label>
							@endif
						</div>
					</div>
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="password">Contrase&ntilde;a: </label>							
							<input type="password" class="form-control" name="password" id="password" placeholder="Contrase&ntilde;a" />@if($errors -> first('password'))
								<label class="control-label"><img src="img/alert.png" title="{{$errors-> first('password')}}" alt="*" /></label>
							@endif
						</div>
						
						<div class="form-group col-md-6">
							<label for="password2">Confirmar Contrase&ntilde;a: </label>								
							<input type="password" class="form-control" name="password2" id="password2" placeholder="Confirmar Contrase&ntilde;a"/>
						</div>							
					</div>
					
					<button type="submit" class="btn btn-primary">Modificar</button>
					
				</form>
			</div>
		</div>
	</div>
@endsection

@section('title')
    Mi Perfil
@endsection

@section('header-content')
    Mi Perfil
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li class="active">Mi Perfil</li>
@endsection