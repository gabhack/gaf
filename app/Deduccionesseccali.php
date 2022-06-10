<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sabana extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'sabana';

    protected $guarded = ['id'];

    protected $fillable = [
      $table->increments('id');
      $table->timestamps();
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
