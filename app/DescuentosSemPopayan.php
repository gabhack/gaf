<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSemPopayan extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossempopayan';

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
