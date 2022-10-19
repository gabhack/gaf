<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuentossecedu extends Model
{
  protected $connection = 'pgsql';

  protected $table = 'descuentossecedu';

  protected $guarded = ['id'];

  protected $fillable = [
    'doc',
    'nomp',
    'mliquid',
    'fecdata',
    'mesdata',
    'anodata',
  ];
}
