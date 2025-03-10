<?php

use App\Sede;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sedes = [
            [
                'empresa_id' => 1,
                'departamento_id' => 6,
                'ciudad_id' => 169,
                'nombre' => 'Sede Bogotá 1'
            ],
            [
                'empresa_id' => 1,
                'departamento_id' => 6,
                'ciudad_id' => 169,
                'nombre' => 'Sede Bogotá 2'
            ],
            [
                'empresa_id' => 1,
                'departamento_id' => 6,
                'ciudad_id' => 169,
                'nombre' => 'Sede Bogotá 3'
            ],
            [
                'empresa_id' => 1,
                'departamento_id' => 7,
                'ciudad_id' => 178,
                'nombre' => 'Sede Cartagena 1'
            ],
            [
                'empresa_id' => 1,
                'departamento_id' => 7,
                'ciudad_id' => 178,
                'nombre' => 'Sede Cartagena 2'
            ]
        ];

        collect($sedes)->map(function ($sede) {
            Sede::updateOrCreate($sede);
        });
    }
}
