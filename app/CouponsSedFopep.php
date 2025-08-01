<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedFopep extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedfopep';

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

    protected $hidden = [
        'created_at',
    ];
}
