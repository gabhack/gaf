<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedCaldas extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedcaldas';

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
