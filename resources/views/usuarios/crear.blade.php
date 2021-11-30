@extends('layouts.app2')

@section('content')
	<div class="col-md-12">
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link" href="{{url('usuarios')}}">Lista de Usuarios</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active">Crear Usuario</a>
			</li>
		</ul>
		<br/>
		<div class="panel-body">
			<form method="POST" action="{{url('usuarios/store')}}">
				{{ csrf_field() }}
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="name">Nombres</label>
						<input type="text" class="form-control" name="name" id="name" placeholder="Nombre" value="{{ old('name') }}" required>
					</div>
					<div class="form-group col-md-6">
						<label for="email">Email</label>
						<input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="password">Contraseña</label>
						<input type="password" class="form-control" name="password" id="password" placeholder="" required>
					</div>
					<div class="form-group col-md-6">
						<label for="clave">Repetir Contraseña</label>
						<input type="password" class="form-control" name="clave" id="clave" placeholder="" required>
					</div>
				</div>
				@if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin() || IsCompany() || IsUserCreator())
					<div class="form-row">
						@if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin())
							<div class="form-group col-md-6">
								<label for="rol">Rol</label>
								<select class="form-control" name="rol" id="rol" required>
									<option value="">-Seleccione-</option>
									@foreach($roles as $rol)
										<option value="{{$rol->id}}">{{$rol->rol}}</option>
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
										<input type="checkbox" id="hego" name="hego"><label for="hego">Acceso HEGO</label>
									</div>
								</div>
							@endif
							@if (IsCompany() && !(IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()))
								<div class="col-md-12">
									<div class="form-check">
										<input type="checkbox" id="creausuarios" name="creausuarios" ><label for="creausuarios">Hab. Crear Usuarios</label>
									</div>
								</div>
							@endif
							@if (!(IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()))
								<div class="col-md-4">
									<div class="form-check">
										<input type="checkbox" id="ami_silver" name="ami_silver"><label for="creausuarios">AMI®Silver</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-check">
										<input type="checkbox" id="ami_gold" name="ami_gold"><label for="creausuarios">AMI®Gold</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-check">
										<input type="checkbox" id="ami_diamond" name="ami_diamond"><label for="creausuarios">AMI®Diamond</label>
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
    Usuario / Crear
@endsection

@section('header-content')
    Crear Usuario
@endsection

@section('breadcrumb')
    <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li><a href="{{url('usuarios')}}">Usuarios</a></li>
    <li class="active">Crear Usuario</li>
@endsection