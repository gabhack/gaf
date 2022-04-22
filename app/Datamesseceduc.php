<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Datamesseceduc extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamesseceduc';

    protected $guarded = ['id'];

    protected $fillable = [
      'doc',
      'nomp',
      'fechingr',
      'antiguedad',
      'fecnacimient',
      'edad',
      'esquema',
      'cargo',
      'vpension',
      'fecnombr',
      'fecposesion',
      'nivcontr',
      'estlaboral',
      'centrocosto',
      'mnpioydep',
      'tel',
      'dir',
      'correo',
      'sedecoleg',
      'fecdata',
      'mesdata',
      'anodata',
    ];
}
