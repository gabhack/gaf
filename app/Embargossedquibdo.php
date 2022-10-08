<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embargossedquibdo extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargossedquibdo';

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
