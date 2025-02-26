<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    public function hasPermission($permission)
    {
        return $this->permissions->contains('name', $permission);
    }

    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (is_numeric($permission)) {
            $permission = Permission::find($permission);
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
            $permission = Permission::where('name', $permission)->first();
        }

        if (is_numeric($permission)) {
            $permission = Permission::find($permission);
        }

        if (!$permission) {
            return;
        }

        $this->permissions()->detach($permission->id);
    }
}
