<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbargosSemMonteria extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargossemmonteria';

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
