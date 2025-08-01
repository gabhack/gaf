<?php

namespace App\Imports;

use App\Descnoap;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class DescnoapImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new Descnoap([            
            'clase' => $row[0],
            'tercero' => $row[1],
            'nomtercero' => $row[2],
            'td' => $row[3],
            'doc' => $row[4],
            'nomp' => $row[5],
            'pagare' => $row[6],
            'porcentaje' => $row[7],
            'vfijo' => $row[8],
            'vaplicado' => $row[9],
            'vtotal' => $row[10],
            'vpagado' => $row[11],
            'saldo' => $row[12],
            'fgrab' => $row[13],
            'forma' => $row[14],
            'incon' => $row[15],
            'codentiant' => $row[16],
            'nonentant' => $row[17],
            'fechacesion' => $row[18],
            'tdesc' => $row[19],
        ]);
    }
}