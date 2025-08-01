<?php

namespace App\Imports;

use App\FechaVinc;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class FechaVincImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new FechaVinc([
            'doc'=>$row[0],
            'vinc'=>$row[1],
            'tp'=>$row[2],
        ]);
    }
}