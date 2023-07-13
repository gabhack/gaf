<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamesFopep extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamesfode';

    protected $fillable = [
        'fondo',
        'td',
        'doc',
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
