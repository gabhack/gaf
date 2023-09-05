<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbargosSedValle extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargossedvalle';

    protected $guarded = ['id'];

    protected $fillable = [
        'doc',
        'nomp',
        'nitdeman',
        'ndeman',
        'finiemb',
        'ffinemb',
        'memb',
        'tingr',
        'tegre',
        'temb',
        'neto',
        'fecdata',
        'mesdata',
        'anodata',
    ];
}
