<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamesSedMagdalena extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'datamessedmagdalena';

    protected $guarded = ['id'];

    protected $hidden = ['created_at'];

    protected $fillable = [
        'codempleado',
        'empleado',
        'cargo_codigo',
        'cargo',
        'basico',
        'nivelcontratacion',
        'ciudad',
        'ie_sede_area',
        'telefono',
        'email',
        'dir',
        'fecnacimient'
    ];
}
