@extends('layouts.app2')

@section('content')
<div class="container mt-5">
    <h2>Comparar Datos con Política General</h2>

    <div class="card mb-4">
        <div class="card-header">Política General Guardada</div>
        <div class="card-body">
            <p><strong>Tipo:</strong> {{ $parametros->tipo ?? 'N/A' }}</p>
            <p><strong>Género Masculino:</strong> {{ $parametros->masculino ? 'Sí' : 'No' }}</p>
            <p><strong>Género Femenino:</strong> {{ $parametros->femenino ? 'Sí' : 'No' }}</p>
            <p><strong>Edad Masculino:</strong> {{ $parametros->edad_masculino ?? 'N/A' }}</p>
            <p><strong>Edad Femenino:</strong> {{ $parametros->edad_femenino ?? 'N/A' }}</p>
            <p><strong>Tipo de Contrato Masculino:</strong> {{ $parametros->tipo_contrato_masculino ?? 'N/A' }}</p>
            <p><strong>Tipo de Contrato Femenino:</strong> {{ $parametros->tipo_contrato_femenino ?? 'N/A' }}</p>
            <p><strong>Cargo Masculino:</strong> {{ $parametros->cargo_masculino ?? 'N/A' }}</p>
            <p><strong>Cargo Femenino:</strong> {{ $parametros->cargo_femenino ?? 'N/A' }}</p>
            <p><strong>Código de Cupón:</strong> {{ $parametros->codigo_cupon ?? 'N/A' }}</p>
            <p><strong>Horas Extras Masculino:</strong> {{ $parametros->horas_extras_masculino ? 'Sí' : 'No' }}</p>
            <p><strong>Asignación AA Masculino:</strong> {{ $parametros->asignacion_aa_masculino ? 'Sí' : 'No' }}</p>
            <p><strong>Asignación AAA Masculino:</strong> {{ $parametros->asignacion_aaa_masculino ? 'Sí' : 'No' }}</p>
            <p><strong>Horas Extras Femenino:</strong> {{ $parametros->horas_extras_femenino ? 'Sí' : 'No' }}</p>
            <p><strong>Asignación AA Femenino:</strong> {{ $parametros->asignacion_aa_femenino ? 'Sí' : 'No' }}</p>
            <p><strong>Asignación AAA Femenino:</strong> {{ $parametros->asignacion_aaa_femenino ? 'Sí' : 'No' }}</p>
            <p><strong>Porcentaje Masculino:</strong> {{ $parametros->porcentaje_masculino ?? '0%' }}</p>
            <p><strong>Porcentaje Femenino:</strong> {{ $parametros->porcentaje_femenino ?? '0%' }}</p>
        </div>
    </div>

    <div class="alert alert-info">
        <p>
            Por favor, suba un archivo Excel con columnas que coincidan con los campos de la política general
            guardada. Solo se compararán los campos que no sean nulos.
        </p>
    </div>

    <form action="{{ route('parametros_comparativa.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="excel_file">Archivo Excel:</label>
            <input type="file" name="excel_file" id="excel_file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Subir y Comparar</button>
    </form>
</div>
@endsection
