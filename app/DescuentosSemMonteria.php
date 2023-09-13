<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSemMonteria extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossemmonteria';

    protected $guarded = ['id'];

    protected $fillable = ['doc', 'nomp', 'mliquid', 'fecdata', 'valor', 'pagaduria', 'nomina'];
}
