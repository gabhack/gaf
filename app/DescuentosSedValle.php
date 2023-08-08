<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescuentosSedValle extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossedvalle';

    protected $guarded = ['id'];

    protected $fillable = [
        'doc',
        'nomp',
        'mliquid',
        'fecdata',
        'mesdata',
        'anodata',
    ];
}
