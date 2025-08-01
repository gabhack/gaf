<?php

namespace App\Imports;

use App\Fiducidiaria;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FiducidiariaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return Fiducidiaria::updateOrCreate(
            [
                'DOCUMENTO' => $row['documento'],
            ],
            [
                'NOMBRES' => $row['nombres'],
                'APELLIDOS' => $row['apellidos'],
                'SEXO' => $row['sexo'],
                'ESTADO_CIVIL' => $row['estado_civil'],
                'FECHA_NACIMIENTO_DOCENTE' => $this->transformDate($row['fecha_nacimiento_docente']),
                'EDAD_ACTUAL' => $row['edad_actual'],
                'ESTADO_PENSIONADO' => $row['estado_pensionado'],
                'NOM_DEPTO' => $row['nom_depto'],
                'VALOR_MESADA' => $row['valor_mesada'],
                'VALORBRUTO' => $row['valorbruto'],
                'VALORDESCUENTOS' => $row['valordescuentos'],
                'PAGO_NET' => $row['pago_net'],
            ]
        );
    }

    private function transformDate($value)
    {
        if (is_numeric($value)) {
            return Carbon::createFromDate(1900, 1, 1)->addDays($value - 2)->format('Y-m-d');
        }
        return null;
    }
}
