<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSedCauca extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossedcauca';

    protected $guarded = ['id'];

    protected $fillable = [
        'doc',
        'nomp',
        'mliquid',
        'fecdata',
        'mesdata',
        'anodata',
        'pagaduria',
        'noent',
        'causal',
    ];
}
