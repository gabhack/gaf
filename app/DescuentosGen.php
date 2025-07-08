<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ParsesPgTzDates;

class DescuentosGen extends Model
{
    use ParsesPgTzDates;

    protected $connection = 'pgsql';
    protected $table      = 'descuentosgen';
    protected $guarded    = ['id'];
    protected $fillable   = [
        'doc',
        'nomp',
        'mliquid',
        'valor',
        'pagaduria',
        'fecdata',
        'nomina',
    ];

    protected $casts = [
        'fecdata' => 'datetime',
        'nomina'  => 'datetime',
    ];
}
