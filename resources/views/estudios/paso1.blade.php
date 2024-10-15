@extends('layouts.hego')

@section('title')
    HEGO | Nuevo Estudio
@endsection

@section('panel')
    <div class="panel panel-default">
        <h2 class="mt-3 mb-3">Seleccione persona para estudio</h2>
        <div class="panel-body">
            <form action="{{ url('estudios/iniciar') }}" method="post" enctype="multipart/form-data">
                {!! Form::token() !!}
                <div class="form-row" id="panel-pagaduria">
                    <div class="form-group col-md-4">
                        <input type="number" class="form-control2" name="documento" id="documento" placeholder="Documento"
                               value="{{ isset($documento) ? $documento : '' }}">
                    </div>
                    <div class="form-group-col-md-2">
                        <button style="padding: 8px 16px; border-radius: 8px; border-color: none; font-size: 14px; font-weight: 500; line-height: 18.23px; min-height: 40px; background-color: #0E866C; border-color: #0E866C; color: white;" type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
