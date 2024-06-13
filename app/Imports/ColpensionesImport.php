<?php
namespace App\Imports;

use App\Models\Colpensiones;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ColpensionesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $nacimiento = $this->transformDate($row['nacimiento']);
    
        return Colpensiones::updateOrCreate(
            [
                'documento' => $row['documento'],
            ],
            [
                'primer_apellido' => $row['primer_apellido'],
                'segundo_apellido' => $row['segundo_apellido'],
                'primer_nombre' => $row['primer_nombre'],
                'segundo_nombre' => $row['segundo_nombre'],
                'direccion' => $row['direccion'],
                'telefono' => $row['telefono'],
                'correo_electronico' => $row['correo_electronico'],
                'nacimiento' => $nacimiento,
                'sexo' => $row['sexo'],
                'departamento' => $row['departamento'],
                'municipio' => $row['municipio'],
                'vpensiones' => $row['vpensiones'],
                'vsalud' => $row['vsalud'],
                'vembargo' => $row['vembargo'],
                'vdescuentos' => $row['vdescuentos'],
                'capacidad' => $row['capacidad'],
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
