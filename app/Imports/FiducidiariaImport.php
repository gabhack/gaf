<?php

namespace App\Imports;

use App\Models\Fiducidiaria;
use App\Models\UploadProgress;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FiducidiariaImport implements ToModel, WithHeadingRow
{
    protected $progress;

    public function __construct(UploadProgress $progress)
    {
        $this->progress = $progress;
    }

    public function model(array $row)
    {
        $fiducidiaria = Fiducidiaria::updateOrCreate(
            ['documento' => $row['documento']],
            [
                'nombres' => $row['nombres'],
                'apellidos' => $row['apellidos'],
                'sexo' => $row['sexo'],
                'estado_civil' => $row['estado_civil'],
                'fecha_nacimiento_docente' => $row['fecha_nacimiento_docente'],
                'edad_actual' => $row['edad_actual'],
                'estado_pensionado' => $row['estado_pensionado'],
                'nom_depto' => $row['nom_depto'],
                'valor_mesada' => $row['valor_mesada'],
                'valorbruto' => $row['valorbruto'],
                'valordescuentos' => $row['valordescuentos'],
                'pago_net' => $row['pago_net']
            ]
        );

        // Update progress
        $this->progress->processed_rows++;
        $this->progress->save();

        return $fiducidiaria;
    }
}
