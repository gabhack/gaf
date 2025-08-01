<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciudades extends Model
{
    use SoftDeletes;

    protected $table = 'ciudades';

    protected $fillable = [
        'departamento_id',
        'codigo',
        'nombre',
    ];

    public function departamento()
    {
        return $this->belongsTo('\App\Departamentos', 'departamento_id');
    }

    public function oficinas()
    {
        return $this->hasMany('\App\Oficinas', 'ciudad_id');
    }
}
