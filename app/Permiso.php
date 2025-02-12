<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'name',
    ];

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'role_has_permissions', 'permission_id', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class);
    }
}
