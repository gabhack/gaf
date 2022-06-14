<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deduccionesseccali extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'deduccionesseccali';

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
