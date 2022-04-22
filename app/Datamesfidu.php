<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Datamesfidu extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamesfidu';

    protected $guarded = ['id'];

    protected $fillable = [
      'mnpio',
      'doc',
      'td',
      'tdd',
      'solonomp',
      'soloapellp',
      'genero',
      'estcivil',
      'fecnacimient',
      'edad',
      'tipvinc',
      'desctipvinc',
      'fuenrecurso',
      'descfuenrecurso',
      'numdep',
      'dpto',
      'resol',
      'fechresol',
      'fechefect',
      'vpension',
      'estpens',
      'docbenef',
      'td',
      'nombenef',
      'tel',
      'dir',
      'correo',
      'nomcomprob',
      'periodo',
      'tipprest',
      'fechpago',
      'sucursal',
      'vpension',
      'vdescbruto',
      'pagonetbruto',
      'fecdata',
      'mesdata',
      'anodata',
    ];
}
