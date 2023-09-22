<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsGen extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponsgen';

    protected $fillable = [
        'id',
        'doc',
        'code',
        'concept',
        'ingresos',
        'egresos',
        'names',
        'period',
        'pagaduria',
        'inicioperiodo',
        'finperiodo',
    ];
}
