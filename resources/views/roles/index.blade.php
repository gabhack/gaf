@extends('layouts.app')

@section('content')
    <h2>Roles</h2>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active">Lista de Roles</a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ url('roles/crear') }}"
            >
                Crear Rol
            </a>
        </li>
    </ul>
    <br />
    <h4>Lista de Roles</h4>
    <table class="table table-hover table-striped table-condensed table-bordered">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Rol</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lista as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td class="text-center">
                        <a
                            class="btn btn-info"
                            href="{{ url('roles/edit', ['id' => $role->id]) }}"
                            title="Modificar"
                        >
                            <i
                                class="fa fa-pencil-square-o"
                                aria-hidden="true"
                            ></i>
                        </a>
                        <a
                            class="btn btn-info"
                            href="{{ url('roles/delete', ['id' => $role->id]) }}"
                            title="Eliminar"
                            onclick="return confirm('Seguro que desea eliminar este item y su informacion relacionada?')"
                        >
                            <i
                                class="fa fa-trash-o"
                                aria-hidden="true"
                            ></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
