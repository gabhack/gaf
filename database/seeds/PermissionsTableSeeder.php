<?php

use App\Permiso;
use App\Roles;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'ver usuarios',
            'ver empresas',
            'ver area comercial',
            'ver visado',
            'ver prospeccion mercado',
            'ver recuperacion cartera',
            'ver investigacion',
            'ver localizacion usuarios'
        ];

        foreach ($permissions as $permission) {
            Permiso::updateOrCreate(['name' => $permission]);
        }

        // Asignar permisos al rol EMPRESA
        $rolEmpresa = Roles::firstOrCreate(['rol' => 'EMPRESA']);
        $rolEmpresa->givePermission('ver usuarios');
        $rolEmpresa->givePermission('ver area comercial');
    }
}
