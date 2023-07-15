<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbargosSemCali extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargossemcali';

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
        'fecdata',
        'mesdata',
        'anodata',
    ];
}
