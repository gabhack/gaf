<?php

use Faker\Generator as Faker;

$factory->define(App\Empresa::class, function (Faker $faker) {
	return [
		'tipo_sociedad_id' => $faker->numberBetween(1, 2),
		'tipo_empresa_id' => $faker->numberBetween(1, 2),
		'tipo_documento_id' => $faker->numberBetween(1, 3),
		'ciudad_id' => $faker->numberBetween(1, 10),
		'consultas_diarias' => $faker->numberBetween(1, 100),
		'nombre' => $faker->company,
		'numero_documento' => $faker->unique()->numerify('##########'),
		'correo' => $faker->unique()->companyEmail,
		'pagina_web' => $faker->url,
		'pais' => $faker->country,
		'direccion' => $faker->address,
	];
});
