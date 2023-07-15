<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSemBarranquilla extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssembarranquilla';

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
