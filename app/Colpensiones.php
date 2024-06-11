<?php

namespace App\Imports;

use App\Models\Colpensiones;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ColpensionesImport implements ToModel, WithHeadingRow
{
    protected $connection = 'pgsql';

    public function model(array $row)
    {
        return Colpensiones::updateOrCreate(
            ['documento' => $row['documento']],
            [
                'primer_apellido' => $row['primer_apellido'],
                'segundo_apellido' => $row['segundo_apellido'],
                'primer_nombre' => $row['primer_nombre'],
                'segundo_nombre' => $row['segundo_nombre'],
                'direccion' => $row['direccion'],
                'telefono' => $row['telefono'],
                'correo_electronico' => $row['correo_electronico'],
                'nacimiento' => $row['nacimiento'],
                'sexo' => $row['sexo'],
                'departamento' => $row['departamento'],
                'municipio' => $row['municipio'],
                'vpensiones' => $row['vpensiones'],
                'vsalud' => $row['vsalud'],
                'vembargo' => $row['vembargo'],
                'vedescuentos' => $row['vedescuentos'],
                'capacidad' => $row['capacidad'],
            ]
        );
    }
}
