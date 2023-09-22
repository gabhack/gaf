<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosGen extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentosgen';

    protected $guarded = ['id'];

    protected $fillable = ['doc', 'nomp', 'mliquid', 'valor', 'pagaduria', 'fecdata', 'nomina'];
}
