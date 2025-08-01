<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmbargosSemQuibdo extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargossemquibdo';

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
