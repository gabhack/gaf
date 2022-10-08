<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuentossedpopayan extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossedpopayan';

    protected $guarded = ['id'];

    protected $fillable = [
      'doc',
      'nomp',
      'mliquid',
      'fecdata',
      'mesdata',
      'anodata',
      'pagaduria',
      'noent',
      'causal',
    ];
}
