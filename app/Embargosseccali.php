<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embargosseccali extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargosseccali';

    protected $guarded = ['id'];

    protected $fillable = [
      'doc',
      'nomp',
      'docdeman',
      'entidaddeman',
      'fembini',
      'fembfin',
      'motemb',
      'tingr',
      'tegre',
      'temb',
      'netoemb',
      'fecdata',
      'mesdata',
      'anodata',
    ];
}
