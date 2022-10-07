<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Couponssedpopayan extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'couponssedpopayan';
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
