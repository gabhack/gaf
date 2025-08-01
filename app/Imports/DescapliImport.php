<?php

namespace App\Imports;

use App\Descapli;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class DescapliImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new Descapli([
            'periodo' => $row[0],
            'concecutivo' => $row[1],
            'clase' => $row[2],
            'tercero' => $row[3],
            'nomtercero' => $row[4],
            'td' => $row[5],
            'doc' => $row[6],
            'nomp' => $row[7],
            'pagare' => $row[8],
            'porcentaje' => $row[9],
            'vaplicado' => $row[10],
            'vtotal' => $row[11],
            'vpagado' => $row[12],
            'saldo' => $row[13],
            'fgrab' => $row[14],
            'forma' => $row[15],
            'codentiant' => $row[16],
            'nonentant' => $row[17],
            'fechacesion' => $row[18],
            'tdesc' => $row[19],
            'p5d' => $row[20],
            'p4d' => $row[21],
        ]);
    }
}