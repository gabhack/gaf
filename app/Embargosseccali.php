<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sabana extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'sabana';

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
