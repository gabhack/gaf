<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSedCaldas extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossedcaldas';

    protected $guarded = ['id'];

    protected $fillable = ['doc', 'nomp', 'mliquid', 'fecdata', 'valor', 'pagaduria', 'nomina'];
}
