<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedCauca extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedcauca';

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
