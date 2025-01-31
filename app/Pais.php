<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use SoftDeletes;

    protected $table = 'paises';

    protected $fillable = [
        'codigo',
        'nombre'
    ];

    public function departamentos()
    {
        return $this->hasMany('\App\Departamentos', 'pais_id');
    }
}
