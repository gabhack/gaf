@extends('layouts.app')

@section('content')
	<div class="col-md-12 col-md-offset-0">		
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('usuarios')}}">Lista de Usuarios</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{url('usuarios/crear')}}">Crear Usuarios</a>
			</li>	
			<li class="nav-item">
				<a class="nav-link active">Editar Usuario</a>
			</li>		
		</ul>
		<br/>
		<div class="panel-body">
			<form action="{{url('usuarios/update', ['id' => $usuario->id])}}" method="post">
				{!! Form::token() !!}
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="name">Nombre</label>
						<input type="text" class="form-control" name="name" id="name" placeholder="Nombre" value="{{$usuario->name}}" required>
					</div>
					<div class="form-group col-md-6">
						<label for="email">Email</label>
						<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{$usuario->email}}" required>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="password">Contraseña</label>
						<input type="password" class="form-control" name="password" id="password" placeholder="">
					</div>
					<div class="form-group col-md-6">
						<label for="clave">Repetir Contraseña</label>
						<input type="password" class="form-control" name="clave" id="clave" placeholder="">
					</div>
				</div>
				@if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin() || IsCompany() || IsUserCreator())
					<div class="form-row">
						@if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin())
							<div class="form-group col-md-6">
								<label for="rol">Rol</label>
								<select class="form-control" name="rol" id="rol">
									<option value="">-Seleccione-</option>
									@foreach($roles as $rol)
										<option value="{{$rol->id}}" {{$rol->id == $usuario->roles_id ? 'selected="selected"' : ''}} >{{$rol->rol}}</option>
									@endforeach
								</select>
							</div>
						@endif
						<div class="form-group col-md-6">
							<div class="text-center col-md-12">
								<label class="text-bold">Características Adicionales</label>
							</div>
							@if (IsSuperAdmin() || IsHEGOAdmin())
								<div class="col-md-12">
									<div class="form-check">
										<input type="checkbox" id="hego" name="hego" {{$usuario->hego ? ' checked' : ''}}><label for="hego">Acceso HEGO</label>
									</div>
								</div>
							@endif
							@if (IsCompany() && !(IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()))
								<div class="col-md-12">
									<div class="form-check">
										<input type="checkbox" id="creausuarios" name="creausuarios" {{$usuario->creausuarios ? ' checked' : ''}}><label for="creausuarios">Hab. Crear Usuarios</label>
									</div>
								</div>
							@endif
							@if (!(IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()))
								<div class="col-md-4">
									<div class="form-check">
										<input type="checkbox" id="ami_silver" name="ami_silver" {{$usuario->ami_silver ? ' checked' : ''}}><label for="creausuarios">AMI®Silver</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-check">
										<input type="checkbox" id="ami_gold" name="ami_gold" {{$usuario->ami_gold ? ' checked' : ''}}><label for="creausuarios">AMI®Gold</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-check">
										<input type="checkbox" id="ami_diamond" name="ami_diamond" {{$usuario->ami_diamond ? ' checked' : ''}}><label for="creausuarios">AMI®Diamond</label>
									</div>
								</div>
							@endif
						</div>
					</div>
				@endif
				<button type="submit" class="btn btn-primary">Guardar</button>
			</form>
		</div>
	</div>

@endsection

@section('title')
    Usuario - Editar / {{$usuario->name}}
@endsection

@section('header-content')
    Usuarios
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li><a href="{{url('usuarios')}}">Usuarios</a></li>
    <li class="active">Editar Usuario</li>
    <li class="active">{{$usuario->name}}</li>
@endsection