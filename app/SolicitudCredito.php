<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudCredito extends Model
{
    use SoftDeletes;
    
	protected $table = 'solicitud_credito';

    protected $fillable = ['valor_solicitado', 'nro_cuotas', 'aval', 'iva_aval', 'comision', 'valor1', 'valor2', 'iva_ck', 'interes_inicial', 'gmf', 'seguro', 'total_pagar', 'cuota', 'credito_total','estudio_id'];
}
