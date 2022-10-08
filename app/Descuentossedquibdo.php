<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuentossedquibdo extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentossedquibdo';

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
