<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    public function permissions()
    {
        return $this->belongsToMany(Permiso::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permiso::where('name', $permission)->first();
        }

        if (is_numeric($permission)) {
            $permission = Permiso::find($permission);
        }

        if (!$permission) {
            throw new \Exception("El permiso no existe.");
        }

        $hasPermission = $this->permissions->contains($permission->id);
        if (!$hasPermission) {
            $this->permissions()->attach($permission->id);
        }
    }

    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permiso::where('name', $permission)->first();
        }

        if (is_numeric($permission)) {
            $permission = Permiso::find($permission);
        }

        if (!$permission) {
            return;
        }

        $this->permissions()->detach($permission->id);
    }
}
