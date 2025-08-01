<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbargosSemPopayan extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargossempopayan';

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
    ];
}
