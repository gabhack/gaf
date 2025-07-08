<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ParsesPgTzDates;

class EmbargosGen extends Model
{
    use ParsesPgTzDates;

    protected $connection = 'pgsql';
    protected $table      = 'embargosgen';
    protected $guarded    = ['id'];
    protected $fillable   = [
        'doc',
        'nomp',
        'docdeman',
        'entidaddeman',
        'fembini',
        'fembfin',
        'motemb',
        'tingr',
        'tegre',
        'temb',
        'netoemb',
        'pagaduria',
        'nomina',
        'tipoembargo',
    ];

    protected $casts = [
        'fembini' => 'datetime',
        'fembfin' => 'datetime',
        'nomina'  => 'datetime',
    ];
}
