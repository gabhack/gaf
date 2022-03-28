<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataMes extends Model
{    
    protected $connection = 'pgsql';
    protected $table='datames';

    protected $fillable = [
        'fondo',
        'td',
        'x',
        'nomp',
        'fecnacimient',
        'dir',
        'dpto',
        'mnpio',
        'tp',
        'nbanco',
        'sucursal',
        'tel',
        'cel',
        'correo',
        'vpension',
        'vsalud',
        'vembargos',
        'vdesc',
        'cupo',
    ];
}
