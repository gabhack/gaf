<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbargosGen extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargosgen';

    protected $guarded = ['id'];

    protected $fillable = [
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
        'tipoembargo'
    ];
}
