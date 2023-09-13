<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedCordoba extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedcordoba';

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
