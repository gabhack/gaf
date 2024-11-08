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
		collect(['SAS', 'LTDA'])->map(function ($tipoSociedad) {
			TipoSociedad::updateOrCreate(['nombre' => $tipoSociedad]);
		});
	}
}
