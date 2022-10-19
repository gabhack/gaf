<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedAtlantico extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedatlantico';

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
