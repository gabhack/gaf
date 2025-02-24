<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    protected $table = 'empresas';

    protected $fillable = [
        'tipo_sociedad_id',
        'tipo_empresa_id',
        'tipo_documento_id',
        'consultas_diarias',
        'nombre',
        'numero_documento',
        'correo',
        'pagina_web',
        'pais_id',
        'departamento_id',
        'ciudad_id',
        'direccion',
    ];

    public function tipo_sociedad()
    {
        return $this->belongsTo(TipoSociedad::class)->withDefault();
    }

    public function tipo_empresa()
    {
        return $this->belongsTo(TipoEmpresa::class)->withDefault();
    }

    public function tipo_documento()
    {
        return $this->belongsTo(TipoDocumento::class)->withDefault();
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class)->withDefault();
    }

    public function departamento()
    {
        return $this->belongsTo(Departamentos::class)->withDefault();
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudades::class)->withDefault();
    }

    public function representante_legal_empresa()
    {
        return $this->hasOne(RepresentanteLegalEmpresa::class)->withDefault();
    }

    public function documento_empresa()
    {
        return $this->hasOne(DocumentoEmpresa::class)->withDefault();
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'empresas_permisos');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'empresa_id', 'id')->withDefault();
    }
}
