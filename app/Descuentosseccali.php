<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuentosseccali extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'descuentosseccali';

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
