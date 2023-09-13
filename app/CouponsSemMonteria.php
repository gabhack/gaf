<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSemMonteria extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssemmonteria';

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
