@extends('layouts.hego')

@section('title')
    HEGO | Nuevo Estudio
@endsection

@section('panel')
    <div class="panel panel-default">
        <div class="panel-heading">Seleccione persona para estudio</div>
        <div class="panel-body">
            <form action="{{ url('estudios/iniciar') }}" method="post" enctype="multipart/form-data">
                {!! Form::token() !!}
                <div class="form-row" id="panel-pagaduria">
                    <div class="form-group col-md-4">
                        <input type="number" class="form-control" name="documento" id="documento" placeholder="Documento"
                               value="{{ isset($documento) ? $documento : '' }}">
                    </div>
                    <div class="form-group-col-md-2">
                        <button type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
