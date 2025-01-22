<?php

use Faker\Generator as Faker;

$factory->define(App\Comercial::class, function (Faker $faker) {
	return [
		'sede_id' => $faker->numberBetween(1, 5),
		'cargo_id' => $faker->numberBetween(1, 12),
		'tipo_documento_id' => $faker->numberBetween(1, 3),
		'ami_id' => $faker->numberBetween(1, 5),
		'hego_id' => $faker->numberBetween(1, 3),
		'consultas_diarias' => $faker->numberBetween(1, 100),
		'nombre_completo' => $faker->company,
		'numero_documento' => $faker->unique()->numerify('##########'),
		'nacionalidad' => $faker->company,
		'correo' => $faker->company,
		'numero_contacto' =>  $faker->numerify('##########'),
		'src_documento_identidad' => $faker->company,
	];
});
