<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbargosSedBolivar extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargossedbolivar';

    protected $guarded = ['id'];

    protected $fillable = [
        'idemp',
        'nemp',
        'iddem',
        'ndem',
        'cuenta',
        'juzgado',
        'expediente',
        'valor',
        'pagaduria',
    ];
}
