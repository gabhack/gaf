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
      'nomfopep',
      'tfopep',
      'celfopep',
      'corfopep',
      'nfidu',
      'telfidu',
      'corfidu',
      'nsecvalle',
      'tsecvalle',
      'csecvalle',
      'nseccali',
      'tseccali',
      'cseccali',
      'fvincfopep',
      'departfopep',
      'municfopep',
      'tpfopep',
      'bfopep',
      'pagfopep',
      'ingfopep',
      'edadfopep',
      'municfidu',
      'generofidu',
      'estcivilfidu',
      'edadfidu',
      'descvincfidu',
      'tdescfidu',
      'depfidu',
      'fvincfidu',
      'tpfidu',
      'ingrfidu',
      'pagfidu',
      'antsecvalle',
      'edadsecvalle',
      'cargsecvalle',
      'tvincsecvalle',
      'estlabsecvalle',
      'municsecvalle',
      'ingsecvalle',
      'pagsecvalle',
      'edadseccali',
      'cargoseccali',
      'ncontrseccali',
      'slabseccali',
      'nvincseccali',
      'generoseccali',
      'cargoseccali',
      'ingrseccali',
      'pagseccali',
      'coincidenciaspag',
      'descaplfopep',
      'desnoapfopep',
      'alertliqseccali',
      'embseccali',
      'deduccseccali',
      'embsecvalle',
      'alerliqsecvalle',
    ];
}
