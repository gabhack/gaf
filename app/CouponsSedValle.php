<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedValle extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedvalle';

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
