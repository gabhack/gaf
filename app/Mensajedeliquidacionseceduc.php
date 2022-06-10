<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensajedeliquidacionseceduc extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'mensajedeliquidacionseceduc';

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
