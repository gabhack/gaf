<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSemCali extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossemcali';

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
