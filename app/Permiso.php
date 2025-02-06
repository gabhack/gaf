<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'name',
    ];

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class);
    }
}
