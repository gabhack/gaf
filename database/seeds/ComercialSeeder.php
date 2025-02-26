<?php

use App\Comercial;
use Illuminate\Database\Seeder;

class ComercialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Comercial::class, 50)->create();
    }
}
