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
        'role_id',
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
        'password',
    ];

    protected $appends = ['consultas_diarias'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        if ($this->role->name == $role) {
            return true;
        }

        return false;
    }

    public function rolePermissions()
    {
        return $this->role ? $this->role->permissions : collect([]);
    }

    public function directPermissions()
    {
        return $this->morphToMany(Permission::class, 'model', 'model_has_permissions', 'model_id', 'permission_id');
    }

    public function getConsultasDiariasAttribute()
    {
        return $this->empresa->consultas_diarias ?? $this->comercial->consultas_diarias ?? null;
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

        $hasPermission = $this->directPermissions->contains($permission->id);
        if (!$hasPermission) {
            $this->directPermissions()->attach($permission->id);
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

        $this->directPermissions()->detach($permission->id);
    }

    public function syncPermissions($permissions)
    {
        $permissions = collect($permissions)->map(function ($permission) {
            if (is_string($permission)) {
                return Permission::where('name', $permission)->first();
            }

            if (is_numeric($permission)) {
                return Permission::find($permission);
            }

            return $permission;
        });

        $permissions = $permissions->filter();

        $this->directPermissions()->sync($permissions->pluck('id'));
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
        return $this->hasOne(Empresa::class);
    }

    public function comercial()
    {
        return $this->hasOne(Comercial::class);
    }

    public function padre()
    {
        return $this->hasOne('\App\User', 'id', 'users_id');
    }

    public function company()
    {
        return $this->hasOne('\App\User', 'id', 'id_company');
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
