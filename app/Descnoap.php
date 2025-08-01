<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descnoap extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'descnoap';
    protected $fillable = [
        'clase',
        'tercero',
        'nomtercero',
        'td',
        'doc',
        'nomp',
        'pagare',
        'porcentaje',
        'vfijo',
        'vaplicado',
        'vtotal',
        'vpagado',
        'saldo',
        'fgrab',
        'forma',
        'incon',
        'codentiant',
        'nonentant',
        'fechacesion',
        'tdesc',
    ];
}
