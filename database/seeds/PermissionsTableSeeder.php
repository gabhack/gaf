<?php

use App\Permission;
use App\Role;
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
            'ver sedes',
            'ver visado',
            'ver prospeccion mercado',
            'ver recuperacion cartera',
            'ver investigacion',
            'ver localizacion usuarios',
            'hacer consultas',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assign permissions to EMPRESA role
        $rolEmpresa = Role::firstOrCreate([
            'name' => 'EMPRESA',
            'guard_name' => 'web'
        ]);
        $rolEmpresa->givePermission('ver usuarios');
        $rolEmpresa->givePermission('ver area comercial');
        $rolEmpresa->givePermission('ver visado');
        $rolEmpresa->givePermission('hacer consultas');
        $rolEmpresa->givePermission('ver sedes');

        // Assign permissions to COMERCIAL role
        $rolComercial = Role::firstOrCreate([
            'name' => 'COMERCIAL',
            'guard_name' => 'web'
        ]);
        $rolComercial->givePermission('ver visado');
        $rolComercial->givePermission('hacer consultas');
    }
}
