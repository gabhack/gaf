@extends('layouts.app2')

@section('content')
<div class="container mt-5">
    <h2>Resultados de la Comparación</h2>

    @if($matchingRecords->isEmpty())
        <div class="alert alert-warning">No se encontraron coincidencias.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Código de Cupón</th>
                    <th>Cargo</th>
                    <th>Tipo de Contrato</th>
                    <!-- Agrega más columnas según sea necesario -->
                </tr>
            </thead>
            <tbody>
                @foreach($matchingRecords as $record)
                    <tr>
                        <td>{{ $record->doc }}</td>
                        <td>{{ $record->names }}</td>
                        <td>{{ $record->code }}</td>
                        <td>{{ $record->cargo }}</td>
                        <td>{{ $record->tipo_contrato }}</td>
                        <!-- Agrega más columnas según sea necesario -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-primary">Volver atrás</a>
    </div>
</div>
@endsection
