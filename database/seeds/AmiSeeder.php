<?php

use App\Ami;
use Illuminate\Database\Seeder;

class AmiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Visado',
            'Prospección de mercado',
            'Recuperación de cartera',
            'Investigación',
            'Localización de usuarios'
        ])->map(function ($ami) {
            Ami::updateOrCreate(['nombre' => $ami]);
        });
    }
}
