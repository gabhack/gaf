<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSemQuibdo extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'couponssemquibdo';

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
