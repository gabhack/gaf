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
				'ciudad_id' => 13,
				'nombre' => 'Sede bogotá 1'
			],
			[
				'ciudad_id' => 13,
				'nombre' => 'Sede bogotá 2'
			],
			[
				'ciudad_id' => 13,
				'nombre' => 'Sede bogotá 3'
			],
			[
				'ciudad_id' => 22,
				'nombre' => 'Sede cartagena 1'
			],
			[
				'ciudad_id' => 22,
				'nombre' => 'Sede cartagena 2'
			]
		];
		collect($sedes)->map(function ($sede) {
			Sede::updateOrCreate($sede);
		});
	}
}
