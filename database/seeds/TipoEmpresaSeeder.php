<?php

use App\TipoEmpresa;
use Illuminate\Database\Seeder;

class TipoEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(['Fondo de inversión', 'Fiduciaria', 'Originadores'])->map(function ($tipoEmpresa) {
            TipoEmpresa::updateOrCreate(['nombre' => $tipoEmpresa]);
        });
    }
}
