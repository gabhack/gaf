<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSedChoco extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossedchoco';

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
