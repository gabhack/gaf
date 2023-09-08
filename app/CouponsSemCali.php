<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSemCali extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssemcali';

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
        'finalperiodo',
    ];
}
