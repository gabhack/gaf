<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedBarranquilla extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedbarranquilla';

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
        'finperiodo'
    ];
}
