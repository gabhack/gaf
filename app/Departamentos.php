<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamentos extends Model
{
    use SoftDeletes;

    protected $table = 'departamentos';

    protected $fillable = [
        'pais_id',
        'codigo',
        'nombre',
    ];

    public function pais()
    {
        return $this->belongsTo('\App\Pais', 'pais_id');
    }

    public function ciudades()
    {
        return $this->hasMany('\App\Ciudades', 'departamento_id');
    }
}
