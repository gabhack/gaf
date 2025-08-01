@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-danger">
                <div class="panel-heading">No autorizado</div>

                <div class="panel-body">
                    Su perfil no tiene permisos para ingresar a esta sección
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
