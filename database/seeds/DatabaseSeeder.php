<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TipoEmpresaSeeder::class);
        $this->call(TipoSociedadSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(AmiSeeder::class);
        $this->call(HegoSeeder::class);
        $this->call(UbicacionesSeeder::class);
        $this->call(EmpresaSeeder::class);
        $this->call(SedeSeeder::class);
        $this->call(ComercialSeeder::class);
        $this->call(PermissionsTableSeeder::class);
    }
}
