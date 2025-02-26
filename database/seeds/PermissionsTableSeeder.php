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
            'ver visado',
            'ver prospeccion mercado',
            'ver recuperacion cartera',
            'ver investigacion',
            'ver localizacion usuarios'
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
    }
}
