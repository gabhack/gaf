<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSedAtlantico extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossedatlantico';

    protected $guarded = ['id'];

    protected $fillable = ['doc', 'nomp', 'mliquid', 'valor', 'pagaduria', 'nomina'];
}
