<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSemBarranquilla extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossembarranquilla';

    protected $guarded = ['id'];

    protected $fillable = ['doc', 'nomp', 'mliquid', 'valor', 'pagaduria', 'nomina'];
}
