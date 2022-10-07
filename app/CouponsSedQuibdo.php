<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSedQuibdo extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssedquibdo';

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
