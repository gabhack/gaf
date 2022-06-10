<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Embargosseceduc extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'embargosseceduc';

    protected $guarded = ['id'];

    protected $fillable = [
      'doc',
      'nomp',
      'nitdeman',
      'ndeman',
      'finiemb',
      'ffinemb',
      'memb',
      'tingr',
      'tegre',
      'temb',
      'neto',
      'fecdata',
      'mesdata',
      'anodata',
    ];
}
