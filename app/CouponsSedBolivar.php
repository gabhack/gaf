<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedBolivar extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedbolivar';

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
