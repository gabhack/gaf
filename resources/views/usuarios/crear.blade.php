@extends('layouts.app2')

@section('content')
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="{{ url('usuarios') }}"
                >
                    Lista de Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active">Crear Usuario</a>
            </li>
        </ul>
        <br />
        <div class="panel-body">
            <form
                method="POST"
                action="{{ url('usuarios/store') }}"
            >
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Nombres</label>
                        <input
                            class="form-control"
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            placeholder="Nombre"
                            required
                        >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input
                            class="form-control"
                            id="email"
                            name="email"
                            type="email"
                            placeholder="Email"
                            required
                        >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Contraseña</label>
                        <input
                            class="form-control"
                            id="password"
                            name="password"
                            type="password"
                            placeholder=""
                            required
                        >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="clave">Repetir Contraseña</label>
                        <input
                            class="form-control"
                            id="clave"
                            name="clave"
                            type="password"
                            placeholder=""
                            required
                        >
                    </div>
                </div>
                @if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin() || IsCompany() || IsUserCreator())
                    <div class="form-row">
                        @if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin())
                            <div class="form-group col-md-6">
                                <label for="role">Rol</label>
                                <select
                                    class="form-control"
                                    id="role"
                                    name="role"
                                    required
                                >
                                    <option value="">-Seleccione-</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
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
                                        <input
                                            id="hego"
                                            name="hego"
                                            type="checkbox"
                                        >
                                        <label for="hego">Acceso HEGO</label>
                                    </div>
                                </div>
                            @endif
                            @if (IsCompany() && !(IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()))
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input
                                            id="creausuarios"
                                            name="creausuarios"
                                            type="checkbox"
                                        >
                                        <label for="creausuarios">Hab. Crear Usuarios</label>
                                    </div>
                                </div>
                            @endif
                            @if (!(IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()))
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input
                                            id="ami_silver"
                                            name="ami_silver"
                                            type="checkbox"
                                        >
                                        <label for="creausuarios">AMI®Silver</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input
                                            id="ami_gold"
                                            name="ami_gold"
                                            type="checkbox"
                                        >
                                        <label for="creausuarios">AMI®Gold</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input
                                            id="ami_diamond"
                                            name="ami_diamond"
                                            type="checkbox"
                                        >
                                        <label for="creausuarios">AMI®Diamond</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                <button
                    class="btn btn-primary"
                    type="submit"
                >
                    Guardar
                </button>
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
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li><a href="{{ url('usuarios') }}">Usuarios</a></li>
    <li class="active">Crear Usuario</li>
@endsection
