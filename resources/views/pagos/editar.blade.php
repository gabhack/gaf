@extends('layouts.app')

@section('content')
    <div class="col-md-12 col-md-offset-0">
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
                <a
                    class="nav-link"
                    href="{{ url('usuarios/crear') }}"
                >
                    Crear Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active">Editar Usuario</a>
            </li>
        </ul>
        <br />
        <div class="panel-body">
            <form
                action="{{ url('usuarios/update', ['id' => $usuario->id]) }}"
                method="post"
            >
                {!! Form::token() !!}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Nombre</label>
                        <input
                            class="form-control"
                            id="name"
                            name="name"
                            type="text"
                            value="{{ $usuario->name }}"
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
                            value="{{ $usuario->email }}"
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
                                >
                                    <option value="">-Seleccione-</option>
                                    @foreach ($roles as $role)
                                        <option
                                            value="{{ $role->id }}"
                                            {{ $role->id == $usuario->role_id ? 'selected="selected"' : '' }}
                                        >
                                            {{ $role->name }}
                                        </option>
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
                                            {{ $usuario->hego ? ' checked' : '' }}
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
                                            {{ $usuario->creausuarios ? ' checked' : '' }}
                                        />
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
                                            {{ $usuario->ami_silver ? ' checked' : '' }}
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
                                            {{ $usuario->ami_gold ? ' checked' : '' }}
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
                                            {{ $usuario->ami_diamond ? ' checked' : '' }}
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
    Usuario - Editar / {{ $usuario->name }}
@endsection

@section('header-content')
    Usuarios
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i>Inicio</a></li>
    <li><a href="{{ url('usuarios') }}">Usuarios</a></li>
    <li class="active">Editar Usuario</li>
    <li class="active">{{ $usuario->name }}</li>
@endsection
