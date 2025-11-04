<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstudioCartera extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'analisis_cartera_avanzado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'politica_portafolio_id',
        'politica_fondo_id',
        'nombre_archivo',
        'mes',
        'anio',
        'descripcion',
        'total_registros',
        'registros_exitosos',
        'registros_con_errores',
        'datos_procesados',
        'metadatos'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'datos_procesados' => 'array',
        'metadatos' => 'array',
        'total_registros' => 'integer',
        'registros_exitosos' => 'integer',
        'registros_con_errores' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // Ocultar datos_procesados por defecto para reducir payload
        // Se pueden cargar explícitamente cuando sea necesario
    ];

    /**
     * Relación con el usuario que creó el estudio
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener datos de la política de portafolio desde metadatos
     * (No usamos relación Eloquent porque las políticas están en metadatos)
     */
    public function getPoliticaPortafolioDataAttribute()
    {
        if (!$this->metadatos || !isset($this->metadatos['politica_portafolio'])) {
            return null;
        }
        return $this->metadatos['politica_portafolio'];
    }

    /**
     * Obtener datos de la política de fondo desde metadatos
     * (No usamos relación Eloquent porque las políticas están en metadatos)
     */
    public function getPoliticaFondoDataAttribute()
    {
        if (!$this->metadatos || !isset($this->metadatos['politica_fondo'])) {
            return null;
        }
        return $this->metadatos['politica_fondo'];
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopePorUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopePorFecha($query, $fechaDesde, $fechaHasta)
    {
        if ($fechaDesde) {
            $query->whereDate('created_at', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('created_at', '<=', $fechaHasta);
        }
        return $query;
    }

    /**
     * Scope para filtrar por mes y año
     */
    public function scopePorMesAnio($query, $mes = null, $anio = null)
    {
        if ($mes) {
            $query->where('mes', $mes);
        }
        if ($anio) {
            $query->where('anio', $anio);
        }
        return $query;
    }

    /**
     * Scope para ordenar por más reciente
     */
    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessor para obtener el nombre del usuario
     */
    public function getNombreUsuarioAttribute()
    {
        return $this->user ? $this->user->name : 'Usuario eliminado';
    }

    /**
     * Accessor para obtener el nombre de la política de portafolio
     */
    public function getNombrePoliticaPortafolioAttribute()
    {
        if (!$this->metadatos || !isset($this->metadatos['nombre_politica_portafolio'])) {
            return 'No disponible';
        }
        return $this->metadatos['nombre_politica_portafolio'];
    }

    /**
     * Accessor para obtener el nombre de la política de fondo
     */
    public function getNombrePoliticaFondoAttribute()
    {
        if (!$this->metadatos || !isset($this->metadatos['nombre_politica_fondo'])) {
            return 'No disponible';
        }
        return $this->metadatos['nombre_politica_fondo'];
    }

    /**
     * Accessor para obtener el periodo formateado
     */
    public function getPeriodoAttribute()
    {
        return $this->mes . '/' . $this->anio;
    }

    /**
     * Accessor para obtener la fecha de creación formateada
     */
    public function getFechaCreacionFormateadaAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '';
    }

    /**
     * Mutator para asegurar que datos_procesados sea un array
     */
    public function setDatosProcesadosAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['datos_procesados'] = $value;
        } else {
            $this->attributes['datos_procesados'] = json_encode($value);
        }
    }

    /**
     * Mutator para asegurar que metadatos sea un array
     */
    public function setMetadatosAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['metadatos'] = $value;
        } else {
            $this->attributes['metadatos'] = json_encode($value);
        }
    }
}
