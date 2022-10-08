<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embargossedpopayan extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargossedpopayan';

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
