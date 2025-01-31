<?php

use App\TipoSociedad;
use Illuminate\Database\Seeder;

class TipoSociedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Sociedad por Acciones Simplificada: S.A.S.',
            'Sociedad Limitada: Ltda',
            'Empresa Unipersonal: E.U.',
            'Sociedad Anónima: S.A.',
            'Sociedad Colectiva: S.C.',
            'Sociedad en Comandita Simple: S. en C.',
            'Sociedad en Comandita por Acciones: S.C.A.',
            'Empresa Asociativa de Trabajo: E.A.T.',
        ])->map(function ($tipoSociedad) {
            TipoSociedad::updateOrCreate(['nombre' => $tipoSociedad]);
        });
    }
}
