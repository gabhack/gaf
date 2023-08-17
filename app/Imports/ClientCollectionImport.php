<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class ClientCollectionImport implements  ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $collection)
    {
        
    }
}
