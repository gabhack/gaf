<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeduccionesSemCali extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'deduccionessemcali';

    protected $guarded = ['id'];

    protected $fillable = [
        'doc',
        'nomp',
        'valordeduc',
        'centrocostdeduc',
        'entiddeduc',
        'fecdata',
        'mesdata',
        'anodata',
    ];
}
