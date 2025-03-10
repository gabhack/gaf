<?php

use App\Comercial;
use App\Empresa;
use App\Role;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comercial::class, function (Faker $faker) {
    $roleComercial = Role::firstOrCreate([
        'name' => 'COMERCIAL',
        'guard_name' => 'web',
    ]);

    $user = factory(User::class)->create([
        'role_id' => $roleComercial->id,
        'password' => bcrypt('12345678'),
    ]);

    $empresas = Empresa::all()->pluck('id');

    return [
        'user_id' => $user->id,
        'empresa_id' => intval($faker->randomElement($empresas)),
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
