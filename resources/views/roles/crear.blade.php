@extends('layouts.app')

@section('content')
    <div class="col-md-12 col-md-offset-0">
        <h2>Roles</h2>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="{{ url('roles') }}"
                >
                    Lista de Roles
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active">Crear Rol</a>
            </li>
        </ul>
        <br />
        <h4>Crear Roles</h4>
        <div class="panel panel-default">
            <div class="panel-heading">Crear Roles</div>
            <div class="panel-body">
                <form
                    action="{{ url('roles/store') }}"
                    method="post"
                >
                    {!! Form::token() !!}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="role">Rol</label>
                            <input
                                class="form-control"
                                id="role"
                                name="role"
                                type="text"
                                placeholder="Rol"
                            >
                        </div>
                    </div>
                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        Guardar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
