<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoupunsSemCali extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'coupunssemcali';

    protected $fillable = [
        'id',
        'doc',
        'code',
        'concept',
        'ingresos',
        'egresos',
        'names',
        'period',
        'pagaduria'
    ];
}
