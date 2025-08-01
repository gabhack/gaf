<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comercial extends Model
{
    use SoftDeletes;

    protected $table = 'comerciales';

    protected $fillable = [
        'user_id',
        'empresa_id',
        'sede_id',
        'cargo_id',
        'tipo_documento_id',
        'ami_id',
        'hego_id',
        'consultas_diarias',
        'nombre_completo',
        'numero_documento',
        'nacionalidad',
        'correo',
        'numero_contacto',
        'src_documento_identidad',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class)->withDefault();
    }

    public function cargo()
    {
        return $this->belongsTo(Cargos::class)->withDefault();
    }

    public function tipo_documento()
    {
        return $this->belongsTo(TipoDocumento::class)->withDefault();
    }

    public function ami()
    {
        return $this->belongsTo(Ami::class)->withDefault();
    }

    public function hego()
    {
        return $this->belongsTo(Hego::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
