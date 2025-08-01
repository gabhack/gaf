<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedChoco extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedchoco';

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
