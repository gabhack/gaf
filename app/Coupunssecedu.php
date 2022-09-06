<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupunssecedu extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'coupunssecedu';
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
