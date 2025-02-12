<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'roles_id',
        'empresa_id',
        'id_company',
        'id_padre',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function rol()
    {
        return $this->hasOne(Roles::class, 'id', 'roles_id');
    }

    public function rolePermissions()
    {
        return $this->rol ? $this->rol->permissions : collect();
    }

    public function directPermissions()
    {
        return $this->morphToMany(Permiso::class, 'model', 'model_has_permissions', 'model_id', 'permission_id');
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

        $hasPermission = $this->directPermissions->contains($permission->id);
        if (!$hasPermission) {
            $this->directPermissions()->attach($permission->id);
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

        $this->directPermissions()->detach($permission->id);
    }

    public function hasPermission($permission)
    {
        if ($this->rolePermissions()->contains('name', $permission)) {
            return true;
        }

        if ($this->directPermissions->contains('name', $permission)) {
            return true;
        }

        return false;
    }

    public function empresa()
    {
        return $this->hasOne('\App\Empresas', 'id', 'id_company');
    }

    public function padre()
    {
        return $this->hasOne('\App\User', 'id', 'users_id');
    }

    public function company()
    {
        return $this->hasOne('\App\User', 'id', 'id_company');
    }

    public function hasRole($rol)
    {
        if ($this->rol->rol == $rol) {
            return true;
        } else {
            return false;
        }
    }

    public function consultas()
    {
        return $this->hasMany('App\Consultas', 'users_id', 'id');
    }

    public function usuarioscompany()
    {
        return $this->hasMany('App\User', 'id_company', 'id');
    }

    public function usuarioshijos()
    {
        return $this->hasMany('App\User', 'id_padre', 'id');
    }
}
