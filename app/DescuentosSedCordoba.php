<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSedCordoba extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossedcordoba';

    protected $guarded = ['id'];

    protected $fillable = ['doc', 'nomp', 'mliquid', 'fecdata', 'valor', 'pagaduria', 'nomina'];
}
