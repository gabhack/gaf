@extends('layouts.app2')

@section('content')
    <div class="container mt-5">
        <h2 style="font-size: 25px; font-weight: 500; line-height: 32.55px;">
            Comparar Datos con Política General
        </h2>
        <div style="font-size: 16px; font-weight: 400; line-height: 20.83px;">
            Política General Guardada
        </div>
        <table style="width: 750px; border-collapse: collapse; border: 1px solid #d3d8de; margin: 40px auto">
            <thead>
            </thead>
            <tbody
                style="
                font-size: 14px;
                font-weight: 400;
                line-height: 18.23px;
            ">
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: 700;">Tipo</td>
                    <td style="padding: 10px;">
                        {{ $parametros->tipo ?? 'N/A' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Género Masculino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->masculino ? 'Sí' : 'No' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Género Femenino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->femenino ? 'Sí' : 'No' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Edad Masculino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->edad_masculino ?? 'N/A' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Edad Femenino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->edad_femenino ?? 'N/A' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Tipo de Contrato Masculino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->tipo_contrato_masculino ?? 'N/A' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Tipo de Contrato Femenino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->tipo_contrato_femenino ?? 'N/A' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Cargo Masculino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->cargo_masculino ?? 'N/A' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Cargo Femenino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->cargo_femenino ?? 'N/A' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Código de Cupón</td>
                    <td style="padding: 10px;">
                        {{ $parametros->codigo_cupon ?? 'N/A' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Horas Extras Masculino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->horas_extras_masculino ? 'Sí' : 'No' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Asignación AA Masculino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->asignacion_aa_masculino ? 'Sí' : 'No' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Asignación AAA Masculino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->asignacion_aaa_masculino ? 'Sí' : 'No' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Horas Extras Femenino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->horas_extras_femenino ? 'Sí' : 'No' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Asignación AA Femenino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->asignacion_aa_femenino ? 'Sí' : 'No' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Asignación AAA Femenino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->asignacion_aaa_femenino ? 'Sí' : 'No' }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #d3d8de; background: #f4f5f7">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Porcentaje Masculino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->porcentaje_masculino ?? '0%' }} %
                    </td>
                </tr>
                <tr style="background: #fff">
                    <td style="padding: 10px; padding-left: 15px; font-weight: bold;">Porcentaje Femenino</td>
                    <td style="padding: 10px;">
                        {{ $parametros->porcentaje_femenino ?? '0%' }} %
                    </td>
                </tr>
            </tbody>
        </table>

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
    <style>

    </style>
@endsection
