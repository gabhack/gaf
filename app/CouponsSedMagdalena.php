<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedMagdalena extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedmagdalena';

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
