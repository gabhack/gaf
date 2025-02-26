<?php

use App\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Tarjeta de identidad',
            'Cédula de ciudadanía',
            'Cédula de extranjería',
            'NIT',
            'Pasaporte'
        ])->map(function ($tipoDocumento) {
            TipoDocumento::updateOrCreate(['nombre' => $tipoDocumento]);
        });
    }
}
