<?php

use App\Hego;
use Illuminate\Database\Seeder;

class HegoSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		collect(['Solicitud', 'Tesorería', 'Cartera'])->map(function ($ami) {
			Hego::updateOrCreate(['nombre' => $ami]);
		});
	}
}
