<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descapli extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'descalpi';
    protected $fillable = [
        'periodo',
        'concecutivo',
        'clase',
        'tercero',
        'nomtercero',
        'td',
        'doc',
        'nomp',
        'pagare',
        'porcentaje',
        'vaplicado',
        'vtotal',
        'vpagado',
        'saldo',
        'fgrab',
        'forma',
        'codentiant',
        'nonentant',
        'fechacesion',
        'tdesc',
        'p5d',
        'p4d',
        'numpagopt',
    ];
}
