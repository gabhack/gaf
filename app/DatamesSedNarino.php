<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamesSedNarino extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamessednarino';

    protected $guarded = ['id'];

    protected $fillable = [
        'nvinc',
        'doc',
        'nomp',
        'fingr',
        'fretir',
        'antigüedad',
        'fecnacimient',
        'edad',
        'esquema',
        'codcargo',
        'cargo',
        'grado',
        'escalafon',
        'vingreso',
        'codencargo',
        'gradoenc',
        'coddep',
        'fnombramiento',
        'nnombramiento',
        'fpose',
        'npose',
        'continuidad',
        'fconti',
        'cconti',
        'mconti',
        'frecur',
        'ncontr',
        'slabor',
        'rvinc',
        'novvinc',
        'codcosto',
        'cencosto',
        'ciudad',
        'genero',
        'prof',
        'tel',
        'dir',
        'email',
        'tcargo',
        'status',
        'ncdb',
        'ccpp',
        'codareaedu',
        'areaedu',
        'codareaedutec',
        'areaedutec',
        'otraareaedutec',
        'ndicta',
        'tdepen',
        'cdepen',
        'depen',
        'codesteduc',
        'eeduc',
        'deduc',
        'ubicación',
        'column1',
        'sede2',
        'eeduc2',
        'fecdata',
        'mesdata',
        'anodata',
    ];
}
