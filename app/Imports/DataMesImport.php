<?php

namespace App\Imports;

use App\DatamesFopep;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class DataMesImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new DatamesFopep([
            'fondo' => $row[0],
            'td' => $row[1],
            'doc' => $row[2],
            'x' => $row[3],
            'nomp' => $row[4],
            'fecnacimient' => $row[5],
            'dir' => $row[6],
            'dpto' => $row[7],
            'mnpio' => $row[8],
            'tp' => $row[9],
            'nbanco' => $row[10],
            'sucursal' => $row[11],
            'tel' => $row[12],
            'cel' => $row[13],
            'correo' => $row[14],
            'vpension' => $row[15],
            'vsalud' => $row[16],
            'vembargos' => $row[17],
            'vdesc' => $row[18],
            'cupo' => $row[19],
        ]);
    }
}
