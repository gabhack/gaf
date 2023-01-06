<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsSemSahagun extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'cuponsemsahagun';

    protected $fillable = ['id', 'doc', 'code', 'concept', 'ingresos', 'egresos', 'names', 'period', 'pagaduria'];
}
