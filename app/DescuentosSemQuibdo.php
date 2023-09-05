<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSemQuibdo extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossemquibdo';

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
