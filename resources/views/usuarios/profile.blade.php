@extends('layouts.app')

@section('js')
	<script type="text/javascript" src="{{asset('js/validaciones/Usuarios.js')}}"></script>	
@endsection

@section('content')
	<div class="col-md-12 col-md-offset-0">
	
		<h2>Mi Perfil</h2>
		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active">Mi Perfil</a>
			</li>
		</ul>
		<br />		
		<h4>Informaci&oacute;n Personal</h4>
		
		<div class="panel panel-default">
			<div class="panel-heading">Informaci&oacute;n Personal</div>
			<div class="panel-body">
				<form action="{{url('profile/store')}}" id="editar" method="post">
					{!! Form::token() !!}
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="nombre">Nombre: </label>								
							<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="{{$user->name}}" />
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
@stop