<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSemPopayan extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssempopayan';

    protected $fillable = [
        'id',
        'doc',
        'code',
        'concept',
        'ingresos',
        'egresos',
        'names',
        'period',
        'pagaduria'
    ];
}
